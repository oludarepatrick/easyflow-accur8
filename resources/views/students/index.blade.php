@extends('layouts.app')

@section('title', 'Active Students')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Active Students ({{ $currentSession }} - {{ $currentTerm }})</h3>

    <!-- Success message -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search Form -->
    <form method="GET" action="{{ route('students.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="form-control" placeholder="Search by name or username or schooltype...">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="studentsTable">
            <thead class="table-dark">
                <tr>
                    <th data-column="0"># ▲</th>
                    <th data-column="1">Firstname ▲</th>
                    <th data-column="2">Lastname ▲</th>
                    <th data-column="3">Class ▲</th>
                    <th data-column="3">School ▲</th>
                    <th data-column="4">Status ▲</th>
                    <th data-column="5">Edit ▲</th>
                    <th data-column="6">Delete ▲</th>
                    <th data-column="7">Create ▲</th>
                    <th data-column="8">View ▲</th>
                    <th data-column="9">Last Payment ▲</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->firstname }}</td>
                        <td>{{ $student->lastname }}</td>
                        <td>{{ $student->class }}</td>
                        <td>{{ ucfirst($student->schooltype) }}</td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td>
                            <!-- Edit Button -->
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                        </td>
                        <td>
                            <!-- Delete Button -->
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                        <td>
                            <!-- Create receipt Button -->
                            <a href="{{ route('students.receipts.create', $student->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Create
                            </a>
                        </td>
                        <td>
                            @if($student->activeReceipt)
                                <a href="{{ route('students.receipts.show', $student->activeReceipt->id) }}" 
                                   class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-receipt"></i> View
                                </a>
                                @php
                                    $balance = $student->activeReceipt->amount_due ?? 0;
                                    $isPaid = $balance <= 0;
                                @endphp
                                <i class="bi bi-info-circle-fill ms-2"
                                   style="cursor: pointer; color: {{ $isPaid ? 'green' : 'red' }};"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="{{ $isPaid ? 'Fully Paid: ₦' . number_format($student->activeReceipt->amount_paid, 2) : 'Balance: ₦' . number_format($balance, 2) }}">
                                </i>
                            @else
                                <span class="badge bg-warning text-dark">No Receipt</span>
                            @endif
                        </td>
                        <td>
                            @if($student->activeReceipt && $student->activeReceipt->payments->count())
                                {{ $student->activeReceipt->payments->last()->payment_date->format('d M Y') }}
                            @else
                                <span class="text-muted">No Payment</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No students found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $students->links() }}
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    // Bootstrap tooltips (unchanged behavior)
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Sorting logic
    const table = document.getElementById("studentsTable");
    if (!table) return;
    const headers = Array.from(table.querySelectorAll("th"));
    const tbody = table.querySelector("tbody");
    if (!tbody) return;

    // Add arrow span and make headers clickable
    headers.forEach((header, idx) => {
        // skip if header already has our arrow (avoid duplicate on hot reload)
        if (!header.querySelector('.sort-arrow')) {
            const arrow = document.createElement('span');
            arrow.className = 'ms-2 sort-arrow';
            arrow.style.fontSize = '0.8em';
            arrow.textContent = '▲';
            header.appendChild(arrow);
        }
        header.style.cursor = 'pointer';
        header.dataset.order = 'none'; // possible values: 'none','asc','desc'
        header.addEventListener('click', () => sortTableByColumn(idx));
    });

    // Utility: parse cell into value (number/date/string)
    function getCellValue(row, idx) {
        const cell = row.children[idx];
        if (!cell) return '';
        const raw = cell.textContent.trim();

        if (!raw) return '';

        // Try parse date first (use Date.parse on original text)
        const parsedDate = Date.parse(raw);
        if (!isNaN(parsedDate) && /[0-9]{4}|[A-Za-z]{3}/.test(raw)) { // fairly confident it's a date
            return parsedDate; // numeric ms
        }

        // Strip currency symbols and commas for numeric detection
        const cleaned = raw.replace(/[₦,$\s,]/g, '').replace(/,/g, '');
        if (cleaned !== '' && !isNaN(cleaned)) {
            return parseFloat(cleaned);
        }

        // fallback: lowercased string
        return raw.toLowerCase();
    }

    function compareValues(a, b, order) {
        // empty values pushed to end
        const emptyA = (a === '' || a === null || typeof a === 'undefined');
        const emptyB = (b === '' || b === null || typeof b === 'undefined');
        if (emptyA && emptyB) return 0;
        if (emptyA) return 1;
        if (emptyB) return -1;

        // both numbers (includes date ms)
        if (typeof a === 'number' && typeof b === 'number') {
            return order * (a - b);
        }

        // mixed numeric vs string: numeric first when ascending
        if (typeof a === 'number' && typeof b !== 'number') return -1 * order;
        if (typeof a !== 'number' && typeof b === 'number') return 1 * order;

        // strings
        if (typeof a === 'string' && typeof b === 'string') {
            return order * a.localeCompare(b);
        }

        return 0;
    }

    function sortTableByColumn(index, initialOrder = null) {
        const rows = Array.from(tbody.querySelectorAll('tr'));
        let order;
        if (initialOrder !== null) order = initialOrder;
        else {
            const current = headers[index].dataset.order;
            order = current === 'asc' ? -1 : 1; // toggle: asc -> desc, none/desc -> asc
        }

        rows.sort((r1, r2) => {
            const v1 = getCellValue(r1, index);
            const v2 = getCellValue(r2, index);
            return compareValues(v1, v2, order);
        });

        // Re-append rows in sorted order
        rows.forEach(r => tbody.appendChild(r));

        // Reset arrows & orders
        headers.forEach(h => {
            h.dataset.order = 'none';
            const sp = h.querySelector('.sort-arrow');
            if (sp) sp.textContent = '▲';
        });

        // Set this header arrow state
        headers[index].dataset.order = order === 1 ? 'asc' : 'desc';
        const arrowSpan = headers[index].querySelector('.sort-arrow');
        if (arrowSpan) arrowSpan.textContent = order === 1 ? '▲' : '▼';
    }

    // Initial sort by Last Payment column (prioritize payment date)
    // Last Payment is the last header (index = headers.length - 1)
    const lastIndex = headers.length - 1;
    if (lastIndex >= 0) {
        // Sort descending so newest payments appear first
        sortTableByColumn(lastIndex, -1);
    }
});
</script>
@endsection
