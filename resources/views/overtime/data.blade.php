@extends('layouts.app')

@section('title', 'Overtime Data')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp
   <!-- Loading overlay -->
<div id="loadingOverlay" class="hidden absolute inset-0 bg-white bg-opacity-70 flex justify-center items-center rounded-xl z-50">
    <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>
</div>

    <div class="bg-gray-100  dark:bg-gray-900 p-1 min-h-screen">
        <h1 class="text-4xl font-extrabold mb-6  dark:bg-gray-900">
            Overtime <span class="text-black-400">Data</span>
        </h1>

        {{-- Filter --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mb-6">
            <div>
                <label for="tanggal_awal" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date From</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" class="mt-1 block w-full h-8 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring focus:ring-blue-300" value="{{ request('tanggal_awal') }}">
            </div>
            <div>
                <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date To</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="mt-1 block w-full h-8 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring focus:ring-blue-300" value="{{ request('tanggal_akhir') }}">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Filter by Status</label>
                <select name="status" id="status" class="mt-1 block w-full h-8 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring focus:ring-blue-300">
                    <option value="" disabled {{ request('status') ? '' : 'selected' }}>Status</option>
                    <option value="all">All</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="f_approve" {{ request('status') == 'f_approve' ? 'selected' : '' }}>Waiting</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="refused" {{ request('status') == 'refused' ? 'selected' : '' }}>Refused</option>
                </select>
            </div>
            <div class="flex space-x-4">
                <button type="button" id="filterButton" class="h-8 px-6 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Filter</button>
                <button type="button" id="printButton" class="h-8 px-6 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Print</button>
            </div>
        </div>
        {{-- Tabel Data --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow max-h-[500px] overflow-auto">
            <table class="min-w-[1200px] table-auto border-collapse border border-gray-300 dark:border-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700 text-left text-sm text-gray-600 dark:text-gray-200 uppercase sticky top-0 z-10">
                    <tr>
                        <th class="py-3 px-4 border border-gray-300 dark:border-gray-700">Code OVT</th>
                        <th class="py-3 px-4 border border-gray-300 dark:border-gray-700">Nik</th>
                        <th class="py-3 px-4 border border-gray-300 dark:border-gray-700">Employee</th>
                        <th class="py-3 px-4 border border-gray-300 dark:border-gray-700">Duration Type</th>
                        <th class="py-3 px-4 border border-gray-300 dark:border-gray-700">Date From</th>
                        <th class="py-3 px-4 border border-gray-300 dark:border-gray-700">Date To</th>
                        <th class="py-3 px-4 border border-gray-300 dark:border-gray-700">Duration</th>
                        <th class="py-3 px-4 border border-gray-300 dark:border-gray-700">Actual Hours</th>
                        <th class="py-3 px-4 border border-gray-300 dark:border-gray-700">Status</th>
                        <th class="py-3 px-4 border border-gray-300 dark:border-gray-700">Author</th> 
                    </tr>
                </thead>
                @php
    $statusFilter = request('status');
    $tanggalAwal = request('tanggal_awal');
    $tanggalAkhir = request('tanggal_akhir');
@endphp

<tbody class="text-sm text-gray-800 dark:text-gray-100 divide-y divide-gray-200 dark:divide-gray-700">
    @if(!$statusFilter && !$tanggalAwal && !$tanggalAkhir)
        <tr>
            <td colspan="10" class="text-center py-4 px-4 text-gray-500 dark:text-gray-400 italic dark:bg-gray-900">Silakan pilih filter status atau tanggal terlebih dahulu, lalu klik tombol Filter.</td>
        </tr>
    @else
        @forelse ($overtimeData as $overtime)
            <tr class="dark:bg-gray-900">
                <td class="py-2 px-4 border dark:border-gray-700">{{ $overtime['name'] ?? '-' }}</td>
                <td class="py-2 px-4 border dark:border-gray-700">{{ $overtime['employee_barcode'] ?? '-' }}</td>
                <td class="py-2 px-4 border dark:border-gray-700">
                    @if(is_array($overtime['employee_id']) && isset($overtime['employee_id'][0]) && is_array($overtime['employee_id'][0]))
                        {{ implode(', ', array_map(fn($e) => $e[1], $overtime['employee_id'])) }}
                    @else
                        {{ is_array($overtime['employee_id']) ? ($overtime['employee_id'][1] ?? '-') : '-' }}
                    @endif
                </td>
                <td class="py-2 px-4 border dark:border-gray-700">{{ $overtime['duration_type'] ?? '-' }}</td>
                <td class="py-2 px-4 border dark:border-gray-700">{{ $overtime['date_from'] ? Carbon::parse($overtime['date_from'])->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') : '-' }}</td>
                <td class="py-2 px-4 border dark:border-gray-700">{{ $overtime['date_to'] ? Carbon::parse($overtime['date_to'])->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') : '-' }}</td>
                <td class="py-2 px-4 border dark:border-gray-700">{{ $overtime['days_no_tmp'] ?? '-' }}</td>
                <td class="py-2 px-4 border dark:border-gray-700">{{ $overtime['actual_ovt'] ?? '-' }}</td>
                <td class="py-2 px-4 border dark:border-gray-700">
                    @php
                    $status = $overtime['state'] ?? 'unknown';
                
                    // Ubah nama status 'f_approve' menjadi 'waiting'
                    $displayStatus = strtolower($status) === 'f_approve' ? 'waiting' : $status;
                
                    $statusColor = match (strtolower($status)) {
                        'draft' => 'bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-white',
                        'f_approve' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-400 dark:text-black',
                        'approved' => 'bg-green-100 text-green-800 dark:bg-green-500 dark:text-white',
                        'refused' => 'bg-red-100 text-red-800 dark:bg-red-500 dark:text-white',
                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-500 dark:text-white',
                    };
                @endphp
                
                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                    {{ ucfirst($displayStatus) }}
                </span>
                
                </td>
                <td class="py-2 px-4 border dark:border-gray-700">
                    @if(is_array($overtime['overtime_author']))
                        {{ $overtime['overtime_author'][1] ?? '-' }}
                    @elseif(is_object($overtime['overtime_author']))
                        {{ $overtime['overtime_author']->name ?? '-' }}
                    @else
                        {{ $overtime['overtime_author'] ?? '-' }}
                    @endif
                </td>
                
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center py-4 px-4 text-gray-500 dark:text-gray-400 italic dark:bg-gray-900">Data untuk filter status dan tanggal tersebut tidak ada.</td>
            </tr>
        @endforelse
    @endif
</tbody>
            </table>
        </div>
    </div>
    @php
    $authorName = auth()->user()->name ?? 'Unknown User';
@endphp
<script>
    const author = @json($authorName);

    document.getElementById('printButton').addEventListener('click', function () {
        const table = document.querySelector('table');

        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateString = now.toLocaleDateString('id-ID', options);

        let hours = now.getHours();
        let minutes = now.getMinutes().toString().padStart(2, '0');
        let waktu = '';

        if (hours < 12) {
            waktu = `${hours}:${minutes} pagi`;
        } else if (hours < 15) {
            waktu = `${hours}:${minutes} siang`;
        } else if (hours < 18) {
            waktu = `${hours}:${minutes} sore`;
        } else {
            waktu = `${hours}:${minutes} malam`;
        }

        const timestamp = `${dateString}, pukul ${waktu}`;

        const dateCode = now.toISOString().slice(0,10).replace(/-/g, '');
        const timeCode = now.toTimeString().slice(0,8).replace(/:/g, '');
        const randomCode = Math.random().toString(36).substring(2, 8).toUpperCase();
        const uniqueCode = `OVR-${dateCode}-${timeCode}-${randomCode}`;

        const newWin = window.open('', '', 'width=800,height=600');
        newWin.document.write(`
            <html>
                <head>
                    <title>Print Overtime Data - ${uniqueCode}</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                        th { background-color: #f0f0f0; }
                        .footer {
                            margin-top: 20px;
                            font-size: 12px;
                            color: #555;
                            border-top: 1px dashed #aaa;
                            padding-top: 10px;
                        }
                        .signature-section {
                            margin-top: 40px;
                            display: flex;
                            justify-content: space-between;
                            text-align: center;
                        }
                        .signature-box {
                            width: 40%;
                        }
                        .signature-space {
                            height: 60px;
                            border-bottom: 1px solid #000;
                            margin: 30px 0 10px;
                        }
                    </style>
                </head>
                <body>
                    <h2>Overtime Request Data - ${uniqueCode}</h2>
                    ${table.outerHTML}

                    <div class="footer">
                        Dicetak pada: ${timestamp}<br>
                        Kode Dokumen: ${uniqueCode}<br>
                        Author: ${author}
                    </div>

                    <div class="signature-section">
                        <div class="signature-box">
                            <strong>Kabag HRDGA</strong>
                            <div class="signature-space"></div>
                        </div>
                        <div class="signature-box">
                            <strong>Factory Manager</strong>
                            <div class="signature-space"></div>
                        </div>
                    </div>
                </body>
            </html>
        `);
        newWin.document.close();
        newWin.focus();

        newWin.print();
        newWin.close();
    });
</script>


    {{-- Filter Script --}}
    <script>
        document.getElementById('filterButton').addEventListener('click', function () {
            const tanggalAwal = document.getElementById('tanggal_awal').value;
            const tanggalAkhir = document.getElementById('tanggal_akhir').value;
            const status = document.getElementById('status').value;

            let url = '{{ route("overtime.data") }}';
            const params = new URLSearchParams();

            if (tanggalAwal) params.append('tanggal_awal', tanggalAwal);
            if (tanggalAkhir) params.append('tanggal_akhir', tanggalAkhir);
            if (status) params.append('status', status);

            if ([...params].length) {
                url += '?' + params.toString();
            }

            // Show spinner
            $('#overtimeForm').on('filter', function() {
    $('#loadingOverlay').removeClass('hidden');
});
            // Redirect after short delay to show spinner
            setTimeout(() => {
                window.location.href = url;
            }, 300);
        });
    </script>
   <script>
document.getElementById('filterButton').addEventListener('click', function () {
    document.getElementById('loadingOverlay').classList.remove('hidden');

    const tanggalAwal = document.getElementById('tanggal_awal').value;
    const tanggalAkhir = document.getElementById('tanggal_akhir').value;
    const status = document.getElementById('status').value;

    const params = new URLSearchParams();

    // Hanya append jika ada isinya (tanggal boleh kosong)
    if (tanggalAwal) params.append('tanggal_awal', tanggalAwal);
    if (tanggalAkhir) params.append('tanggal_akhir', tanggalAkhir);

    // Status tetap selalu di-append (termasuk "" untuk All)
    params.append('status', status);

    // Reload page dengan semua parameter yg valid
    window.location.href = `?${params.toString()}`;
});

</script>  
@endsection