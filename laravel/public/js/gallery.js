(() => {
    const page = document.querySelector('.gallery-page');
    if (!page) return;

    const cards = [...page.querySelectorAll('[data-gallery-card]')];
    const filters = [...page.querySelectorAll('[data-gallery-filter]')];
    const resultCount = page.querySelector('[data-gallery-count]');
    const emptyState = page.querySelector('[data-gallery-empty]');
    const dialog = page.querySelector('[data-gallery-lightbox]');
    const dialogImage = page.querySelector('[data-gallery-lightbox-image]');
    const dialogCategory = page.querySelector('[data-gallery-lightbox-category]');
    const dialogTitle = page.querySelector('[data-gallery-lightbox-title]');
    const dialogCaption = page.querySelector('[data-gallery-lightbox-caption]');
    const dialogCount = page.querySelector('[data-gallery-lightbox-count]');
    const closeButton = page.querySelector('[data-gallery-close]');
    const previousButton = page.querySelector('[data-gallery-prev]');
    const nextButton = page.querySelector('[data-gallery-next]');
    let currentButtons = [];
    let currentIndex = 0;
    let returnFocusTo = null;

    const visibleButtons = () => cards
        .filter((card) => !card.hidden)
        .map((card) => card.querySelector('[data-gallery-open]'));

    filters.forEach((filter) => {
        filter.addEventListener('click', () => {
            const category = filter.dataset.galleryFilter;
            filters.forEach((button) => button.setAttribute('aria-pressed', String(button === filter)));
            cards.forEach((card) => {
                card.hidden = category !== 'all' && card.dataset.category !== category;
            });

            const count = cards.filter((card) => !card.hidden).length;
            if (resultCount) resultCount.textContent = count;
            if (emptyState) emptyState.hidden = count > 0;
        });
    });

    const showPhoto = (index) => {
        if (!currentButtons.length) return;
        currentIndex = (index + currentButtons.length) % currentButtons.length;
        const button = currentButtons[currentIndex];
        dialogImage.src = button.dataset.gallerySrc;
        dialogImage.alt = button.dataset.galleryAlt;
        dialogTitle.textContent = button.dataset.galleryTitle;
        dialogCategory.textContent = button.dataset.galleryCategory;
        dialogCaption.textContent = button.dataset.galleryCaption;
        dialogCount.textContent = `${currentIndex + 1} / ${currentButtons.length}`;
        previousButton.disabled = currentButtons.length < 2;
        nextButton.disabled = currentButtons.length < 2;
    };

    page.querySelectorAll('[data-gallery-open]').forEach((button) => {
        button.addEventListener('click', () => {
            currentButtons = visibleButtons();
            currentIndex = currentButtons.indexOf(button);
            returnFocusTo = button;
            showPhoto(currentIndex);
            dialog.showModal();
        });
    });

    closeButton.addEventListener('click', () => dialog.close());
    previousButton.addEventListener('click', () => showPhoto(currentIndex - 1));
    nextButton.addEventListener('click', () => showPhoto(currentIndex + 1));
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) dialog.close();
    });
    dialog.addEventListener('close', () => {
        dialogImage.removeAttribute('src');
        if (returnFocusTo) returnFocusTo.focus();
    });
    dialog.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowLeft') showPhoto(currentIndex - 1);
        if (event.key === 'ArrowRight') showPhoto(currentIndex + 1);
    });

    const imageWrap = page.querySelector('.gallery-lightbox-image-wrap');
    let touchStartX = 0;
    let touchStartY = 0;
    imageWrap?.addEventListener('touchstart', (event) => {
        const touch = event.changedTouches[0];
        touchStartX = touch.clientX;
        touchStartY = touch.clientY;
    }, { passive: true });
    imageWrap?.addEventListener('touchend', (event) => {
        const touch = event.changedTouches[0];
        const deltaX = touch.clientX - touchStartX;
        const deltaY = touch.clientY - touchStartY;
        if (Math.abs(deltaX) > 48 && Math.abs(deltaX) > Math.abs(deltaY)) showPhoto(currentIndex + (deltaX < 0 ? 1 : -1));
    }, { passive: true });
})();
