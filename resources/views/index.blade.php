@extends('layouts.main')

@section('main-body')
<!-- Main Content -->
<main class="flex flex-1 justify-between items-center ml-10 pl-4 pt-4 pb-4 pr-25 mr-20">
    <div class="max-w-md space-y-4">
        <h1 class="text-3xl font-bold text-gray-800">SELAMAT DATANG!</h1>
        <h2 class="text-2xl font-bold text-blue-800">KLINIK HESTIA MEDIKA</h2>
        <h3 class="text-xl font-bold text-gray-800">SIAP MELAYANI ANDA</h3>
        <p class="text-gray-600">
            Kami menyediakan layanan kesehatan yang cepat, nyaman, dan terpercaya.
        </p>
    </div>
    
    <div class="relative w-64 h-64">
        <!-- Lingkaran Background -->
        <div class="absolute inset-0 rounded-full bg-[#C5BAFF]/30"></div>
    
        <!-- Gambar Dokter -->
        <img src="{{ asset('storage/img/doctor.jpg') }}" alt="Dokter"
            class="absolute left-1/2 -translate-x-1/2 bottom-0 w-64 h-auto z-10 rounded-b-full object-cover" />
    </div>
    
</main>
@endsection

