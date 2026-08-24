@push('styles')
<style>
    /* ===================================================
       CMSC / Fee Master Module Table Exact Styles
       =================================================== */
    .module-container {
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #333;
        font-size: 14px;
    }

    .module-box {
        position: relative;
        border-radius: 4px;
        background: #ffffff;
        border: 1px solid #d2d6de;
        margin-bottom: 20px;
        width: 100%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    .module-box-header {
        color: #333;
        background: #fff;
        border-bottom: 1px solid #f4f4f4;
        padding: 12px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .module-box-title {
        display: inline-block;
        font-size: 17px;
        margin: 0;
        line-height: 1.2;
        font-weight: 600;
        color: #333333;
    }

    .module-box-body {
        padding: 15px;
        background: #fff;
    }

    /* Toolbar above Table */
    .dt-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .dt-search-form {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .dt-search-input {
        height: 34px;
        width: 220px;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s ease;
    }

    .dt-search-input:focus {
        border-color: #1e3a8a;
    }

    .btn-search-cmsc {
        background-color: #1e3a8a;
        color: #ffffff;
        border: 1px solid #1e3a8a;
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        height: 34px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-search-cmsc:hover {
        background-color: #162c6d;
        border-color: #162c6d;
    }

    .dt-buttons-group {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        position: relative;
    }

    .dt-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: #1e3a8a;
        border: 1px solid #1e3a8a;
        color: #ffffff;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
    }

    .dt-btn:hover {
        background: #162c6d;
        border-color: #162c6d;
        transform: translateY(-1px);
    }

    .dt-btn[data-tooltip]::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 115%;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: #fff;
        font-size: 11px;
        padding: 3px 6px;
        border-radius: 3px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
        z-index: 100;
    }

    .dt-btn[data-tooltip]:hover::after {
        opacity: 1;
    }

    /* Column Visibility Dropdown Popover */
    .colvis-dropdown {
        position: absolute;
        top: 110%;
        right: 0;
        background: #ffffff;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        padding: 10px;
        z-index: 10000;
        display: none;
        min-width: 180px;
        max-height: 250px;
        overflow-y: auto;
    }

    .colvis-dropdown-title {
        font-weight: bold;
        font-size: 12px;
        color: #1e3a8a;
        margin-bottom: 8px;
        border-bottom: 1px solid #eee;
        padding-bottom: 4px;
    }

    .colvis-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 0;
        font-size: 12px;
        color: #333;
        cursor: pointer;
        user-select: none;
    }

    .colvis-item input[type="checkbox"] {
        cursor: pointer;
    }

    /* Table Styling */
    .cmsc-table-wrap {
        overflow-x: auto;
        border: 1px solid #d2d6de;
        border-radius: 4px;
    }

    .cmsc-module-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        background: #fff;
    }

    .cmsc-module-table thead th {
        background-color: #1e3a8a;
        color: #ffffff;
        font-weight: 600;
        padding: 10px 12px;
        text-align: left;
        border: 1px solid #162c6d;
        white-space: nowrap;
        user-select: none;
        cursor: pointer;
        position: relative;
    }

    .cmsc-module-table thead th:hover {
        background-color: #162c6d;
    }

    .cmsc-module-table thead th .sort-arrow {
        font-size: 11px;
        margin-left: 5px;
        opacity: 0.85;
    }

    .cmsc-module-table tbody td {
        padding: 8px 12px;
        border: 1px solid #e9ecef;
        vertical-align: middle;
        color: #333;
    }

    .cmsc-module-table tbody tr:hover td {
        background-color: #f8fafd;
    }

    /* Footer Bar */
    .table-footer-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 12px;
        font-size: 12px;
        color: #666;
    }

    /* Toast Notification */
    #exportToast {
        position: fixed;
        bottom: 25px;
        right: 25px;
        background: #1e3a8a;
        color: #fff;
        padding: 10px 18px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        display: none;
        z-index: 99999;
    }
</style>
@endpush

<div class="module-container">
    <div class="module-box">
        <div class="module-box-header">
            <h3 class="module-box-title">{{ $module['label'] }} List</h3>
            @if(isset($module['table']))
                <span style="font-size: 12px; color: #777;">Table: {{ $module['table'] }}</span>
            @endif
        </div>

        <div class="module-box-body">
            {{-- Toolbar: Search Form & Export Buttons --}}
            <div class="dt-toolbar">
                <form method="GET" action="{{ route($module['route']) }}" class="dt-search-form" id="moduleSearchForm">
                    <input type="text" 
                           name="search" 
                           id="tableSearch" 
                           placeholder="Search {{ strtolower($module['label']) }}..." 
                           class="dt-search-input" 
                           value="{{ request('search') }}" 
                           onkeyup="filterModuleTable()" 
                           autocomplete="off" />
                    <button type="submit" class="btn-search-cmsc" title="Search">
                        <i class="fa fa-search"></i> Search
                    </button>
                </form>

                <div class="dt-buttons-group">
                    <button type="button" class="dt-btn" data-tooltip="Copy" onclick="exportModuleTable('copy')">
                        <i class="fa fa-copy"></i>
                    </button>
                    <button type="button" class="dt-btn" data-tooltip="Excel" onclick="exportModuleTable('excel')">
                        <i class="fa fa-file-excel"></i>
                    </button>
                    <button type="button" class="dt-btn" data-tooltip="CSV" onclick="exportModuleTable('csv')">
                        <i class="fa fa-file-csv"></i>
                    </button>
                    <button type="button" class="dt-btn" data-tooltip="PDF" onclick="exportModuleTable('pdf')">
                        <i class="fa fa-file-pdf"></i>
                    </button>
                    <button type="button" class="dt-btn" data-tooltip="Print" onclick="exportModuleTable('print')">
                        <i class="fa fa-print"></i>
                    </button>
                    <button type="button" class="dt-btn" data-tooltip="Columns" id="colvisBtn" onclick="toggleColumnVisibilityMenu(event)">
                        <i class="fa fa-columns"></i>
                    </button>

                    {{-- Column Visibility Dropdown --}}
                    <div id="colvisDropdown" class="colvis-dropdown" onclick="event.stopPropagation()">
                        <div class="colvis-dropdown-title">Toggle Columns</div>
                        <div id="colvisItems"></div>
                    </div>
                </div>
            </div>

            {{-- Module Table --}}
            <div class="cmsc-table-wrap">
                <table class="cmsc-module-table" id="genericModuleTable">
                    <thead>
                        <tr>
                            @foreach ($module['columns'] as $index => $column)
                                <th class="sortable" 
                                    data-col-index="{{ $index }}" 
                                    onclick="sortModuleTable({{ $index }})" 
                                    title="Click to sort by {{ \Illuminate\Support\Str::headline($column) }}">
                                    {{ \Illuminate\Support\Str::headline($column) }}
                                    <span class="sort-arrow" id="sortArrow{{ $index }}">▾</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                @foreach ($module['columns'] as $column)
                                    <td>
                                        {{ \Illuminate\Support\Str::limit(strip_tags((string) data_get($record, $column)), 120) }}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr class="no-records-row">
                                <td colspan="{{ count($module['columns']) }}" style="text-align: center; padding: 20px; color: #777;">
                                    No {{ strtolower($module['label']) }} records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination & Counter --}}
            <div class="table-footer-bar">
                <div id="recordCounterInfo">
                    Showing {{ $records->firstItem() ?? 0 }} to {{ $records->lastItem() ?? 0 }} of {{ $records->total() ?? count($records) }} records
                </div>
                <div>
                    {{ $records->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast Notification --}}
<div id="exportToast">Table copied to clipboard!</div>

@push('scripts')
<script>
    var currentSortColumn = -1;
    var sortDirections = {};

    document.addEventListener('DOMContentLoaded', function() {
        initColumnVisibilityMenu();

        // Close column visibility dropdown when clicking outside
        document.addEventListener('click', function(e) {
            var dropdown = document.getElementById('colvisDropdown');
            var btn = document.getElementById('colvisBtn');
            if (dropdown && !dropdown.contains(e.target) && btn && !btn.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    });

    // Toast Notification helper
    function showToast(msg) {
        var toast = document.getElementById('exportToast');
        if (!toast) return;
        toast.innerText = msg;
        toast.style.display = 'block';
        setTimeout(function() { toast.style.display = 'none'; }, 2500);
    }

    // Client-side Live Filter
    function filterModuleTable() {
        var input = document.getElementById('tableSearch');
        if (!input) return;
        var filter = input.value.toLowerCase().trim();
        var table = document.getElementById('genericModuleTable');
        if (!table) return;
        var rows = table.querySelectorAll('tbody tr');

        var visibleCount = 0;
        rows.forEach(function(row) {
            if (row.classList.contains('no-records-row')) return;
            var text = row.innerText.toLowerCase();
            if (filter === '' || text.indexOf(filter) > -1) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        var info = document.getElementById('recordCounterInfo');
        if (info && filter !== '') {
            info.innerText = 'Showing ' + visibleCount + ' matching records (filtered)';
        }
    }

    // Interactive Column Sorting
    function sortModuleTable(colIndex) {
        var table = document.getElementById('genericModuleTable');
        if (!table) return;
        var tbody = table.querySelector('tbody');
        if (!tbody) return;

        var rows = Array.from(tbody.querySelectorAll('tr')).filter(function(r) {
            return !r.classList.contains('no-records-row');
        });
        if (rows.length === 0) return;

        var currentDir = sortDirections[colIndex] || 'asc';
        var newDir = currentDir === 'asc' ? 'desc' : 'asc';
        sortDirections[colIndex] = newDir;

        // Reset all arrows
        var headers = table.querySelectorAll('thead th');
        headers.forEach(function(th, idx) {
            var arrow = th.querySelector('.sort-arrow');
            if (arrow) {
                if (idx === colIndex) {
                    arrow.innerText = newDir === 'asc' ? ' ▲' : ' ▼';
                } else {
                    arrow.innerText = ' ▾';
                }
            }
        });

        // Perform sorting
        rows.sort(function(rowA, rowB) {
            var cellA = (rowA.cells[colIndex] ? rowA.cells[colIndex].innerText : '').trim();
            var cellB = (rowB.cells[colIndex] ? rowB.cells[colIndex].innerText : '').trim();

            var valA = parseFloat(cellA.replace(/[^0-9.-]+/g, ''));
            var valB = parseFloat(cellB.replace(/[^0-9.-]+/g, ''));

            var isNum = !isNaN(valA) && !isNaN(valB) && (cellA === valA.toString() || cellA.indexOf('$') > -1 || cellA.indexOf('PKR') > -1);

            var cmp = 0;
            if (isNum) {
                cmp = valA - valB;
            } else {
                cmp = cellA.localeCompare(cellB, undefined, { numeric: true, sensitivity: 'base' });
            }

            return newDir === 'asc' ? cmp : -cmp;
        });

        // Re-append sorted rows
        rows.forEach(function(row) {
            tbody.appendChild(row);
        });
    }

    // Column Visibility Toggle Menu
    function initColumnVisibilityMenu() {
        var table = document.getElementById('genericModuleTable');
        var container = document.getElementById('colvisItems');
        if (!table || !container) return;

        var headers = table.querySelectorAll('thead th');
        container.innerHTML = '';

        headers.forEach(function(th, idx) {
            var labelText = th.innerText.replace(/[▾▲▼]/g, '').trim();
            var item = document.createElement('label');
            item.className = 'colvis-item';
            
            var chk = document.createElement('input');
            chk.type = 'checkbox';
            chk.checked = true;
            chk.setAttribute('data-col-index', idx);
            chk.onchange = function() {
                toggleColumnVisibility(idx, this.checked);
            };

            item.appendChild(chk);
            item.appendChild(document.createTextNode(labelText));
            container.appendChild(item);
        });
    }

    function toggleColumnVisibilityMenu(e) {
        e.stopPropagation();
        var dropdown = document.getElementById('colvisDropdown');
        if (dropdown) {
            dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
        }
    }

    function toggleColumnVisibility(colIndex, isVisible) {
        var table = document.getElementById('genericModuleTable');
        if (!table) return;

        var headers = table.querySelectorAll('thead th');
        if (headers[colIndex]) {
            headers[colIndex].style.display = isVisible ? '' : 'none';
        }

        var rows = table.querySelectorAll('tbody tr');
        rows.forEach(function(row) {
            if (row.cells[colIndex]) {
                row.cells[colIndex].style.display = isVisible ? '' : 'none';
            }
        });
    }

    // Table Export Functions (Copy, Excel, CSV, PDF, Print)
    function exportModuleTable(type) {
        var table = document.getElementById('genericModuleTable');
        if (!table) return;

        var visibleColIndices = [];
        var headers = table.querySelectorAll('thead th');
        headers.forEach(function(th, idx) {
            if (th.style.display !== 'none') {
                visibleColIndices.push(idx);
            }
        });

        if (type === 'copy') {
            var textData = [];
            
            // Header text
            var headerCells = [];
            visibleColIndices.forEach(function(idx) {
                headerCells.push(headers[idx].innerText.replace(/[▾▲▼]/g, '').trim());
            });
            textData.push(headerCells.join("\t"));

            // Body text
            var rows = table.querySelectorAll('tbody tr');
            rows.forEach(function(r) {
                if (r.style.display === 'none' || r.classList.contains('no-records-row')) return;
                var rowCells = [];
                visibleColIndices.forEach(function(idx) {
                    if (r.cells[idx]) {
                        rowCells.push(r.cells[idx].innerText.trim());
                    }
                });
                textData.push(rowCells.join("\t"));
            });

            var copyText = textData.join("\n");
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(copyText).then(function() {
                    showToast('Table copied to clipboard!');
                });
            } else {
                var textArea = document.createElement("textarea");
                textArea.value = copyText;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showToast('Table copied to clipboard!');
            }
        } else if (type === 'excel' || type === 'csv') {
            var rowsData = [];
            
            // Header
            var headerCells = [];
            visibleColIndices.forEach(function(idx) {
                headerCells.push('"' + headers[idx].innerText.replace(/[▾▲▼]/g, '').replace(/"/g, '""').trim() + '"');
            });
            rowsData.push(headerCells.join(type === 'csv' ? ',' : "\t"));

            // Body
            var rows = table.querySelectorAll('tbody tr');
            rows.forEach(function(r) {
                if (r.style.display === 'none' || r.classList.contains('no-records-row')) return;
                var rowCells = [];
                visibleColIndices.forEach(function(idx) {
                    if (r.cells[idx]) {
                        rowCells.push('"' + r.cells[idx].innerText.replace(/"/g, '""').trim() + '"');
                    }
                });
                rowsData.push(rowCells.join(type === 'csv' ? ',' : "\t"));
            });

            var mimeType = type === 'csv' ? 'text/csv;charset=utf-8;' : 'application/vnd.ms-excel;charset=utf-8;';
            var fileExt = type === 'csv' ? 'csv' : 'xls';
            var blob = new Blob(["\ufeff" + rowsData.join("\n")], { type: mimeType });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = '{{ strtolower(str_replace(" ", "_", $module["label"])) }}_export_' + (new Date().toISOString().slice(0,10)) + '.' + fileExt;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            showToast('Exported to ' + type.toUpperCase() + '!');
        } else if (type === 'print' || type === 'pdf') {
            var printWin = window.open('', '_blank', 'width=900,height=700');
            if (!printWin) {
                alert('Please allow popups to print/export PDF.');
                return;
            }

            var title = '{{ $module["label"] }} Report';
            var html = '<html><head><title>' + title + '</title>';
            html += '<style>';
            html += 'body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; color: #333; }';
            html += 'h2 { color: #1e3a8a; font-size: 18px; margin-bottom: 5px; }';
            html += '.print-meta { font-size: 11px; color: #666; margin-bottom: 15px; border-bottom: 1px solid #ccc; padding-bottom: 5px; }';
            html += 'table { width: 100%; border-collapse: collapse; margin-top: 10px; }';
            html += 'th { background-color: #1e3a8a; color: #ffffff; font-weight: bold; padding: 8px; border: 1px solid #1e3a8a; text-align: left; }';
            html += 'td { padding: 6px 8px; border: 1px solid #ddd; }';
            html += 'tr:nth-child(even) td { background-color: #f9f9f9; }';
            html += '</style></head><body>';

            html += '<h2>' + title + '</h2>';
            html += '<div class="print-meta">Generated on: ' + (new Date().toLocaleString()) + '</div>';
            html += '<table><thead><tr>';

            visibleColIndices.forEach(function(idx) {
                html += '<th>' + headers[idx].innerText.replace(/[▾▲▼]/g, '').trim() + '</th>';
            });
            html += '</tr></thead><tbody>';

            var rows = table.querySelectorAll('tbody tr');
            rows.forEach(function(r) {
                if (r.style.display === 'none' || r.classList.contains('no-records-row')) return;
                html += '<tr>';
                visibleColIndices.forEach(function(idx) {
                    if (r.cells[idx]) {
                        html += '<td>' + r.cells[idx].innerText.trim() + '</td>';
                    }
                });
                html += '</tr>';
            });

            html += '</tbody></table></body></html>';

            printWin.document.write(html);
            printWin.document.close();
            printWin.focus();

            setTimeout(function() {
                printWin.print();
                if (type === 'print') {
                    printWin.close();
                }
            }, 500);

            showToast(type === 'pdf' ? 'Opened PDF Print Dialog!' : 'Print Dialog Opened!');
        }
    }
</script>
@endpush
