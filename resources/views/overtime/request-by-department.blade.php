@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif
    <h2 class="text-2xl font-bold mb-6">Formulir Lembur Berdasarkan Departemen</h2>

    <div class="mb-3 flex items-center gap-3">
        <label class="form-label">Batch:</label>
        <div id="masterBatchDisplay" class="fw-bold">loading...</div>
        <button type="button" id="generateBatchBtn" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
            Generate Batch
        </button>
    </div>

    <div class="mb-4 hidden">
        <label for="overtime_author_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Author</label>
        <input type="text" id="overtime_author_name" class="w-full max-w-sm px-3 py-2 border rounded-md shadow-sm bg-gray-100 dark:bg-gray-800 dark:text-gray-100" value="{{ auth()->user()->name }}" readonly>
        
        <input type="hidden" name="overtime_author" value="{{ auth()->user()->employee_id }}">
    </div>  

    {{-- Global Input --}}
    <div class="grid grid-cols-4 gap-4 bg-gray-50 p-4 mb-6 border rounded">
        <div>
            <label class="font-medium">Type:</label>
            <select id="global_type" class="w-full border p-2 rounded">
                <option value="leave">Leave</option>
                <option value="cash">Cash</option>
            </select>
        </div>
        <div>
            <label class="font-medium">Durasion:</label>
            <select id="global_duration" class="w-full border p-2 rounded">
                <option value="days">Days</option>
                <option value="hours">Hours</option>
                <option value="libur">Libur</option>
                <option value="nasional">Libur Nasional</option>
            </select>
        </div>
        <div>
            <label class="font-medium">Date From:</label>
            <input type="datetime-local" id="global_date_from" class="w-full border p-2 rounded">
        </div>
        <div>
            <label class="font-medium">Date To:</label>
            <input type="datetime-local" id="global_date_to" class="w-full border p-2 rounded">
        </div>
    </div>

    {{-- Pilih Departemen --}}
    <div class="mb-4">
        <label for="department_select" class="block font-semibold">Pilih Departemen:</label>
        <select id="department_select" class="w-64 p-2 border rounded">
            <option value="">-- Pilih Departemen --</option>
        </select>
    </div>

    {{-- Form --}}
    <form action="{{ route('request.store') }}" method="POST">
        @csrf
        <input type="hidden" name="master_batch" id="master_batch">
        <input type="hidden" name="overtime_author" value="{{ auth()->user()->employee_id }}">

        <table class="min-w-full divide-y divide-gray-200" id="employee-table">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 uppercase">Employee</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 uppercase">Dept</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 uppercase">Job</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 uppercase">Manager</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 uppercase">Type</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 uppercase">Contract</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 uppercase">Duration</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 uppercase">Date From</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 uppercase">Date To</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 uppercase">Actual Hours</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 uppercase">Action</th>
              </tr>
            </thead>
            <tbody id="employee-tbody" class="bg-white divide-y divide-gray-200">
              <!-- Diisi oleh JS -->
            </tbody>
          </table>

        <button type="submit" class="mt-6 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Kirim Lembur</button>
    </form>

    @if(session('clear_batch'))
    <script>
        localStorage.removeItem('currentBatch');
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('masterBatchDisplay').textContent = 'Tidak ada batch';
            document.getElementById('master_batch').value = '';
        });
    </script>
@endif


</div>

<script>

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
    const deptSelect = document.getElementById('department_select');
    const tbody = document.getElementById('employee-tbody');
    const batchDisplay = document.getElementById('masterBatchDisplay');
    const batchInput = document.getElementById('master_batch');
    const generateBatchBtn = document.getElementById('generateBatchBtn');
    const form = document.querySelector('form');

    const globalType = document.getElementById('global_type');
    const globalDuration = document.getElementById('global_duration');
    const globalDateFrom = document.getElementById('global_date_from');
    const globalDateTo = document.getElementById('global_date_to');

    function tampilkanBatch() {
        const batch = localStorage.getItem('currentBatch');
        if (batch) {
            batchDisplay.textContent = batch;
            batchInput.value = batch;
        } else {
            batchDisplay.textContent = 'Tidak ada batch';
            batchInput.value = '';
        }
    }

    async function fetchLastBatch() {
        try {
            const res = await fetch('http://localhost:3000/overtime/master-batch');
            const data = await res.json();
            return data?.new_batch?.name || null;
        } catch (err) {
            console.error('Gagal fetch batch:', err);
            return null;
        }
    }

    generateBatchBtn.addEventListener('click', async () => {
        if (localStorage.getItem('currentBatch')) {
            alert('Batch sudah digenerate!');
            return;
        }
        const newBatch = await fetchLastBatch();
        if (newBatch) {
            localStorage.setItem('currentBatch', newBatch);
            batchDisplay.textContent = newBatch;
            batchInput.value = newBatch;
        } else {
            alert('Gagal mengambil batch!');
        }
    });

    document.querySelector('form').addEventListener('submit', function (e) {
    setTimeout(() => {
        if (document.body.innerText.includes('Semua data lembur berhasil disimpan')) {
            localStorage.removeItem('currentBatch');
        }
    }, 500);
});

    tampilkanBatch();

    fetch('http://localhost:3000/employee/alldepartment')
        .then(res => res.json())
        .then(data => {
            const deptMap = new Map();

            // Perbaikan: validasi parent_id sebagai array
            data.forEach(dept => {
                const parentId = Array.isArray(dept.parent_id) ? dept.parent_id[0] : null;
                deptMap.set(dept.id, {
                    ...dept,
                    parent_id: parentId,
                    children: []
                });
            });

            const roots = [];
            deptMap.forEach(dept => {
                if (dept.parent_id && deptMap.has(dept.parent_id)) {
                    deptMap.get(dept.parent_id).children.push(dept);
                } else {
                    roots.push(dept);
                }
            });

            fetch('http://localhost:3000/employee/alldepartment')
        .then(res => res.json())
        .then(data => {
            const deptMap = new Map();

            data.forEach(dept => {
                const parentId = Array.isArray(dept.parent_id) ? dept.parent_id[0] : null;
                deptMap.set(dept.id, { ...dept, parent_id: parentId, children: [] });
            });

            deptMap.forEach(dept => {
                if (dept.parent_id && deptMap.has(dept.parent_id)) {
                    deptMap.get(dept.parent_id).children.push(dept);
                }
            });

            function buildFullPath(dept, map) {
                const names = [];
                while (dept) {
                    names.unshift(dept.name);
                    dept = map.get(dept.parent_id);
                }
                return names.join(' / ');
            }

            deptMap.forEach(dept => {
                if (!dept.parent_id || !deptMap.has(dept.parent_id)) {
                    const stack = [dept];
                    while (stack.length) {
                        const current = stack.pop();
                        const option = document.createElement('option');
                        option.value = current.id;
                        option.textContent = buildFullPath(current, deptMap);
                        deptSelect.appendChild(option);
                        current.children.forEach(child => stack.push(child));
                    }
                }
            });
        })

            appendOptions(roots);
        })
        .catch(err => console.error('Gagal load departemen:', err));

    deptSelect.addEventListener('change', async function () {
        const depId = this.value;
        tbody.innerHTML = '';
        if (!depId) return;

        try {
            const res = await fetch(`http://localhost:3000/employee/department/${depId}/employees`);
            const employees = await res.json();

            if (!employees.length) {
                tbody.innerHTML = `<tr><td colspan="11" class="text-center p-4 text-gray-500">Tidak ada karyawan.</td></tr>`;
                return;
            }

            employees.forEach((emp, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="border p-2">
                        <input type="hidden" name="employee[${index}][id]" value="${emp.id}">
                        ${emp.name}
                    </td>
                    <td class="border p-2"><input type="text" name="employee[${index}][department]" value="${deptSelect.options[deptSelect.selectedIndex].text}" class="w-full" readonly></td>
                    <td class="border p-1"><input type="text" class="job-field w-24 text-sm p-1.5 border rounded-md" name="employee[${index}][job]" disabled></td>
                    <td class="border p-1"><input type="text" class="manager-field w-24 text-sm p-1.5 border rounded-md" name="employee[${index}][manager]" disabled></td>
                    <td class="border p-1">
                        <select name="employee[${index}][type]" class="text-sm w-24 p-1.5 border rounded-md" required>
                            <option value="leave" ${globalType.value === 'leave' ? 'selected' : ''}>Leave</option>
                            <option value="cash" ${globalType.value === 'cash' ? 'selected' : ''}>Cash</option>
                        </select>
                    </td>
                    <td class="border p-1"><input type="text" class="contract-field w-20 text-sm p-1.5 border rounded-md" name="employee[${index}][contract]" disabled></td>
                    <td class="border p-1">
                        <select name="employee[${index}][duration_type]" class="duration-type text-sm w-full p-1.5 border rounded-md" required>
                            <option value="days" ${globalDuration.value === 'days' ? 'selected' : ''}>Days</option>
                            <option value="hours" ${globalDuration.value === 'hours' ? 'selected' : ''}>Hours</option>
                            <option value="libur" ${globalDuration.value === 'libur' ? 'selected' : ''}>Libur</option>
                            <option value="nasional" ${globalDuration.value === 'nasional' ? 'selected' : ''}>Libur Nasional</option>
                        </select>
                    </td>
                    <td class="border p-1"><input type="datetime-local" name="employee[${index}][date_from]" class="date-from text-sm w-full p-1.5 border rounded-md" value="${globalDateFrom.value}" required></td>
                    <td class="border p-1"><input type="datetime-local" name="employee[${index}][date_to]" class="date-to text-sm w-full p-1.5 border rounded-md" value="${globalDateTo.value}" required></td>
                    <td class="border p-1"><input type="text" name="employee[${index}][actual_hours]" class="actual-hours text-sm w-full p-1.5 border rounded-md" readonly></td>
                    <td class="border p-1 text-center">
                        <button type="button" onclick="this.closest('tr').remove()" class="text-red-600 text-xs">Delete</button>
                    </td>
                `;
                tbody.appendChild(row);

                const currentRow = tbody.lastElementChild;
                const durationField = currentRow.querySelector('.duration-type');
                const dateFromField = currentRow.querySelector('.date-from');
                const dateToField = currentRow.querySelector('.date-to');
                const actualHoursField = currentRow.querySelector('.actual-hours');

                const updateHours = () => {
                const from = new Date(dateFromField.value);
                const to = new Date(dateToField.value);
                const type = durationField.value;

                if (!isNaN(from) && !isNaN(to) && from < to) {
                    const diffMs = to - from;
                    const diffHours = diffMs / (1000 * 60 * 60);

                    if (type === 'hours') {
                        actualHoursField.value = diffHours.toFixed(2);
                    } else if (type === 'days') {
                        const diffDays = diffHours / 24;
                        actualHoursField.value = diffDays.toFixed(2);
                    } else {
                        actualHoursField.value = '-'; // Untuk libur dan nasional
                    }
                } else {
                    actualHoursField.value = '';
                }
            };

            // Panggil pertama kali saat render
            updateHours();

            // Tambahkan event listener
            dateFromField.addEventListener('change', updateHours);
            dateToField.addEventListener('change', updateHours);
            durationField.addEventListener('change', updateHours);

            });
        } catch (err) {
            console.error('Gagal memuat karyawan:', err);
        }
    });
});
</script>
@endsection
