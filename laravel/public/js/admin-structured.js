(() => {
    const humanize = (value) => String(value)
        .replace(/([a-z0-9])([A-Z])/g, '$1 $2')
        .replace(/[_-]+/g, ' ')
        .replace(/\s+/g, ' ')
        .replace(/^./, (letter) => letter.toUpperCase());

    const blankFrom = (sample) => {
        if (Array.isArray(sample)) return [];
        if (sample && typeof sample === 'object') {
            return Object.fromEntries(Object.entries(sample).map(([key, value]) => [key, blankFrom(value)]));
        }
        if (typeof sample === 'boolean') return false;
        return '';
    };

    const scalarControl = (value, label, onChange) => {
        const field = document.createElement('label');
        field.className = 'structured-field';
        const caption = document.createElement('span');
        caption.textContent = humanize(label);
        field.append(caption);

        if (typeof value === 'boolean') {
            const control = document.createElement('input');
            control.type = 'checkbox';
            control.checked = value;
            control.addEventListener('change', () => onChange(control.checked));
            field.append(control);
            return field;
        }

        const longText = typeof value === 'string' && (value.length > 120 || /description|paragraph|summary|text|body|caption|intro|headline/i.test(label));
        const control = document.createElement(longText ? 'textarea' : 'input');
        if (control.tagName === 'INPUT') control.type = typeof value === 'number' ? 'number' : 'text';
        else control.rows = Math.max(3, Math.min(8, Math.ceil((String(value ?? '').length || 80) / 100)));
        control.value = value ?? '';
        control.addEventListener('input', () => onChange(control.type === 'number' ? (control.value === '' ? null : Number(control.value)) : control.value));
        field.append(control);
        return field;
    };

    const renderValue = (value, label, onChange, template = null) => {
        if (Array.isArray(value)) {
            const block = document.createElement('fieldset');
            block.className = 'structured-group structured-list';
            const legend = document.createElement('legend');
            legend.textContent = humanize(label);
            block.append(legend);
            const rows = document.createElement('div');
            rows.className = 'structured-items';
            block.append(rows);
            let items = value;
            let itemTemplate = value.length
                ? blankFrom(value[0])
                : (Array.isArray(template) ? (template.length ? blankFrom(template[0]) : '') : (template ?? ''));

            const redraw = () => {
                rows.replaceChildren();
                items.forEach((item, index) => {
                    const row = document.createElement('div');
                    row.className = 'structured-item';
                    const content = renderValue(item, `${humanize(label)} ${index + 1}`, (next) => {
                        items[index] = next;
                        onChange(items);
                    }, itemTemplate);
                    row.append(content);
                    const remove = document.createElement('button');
                    remove.type = 'button';
                    remove.className = 'button button-small button-quiet structured-remove';
                    remove.textContent = 'Remove';
                    remove.setAttribute('aria-label', `Remove ${humanize(label)} ${index + 1}`);
                    remove.addEventListener('click', () => {
                        items.splice(index, 1);
                        onChange(items);
                        redraw();
                    });
                    row.append(remove);
                    rows.append(row);
                });
            };
            redraw();

            const add = document.createElement('button');
            add.type = 'button';
            add.className = 'button button-small button-outline structured-add';
            add.textContent = `Add ${humanize(label).replace(/s$/, '')}`;
            add.addEventListener('click', () => {
                const next = itemTemplate && typeof itemTemplate === 'object' ? blankFrom(itemTemplate) : (Array.isArray(itemTemplate) ? (itemTemplate.length ? blankFrom(itemTemplate[0]) : '') : '');
                items = [...items, next];
                onChange(items);
                redraw();
            });
            block.append(add);
            return block;
        }

        if (value && typeof value === 'object') {
            const block = document.createElement('fieldset');
            block.className = 'structured-group';
            const legend = document.createElement('legend');
            legend.textContent = humanize(label);
            block.append(legend);
            const fields = document.createElement('div');
            fields.className = 'structured-fields';
            for (const [key, child] of Object.entries(value)) {
                fields.append(renderValue(child, key, (next) => {
                    value[key] = next;
                    onChange(value);
                }, template && typeof template === 'object' ? template[key] : null));
            }
            block.append(fields);
            return block;
        }

        return scalarControl(value, label, onChange);
    };

    document.querySelectorAll('[data-structured-editor]').forEach((editor) => {
        const valueInput = editor.querySelector('[data-structured-value]');
        const templateInput = editor.querySelector('[data-structured-template]');
        const root = editor.querySelector('[data-structured-root]');
        const label = editor.dataset.label || 'Content';
        if (!valueInput || !root) return;
        let value;
        let template = null;
        try {
            value = JSON.parse(valueInput.value || 'null');
            if (templateInput?.value) template = JSON.parse(templateInput.value);
        } catch {
            const error = document.createElement('p');
            error.className = 'notice notice-error';
            error.textContent = 'This content could not be opened in the structured editor. Please contact your administrator before saving it.';
            editor.append(error);
            return;
        }
        if (value === null || typeof value !== 'object') value = [];
        const update = (next) => {
            value = next;
            valueInput.value = JSON.stringify(value);
        };
        root.append(renderValue(value, label, update, template));
        update(value);
    });

    document.querySelectorAll('[data-cms-editor-form]').forEach((form) => {
        const status = form.querySelector('[data-editor-status]');
        if (!status) return;
        const updateDirtyState = () => {
            status.textContent = 'Unsaved changes. Choose Save draft or Save & publish.';
        };
        form.addEventListener('input', updateDirtyState);
        form.addEventListener('change', updateDirtyState);
        form.addEventListener('submit', (event) => {
            status.textContent = event.submitter?.hasAttribute('data-editor-publish')
                ? 'Saving your changes and publishing…'
                : 'Saving your draft…';
        });
    });
})();