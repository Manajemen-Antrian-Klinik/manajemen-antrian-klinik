@extends('layouts.main')

@section('main-body')
<main class="flex flex-col flex-1 px-10 py-6 space-y-6">
    <div class="grid grid-cols-3 gap-6">
        <!-- Bagian Kiri -->
        <div class="col-span-2 space-y-6">
            <!-- Dokter yang Bertugas -->
            <section class="bg-gradient-to-r from-purple-100 to-blue-200 p-6 rounded-lg shadow-md">
                <div class="flex items-center space-x-4">
                    <img src="https://via.placeholder.com/100" alt="Dokter" class="w-24 h-24 rounded-full object-cover border-2 border-gray-300">
                    <div>
                        <p class="text-lg font-semibold text-gray-600">Dokter yang Bertugas</p>
                        <h2 class="text-2xl font-bold text-blue-700">dr. Remy Alisa Cahyani</h2>
                    </div>
                </div>
            </section>

            <!-- Informasi Kuota dan Antrian -->
            <section class="grid grid-cols-4 gap-6">
                <!-- Kuota -->
                <div class="bg-gray-200 p-6 rounded-lg shadow-md text-center">
                    <p class="text-gray-600 text-lg font-semibold">Kuota</p>
                    <p class="text-3xl font-bold text-blue-700">60</p>
                </div>
                <!-- Sisa Kuota -->
                <div class="bg-gray-200 p-6 rounded-lg shadow-md text-center">
                    <p class="text-gray-600 text-lg font-semibold">Sisa Kuota</p>
                    <p class="text-3xl font-bold text-blue-700">20</p>
                </div>
                <!-- No Antrian Dilayani -->
                <div class="bg-gray-200 p-6 rounded-lg shadow-md text-center">
                    <p class="text-gray-600 text-lg font-semibold">No Antrian Dilayani</p>
                    <p class="text-3xl font-bold text-blue-700">17</p>
                </div>
                <!-- Selesai Dilayani -->
                <div class="bg-gray-200 p-6 rounded-lg shadow-md text-center">
                    <p class="text-gray-600 text-lg font-semibold">Selesai Dilayani</p>
                    <p class="text-3xl font-bold text-blue-700">16</p>
                </div>
            </section>
        </div>

        <!-- Bagian Kanan -->
        <div class="bg-gray-100 p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-600">Bagian Kanan</h2>
            <p class="text-gray-700">Ini adalah konten untuk bagian kanan halaman.</p>
            <!-- Anda dapat menambahkan konten lain di sini -->
        </div>
    </div>
</main>
@endsection