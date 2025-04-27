@extends('layouts.main')

@section('main-body')
<div class="flex-grow container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Pembayaran Pasien</h1>
        
        <!-- Dropdown Pilih Tanggal -->
        <div class="relative">
            <select id="date-selector" class="block appearance-none bg-blue-200 border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:border-blue-500">
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

    <!-- Filter Status Pembayaran -->
    <div class="mb-6 flex space-x-2">
        <a href="{{ route('admin.pembayaran.index', ['date' => $selectedDate->format('Y-m-d'), 'status' => 'semua']) }}" 
            class="px-4 py-2 rounded {{ request('status', 'semua') === 'semua' ? 'bg-[#4A90E2] text-white' : 'bg-[#ffffff] text-gray-800' }}">
            Semua
        </a>
        <a href="{{ route('admin.pembayaran.index', ['date' => $selectedDate->format('Y-m-d'), 'status' => 'belum-bayar']) }}" 
            class="px-4 py-2 rounded {{ request('status') === 'belum-bayar' ? 'bg-[#FF6F61] text-white' : 'bg-[#ffffff] text-gray-800' }}">
            Belum Bayar
        </a>
        <a href="{{ route('admin.pembayaran.index', ['date' => $selectedDate->format('Y-m-d'), 'status' => 'sudah-bayar']) }}" 
            class="px-4 py-2 rounded {{ request('status') === 'sudah-bayar' ? 'bg-[#7ED321] text-white' : 'bg-[#ffffff] text-gray-800' }}">
            Sudah Bayar
        </a>
    </div>

    <div class="bg-[#C5BAFF] shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        No. Antrian
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Nama Pasien
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Status Antrian
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Biaya
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Status Pembayaran
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr class="hover:bg-[#C5BAFF]">
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap font-semibold">
                            {{ $payment->queue->queue_number }}
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{ $payment->queue->patient->name }}
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold rounded-full
                            {{ $payment->queue->status === 'Selesai' ? 'bg-green-200 text-green-900' : 
                            ($payment->queue->status === 'Dalam Pemeriksaan' ? 'bg-orange-200 text-orange-900' : 'bg-gray-200 text-gray-900') }}">
                            {{ $payment->queue->status }}
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold rounded-full
                            {{ $payment->status === 'paid' ? 'bg-green-200 text-green-900' : 'bg-red-200 text-red-900' }}">
                            {{ $payment->status === 'paid' ? 'Sudah Bayar' : 'Belum Bayar' }}
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <div class="flex space-x-2">
                            @if($payment->status === 'unpaid' && $payment->queue->status === 'Selesai')
                                <button onclick="openPaymentModal('{{ $payment->id }}')" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded text-sm transition-colors">
                                    Proses Pembayaran
                                </button>
                            @endif
                            
                            @if($payment->status === 'paid')
                                <button onclick="openReceiptModal('{{ $payment->id }}')" class="text-white bg-green-500 hover:bg-green-600 px-3 py-1 rounded text-sm">
                                    Lihat Kuitansi
                                </button>
                            @endif
                            
                            <button onclick="openEditPaymentModal('{{ $payment->id }}')" class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded text-sm">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-[#C5BAFF] text-sm text-center">
                        <p class="text-gray-500">Tidak ada data pembayaran untuk tanggal ini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-5 py-5 bg-[#C5BAFF] border-t flex flex-col xs:flex-row items-center xs:justify-between">
            {{ $payments->appends(['date' => $selectedDate->format('Y-m-d'), 'status' => request('status', 'semua')])->links() }}
        </div>
    </div>
</div>

<!-- Modal Proses Pembayaran -->
<div id="paymentModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="paymentForm" action="{{ route('admin.pembayaran.process') }}" method="POST">
                @csrf
                <input type="hidden" name="payment_id" id="payment_id">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Proses Pembayaran
                            </h3>
                            
                            <div class="mb-4">
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pembayaran</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="text" name="amount" id="amount" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md py-2" placeholder="0">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                                <select name="payment_method" id="payment_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="cash">Tunai</option>
                                    <option value="debit">Debit</option>
                                    <option value="credit">Kartu Kredit</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea name="notes" id="notes" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Proses Pembayaran
                    </button>
                    <button type="button" onclick="closePaymentModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pembayaran -->
<div id="editPaymentModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="editPaymentForm" action="{{ route('admin.pembayaran.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_payment_id" id="edit_payment_id">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Edit Pembayaran
                            </h3>
                            
                            <div class="mb-4">
                                <label for="edit_amount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pembayaran</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="text" name="amount" id="edit_amount" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md py-2" placeholder="0">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="edit_payment_method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                                <select name="payment_method" id="edit_payment_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="cash">Tunai</option>
                                    <option value="debit">Debit</option>
                                    <option value="credit">Kartu Kredit</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                                <select name="status" id="edit_status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="unpaid">Belum Bayar</option>
                                    <option value="paid">Sudah Bayar</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="edit_notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea name="notes" id="edit_notes" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeEditPaymentModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Kuitansi -->
<div id="receiptModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Kuitansi Pembayaran
                            </h3>
                            <div class="text-sm text-gray-500" id="receipt_date">
                                <!-- Tanggal Kuitansi -->
                            </div>
                        </div>
                        
                        <div class="border-t border-b border-gray-200 py-4 mb-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600">No. Kuitansi:</span>
                                <span class="text-sm font-medium" id="receipt_number"></span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600">Nama Pasien:</span>
                                <span class="text-sm font-medium" id="receipt_patient_name"></span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600">No. Antrian:</span>
                                <span class="text-sm font-medium" id="receipt_queue_number"></span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4 mb-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600">Total Biaya:</span>
                                <span class="text-md font-bold" id="receipt_amount"></span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600">Metode Pembayaran:</span>
                                <span class="text-sm font-medium" id="receipt_payment_method"></span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600">Status:</span>
                                <span class="relative inline-block px-3 py-1 font-semibold text-sm rounded-full bg-green-200 text-green-900">
                                    Lunas
                                </span>
                            </div>
                        </div>
                        
                        <div class="text-sm text-gray-700 mb-4">
                            <p class="italic" id="receipt_notes"></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeReceiptModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date selector functionality
        const dateSelector = document.getElementById('date-selector');
        if (dateSelector) {
            dateSelector.addEventListener('change', function() {
                const selectedDate = this.value;
                const url = new URL(window.location.href);
                
                // Update parameter date
                url.searchParams.set('date', selectedDate);
                
                // Reload halaman dengan parameter baru
                window.location.href = url.toString();
            });
        }
        
        // Format input number to currency
        const amountInputs = document.querySelectorAll('#amount, #edit_amount');
        amountInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                // Remove non-numeric characters
                let value = this.value.replace(/\D/g, '');
                
                // Format with thousand separator
                if (value !== '') {
                    value = parseInt(value, 10).toLocaleString('id-ID');
                }
                
                this.value = value;
            });
        });
    });

    // Payment Modal Functions
    function openPaymentModal(paymentId) {
        document.getElementById('payment_id').value = paymentId;
        document.getElementById('paymentModal').classList.remove('hidden');
        
        // Load payment details via AJAX
        fetch(`/adm-payment/${paymentId}/details`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('amount').value = data.amount ? parseInt(data.amount).toLocaleString('id-ID') : '';
                if (data.payment_method) {
                    document.getElementById('payment_method').value = data.payment_method;
                }
                document.getElementById('notes').value = data.notes || '';
            })
            .catch(error => console.error('Error:', error));
    }
    
    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }
    
    // Edit Payment Modal Functions
    function openEditPaymentModal(paymentId) {
        document.getElementById('edit_payment_id').value = paymentId;
        document.getElementById('editPaymentModal').classList.remove('hidden');
        
        // Load payment details via AJAX
        fetch(`/adm-payment/${paymentId}/details`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_amount').value = data.amount ? parseInt(data.amount).toLocaleString('id-ID') : '';
                if (data.payment_method) {
                    document.getElementById('edit_payment_method').value = data.payment_method;
                }
                if (data.status) {
                    document.getElementById('edit_status').value = data.status;
                }
                document.getElementById('edit_notes').value = data.notes || '';
            })
            .catch(error => console.error('Error:', error));
    }
    
    function closeEditPaymentModal() {
        document.getElementById('editPaymentModal').classList.add('hidden');
    }
    
    // Receipt Modal Functions
    function openReceiptModal(paymentId) {
        document.getElementById('receiptModal').classList.remove('hidden');
        
        // Load receipt details via AJAX
        fetch(`/adm-payment/${paymentId}/receipt`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('receipt_number').textContent = data.receipt_number;
                document.getElementById('receipt_date').textContent = data.formatted_date;
                document.getElementById('receipt_patient_name').textContent = data.patient_name;
                document.getElementById('receipt_queue_number').textContent = data.queue_number;
                document.getElementById('receipt_amount').textContent = `Rp ${parseInt(data.amount).toLocaleString('id-ID')}`;
                
                let paymentMethod = 'Tunai';
                if (data.payment_method === 'debit') paymentMethod = 'Kartu Debit';
                if (data.payment_method === 'credit') paymentMethod = 'Kartu Kredit';
                if (data.payment_method === 'transfer') paymentMethod = 'Transfer Bank';
                
                document.getElementById('receipt_payment_method').textContent = paymentMethod;
                document.getElementById('receipt_notes').textContent = data.notes || 'Terima kasih atas kunjungan Anda.';
            })
            .catch(error => console.error('Error:', error));
    }
    
    function closeReceiptModal() {
        document.getElementById('receiptModal').classList.add('hidden');
    }
</script>
@endsection