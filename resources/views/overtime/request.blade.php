@extends('layouts.app')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-xl shadow-md w-full max-w-[1400px] mx-auto overflow-x-auto relative">

    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">Overtime Request Form</h1>

    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    <div class="mb-3 flex items-center gap-3">
    <label class="form-label">Batch:</label>
    <div id="masterBatchDisplay" class="fw-bold">loading...</div>
    <button type="button" id="generateBatchBtn" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
        Generate Batch
    </button>
    </div>    
    <form id="overtimeForm" method="POST" action="{{ route('overtime.submit') }}" enctype="multipart/form-data">
        @csrf  
            <!-- Loading overlay -->
<div id="loadingOverlay" class="hidden fixed inset-0 bg-white bg-opacity-70 flex justify-center items-center z-50">
    <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>
</div>
        <div class="mb-4 hidden">
            <label for="overtime_author_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Author</label>
            <input type="text" id="overtime_author_name" class="w-full max-w-sm px-3 py-2 border rounded-md shadow-sm bg-gray-100 dark:bg-gray-800 dark:text-gray-100" value="{{ auth()->user()->name }}" readonly>
            
            <input type="hidden" name="overtime_author" value="{{ auth()->user()->employee_id }}">
        </div>        
        <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Overtime Lines</h2>
            <table class="min-w-full border border-gray-300 dark:border-gray-700 text-sm">
                <thead class="bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-200">
                    <tr>
                        <th class="border p-2">Employee</th>
                        <th class="border p-2">Dept</th>
                        <th class="border p-2">Job</th>
                        <th class="border p-2">Manager</th>
                        <th class="border p-2">Type</th>
                        <th class="border p-2">Contract</th>
                        <th class="border p-2">Duration</th>
                        <th class="border p-2">Date From</th>
                        <th class="border p-2">Date To</th>
                        <th class="border p-2">Actual Hours</th>
                        <th class="border p-2">Action</th>
                    </tr>
                </thead>
                <tbody id="overtimeLines" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    <!-- Dynamic lines will be added here -->
                </tbody>
            </table>

            <!-- Hidden inputs to hold joined string data -->
            <input type="hidden" name="employee_id" id="employee_id" />
            <input type="hidden" name="department_id" id="department_id" />
            <input type="hidden" name="job_id" id="job_id" />
            <input type="hidden" name="manager_id" id="manager_id" />
            <input type="hidden" name="type" id="type" />
            <input type="hidden" name="contract_id" id="contract_id" />
            <input type="hidden" name="duration_type" id="duration_type" />
            <input type="hidden" name="date_from" id="date_from" />
            <input type="hidden" name="date_to" id="date_to" />

            <div class="mt-6 flex justify-between items-center gap-4">
                <button type="button" onclick="addLine()"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-md shadow transition-colors duration-200">
                    + Add Line
                </button>
            
                <button type="submit"
                    class="font-medium text-sm py-1.5 px-5 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 active:scale-95 transition-all text-white dark:bg-blue-700 dark:hover:bg-blue-800">
                    Submit
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    
    function showLoading() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').classList.add('hidden');
    }
    document.getElementById('overtimeForm').addEventListener('submit', function() {
    showLoading();
    localStorage.removeItem('currentBatch');
});

   async function loadAuthors() {
    try {
        const response = await fetch('http://localhost:3000/overtime/admin-employees');
        const data = await response.json();
        if (data.status === 'success' && Array.isArray(data.data)) {
            const select = document.getElementById('overtime_author');
            select.innerHTML = ''; // kosongkan dulu
            data.data.forEach(author => {
                const option = document.createElement('option');
                option.value = author.id;
                option.textContent = author.name;
                select.appendChild(option);
            });
        } else {
            console.error('Gagal load data author admin:', data);
        }
    } catch (error) {
        console.error('Error fetch author admin:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    tampilkanBatch();
    loadAuthors().then(() => {
        $('#overtime_author').select2({
            placeholder: 'Select an author',
            allowClear: true
            // tidak pakai ajax di sini
        });
    });
});


function fetchLastBatch() {
    return fetch('http://localhost:3000/overtime/master-batch')
        .then(response => response.json())
        .then(data => {
            if (!data || !data.new_batch || !data.new_batch.name) {
                return null;
            }
            return data.new_batch.name;
        })
        .catch(err => {
            console.error('Fetch error:', err);
            return null;
        });
}

function generateNewBatch(lastBatch) {
    const today = new Date().toISOString().slice(0, 10).replace(/-/g, '');

    if (!lastBatch) {
        // Jika batch terakhir kosong, mulai dari 0001
        return `BATCH-${today}-0001`;
    }

    const parts = lastBatch.split('-');
    if (parts.length !== 3) {
        // Format batch tidak valid, mulai dari 0001
        return `BATCH-${today}-0001`;
    }

    const prefix = `${parts[0]}-${parts[1]}`;  // Contoh: BATCH-20250527
    const lastNumber = parseInt(parts[2], 10);

    if (parts[1] !== today) {
        // Kalau batch terakhir bukan dari hari ini, reset nomor ke 0001
        return `BATCH-${today}-0001`;
    }

    // Kalau batch terakhir sudah dari hari ini, nomor bertambah 1
    const newNumber = lastNumber.toString().padStart(4, '0');
    return `${prefix}-${newNumber}`;
}

async function fetchLastBatch() {
    try {
        const response = await fetch('http://localhost:3000/overtime/master-batch');
        const data = await response.json();
        if (!data || !data.new_batch || !data.new_batch.name) {
            return null;
        }
        // Backend sudah kasih batch terbaru yang sudah +1
        return data.new_batch.name;
    } catch (err) {
        console.error('Fetch error:', err);
        return null;
    }
}


function generateNewBatch(lastBatch) {
    const today = new Date().toISOString().slice(0, 10).replace(/-/g, '');

    if (!lastBatch) {
        return `BATCH-${today}-0001`;
    }

    const parts = lastBatch.split('-');
    if (parts.length !== 3) {
        return `BATCH-${today}-0001`;
    }

    const prefix = `${parts[0]}-${parts[1]}`;
    const lastNumber = parseInt(parts[2], 10);

    // Jika tanggal sudah berbeda, reset nomor jadi 0001
    if (parts[1] !== today) {
        return `BATCH-${today}-0001`;
    }

    const newNumber = (lastNumber + 1).toString().padStart(4, '0');
    return `${prefix}-${newNumber}`;
}

async function tampilkanBatch() {
    const display = document.getElementById('masterBatchDisplay');
    const hiddenInput = document.getElementById('master_batch');

    let currentBatch = localStorage.getItem('currentBatch');
    if (currentBatch) {
        // Jika batch sudah disimpan di localStorage, tampilkan saja
        display.textContent = currentBatch;
        hiddenInput.value = currentBatch;
        return;
    }

    display.textContent = 'Tidak ada batch';
    hiddenInput.value = '';
}

document.getElementById('generateBatchBtn').addEventListener('click', async () => {
    let currentBatch = localStorage.getItem('currentBatch');
    if (currentBatch) {
        alert('Batch sudah digenerate: ' + currentBatch);
        return;
    }

    const newBatch = await fetchLastBatch(); // Backend sudah buat batch baru dengan +1

    if (!newBatch) {
        alert('Gagal mendapatkan batch terbaru dari server.');
        return;
    }

    localStorage.setItem('currentBatch', newBatch);

    document.getElementById('masterBatchDisplay').textContent = newBatch;
    document.getElementById('master_batch').value = newBatch;
});


// Saat form disubmit, hapus batch supaya bisa generate baru di sesi berikutnya
document.getElementById('overtimeForm').addEventListener('submit', function() {
    localStorage.removeItem('currentBatch');
});

// Saat halaman load, tampilkan batch yang tersimpan (jika ada)
document.addEventListener('DOMContentLoaded', () => {
    tampilkanBatch();
});
function addLine() {
    const tableBody = document.getElementById('overtimeLines');

    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="border p-1">
               <select name="employee[]" class="employee-select text-sm w-24 p-1.5 border rounded-md bg-white dark:bg-gray-700 dark:text-gray-100" required>
                    <option value="">-- Pilih --</option>
                    @if (!empty($employees))
                        @foreach ($employees as $employee)
                            <option value="{{ $employee['id'] }}">{{ $employee['barcode'] }} - {{ $employee['name'] }}</option>
                        @endforeach
                    @endif
                </select>
            </td>
            <td class="border p-1">
                <input type="text" class="department-field text-sm w-24 p-1.5 border rounded-md bg-gray-50 dark:bg-gray-700 dark:text-gray-100" readonly>
            </td>
        <td class="border p-1">
        <input type="text" name="job[]" class="job-field w-24 text-sm p-1.5 border rounded-md bg-gray-50 dark:bg-gray-700 dark:text-gray-100" disabled>
        </td>
        <td class="border p-1">
        <input type="text" name="manager[]" class="manager-field w-20 text-sm p-1.5 border rounded-md bg-gray-50 dark:bg-gray-700 dark:text-gray-100" disabled>
        </td>

        <td class="border p-1">
            <select name="type[]" class="type-select text-sm w-24 p-1.5 border rounded-md bg-white dark:bg-gray-700 dark:text-gray-100" required>
                <option value="leave">Leave</option>
                <option value="cash">Cash</option>
            </select>
        </td>
        <td class="border p-1">
        <input type="text" name="contract[]" class="contract-field w-20 text-sm p-1.5 border rounded-md bg-gray-50 dark:bg-gray-700 dark:text-gray-100" disabled>
        </td>
        <td class="border p-1">
            <select name="duration_type[]" class="duration-type text-sm w-full p-1.5 border rounded-md bg-white dark:bg-gray-700 dark:text-gray-100" required>
                <option value="days">Days</option>
                <option value="hours">Hour</option>
                <option value="libur">Libur</option>
                <option value="nasional">Libur Nasional</option>
            </select>
        </td>
        <td class="border p-1">
            <input type="datetime-local" name="date_from[]" class="date-from text-sm w-full p-1.5 border rounded-md bg-white dark:bg-gray-700 dark:text-gray-100" required>
        </td>
        <td class="border p-1">
            <input type="datetime-local" name="date_to[]" class="date-to text-sm w-full p-1.5 border rounded-md bg-white dark:bg-gray-700 dark:text-gray-100" required>
        </td>
        <td class="border p-1">
            <input type="text" name="actual_hours[]" class="actual-hours text-sm w-full p-1.5 border rounded-md bg-gray-50 dark:bg-gray-700 dark:text-gray-100" readonly>
        </td>
        <td class="border p-1 text-center">
            <button type="button" onclick="this.closest('tr').remove()" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-500 text-xs">Delete</button>
        </td>
    `;

    tableBody.appendChild(row);

    // Apply select2 ke select baru
    $(row).find('.employee-select').select2({
        placeholder: 'Search Employee',
        allowClear: true,
        width: 'style'
    });

    // Prevent manual input pada job, manager, contract
    $(row).find('.job-field, .manager-field, .contract-field').on('keydown paste', e => e.preventDefault());

    // Ketika employee dipilih, fetch department dan set di department-field
    $(row).find('.employee-select').on('change', function () {
        const employeeId = $(this).val();
        const tr = this.closest('tr');

        fetch('http://localhost:3000/employee/department', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ employeeId })
        })
        .then(response => response.json())
        .then(data => {
            $(tr).find('.department-field').val(data.name || '');
            // Kamu bisa set job, manager, contract dari data juga kalau ada
            // $(tr).find('.job-field').val(data.job || '');
            // $(tr).find('.manager-field').val(data.manager || '');
            // $(tr).find('.contract-field').val(data.contract || '');
        })
        .catch(err => {
            console.error('Gagal ambil data department:', err);
            alert('Terjadi kesalahan saat mengambil data department.');
        });
    });

    // Hitung jam lembur
    const updateHours = () => {
        const tr = $(row);
        const type = tr.find('.duration-type').val();
        const from = tr.find('.date-from').val();
        const to = tr.find('.date-to').val();

        if (from && to) {
            const fromDate = new Date(from);
            const toDate = new Date(to);
            const hours = (toDate - fromDate) / (1000 * 60 * 60);

            let result = 0;
            let display = '';

            switch (type) {
                case 'days':
                    result = (hours / 24) * 8;
                    display = `${(hours / 24).toFixed(2)} days`;
                    break;
                case 'hours':
                    result = hours <= 1 ? hours * 1.5 : (1 * 1.5) + ((hours - 1) * 2);
                    display = `${result.toFixed(2)} hours`;
                    break;
                case 'libur':
                case 'nasional':
                    result = hours * 2;
                    display = `${result.toFixed(2)} hours`;
                    break;
            }

            tr.find('.actual-hours').val(display);
        } else {
            tr.find('.actual-hours').val('');
        }
    };

    $(row).find('.duration-type, .date-from, .date-to').on('change', updateHours);
}

// Event sebelum submit, gabungkan input dinamis jadi string dan disable input array asli

$('#overtimeForm').on('submit', function(e) {
    const employees = [];
    const departments = [];
    const jobs = [];
    const managers = [];
    const types = [];
    const contracts = [];
    const durations = [];
    const datesFrom = [];
    const datesTo = [];

    $('#overtimeLines tr').each(function() {
        const tr = $(this);
        employees.push(tr.find('[name="employee[]"]').val());
        departments.push(tr.find('.department-field').val());
        jobs.push(tr.find('[name="job[]"]').val());
        managers.push(tr.find('[name="manager[]"]').val());
        types.push(tr.find('[name="type[]"]').val());
        contracts.push(tr.find('[name="contract[]"]').val());
        durations.push(tr.find('[name="duration_type[]"]').val());
        datesFrom.push(tr.find('[name="date_from[]"]').val());
        datesTo.push(tr.find('[name="date_to[]"]').val());
    });

    $('#employee_id').val(employees.join(','));
    $('#department_id').val(departments.join(','));
    $('#job_id').val(jobs.join(','));
    $('#manager_id').val(managers.join(','));
    $('#type').val(types.join(','));
    $('#contract_id').val(contracts.join(','));
    $('#duration_type').val(durations.join(','));
    $('#date_from').val(datesFrom.join(','));
    $('#date_to').val(datesTo.join(','));



    // Bisa disisipkan ke line atau dikirim per batch tergantung backend
    // Jika backend butuh author di tiap line, gabungkan array seperti yang lain
    $('<input>').attr({
        type: 'hidden',
        name: 'author_id',
        value: Array(employees.length).fill(authorId).join(',')
    }).appendTo('#overtimeForm');



    // Disable semua input array asli agar tidak terkirim dua kali
    $('#overtimeLines').find('select[name$="[]"], input[name$="[]"]').attr('disabled', true);
});

document.addEventListener('DOMContentLoaded', function () {
    tampilkanSatuBatch();
});

</script>
@endsection
