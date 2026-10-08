<?php

namespace App\Services;

use App\Models\{Mapel, Kelas, SlotWaktu, BebanMengajar};

class SimulatedAnnealingService
{
    private float $suhuAwal;
    private float $coolingRate;
    private int $iterasiPerSuhu;
    private array $slots;
    private array $bebanList;
    private array $logKonvergensi = [];
    private int $logEveryN = 50;

    public function __construct(
        float $suhuAwal = 1000.0,
        float $coolingRate = 0.95,
        int $iterasiPerSuhu = 50
    ) {
        $this->suhuAwal       = $suhuAwal;
        $this->coolingRate    = $coolingRate;
        $this->iterasiPerSuhu = $iterasiPerSuhu;
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

        $current     = $this->buatSolusiAwal();
        $currentCost = $this->hitungFitness($current);
        $best        = $current;
        $bestCost    = $currentCost;

        $suhu    = $this->suhuAwal;
        $iterasi = 0;

        while ($suhu > 0.01) {
            for ($i = 0; $i < $this->iterasiPerSuhu; $i++) {
                $iterasi++;

                $neighbor     = $this->buatNeighbor($current);
                $neighborCost = $this->hitungFitness($neighbor);

                $delta = $neighborCost - $currentCost;

                if ($delta < 0 || mt_rand() / mt_getrandmax() < exp(-$delta / $suhu)) {
                    $current     = $neighbor;
                    $currentCost = $neighborCost;

                    if ($currentCost < $bestCost) {
                        $best     = $current;
                        $bestCost = $currentCost;
                    }
                }

                if ($iterasi % $this->logEveryN == 0 || $i == $this->iterasiPerSuhu - 1) {
                    $this->logKonvergensi[] = [
                        'iterasi'         => $iterasi,
                        'suhu'            => round($suhu, 2),
                        'fitness_terbaik' => $bestCost,
                        'fitness_current' => $currentCost,
                    ];
                }
            }

            $suhu *= $this->coolingRate;
        }

        if (count($this->logKonvergensi) > 1000) {
            $step = (int) ceil(count($this->logKonvergensi) / 1000);
            $filtered = [];
            foreach ($this->logKonvergensi as $k => $v) {
                if ($k % $step === 0) $filtered[] = $v;
            }
            $this->logKonvergensi = $filtered;
        }

        $waktu = microtime(true) - $start;

        return [
            'algoritma'         => 'SA',
            'parameter'         => [
                'suhu_awal'        => $this->suhuAwal,
                'cooling_rate'     => $this->coolingRate,
                'iterasi_per_suhu' => $this->iterasiPerSuhu,
            ],
            'fitness_terbaik'   => $bestCost,
            'waktu_komputasi'   => $waktu,
            'iterasi_konvergen' => $this->cariIterasiKonvergen(),
            'pelanggaran_hard'  => $this->hitungPelanggaranHard($best),
            'pelanggaran_soft'  => $this->hitungPelanggaranSoft($best),
            'jadwal'            => $this->formatJadwal($best),
            'log_konvergensi'   => $this->logKonvergensi,
        ];
    }

    private function buatSolusiAwal(): array
    {
        $kelasIds = Kelas::pluck('id')->toArray();
        $solusi = [];

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
            $solusi[$kelasId] = $kromosom;
        }

        return $solusi;
    }

    private function buatNeighbor(array $solusi): array
    {
        $kelasIds = array_keys($solusi);
        $kelasId  = $kelasIds[array_rand($kelasIds)];

        $slotIds = array_keys($solusi[$kelasId]);
        $s1 = $slotIds[array_rand($slotIds)];
        $s2 = $slotIds[array_rand($slotIds)];

        [$solusi[$kelasId][$s1], $solusi[$kelasId][$s2]] =
            [$solusi[$kelasId][$s2], $solusi[$kelasId][$s1]];

        return $solusi;
    }

    private function hitungFitness(array $solusi): float
    {
        return ($this->hitungPelanggaranHard($solusi) * 1000)
             + $this->hitungPelanggaranSoft($solusi);
    }

    private function hitungPelanggaranHard(array $solusi): int
    {
        $pelanggaran = 0;

        // 1. Slot kosong
        foreach ($solusi as $kelasId => $kromosom) {
            foreach ($kromosom as $slotId => $bebanIdx) {
                if ($bebanIdx === -1) {
                    $pelanggaran += 1;
                }
            }
        }

        // 2. Guru bentrok
        $guruPerSlot = [];
        foreach ($solusi as $kelasId => $kromosom) {
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

    private function hitungPelanggaranSoft(array $solusi): int
    {
        $pelanggaran = 0;
        $mapelBeratIds = Mapel::where('is_mapel_berat', true)->pluck('id')->toArray();
        $slotMap = collect($this->slots)->keyBy('id');

        foreach ($solusi as $kelasId => $kromosom) {
            // 1. Mapel berat di jam 7+
            foreach ($kromosom as $slotId => $bebanIdx) {
                if ($bebanIdx === -1) continue;
                $slot = $slotMap[$slotId] ?? null;
                if (!$slot) continue;
                
                $mapelId = $this->bebanList[$bebanIdx]['mapel_id'];
                if (in_array($mapelId, $mapelBeratIds) && $slot['jam'] >= 7) {
                    $pelanggaran += 2;
                }
            }

            // 2. Mapel dobel di hari sama
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
                        $pelanggaran += ($count - 2) * 50;
                    } elseif ($count == 2) {
                        $pelanggaran += 5;
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
                    $pelanggaran += 3;
                }
            }
        }

        return $pelanggaran;
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
                for ($j = $i; $j < min($i + 100, count($log)); $j++) {
                    if ($log[$j]['fitness_terbaik'] < $best) {
                        $stabil = false; break;
                    }
                }
                if ($stabil) return $i;
            }
        }
        return count($log);
    }

    private function formatJadwal(array $solusi): array
    {
        $hasil = [];
        foreach ($solusi as $kelasId => $kromosom) {
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