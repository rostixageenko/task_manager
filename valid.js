document.addEventListener('DOMContentLoaded', () => {
    let isComposing = false;

    function validateTextResult(current, insert, selStart, selEnd) {
        const newValue = current.slice(0, selStart) + insert + current.slice(selEnd);
        if (newValue.length > 50) return { ok: false, msg: 'Максимум 50 символов' };
        if (/^\s/.test(newValue)) return { ok: false, msg: 'Поле не должно начинаться с пробела' };
        if (/\s{2,}/.test(newValue)) return { ok: false, msg: 'Нельзя вводить больше одного пробела подряд' };
        if (!/^[A-Za-zА-Яа-яЁё\s]*$/.test(newValue)) return { ok: false, msg: 'Допустимы только буквы и пробелы' };
        return { ok: true };
    }

    function validateNumberResult(current, insert, selStart, selEnd) {
        if (!/^\d*$/.test(insert)) return { ok: false, msg: 'Допустимы только цифры' };
        const newValue = current.slice(0, selStart) + insert + current.slice(selEnd);
        if (newValue.length > 4) return { ok: false, msg: 'Максимум 4 цифры' };
        return { ok: true };
    }

    const inputs = Array.from(document.querySelectorAll("input[type='text'], input[type='number']"));

    inputs.forEach(input => {
        if (input.type === 'text') input.setAttribute('maxlength', '50');
        if (input.type === 'number') input.setAttribute('maxlength', '4');

        input.addEventListener('beforeinput', (e) => {
            if (isComposing) return;
            if (e.inputType && e.inputType.startsWith('delete')) return;

            let data = e.data;
            if (!data && e.inputType === 'insertFromPaste') {
                try {
                    data = (e.clipboardData && e.clipboardData.getData('text')) || '';
                } catch (err) {
                    data = '';
                }
            }
            if (data === null || data === undefined) data = ''; 

            const start = input.selectionStart ?? input.value.length;
            const end = input.selectionEnd ?? input.value.length;

            let res;
            if (input.type === 'text') {
                res = validateTextResult(input.value, data, start, end);
            } else {
                res = validateNumberResult(input.value, data, start, end);
            }

            if (!res.ok) {
                e.preventDefault();
                alert(res.msg);
            }
        });

        input.addEventListener('paste', (e) => {
            if (isComposing) return;
            const paste = (e.clipboardData || window.clipboardData)?.getData('text') || '';
            const start = input.selectionStart ?? input.value.length;
            const end = input.selectionEnd ?? input.value.length;

            let res;
            if (input.type === 'text') {
                res = validateTextResult(input.value, paste, start, end);
            } else {
                res = validateNumberResult(input.value, paste, start, end);
            }

            if (!res.ok) {
                e.preventDefault();
                alert(res.msg);
            }
        });

        input.addEventListener('input', () => {
            if (isComposing) return;
            if (input.type === 'text') {
                const fixed = input.value.replace(/^\s+/, '').replace(/\s{2,}/g, ' ');
                if (fixed !== input.value) input.value = fixed.slice(0, 50);
                if (input.value.length > 50) input.value = input.value.slice(0, 50);
            } else if (input.type === 'number') {
                const fixed = input.value.replace(/\D/g, '').slice(0, 4);
                if (fixed !== input.value) input.value = fixed;
            }
        });
    });
});
