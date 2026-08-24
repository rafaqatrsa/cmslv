(function () {
    'use strict';

    const page = document.querySelector('.transfer-page');
    const sourceClass = document.querySelector('#source-class');
    const sourceSection = document.querySelector('#source-section');
    const targetClass = document.querySelector('#target-class');
    const targetSection = document.querySelector('#target-section');
    const modal = document.querySelector('[data-transfer-modal]');
    const form = document.querySelector('[data-transfer-form]');
    const error = document.querySelector('[data-transfer-error]');

    if (!page) {
        return;
    }

    const loadSections = async (classId, select, selected = '') => {
        select.innerHTML = '<option value="">Select</option>';

        if (!classId) {
            return;
        }

        const response = await fetch(`${window.location.origin}/admin/adm/stdtransferclasssection/sections?class_id=${encodeURIComponent(classId)}`, { headers: { Accept: 'application/json' } });
        if (!response.ok) {
            return;
        }

        const sections = await response.json();
        sections.forEach((item) => {
            const option = new Option(item.section, item.id, false, String(item.id) === String(selected));
            select.add(option);
        });
    };

    sourceClass?.addEventListener('change', () => loadSections(sourceClass.value, sourceSection));
    targetClass?.addEventListener('change', () => loadSections(targetClass.value, targetSection));

    if (sourceClass?.value) {
        loadSections(sourceClass.value, sourceSection, sourceSection.dataset.selected);
    }

    document.querySelector('[data-select-all]')?.addEventListener('change', (event) => {
        document.querySelectorAll('.student-check').forEach((checkbox) => {
            checkbox.checked = event.target.checked;
        });
    });

    document.querySelector('[data-open-transfer]')?.addEventListener('click', () => {
        if (!document.querySelector('.student-check:checked')) {
            error.textContent = 'Please select at least one student.';
            return;
        }
        modal.classList.remove('hidden');
    });
    document.querySelectorAll('[data-close-transfer]').forEach((button) => button.addEventListener('click', () => modal.classList.add('hidden')));

    document.querySelector('[data-confirm-transfer]')?.addEventListener('click', async (event) => {
        error.textContent = '';
        event.currentTarget.disabled = true;
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                Accept: 'application/json',
            },
            body: new FormData(form),
        });
        const payload = await response.json().catch(() => ({}));

        if (!response.ok) {
            const messages = Object.values(payload.errors || {}).flat();
            error.textContent = messages[0] || payload.message || 'Transfer request failed. Please try again.';
            event.currentTarget.disabled = false;
            return;
        }

        modal.classList.add('hidden');
        if (typeof window.successMsg === 'function') {
            window.successMsg(payload.message);
        } else {
            window.alert(payload.message);
        }
        window.location.reload();
    });
}());
