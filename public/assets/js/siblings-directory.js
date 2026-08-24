(function () {
    'use strict';

    const table = document.querySelector('.sibling-page table');
    const editForm = document.querySelector('#edit-sibling-form');

    if (!table) {
        return;
    }

    const show = (selector) => document.querySelector(selector)?.classList.remove('hidden');
    const hide = (button) => button.closest('.sibling-modal')?.classList.add('hidden');
    const rows = () => [...table.querySelectorAll('tr')].map((row) =>
        [...row.cells]
            .slice(0, -1)
            .map((cell) => cell.innerText.replace(/\s+/g, ' ').trim())
    );
    const download = (content, filename, type) => {
        const link = document.createElement('a');
        link.href = URL.createObjectURL(new Blob([content], { type }));
        link.download = filename;
        link.click();
        URL.revokeObjectURL(link.href);
    };
    const printTable = (title, sourceTable) => {
        const popup = window.open('', '_blank');

        if (!popup) {
            return;
        }

        popup.document.write(`<html><head><title>${title}</title><style>table{border-collapse:collapse;width:100%}td,th{border:1px solid #999;padding:6px;text-align:left}</style></head><body>${sourceTable.outerHTML}</body></html>`);
        popup.document.close();
        popup.focus();
        popup.print();
    };

    document.querySelector('[data-open-add]')?.addEventListener('click', () => show('#add-sibling-modal'));
    document.querySelectorAll('[data-close-modal]').forEach((button) => {
        button.addEventListener('click', () => hide(button));
    });
    document.querySelector('#sibling-select-all')?.addEventListener('change', (event) => {
        document.querySelectorAll('.sibling-check').forEach((item) => {
            item.checked = event.target.checked;
        });
    });
    document.querySelector('[data-selected-pdf]')?.addEventListener('click', () => {
        const selected = [...document.querySelectorAll('.sibling-check:checked')];

        if (!selected.length) {
            window.alert('Please select sibling first.');
            return;
        }

        const clone = table.cloneNode(true);
        clone.querySelectorAll('tbody tr').forEach((row) => {
            if (!selected.some((item) => item.value === row.querySelector('.sibling-check')?.value)) {
                row.remove();
            }
        });
        clone.querySelectorAll('tr').forEach((row) => row.lastElementChild?.remove());
        printTable('Selected Siblings', clone);
    });

    document.querySelectorAll('[data-sibling-export]').forEach((button) => {
        button.addEventListener('click', async () => {
            const action = button.dataset.siblingExport;

            if (action === 'columns') {
                const menu = document.querySelector('[data-column-menu]');
                menu.classList.toggle('hidden');

                if (!menu.dataset.ready) {
                    [...table.querySelectorAll('thead th')].slice(0, -1).forEach((header, index) => {
                        const label = document.createElement('label');
                        label.innerHTML = `<input type="checkbox" checked data-column-index="${index}"> ${header.innerText.trim()}`;
                        menu.append(label);
                    });
                    menu.dataset.ready = '1';
                    menu.querySelectorAll('input').forEach((input) => {
                        input.addEventListener('change', () => {
                            table.querySelectorAll(`tr > *:nth-child(${Number(input.dataset.columnIndex) + 1})`).forEach((cell) => {
                                cell.classList.toggle('hidden', !input.checked);
                            });
                        });
                    });
                }
                return;
            }

            const exportRows = rows();

            if (action === 'copy') {
                await navigator.clipboard?.writeText(exportRows.map((row) => row.join('\t')).join('\n'));
                return;
            }
            if (action === 'csv') {
                download(exportRows.map((row) => row.map((value) => `"${value.replaceAll('"', '""')}"`).join(',')).join('\n'), 'siblings.csv', 'text/csv');
                return;
            }
            if (action === 'excel') {
                download(`<html><meta charset="utf-8"><table>${exportRows.map((row) => `<tr>${row.map((value) => `<td>${value}</td>`).join('')}</tr>`).join('')}</table></html>`, 'siblings.xls', 'application/vnd.ms-excel');
                return;
            }
            if (action === 'pdf' && window.pdfMake) {
                window.pdfMake.createPdf({
                    content: [{ text: 'Sibling List', style: 'header' }, { table: { headerRows: 1, body: exportRows }, layout: 'lightHorizontalLines' }],
                    styles: { header: { fontSize: 14, bold: true, margin: [0, 0, 0, 8] } },
                }).download('siblings.pdf');
                return;
            }

            const clone = table.cloneNode(true);
            clone.querySelectorAll('tr').forEach((row) => row.lastElementChild?.remove());
            printTable('Sibling List', clone);
        });
    });

    document.querySelectorAll('[data-edit-id]').forEach((button) => {
        button.addEventListener('click', () => {
            const students = JSON.parse(button.dataset.editStudents || '[]');
            editForm.dataset.id = button.dataset.editId;
            document.querySelector('#edit-name').value = button.dataset.editName || '';
            document.querySelector('#edit-cnic').value = button.dataset.editCnic || '';
            document.querySelector('#edit-phone').value = button.dataset.editPhone || '';
            document.querySelector('#edit-current-students').innerHTML = students.map((student) =>
                `<label class="sibling-current-row"><input type="checkbox" name="remove_student_session_ids[]" value="${student.student_session_id}">Remove ${student.admission_no} - ${(student.firstname || '')} ${(student.lastname || '')}</label>`
            ).join('') || '<span>No active students</span>';
            document.querySelectorAll('#edit-students option').forEach((option) => {
                option.disabled = students.some((student) => String(student.student_session_id) === option.value);
            });
            show('#edit-sibling-modal');
        });
    });

    editForm?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const response = await fetch(`${window.location.origin}/admin/adm/siblings/${editForm.dataset.id}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                Accept: 'application/json',
            },
            body: new FormData(editForm),
        });

        if (response.ok) {
            window.location.reload();
            return;
        }

        window.alert('Unable to update sibling.');
    });
}());
