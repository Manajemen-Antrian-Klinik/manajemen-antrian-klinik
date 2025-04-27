@extends('layouts.main')

@section('main-body')
<main class="flex flex-col flex-1 px-10 py-6 space-y-6">
    <div class="grid grid-cols-3 gap-6">
        <!-- Bagian Kiri -->
        <div class="col-span-2 space-y-6">
            <!-- Dokter yang Bertugas -->
            <section class="bg-gradient-to-r from-[#C5BAFF] via-[#C5C5FF] to-[#C4D9FF] p-6 rounded-lg shadow-md">
                <div class="flex items-center space-x-4">
                    <img src="https://via.placeholder.com/100" alt="Dokter" class="w-24 h-24 rounded-full object-cover border-2 border-gray-300">
                    <div>
                        <p class="text-lg font-semibold text-[#1E428A]">Dokter yang Bertugas</p>
                        <h2 class="text-2xl font-bold text-[#1E428A]">dr. Remy Alisa Cahyani</h2>
                    </div>
                </div>
            </section>

            <!-- Informasi Kuota dan Antrian -->
            <section class="grid grid-cols-4 gap-6">
                <!-- Kuota -->
                <div class="bg-[rgba(107,91,149,0.75)] p-6 rounded-lg shadow-md text-center">
                    <p class="text-[#ffffff] text-lg font-semibold">Kuota</p>
                    <p class="text-3xl font-bold text-[#ffffff]">{{ $queueData['total_quota'] ?? 20 }}</p>
                </div>
                <!-- Sisa Kuota -->
                <div class="bg-[rgba(107,91,149,0.75)] p-6 rounded-lg shadow-md text-center">
                    <p class="text-[#ffffff] text-lg font-semibold">Sisa Kuota</p>
                    <p class="text-3xl font-bold text-[#ffffff]">{{ $queueData['remaining_quota'] ?? 20 }}</p>
                </div>
                <!-- No Antrian Dilayani -->
                <div class="bg-[rgba(107,91,149,0.75)] p-6 rounded-lg shadow-md text-center">
                    <p class="text-[#ffffff] text-lg font-semibold">No Antrian Dilayani</p>
                    <p class="text-3xl font-bold text-[#ffffff]">{{ $queueData['current_queue'] ?? '-' }}</p>
                </div>
                <!-- Selesai Dilayani -->
                <div class="bg-[rgba(107,91,149,0.75)] p-6 rounded-lg shadow-md text-center">
                    <p class="text-[#ffffff] text-lg font-semibold">Selesai Dilayani</p>
                    <p class="text-3xl font-bold text-[#ffffff]">{{ $queueData['completed_queues'] ?? 0 }}</p>
                </div>
            </section>
        </div>
        
        <!-- Bagian Kanan -->
        <div class="bg-[rgba(107,91,149,0.75)] p-6 rounded-lg shadow-md flex flex-col items-center justify-center">
            @if($userQueue = Auth::user()->queues()->where('date', today()->format('Y-m-d'))->first())
                <div class="text-center">
                    <p class="text-[#ffffff] text-lg mb-2">Nomor Antrian Anda</p>
                    <p class="text-6xl font-bold text-[#ffffff]">{{ $userQueue->queue_number }}</p>
                </div>
            @else
                <div class="text-center">
                    <p class="text-[#ffffff]">Anda belum memiliki antrian hari ini</p>
                    <a href="{{ route('queue.index', today()->format('Y-m-d')) }}" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition inline-block">
                        Ambil Nomor Antrian
                    </a>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection