<?php

namespace App\Services;

use App\Models\{Mapel, Kelas, SlotWaktu, BebanMengajar};

class GeneticAlgorithmService
{
    private int $populasiSize;
    private int $generasi;
    private float $crossoverRate;
    private float $mutationRate;
    private array $slots;
    private array $bebanList;
    private array $logKonvergensi = [];

    public function __construct(
        int $populasiSize = 50,
        int $generasi = 200,
        float $crossoverRate = 0.85,
        float $mutationRate = 0.15
    ) {
        $this->populasiSize  = $populasiSize;
        $this->generasi      = $generasi;
        $this->crossoverRate = $crossoverRate;
        $this->mutationRate  = $mutationRate;
    }

    public function jalankan(): array
    {
        $start = microtime(true);

        $this->slots = SlotWaktu::where('is_istirahat', false)
            ->orderBy('hari')->orderBy('jam_ke')
            ->get()
            ->map(fn($s) => ['id' => $s->id, 'hari' => $s->hari, 'jam' => $s->jam_ke])
            ->toArray();

        $this->bebanList = BebanMengajar::with(['guru'])
            ->whereHas('guru', fn($q) => $q->where('is_bk', false))
            ->get()
            ->map(fn($b) => [
                'guru_id'   => $b->guru_id,
                'mapel_id'  => $b->mapel_id,
                'kelas_id'  => $b->kelas_id,
                'jumlah_jp' => $b->jumlah_jp,
            ])
            ->toArray();

        $populasi = [];
        for ($i = 0; $i < $this->populasiSize; $i++) {
            $populasi[] = $this->buatIndividu();
        }

        for ($gen = 0; $gen < $this->generasi; $gen++) {
            $fitness = array_map(fn($ind) => $this->hitungFitness($ind), $populasi);

            $this->logKonvergensi[] = [
                'generasi'        => $gen,
                'fitness_terbaik' => min($fitness),
                'fitness_rata'    => array_sum($fitness) / count($fitness),
            ];

            $newPopulasi = [];
            while (count($newPopulasi) < $this->populasiSize) {
                $parent1 = $this->tournamentSelect($populasi, $fitness);
                $parent2 = $this->tournamentSelect($populasi, $fitness);

                if (mt_rand() / mt_getrandmax() < $this->crossoverRate) {
                    [$anak1, $anak2] = $this->crossover($parent1, $parent2);
                } else {
                    $anak1 = $parent1;
                    $anak2 = $parent2;
                }

                $anak1 = $this->mutasi($anak1);
                $anak2 = $this->mutasi($anak2);

                $newPopulasi[] = $anak1;
                if (count($newPopulasi) < $this->populasiSize) {
                    $newPopulasi[] = $anak2;
                }
            }
            $populasi = $newPopulasi;
        }

        $fitnessAkhir = array_map(fn($ind) => $this->hitungFitness($ind), $populasi);
        $idxTerbaik = array_search(min($fitnessAkhir), $fitnessAkhir);
        $terbaik = $populasi[$idxTerbaik];

        $waktu = microtime(true) - $start;

        return [
            'algoritma'         => 'GA',
            'parameter'         => [
                'populasi'       => $this->populasiSize,
                'generasi'       => $this->generasi,
                'crossover_rate' => $this->crossoverRate,
                'mutation_rate'  => $this->mutationRate,
            ],
            'fitness_terbaik'   => $fitnessAkhir[$idxTerbaik],
            'waktu_komputasi'   => $waktu,
            'iterasi_konvergen' => $this->cariIterasiKonvergen(),
            'pelanggaran_hard'  => $this->hitungPelanggaranHard($terbaik),
            'pelanggaran_soft'  => $this->hitungPelanggaranSoft($terbaik),
            'jadwal'            => $this->formatJadwal($terbaik),
            'log_konvergensi'   => $this->logKonvergensi,
        ];
    }

    private function buatIndividu(): array
    {
        $kelasIds = Kelas::pluck('id')->toArray();
        $individu = [];

        foreach ($kelasIds as $kelasId) {
            $kebutuhan = [];
            foreach ($this->bebanList as $idx => $beban) {
                if ($beban['kelas_id'] === $kelasId) {
                    for ($j = 0; $j < $beban['jumlah_jp']; $j++) {
                        $kebutuhan[] = $idx;
                    }
                }
            }
            
            $jumlahSlot = count($this->slots);
            if (count($kebutuhan) > $jumlahSlot) {
                $kebutuhan = array_slice($kebutuhan, 0, $jumlahSlot);
            }
            
            shuffle($kebutuhan);

            $kromosom = [];
            foreach ($this->slots as $slot) {
                $kromosom[$slot['id']] = !empty($kebutuhan) 
                    ? array_shift($kebutuhan) 
                    : -1;
            }
            $individu[$kelasId] = $kromosom;
        }

        return $individu;
    }

    private function hitungFitness(array $individu): float
    {
        $hard = $this->hitungPelanggaranHard($individu);
        $soft = $this->hitungPelanggaranSoft($individu);
        return ($hard * 1000) + $soft;
    }

    private function hitungPelanggaranHard(array $individu): int
    {
        $pelanggaran = 0;

        // 1. Slot kosong
        foreach ($individu as $kelasId => $kromosom) {
            foreach ($kromosom as $slotId => $bebanIdx) {
                if ($bebanIdx === -1) {
                    $pelanggaran += 1;
                }
            }
        }

        // 2. Guru bentrok
        $guruPerSlot = [];
        foreach ($individu as $kelasId => $kromosom) {
            foreach ($kromosom as $slotId => $bebanIdx) {
                if ($bebanIdx === -1) continue;
                $guruId = $this->bebanList[$bebanIdx]['guru_id'];
                $key = "{$slotId}_{$guruId}";
                $guruPerSlot[$key] = ($guruPerSlot[$key] ?? 0) + 1;
            }
        }
        foreach ($guruPerSlot as $count) {
            if ($count > 1) {
                $pelanggaran += ($count - 1) * 5;
            }
        }

        return $pelanggaran;
    }

    private function hitungPelanggaranSoft(array $individu): int
    {
        $pelanggaran = 0;
        $mapelBeratIds = Mapel::where('is_mapel_berat', true)->pluck('id')->toArray();
        $slotMap = collect($this->slots)->keyBy('id');

        foreach ($individu as $kelasId => $kromosom) {
            // 1. Mapel berat di jam ke-7+
            foreach ($kromosom as $slotId => $bebanIdx) {
                if ($bebanIdx === -1) continue;
                $slot = $slotMap[$slotId] ?? null;
                if (!$slot) continue;
                
                $mapelId = $this->bebanList[$bebanIdx]['mapel_id'];
                if (in_array($mapelId, $mapelBeratIds) && $slot['jam'] >= 7) {
                    $pelanggaran++;
                }
            }

            // 2. Mapel dobel > 2x di hari sama
            $perHariMapel = [];
            foreach ($kromosom as $slotId => $bebanIdx) {
                if ($bebanIdx === -1) continue;
                $slot = $slotMap[$slotId] ?? null;
                if (!$slot) continue;
                
                $mapelId = $this->bebanList[$bebanIdx]['mapel_id'];
                $hari = $slot['hari'];
                
                $perHariMapel[$hari][$mapelId] = ($perHariMapel[$hari][$mapelId] ?? 0) + 1;
            }

            foreach ($perHariMapel as $hari => $mapels) {
                foreach ($mapels as $mapelId => $count) {
                    if ($count > 2) {
                        $pelanggaran += ($count - 2) * 3;
                    }
                }
            }

            // 3. Mapel 2x berturut-turut
            $slotIdsSorted = array_keys($kromosom);
            usort($slotIdsSorted, function($a, $b) use ($slotMap) {
                $slotA = $slotMap[$a];
                $slotB = $slotMap[$b];
                if ($slotA['hari'] !== $slotB['hari']) {
                    return strcmp($slotA['hari'], $slotB['hari']);
                }
                return $slotA['jam'] - $slotB['jam'];
            });

            for ($i = 0; $i < count($slotIdsSorted) - 1; $i++) {
                $currSlotId = $slotIdsSorted[$i];
                $nextSlotId = $slotIdsSorted[$i + 1];
                
                $currBeban = $kromosom[$currSlotId] ?? -1;
                $nextBeban = $kromosom[$nextSlotId] ?? -1;
                
                if ($currBeban === -1 || $nextBeban === -1) continue;
                
                $currSlot = $slotMap[$currSlotId];
                $nextSlot = $slotMap[$nextSlotId];
                
                if ($currSlot['hari'] !== $nextSlot['hari']) continue;
                if ($nextSlot['jam'] - $currSlot['jam'] !== 1) continue;
                
                $currMapel = $this->bebanList[$currBeban]['mapel_id'];
                $nextMapel = $this->bebanList[$nextBeban]['mapel_id'];
                
                if ($currMapel === $nextMapel) {
                    $pelanggaran += 1;
                }
            }
        }

        return $pelanggaran;
    }

    private function tournamentSelect(array $populasi, array $fitness, int $k = 3): array
    {
        $best = null;
        $bestFit = PHP_FLOAT_MAX;
        for ($i = 0; $i < $k; $i++) {
            $idx = array_rand($populasi);
            if ($fitness[$idx] < $bestFit) {
                $bestFit = $fitness[$idx];
                $best = $populasi[$idx];
            }
        }
        return $best;
    }

    private function crossover(array $p1, array $p2): array
    {
        $anak1 = $p1;
        $anak2 = $p2;
        foreach ($p1 as $kelasId => $kromosom) {
            $titikPotong = array_rand($kromosom);
            $flag = false;
            foreach ($kromosom as $slotId => $val) {
                if ($flag) {
                    $anak1[$kelasId][$slotId] = $p2[$kelasId][$slotId];
                    $anak2[$kelasId][$slotId] = $p1[$kelasId][$slotId];
                }
                if ($slotId == $titikPotong) $flag = true;
            }
        }
        return [$anak1, $anak2];
    }

    private function mutasi(array $individu): array
    {
        foreach ($individu as $kelasId => &$kromosom) {
            if (mt_rand() / mt_getrandmax() < $this->mutationRate) {
                $slotIds = array_keys($kromosom);
                $s1 = $slotIds[array_rand($slotIds)];
                $s2 = $slotIds[array_rand($slotIds)];
                [$kromosom[$s1], $kromosom[$s2]] = [$kromosom[$s2], $kromosom[$s1]];
            }
        }
        return $individu;
    }

    private function cariIterasiKonvergen(): int
    {
        $log = $this->logKonvergensi;
        $best = PHP_FLOAT_MAX;
        for ($i = 0; $i < count($log); $i++) {
            if ($log[$i]['fitness_terbaik'] < $best) {
                $best = $log[$i]['fitness_terbaik'];
            } else {
                $stabil = true;
                for ($j = $i; $j < min($i + 20, count($log)); $j++) {
                    if ($log[$j]['fitness_terbaik'] < $best) {
                        $stabil = false; break;
                    }
                }
                if ($stabil) return $i;
            }
        }
        return count($log);
    }

    private function formatJadwal(array $individu): array
    {
        $hasil = [];
        foreach ($individu as $kelasId => $kromosom) {
            foreach ($kromosom as $slotId => $bebanIdx) {
                if ($bebanIdx === -1) continue;
                $beban = $this->bebanList[$bebanIdx];
                $hasil[] = [
                    'kelas_id' => $kelasId,
                    'slot_id'  => $slotId,
                    'guru_id'  => $beban['guru_id'],
                    'mapel_id' => $beban['mapel_id'],
                ];
            }
        }
        return $hasil;
    }
}