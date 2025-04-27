@extends('layouts.main')

@section('main-body')
<main class="flex flex-col flex-1 px-4 py-2 h-[calc(100vh-180px)] overflow-hidden">
    <!-- Grid 6 Hari -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-3 mx-4">
        @foreach($dates as $day)
        <div class="bg-[rgba(30,66,138,0.7)] p-3 rounded-lg shadow-lg flex flex-col">
            <div class="text-white font-semibold text-sm mb-1">Dr. Remy Alisa Cahyani</div>
            <div class="text-white text-xs">{{ $day['formatted_date'] }}</div>
            <div class="text-white text-xs mb-2">Sisa: {{ $day['remaining'] }} / 20</div>
            <div class="mt-auto">
                @if($day['date'] >= $today && $day['remaining'] > 0)
                    <button onclick="showModal('{{ $day['date'] }}', '{{ $day['formatted_date'] }}')" 
                            class="w-full py-1 px-2 bg-white text-[#000000] rounded text-xs font-semibold hover:bg-purple-100 text-center">
                        DAFTAR
                    </button>
                @else
                    <button class="w-full py-1 px-2 bg-gray-300 text-gray-500 rounded text-xs font-semibold cursor-not-allowed">
                        {{ $day['date'] < $today ? 'EXPIRED' : 'PENUH' }}
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="bg-[#C5BAFF] rounded-lg shadow mx-4 flex-1 overflow-auto">
        <h3 class="text-sm font-semibold mb-2 p-2 bg-[#C5BAFF] sticky top-0 z-10">
            Jadwal Periksa Anda
        </h3>
        
        @if($queues->isEmpty())
            <div class="p-4 text-center">
                <p class="text-gray-500 mb-2">Anda belum memiliki jadwal periksa</p>
            </div>
        @else
            <div class="overflow-y-auto" style="max-height: calc(100vh - 320px)">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#AAA2D4]">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Periksa</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Keluhan</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">No Antrian</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#AAA2D4]">
                        @foreach($queues as $queue)
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap text-sm">{{ $loop->iteration }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm">
                                {{ \Carbon\Carbon::parse($queue->date)->format('d-M-Y') }}
                            </td>
                            <td class="px-3 py-2 text-sm">{{ $queue->complaint }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm">{{ $queue->queue_number }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $queue->status == 'Menunggu' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $queue->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Modal Popup Form Keluhan -->
    <div id="complaintModal" class="fixed inset-0 bg-gray-900/20 flex items-center justify-center hidden z-50 backdrop-blur-sm">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold" id="modalDateTitle"></h3>
                <button onclick="hideModal()" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
            </div>
            <form id="complaintForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="complaint" class="block text-sm font-medium text-gray-700">KELUHAN</label>
                    <textarea id="complaint" name="complaint" rows="3" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                </div>
                <button type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    Simpan
                </button>
            </form>
        </div>
    </div>

    <script>
        function showModal(date, formattedDate) {
            const modal = document.getElementById('complaintModal');
            const form = document.getElementById('complaintForm');
            const title = document.getElementById('modalDateTitle');
            
            title.textContent = `Antrian untuk ${formattedDate}`;
            form.action = `/queue/store/${date}`; // Arahkan ke route store
            modal.classList.remove('hidden');
        }

        function hideModal() {
            document.getElementById('complaintModal').classList.add('hidden');
        }

        // Tutup modal saat klik di luar
        document.getElementById('complaintModal').addEventListener('click', function(e) {
            if (e.target === this) hideModal();
        });
    </script>
@endsection