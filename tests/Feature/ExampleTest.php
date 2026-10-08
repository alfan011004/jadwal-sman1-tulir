<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\HasilOptimasi;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\SlotWaktu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertViewHas('stats', [
                'total_guru' => 0,
                'guru_pengajar' => 0,
                'guru_bk' => 0,
                'total_mapel' => 0,
                'total_kelas' => 0,
                'total_jp' => 0,
                'total_slot' => 0,
            ])
            ->assertViewHas('riwayat');
    }

    public function test_optimization_pages_and_printed_schedule_render(): void
    {
        $this->get(route('optimasi.index'))->assertOk();
        $this->get(route('optimasi.perbandingan'))->assertOk()->assertSee('Belum ada hasil optimasi.');

        $guru = Guru::create(['kode' => '1', 'nama' => 'Guru Uji']);
        $mapel = Mapel::create(['kode' => 'A', 'nama' => 'Matematika', 'is_mapel_berat' => true]);
        $kelas = Kelas::create(['nama' => 'X.1', 'tingkat' => 'X']);
        $slot = SlotWaktu::create([
            'hari' => 'Senin',
            'jam_ke' => 1,
            'jam_mulai' => '07:30',
            'jam_selesai' => '08:15',
            'is_istirahat' => false,
        ]);
        $hasil = HasilOptimasi::create([
            'algoritma' => 'GA',
            'parameter' => [],
            'fitness_terbaik' => 0,
            'waktu_komputasi' => 0.1,
            'iterasi_konvergen' => 1,
            'pelanggaran_hard' => 0,
            'pelanggaran_soft' => 0,
            'jadwal' => [[
                'kelas_id' => $kelas->id,
                'slot_id' => $slot->id,
                'guru_id' => $guru->id,
                'mapel_id' => $mapel->id,
            ]],
            'log_konvergensi' => [],
        ]);

        $this->get(route('optimasi.perbandingan'))
            ->assertOk()
            ->assertSee('GA');

        $this->view('optimasi.hasil', [
            'hasil' => ['GA' => [
                'fitness_terbaik' => 0,
                'waktu_komputasi' => 0.1,
                'iterasi_konvergen' => 1,
                'pelanggaran_hard' => 0,
                'pelanggaran_soft' => 0,
                'log_konvergensi' => [['generasi' => 1, 'fitness_terbaik' => 0]],
            ]],
            'hasilIds' => ['GA' => $hasil->id],
        ])->assertSee(route('cetak.jadwal', $hasil->id));

        $response = $this->get(route('cetak.jadwal', $hasil->id));

        $response
            ->assertOk()
            ->assertSee('JADWAL TUGAS MENGAJAR')
            ->assertSee('data:image/png;base64,iVBORw0KGgo', false)
            ->assertSee('id="semesterInput"', false)
            ->assertSee('id="tahunAjaranInput"', false)
            ->assertSee('id="tanggalCetakInput"', false)
            ->assertSee('Matematika')
            ->assertSee('Guru Uji');

        $this->assertSame(2, substr_count($response->getContent(), 'src="data:image/png;base64,'));
    }
}
