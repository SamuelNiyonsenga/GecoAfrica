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
})();(() => {
    const openButton = document.querySelector('[data-admin-menu-open]');
    const sidebar = document.querySelector('#admin-sidebar');
    const backdrop = document.querySelector('[data-admin-menu-backdrop]');
    const closeMenu = () => { sidebar?.classList.remove('is-open'); if (backdrop) backdrop.hidden = true; openButton?.setAttribute('aria-expanded', 'false'); document.body.classList.remove('admin-menu-open'); openButton?.focus(); };
    openButton?.addEventListener('click', () => { sidebar?.classList.add('is-open'); if (backdrop) backdrop.hidden = false; openButton.setAttribute('aria-expanded', 'true'); document.body.classList.add('admin-menu-open'); sidebar?.querySelector('a,button,summary')?.focus(); });
    backdrop?.addEventListener('click', closeMenu);
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && sidebar?.classList.contains('is-open')) closeMenu(); });
    document.querySelectorAll('[data-nav-group]').forEach((group, index) => {
        const key = `geco-admin-nav-${index}`;
        const saved = localStorage.getItem(key);
        if (saved !== null) group.open = saved === 'open';
        group.addEventListener('toggle', () => localStorage.setItem(key, group.open ? 'open' : 'closed'));
    });
    document.querySelectorAll('[data-auto-dismiss]').forEach((notice) => setTimeout(() => { notice.classList.add('is-dismissed'); setTimeout(() => notice.remove(), 300); }, 6500));
})();
(() => {
    const dialog = document.querySelector('[data-media-dialog]');
    let activeField = null;
    const grid = dialog?.querySelector('[data-picker-grid]');
    const more = dialog?.querySelector('[data-picker-more]');
    const search = dialog?.querySelector('[data-picker-search]');
    const type = dialog?.querySelector('[data-picker-type]');
    let nextUrl = null;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const renderItems = (items) => items.forEach((item) => {
        const button = document.createElement('button'); button.type = 'button'; button.className = 'media-picker-item';
        const preview = item.mime?.startsWith('image/') ? document.createElement('img') : document.createElement('span');
        if (preview.tagName === 'IMG') { preview.src = item.url; preview.alt = ''; } else { preview.className = 'media-picker-file'; preview.textContent = (item.mime || 'File').split('/').pop().toUpperCase(); }
        const name = document.createElement('span'); name.textContent = item.name; button.append(preview, name);
        button.addEventListener('click', () => { if (!activeField) return; activeField.input.value = item.value; activeField.label.textContent = item.name; activeField.input.dispatchEvent(new Event('change', {bubbles:true})); dialog.close(); });
        grid?.append(button);
    });
    const load = async (url = null, append = false) => {
        if (!grid) return; if (!append) grid.replaceChildren();
        const target = url || `/admin/media/picker?q=${encodeURIComponent(search?.value || '')}&type=${encodeURIComponent(type?.value || '')}`;
        const response = await fetch(target, {headers:{Accept:'application/json'}}); const data = await response.json(); renderItems(data.items || []); nextUrl=data.next_page_url; if(more) more.hidden=!nextUrl;
    };
    document.querySelectorAll('[data-media-picker-open]').forEach((button) => button.addEventListener('click', () => {
        const input=document.getElementById(button.dataset.field); const label=button.parentElement.querySelector('[data-media-selected]'); activeField={input,label}; dialog.showModal(); load(); search?.focus();
    }));
    dialog?.querySelector('[data-media-close]')?.addEventListener('click',()=>dialog.close());
    let filterTimer; [search,type].forEach((control)=>control?.addEventListener('input',()=>{clearTimeout(filterTimer);filterTimer=setTimeout(()=>load(),180)}));
    more?.addEventListener('click',()=>nextUrl&&load(nextUrl,true));
    document.querySelectorAll('[data-media-remove]').forEach((button)=>button.addEventListener('click',()=>{const input=document.getElementById(button.dataset.mediaRemove);input.value='';input.dispatchEvent(new Event('change',{bubbles:true}));button.parentElement.querySelector('[data-media-selected]').textContent='No file selected';}));
    dialog?.querySelector('[data-picker-upload]')?.addEventListener('change', async (event)=>{
        const files=event.target.files;if(!files?.length)return;const data=new FormData();data.append('_token',csrf);Array.from(files).forEach((file)=>data.append('files[]',file));
        const status=document.createElement('p');status.setAttribute('role','status');status.textContent='Uploading…';dialog.append(status);
        try{const response=await fetch('/admin/media',{method:'POST',headers:{Accept:'application/json','X-CSRF-TOKEN':csrf},body:data});if(!response.ok)throw new Error('Upload failed');const result=await response.json();if(result.items?.length){renderItems(result.items);status.textContent='Upload complete. Choose the new file above.';}else status.textContent='Upload complete.';}
        catch{status.textContent='Upload failed. Check the file type and try again.';}finally{event.target.value='';}
    });
    document.querySelectorAll('[data-cms-editor-form]').forEach((form)=>{
        const slug=form.querySelector('[data-slug-input]');const toggle=form.querySelector('[data-slug-toggle]');const titleKey=form.dataset.titleField;const title=titleKey?form.querySelector(`[name="fields[${titleKey}]"]`):null;const url=form.querySelector('[data-public-url]');let dirty=false;let submitted=false;
        const makeSlug=(value)=>value.normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
        if(slug && form.dataset.entryExisting!=='1'){slug.readOnly=true;const update=()=>{if(!dirty)slug.value=makeSlug(title?.value||'');if(url)url.textContent=`${location.origin}${location.pathname.replace(/\/create$/,'')}/${slug.value}`;};title?.addEventListener('input',update);update();}
        toggle?.addEventListener('click',()=>{slug.readOnly=!slug.readOnly;toggle.textContent=slug.readOnly?'Edit':'Lock';if(!slug.readOnly)slug.focus();});
        form.addEventListener('input',()=>{dirty=true;if(url&&slug)url.textContent=`${location.origin}${location.pathname.replace(/\/create$/,'')}/${slug.value}`;});
        form.addEventListener('submit',()=>{submitted=true;});
        window.addEventListener('beforeunload',(event)=>{if(dirty&&!submitted){event.preventDefault();event.returnValue='';}});
    });
    const uploadForm=document.querySelector('[data-media-upload-form]');uploadForm?.addEventListener('submit',(event)=>{const input=uploadForm.querySelector('input[type=file]');const progress=uploadForm.querySelector('[data-upload-progress]');const status=uploadForm.querySelector('[data-upload-status]');if(!input?.files?.length||!window.XMLHttpRequest)return;event.preventDefault();const body=new FormData(uploadForm);const xhr=new XMLHttpRequest();xhr.open('POST',uploadForm.action);xhr.upload.onprogress=(e)=>{if(e.lengthComputable){progress.hidden=false;progress.value=Math.round(e.loaded/e.total*100);status.textContent=`Uploading ${progress.value}%`;}};xhr.onload=()=>{if(xhr.status>=200&&xhr.status<400){status.textContent='Upload complete.';location.reload();}else status.textContent='Upload failed. Check the selected files and try again.';};xhr.onerror=()=>status.textContent='Upload failed. Check your connection and try again.';xhr.send(body);});
})();
(() => {
    document.querySelectorAll('[data-section-order]').forEach((list) => {
        const rank = () => list.querySelectorAll('[data-section-row]').forEach((row, index) => { const input=row.querySelector('[data-section-rank]'); if(input) input.value=String(index+1); });
        list.addEventListener('click',(event)=>{const button=event.target.closest('[data-move-up],[data-move-down]');if(!button)return;const row=button.closest('[data-section-row]');if(button.hasAttribute('data-move-up')&&row.previousElementSibling)list.insertBefore(row,row.previousElementSibling);if(button.hasAttribute('data-move-down')&&row.nextElementSibling)list.insertBefore(row.nextElementSibling,row);rank();});
    });
    const sidebar=document.querySelector('#admin-sidebar');document.addEventListener('keydown',(event)=>{if(event.key!=='Tab'||!sidebar?.classList.contains('is-open'))return;const focusable=[...sidebar.querySelectorAll('a[href],button:not([disabled]),summary,[tabindex="0"]')];if(!focusable.length)return;const first=focusable[0],last=focusable.at(-1);if(event.shiftKey&&document.activeElement===first){event.preventDefault();last.focus();}else if(!event.shiftKey&&document.activeElement===last){event.preventDefault();first.focus();}});
})();
