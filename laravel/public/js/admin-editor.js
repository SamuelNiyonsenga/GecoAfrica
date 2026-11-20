// Rich text, photo gallery, character counters and Google preview for the CMS content editor.
// The media picker itself lives in admin-structured.js (window.cmsOpenMediaPicker).
(() => {
    const escapeHtml = (value) => String(value).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const isImage = (path) => /\.(jpe?g|png|webp|gif|svg)$/i.test(path || '');

    /* ---------- Rich text ---------- */
    document.querySelectorAll('[data-richtext]').forEach((editor) => {
        const area = editor.querySelector('[data-richtext-area]');
        const hidden = editor.querySelector('[data-richtext-value]');
        if (!area || !hidden) return;
        let savedRange = null;

        try { document.execCommand('defaultParagraphSeparator', false, 'p'); } catch { /* older browsers */ }
        const sync = (announce = true) => {
            const html = area.innerHTML.trim();
            hidden.value = area.textContent.trim() === '' && !area.querySelector('img') ? '' : html;
            area.classList.toggle('is-empty', hidden.value === '');
            // Only real edits count as unsaved changes (not the first sync when the page opens).
            if (announce) hidden.dispatchEvent(new Event('change', { bubbles: true }));
        };
        const remember = () => {
            const selection = window.getSelection();
            if (selection?.rangeCount && area.contains(selection.anchorNode)) savedRange = selection.getRangeAt(0).cloneRange();
        };
        const restore = () => {
            area.focus();
            if (!savedRange) return;
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(savedRange);
        };

        area.addEventListener('input', sync);
        area.addEventListener('keyup', remember);
        area.addEventListener('mouseup', remember);
        area.addEventListener('blur', remember);
        // Paste as plain paragraphs so Word and web styles never reach the website.
        area.addEventListener('paste', (event) => {
            const text = event.clipboardData?.getData('text/plain');
            if (text === undefined) return;
            event.preventDefault();
            const html = text.split(/\r?\n\s*\r?\n/).map((block) => block.trim()).filter(Boolean)
                .map((block) => `<p>${escapeHtml(block).replace(/\r?\n/g, '<br>')}</p>`).join('');
            document.execCommand('insertHTML', false, html || escapeHtml(text));
            sync();
        });

        editor.querySelectorAll('[data-command]').forEach((button) => button.addEventListener('mousedown', (event) => {
            event.preventDefault(); // keep the text selection
            restore();
            const command = button.dataset.command;
            let value = button.dataset.value || null;
            if (command === 'formatBlock' && value) {
                const current = (document.queryCommandValue('formatBlock') || '').toLowerCase();
                if (current === value) value = 'p'; // pressing Heading again turns it back into text
                value = `<${value}>`;
            }
            document.execCommand(command, false, value);
            if (command === 'removeFormat') document.execCommand('unlink');
            sync();
        }));

        editor.querySelector('[data-richtext-link]')?.addEventListener('mousedown', (event) => {
            event.preventDefault();
            restore();
            const selection = window.getSelection();
            if (!selection || selection.isCollapsed) { window.alert('Select the words you want to turn into a link first.'); return; }
            const url = window.prompt('Link address (for example https://example.com, /contact or an email address):', 'https://');
            if (!url || url === 'https://') return;
            const href = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(url.trim()) ? `mailto:${url.trim()}` : url.trim();
            document.execCommand('createLink', false, href);
            sync();
        });

        editor.querySelector('[data-richtext-image]')?.addEventListener('mousedown', (event) => {
            event.preventDefault();
            remember();
            if (!window.cmsOpenMediaPicker) return;
            window.cmsOpenMediaPicker((item) => {
                if (!item.mime?.startsWith('image/')) { window.alert('Choose an image file.'); return true; }
                const alt = window.prompt('Describe the photo for people who cannot see it:', item.alt_text || '') ?? '';
                const caption = window.prompt('Caption shown under the photo (optional):', '') ?? '';
                restore();
                const src = '/' + String(item.value).replace(/^\/+/, '');
                const figure = `<figure><img src="${escapeHtml(src)}" alt="${escapeHtml(alt)}">${caption.trim() ? `<figcaption>${escapeHtml(caption.trim())}</figcaption>` : ''}</figure><p><br></p>`;
                document.execCommand('insertHTML', false, figure);
                sync();
                return false;
            }, { type: 'images' });
        });

        sync(false);
    });

    /* ---------- Photo gallery field ---------- */
    document.querySelectorAll('[data-gallery-field]').forEach((field) => {
        const list = field.querySelector('[data-gallery-list]');
        const name = field.dataset.name;
        const changed = () => field.dispatchEvent(new Event('change', { bubbles: true }));
        const addItem = (value, url) => {
            if ([...list.querySelectorAll('input')].some((input) => input.value === value)) return;
            const item = document.createElement('li');
            item.className = 'gallery-field-item';
            item.dataset.galleryItem = '';
            item.innerHTML = `<input type="hidden" name="${escapeHtml(name)}" value="${escapeHtml(value)}">`
                + (isImage(value) ? `<img src="${escapeHtml(url)}" alt="">` : `<span class="media-picker-file">${escapeHtml(value.split('.').pop().toUpperCase())}</span>`)
                + '<span class="gallery-field-actions"><button type="button" class="button button-small button-quiet" data-gallery-move="up" aria-label="Move photo earlier">←</button>'
                + '<button type="button" class="button button-small button-quiet" data-gallery-move="down" aria-label="Move photo later">→</button>'
                + '<button type="button" class="button button-small button-quiet" data-gallery-remove aria-label="Remove photo">Remove</button></span>';
            list.append(item);
            changed();
        };
        field.querySelector('[data-gallery-add]')?.addEventListener('click', () => {
            window.cmsOpenMediaPicker?.((item) => { addItem(item.value, item.url); return true; }, { multiple: true, type: 'images' });
        });
        list.addEventListener('click', (event) => {
            const item = event.target.closest('[data-gallery-item]');
            if (!item) return;
            if (event.target.closest('[data-gallery-remove]')) { item.remove(); changed(); }
            const move = event.target.closest('[data-gallery-move]')?.dataset.galleryMove;
            if (move === 'up' && item.previousElementSibling) { list.insertBefore(item, item.previousElementSibling); changed(); }
            if (move === 'down' && item.nextElementSibling) { list.insertBefore(item.nextElementSibling, item); changed(); }
        });
    });

    /* ---------- Character counters ---------- */
    document.querySelectorAll('[data-char-count]').forEach((input) => {
        const limit = Number(input.dataset.charCount);
        const counter = document.createElement('span');
        counter.className = 'char-count';
        counter.setAttribute('aria-live', 'polite');
        input.insertAdjacentElement('afterend', counter);
        const update = () => {
            const length = input.value.length;
            counter.textContent = `${length} / ${limit} characters`;
            counter.classList.toggle('is-over', length > limit - 10);
        };
        input.addEventListener('input', update);
        update();
    });

    /* ---------- Google preview ---------- */
    document.querySelectorAll('[data-seo-panel]').forEach((panel) => {
        const form = panel.closest('form');
        const value = (name) => (name ? form.querySelector(`[name="${name}"]`)?.value ?? '' : '').trim();
        const titleOut = panel.querySelector('[data-seo-preview-title]');
        const descriptionOut = panel.querySelector('[data-seo-preview-description]');
        const urlOut = panel.querySelector('[data-seo-preview-url]');
        const publicUrl = form.querySelector('[data-public-url]');
        const strip = (html) => { const div = document.createElement('div'); div.innerHTML = html; return div.textContent.replace(/\s+/g, ' ').trim(); };
        const update = () => {
            const title = value('fields[seoTitle]') || (value(panel.dataset.fallbackTitle) + panel.dataset.titleSuffix);
            let description = value('fields[seoDescription]') || value(panel.dataset.fallbackDescription) || strip(value('fields[body]'));
            if (description.length > 160) description = description.slice(0, 157).replace(/\s+\S*$/, '') + '…';
            titleOut.textContent = title.length > 62 ? title.slice(0, 60) + '…' : (title || 'Add a title');
            descriptionOut.textContent = description || 'Add a summary or Google description so people know what this page is about.';
            if (publicUrl && urlOut) urlOut.textContent = publicUrl.textContent;
        };
        form.addEventListener('input', update);
        form.addEventListener('change', update);
        update();
    });

    /* ---------- Bulk gallery upload: show chosen file count ---------- */
    document.querySelectorAll('[data-bulk-upload]').forEach((form) => {
        const input = form.querySelector('input[type=file]');
        const status = form.querySelector('[data-bulk-status]');
        input?.addEventListener('change', () => { if (status) status.textContent = input.files.length ? `${input.files.length} file(s) selected.` : ''; });
        form.addEventListener('submit', () => { if (status) status.textContent = 'Uploading… this can take a minute for many photos.'; form.querySelector('button[type=submit]')?.setAttribute('disabled', 'disabled'); });
    });
})();
