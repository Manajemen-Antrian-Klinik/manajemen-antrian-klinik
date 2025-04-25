@extends('layouts.main')

@section('main-body')
<div class="flex-grow container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Antrian</h1>
        
        <!-- Dropdown Pilih Tanggal -->
        <div class="relative">
            <select id="date-selector" class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:border-blue-500">
                @foreach($availableDates as $dateItem)
                <option value="{{ $dateItem['date']->format('Y-m-d') }}" 
                    {{ $dateItem['date']->format('Y-m-d') === $selectedDate->format('Y-m-d') ? 'selected' : '' }}>
                    {{ $dateItem['formatted'] }}
                </option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        No. Antrian
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Nama
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Alamat
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        TTL
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        No. Telp
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Keluhan
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($queues as $queue)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap font-semibold">
                            {{ $queue->queue_number }}
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{ $queue->patient->name }}
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{ $queue->patient->address }}
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{ optional($queue->patient->birth_date)->format('d-m-Y') ?? '-'}}
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{ $queue->patient->phone }}
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{ $queue->complaint }}
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold rounded-full
                            {{ $queue->status === 'Selesai' ? 'bg-green-200 text-green-900' : 
                            ($queue->status === 'Dalam Pemeriksaan' ? 'bg-orange-200 text-orange-900' : 'bg-gray-200 text-gray-900') }}">
                            {{ $queue->status }}
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <div class="flex space-x-2">
                            <!-- Next Button -->
                            <form action="{{ route('admin.antrian.next', $queue->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded text-sm transition-colors
                                    {{ $queue->status === 'Selesai' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ $queue->status === 'Selesai' ? 'disabled' : '' }}>
                                    Next
                                </button>
                            </form>
                            
                            <!-- Previous Button -->
                            <form action="{{ route('admin.antrian.previous', $queue->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded text-sm transition-colors
                                    {{ $queue->status !== 'Dalam Pemeriksaan' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ $queue->status !== 'Dalam Pemeriksaan' ? 'disabled' : '' }}>
                                    Previous
                                </button>
                            </form>
                            
                            <!-- Edit Button -->
                            <button onclick="openEditModal('{{ $queue->id }}')" class="text-white bg-green-500 hover:bg-green-600 px-3 py-1 rounded text-sm">
                                Edit
                            </button>
                            
                            <!-- Delete Button -->
                            <form action="{{ route('admin.antrian.destroy', $queue->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm" 
                                    onclick="return confirm('Yakin ingin menghapus antrian ini?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <!-- Modal content -->
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const dateSelector = document.getElementById('date-selector');
    
    dateSelector.addEventListener('change', function() {
        const selectedDate = this.value;
        const url = new URL(window.location.href);
        
        // Update parameter date
        url.searchParams.set('date', selectedDate);
        
        // Reload halaman dengan parameter baru
        window.location.href = url.toString();
    });
});
</script>
@endsection