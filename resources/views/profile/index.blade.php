@extends('layouts.main')
@section('main-body')
<main class="flex flex-col flex-1 px-10 py-6 space-y-6">
    <div class="bg-[rgba(197, 186, 255, 0.3)] rounded-lg shadow-md w-full p-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-200">Identitas</h2>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded relative mb-3 text-sm" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded relative mb-3 text-sm" role="alert">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                <!-- Kolom Kiri -->
                <div>
                    <!-- Nama Lengkap -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">
                            Nama Lengkap<span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name"
                            value="{{ old('name', $user->name) }}" 
                            class="w-full px-2 py-1.5 text-sm border border-black rounded-md focus:outline-none focus:ring-1 focus:ring-blue-400"
                            required
                        >
                    </div>
                    
                    <!-- Tempat/tanggal lahir -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">
                            Tempat/tanggal lahir<span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="birth_date"
                            value="{{ old('birth_date', $user->birth_date ? date('Y-m-d', strtotime($user->birth_date)) : '') }}"
                            class="w-full px-2 py-1.5 text-sm border border-black rounded-md focus:outline-none focus:ring-1 focus:ring-blue-400"
                        >
                    </div>
                    
                    <!-- Jenis Kelamin -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">
                            Jenis Kelamin<span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="gender"
                            class="w-full px-2 py-1.5 text-sm border border-black rounded-md focus:outline-none focus:ring-1 focus:ring-blue-400 bg-white"
                        >
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki - Laki" {{ old('gender', $user->gender) == 'Laki - Laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender', $user->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    
                    <!-- Alamat -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">
                            Alamat
                        </label>
                        <input 
                            type="text" 
                            name="address"
                            value="{{ old('address', $user->address) }}" 
                            class="w-full px-2 py-1.5 text-sm border border-black rounded-md focus:outline-none focus:ring-1 focus:ring-blue-400"
                        >
                    </div>
                </div>
                
                <!-- Kolom Kanan -->
                <div>
                    <!-- Nomor Telepon -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">
                            Nomor Telepon<span class="text-red-500">*</span>
                        </label>
                        <div class="flex">
                            <span class="inline-flex items-center px-2 text-sm text-gray-500 bg-[#C4D9FF] border border-r-0 border-black rounded-l-md">
                                +62
                            </span>
                            <input 
                                type="text" 
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                class="w-full px-2 py-1.5 text-sm border border-black rounded-r-md focus:outline-none focus:ring-1 focus:ring-blue-400"
                            >
                        </div>
                    </div>
                    
                    <!-- NIK -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">
                            NIK
                        </label>
                        <input 
                            type="text" 
                            name="nik"
                            value="{{ old('nik', $user->nik) }}"
                            placeholder="Nomor Induk Kependudukan"
                            class="w-full px-2 py-1.5 text-sm border border-black rounded-md focus:outline-none focus:ring-1 focus:ring-blue-400"
                        >
                    </div>
                    
                    <!-- Nomor BPJS -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">
                            Nomor BPJS
                        </label>
                        <input 
                            type="text" 
                            name="bpjs_number"
                            value="{{ old('bpjs_number', $user->bpjs_number) }}"
                            placeholder="Nomor BPJS Kesehatan"
                            class="w-full px-2 py-1.5 text-sm border border-black rounded-md focus:outline-none focus:ring-1 focus:ring-blue-400"
                        >
                    </div>
                    
                    <!-- Tombol Simpan -->
                    <div class="mt-3">
                        <button type="submit" class="w-full px-3 py-2 bg-[#408EBA] text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-400 transition-colors duration-200 text-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection