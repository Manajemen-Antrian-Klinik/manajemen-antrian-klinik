<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Queue;
use App\Models\Antrian;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AntrianUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function antrian_can_generate_nomor_antrian()
    {
        // Menggunakan tanggal dinamis
        $todayDate = Carbon::today()->format('Y-m-d');
        
        // Membuat user untuk antrian
        $user = User::factory()->create();

        // Membuat jadwal dengan tanggal hari ini
        Queue::factory()->create([
            'date' => $todayDate,
            'daily_quota' => 20,
            'queue_number' => 1, // Pastikan queue_number tidak di-set di jadwal
            'user_id' => $user->id, // Pastikan user_id null untuk jadwal
        ]);

        // Pastikan data sudah ada
        $this->assertDatabaseHas('queues', [
            'date' => $todayDate,
            'daily_quota' => 20
        ]);

        // Test when no antrian exists yet
        $lastQueue = Queue::where('date', $todayDate)->max('queue_number');
        $nomorAntrian = $lastQueue === null ? 1 : $lastQueue + 1;
        $this->assertEquals(1, $nomorAntrian);

        // Membuat antrian yang sudah ada
        Queue::create([
            'user_id' => $user->id,
            'date' => $todayDate,
            'queue_number' => 1
        ]);

        // Pastikan data antrian sudah ada
        $this->assertDatabaseHas('queues', [
            'user_id' => $user->id,
            'date' => $todayDate,
            'queue_number' => 1
        ]);

        // Test nomor berikutnya
        $lastQueue = Queue::where('date', $todayDate)->max('queue_number');
        $nomorAntrian = $lastQueue + 1;
        $this->assertEquals(2, $nomorAntrian);
    }


    /** @test */
    public function antrian_validates_required_fields()
    {
        $antrian = new Queue();
        
        $this->assertFalse($antrian->isValid([
            'user_id' => null,
            'date' => null,
            'complaint' => null
        ]));
    }

    /** @test */
    public function antrian_calculates_sisa_kuota_correctly()
    {
        // Membuat user dan jadwal yang diperlukan
        $user = User::factory()->create();
        $jadwal = Queue::factory()->create(['date' => Carbon::today()->format('Y-m-d'), 'daily_quota' => 20]);

        // Buat beberapa antrian
        Queue::factory()->count(3)->create(['user_id' => $user->id, 'date' => $jadwal->date]);

        // Hitung sisa kuota
        $remainingQuota = $jadwal->daily_quota - Queue::where('date', $jadwal->date)->count();
        $this->assertEquals(17, $remainingQuota); // Sisa kuota harus 17
    }


    /** @test */
    public function user_can_check_if_already_has_antrian_today()
    {
        $user = User::factory()->create();
        // Menggunakan query untuk memeriksa apakah pengguna sudah memiliki antrian pada tanggal tersebut
        $todayDate = Carbon::today()->format('Y-m-d');

        // User belum punya antrian
        $this->assertFalse(Queue::where('user_id', $user->id)->where('date', $todayDate)->exists());

        // Membuat antrian untuk user pada hari ini
        Queue::factory()->create([
            'user_id' => $user->id,
            'date' => $todayDate
        ]);

        // User sudah punya antrian
        $this->assertTrue(Queue::where('user_id', $user->id)->where('date', $todayDate)->exists());
    }

    /** @test */
    public function antrian_status_defaults_to_menunggu()
    {
        $antrian = Queue::factory()->create();
        $this->assertEquals('Menunggu', $antrian->status); // Sesuaikan huruf kapital
    }

    /** @test */
    public function antrian_keluhan_can_be_empty()
    {
        $antrian = Queue::factory()->create(['complaint' => null]);
        $this->assertNull($antrian->keluhan);

        $antrian = Queue::factory()->create(['complaint' => '']);
        $this->assertEquals('', $antrian->keluhan);
    }
}