@extends('layouts.main')

@section('main-body')
<main class="flex flex-col flex-1 px-10 py-6 space-y-6">
    <div class="w-full space-y-6">
        <!-- Dokter yang Bertugas -->
        <section class="bg-gradient-to-r from-[#C5BAFF] via-[#C5C5FF] to-[#C4D9FF] p-6 rounded-lg shadow-md">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('storage/img/doctor.jpg') }}" alt="Dokter" class="w-24 h-24">
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
</main>
@endsection