(function () {
    'use strict';

    const page = document.querySelector('.promotion-page');
    const form = document.querySelector('[data-promotion-form]');
    const modal = document.querySelector('[data-promotion-modal]');
    const error = document.querySelector('[data-promotion-error]');

    if (!page || !form) {
        return;
    }

    const loadSections = async (classId, select, selected = '') => {
        select.innerHTML = '<option value="">Select</option>';
        if (!classId) {
            return;
        }
        const response = await fetch(`${window.location.origin}/admin/adm/promote_student/sections?class_id=${encodeURIComponent(classId)}`, { headers: { Accept: 'application/json' } });
        if (!response.ok) {
            return;
        }
        (await response.json()).forEach((item) => select.add(new Option(item.section, item.id, false, String(item.id) === String(selected))));
    };

    const sourceClass = document.querySelector('#promotion-source-class');
    const sourceSection = document.querySelector('#promotion-source-section');
    const targetClass = document.querySelector('#promotion-target-class');
    const targetSection = document.querySelector('#promotion-target-section');
    sourceClass?.addEventListener('change', () => loadSections(sourceClass.value, sourceSection));
    targetClass?.addEventListener('change', () => loadSections(targetClass.value, targetSection));
    if (sourceClass?.value) {
        loadSections(sourceClass.value, sourceSection, sourceSection.dataset.selected);
    }
    if (targetClass?.value) {
        loadSections(targetClass.value, targetSection, targetSection.dataset.selected);
    }

    document.querySelector('[data-promotion-select-all]')?.addEventListener('change', (event) => {
        document.querySelectorAll('.promotion-check').forEach((checkbox) => checkbox.checked = event.target.checked);
    });
    document.querySelectorAll('input[name="fee_promotion_mode"]').forEach((radio) => radio.addEventListener('change', () => {
        document.querySelector('[data-promotion-amount]').classList.toggle('hidden', radio.value !== 'increment_previous_tuition_fee_amount' || !radio.checked);
        document.querySelector('[data-promotion-percentage]').classList.toggle('hidden', radio.value !== 'increment_previous_tuition_fee_percentage' || !radio.checked);
    }));
    document.querySelector('[data-open-promotion]')?.addEventListener('click', () => {
        if (!document.querySelector('.promotion-check:checked')) {
            error.textContent = 'Please select at least one student.';
            return;
        }
        modal.classList.remove('hidden');
    });
    document.querySelectorAll('[data-close-promotion]').forEach((button) => button.addEventListener('click', () => modal.classList.add('hidden')));
    document.querySelector('[data-confirm-promotion]')?.addEventListener('click', async (event) => {
        error.textContent = '';
        event.currentTarget.disabled = true;
        const response = await fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '', Accept: 'application/json' },
            body: new FormData(form),
        });
        const payload = await response.json().catch(() => ({}));
        if (!response.ok) {
            error.textContent = Object.values(payload.errors || {}).flat()[0] || payload.message || 'Promotion request failed. Please try again.';
            event.currentTarget.disabled = false;
            return;
        }
        modal.classList.add('hidden');
        window.alert(payload.message);
        window.location.href = payload.redirect;
    });
}());
