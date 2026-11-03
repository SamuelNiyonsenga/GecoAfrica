const menuToggle = document.querySelector('.menu-toggle');
const navigation = document.querySelector('.primary-navigation');

if (menuToggle && navigation) {
    const dropdowns = Array.from(navigation.querySelectorAll('.nav-group'));
    const focusableMenuItems = () => Array.from(navigation.querySelectorAll('a[href], button:not([disabled])'))
        .filter((item) => !item.closest('[hidden]'));

    const closeDropdowns = (except = null) => {
        dropdowns.forEach((group) => {
            if (group === except) return;
            group.classList.remove('is-open');
            group.querySelector('.nav-group-toggle')?.setAttribute('aria-expanded', 'false');
            const panel = group.querySelector('.nav-submenu');
            if (panel) panel.hidden = true;
        });
    };

    const closeMenu = ({ returnFocus = true } = {}) => {
        menuToggle.setAttribute('aria-expanded', 'false');
        menuToggle.setAttribute('aria-label', 'Open navigation');
        navigation.classList.remove('is-open');
        document.body.classList.remove('menu-open');
        closeDropdowns();
        if (returnFocus) menuToggle.focus();
    };

    menuToggle.addEventListener('click', () => {
        const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', String(!isExpanded));
        menuToggle.setAttribute('aria-label', isExpanded ? 'Open navigation' : 'Close navigation');
        navigation.classList.toggle('is-open', !isExpanded);
        document.body.classList.toggle('menu-open', !isExpanded);
        if (!isExpanded) focusableMenuItems()[0]?.focus();
        else closeDropdowns();
    });

    navigation.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.matchMedia('(max-width: 1024px)').matches) closeMenu({ returnFocus: false });
        });
    });

    dropdowns.forEach((group) => {
        const toggle = group.querySelector('.nav-group-toggle');
        const panel = group.querySelector('.nav-submenu');
        if (!toggle || !panel) return;

        const setOpen = (open) => {
            closeDropdowns(open ? group : null);
            group.classList.toggle('is-open', open);
            toggle.setAttribute('aria-expanded', String(open));
            panel.hidden = !open;
        };

        toggle.addEventListener('click', () => setOpen(toggle.getAttribute('aria-expanded') !== 'true'));
        group.addEventListener('mouseenter', () => {
            if (window.matchMedia('(min-width: 1025px)').matches) setOpen(true);
        });
        group.addEventListener('mouseleave', () => {
            if (window.matchMedia('(min-width: 1025px)').matches) setOpen(false);
        });
        toggle.addEventListener('keydown', (event) => {
            if (['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(event.key)) {
                event.preventDefault();
                setOpen(true);
                const items = Array.from(panel.querySelectorAll('a[href]'));
                items[event.key === 'ArrowUp' ? items.length - 1 : 0]?.focus();
            }
        });
        panel.addEventListener('keydown', (event) => {
            const items = Array.from(panel.querySelectorAll('a[href]'));
            const index = items.indexOf(document.activeElement);
            if (event.key === 'Escape') {
                setOpen(false);
                toggle.focus();
            } else if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                const direction = event.key === 'ArrowDown' ? 1 : -1;
                items[(index + direction + items.length) % items.length]?.focus();
            }
        });
    });

    document.addEventListener('pointerdown', (event) => {
        if (!navigation.contains(event.target) && !menuToggle.contains(event.target)) closeDropdowns();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            if (menuToggle.getAttribute('aria-expanded') === 'true') closeMenu();
            else {
                const openGroup = dropdowns.find((group) => group.querySelector('.nav-group-toggle')?.getAttribute('aria-expanded') === 'true');
                closeDropdowns();
                openGroup?.querySelector('.nav-group-toggle')?.focus();
            }
        }
        if (event.key === 'Tab' && menuToggle.getAttribute('aria-expanded') === 'true') {
            const items = focusableMenuItems();
            const first = items[0];
            const last = items[items.length - 1];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last?.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first?.focus();
            }
        }
    });
}

const siteHeader = document.querySelector('.site-header');
if (siteHeader) {
    const headerSentinel = document.createElement('span');
    headerSentinel.className = 'header-scroll-sentinel';
    headerSentinel.setAttribute('aria-hidden', 'true');
    siteHeader.before(headerSentinel);
    if ('IntersectionObserver' in window) {
        const headerObserver = new IntersectionObserver(([entry]) => {
            siteHeader.classList.toggle('is-compact', !entry.isIntersecting);
        }, { threshold: 0 });
        headerObserver.observe(headerSentinel);
    }
}

const homeCarousel = document.querySelector('[data-home-carousel]');

if (homeCarousel) {
    const track = homeCarousel.querySelector('[data-home-track]');
    const slides = Array.from(homeCarousel.querySelectorAll('[data-home-slide]'));
    const dots = Array.from(homeCarousel.querySelectorAll('[data-home-dot]'));
    const currentCount = homeCarousel.querySelector('[data-home-current]');
    const pauseButton = homeCarousel.querySelector('[data-home-toggle]');
    const pauseIcon = homeCarousel.querySelector('[data-home-toggle-icon]');
    const progress = homeCarousel.querySelector('[data-home-progress]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const interval = Math.max(5000, Number(homeCarousel.dataset.interval) || 7500);
    let currentIndex = 0;
    let timer = null;
    let manuallyPaused = reducedMotion;
    let interactionPaused = false;

    const stopAutoplay = () => {
        window.clearTimeout(timer);
        timer = null;
        homeCarousel.classList.remove('is-playing');
        homeCarousel.classList.add('is-paused');
    };

    const startAutoplay = () => {
        window.clearTimeout(timer);
        timer = null;

        if (manuallyPaused || interactionPaused || document.hidden || slides.length < 2) {
            stopAutoplay();
            return;
        }

        homeCarousel.classList.remove('is-paused');
        homeCarousel.classList.remove('is-playing');
        homeCarousel.style.setProperty('--slide-duration', `${interval}ms`);
        window.requestAnimationFrame(() => homeCarousel.classList.add('is-playing'));
        timer = window.setTimeout(() => showSlide(currentIndex + 1), interval);
    };

    function showSlide(index) {
        currentIndex = (index + slides.length) % slides.length;

        slides.forEach((slide, slideIndex) => {
            const isActive = slideIndex === currentIndex;
            slide.classList.toggle('is-active', isActive);
            slide.setAttribute('aria-hidden', String(!isActive));
            slide.inert = !isActive;
        });

        dots.forEach((dot, dotIndex) => {
            if (dotIndex === currentIndex) {
                dot.setAttribute('aria-current', 'true');
            } else {
                dot.removeAttribute('aria-current');
            }
        });

        track.style.transform = `translate3d(-${currentIndex * 100}%, 0, 0)`;
        currentCount.textContent = String(currentIndex + 1).padStart(2, '0');
        startAutoplay();
    }

    homeCarousel.querySelector('[data-home-prev]')?.addEventListener('click', () => showSlide(currentIndex - 1));
    homeCarousel.querySelector('[data-home-next]')?.addEventListener('click', () => showSlide(currentIndex + 1));
    dots.forEach((dot) => dot.addEventListener('click', () => showSlide(Number(dot.dataset.homeDot))));

    pauseButton?.addEventListener('click', () => {
        manuallyPaused = !manuallyPaused;
        pauseButton.setAttribute('aria-pressed', String(manuallyPaused));
        pauseButton.setAttribute('aria-label', manuallyPaused ? 'Play slideshow' : 'Pause slideshow');
        pauseIcon.textContent = manuallyPaused ? '▶' : 'Ⅱ';
        startAutoplay();
    });

    homeCarousel.addEventListener('mouseenter', () => {
        interactionPaused = true;
        stopAutoplay();
    });
    homeCarousel.addEventListener('mouseleave', () => {
        interactionPaused = false;
        startAutoplay();
    });
    homeCarousel.addEventListener('focusin', () => {
        interactionPaused = true;
        stopAutoplay();
    });
    homeCarousel.addEventListener('focusout', (event) => {
        if (!homeCarousel.contains(event.relatedTarget)) {
            interactionPaused = false;
            startAutoplay();
        }
    });
    document.addEventListener('visibilitychange', startAutoplay);

    if (reducedMotion) {
        pauseButton?.setAttribute('aria-label', 'Play slideshow');
        pauseButton?.setAttribute('aria-pressed', 'true');
        if (pauseIcon) pauseIcon.textContent = '▶';
    } else {
        startAutoplay();
    }
}

const revealItems = document.querySelectorAll('[data-reveal]');

if (revealItems.length) {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!prefersReducedMotion && 'IntersectionObserver' in window) {
        document.documentElement.classList.add('has-scroll-reveal');
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -35px 0px' });

        revealItems.forEach((item) => revealObserver.observe(item));
    } else {
        revealItems.forEach((item) => item.classList.add('is-revealed'));
    }
}

const activityCards = Array.from(document.querySelectorAll('[data-activity-card]'));

if (activityCards.length) {
    const activityFilters = Array.from(document.querySelectorAll('[data-activity-filter]'));
    const activitySearch = document.querySelector('[data-activity-search]');
    const activityResults = document.querySelector('[data-activity-results]');
    const activityEmpty = document.querySelector('[data-activity-empty]');
    let activeActivityCategory = 'all';

    const filterActivities = () => {
        const query = (activitySearch?.value || '').trim().toLocaleLowerCase();
        let visibleCount = 0;

        activityCards.forEach((card) => {
            const matchesCategory = activeActivityCategory === 'all' || card.dataset.activityCategory === activeActivityCategory;
            const matchesSearch = !query || (card.dataset.activitySearchText || '').includes(query);
            card.hidden = !(matchesCategory && matchesSearch);
            if (!card.hidden) visibleCount += 1;
        });

        if (activityResults) activityResults.textContent = `Showing ${visibleCount} ${visibleCount === 1 ? 'assignment' : 'assignments'}`;
        if (activityEmpty) activityEmpty.hidden = visibleCount > 0;
    };

    activityFilters.forEach((filter) => {
        filter.addEventListener('click', () => {
            activeActivityCategory = filter.dataset.activityFilter || 'all';
            activityFilters.forEach((item) => item.setAttribute('aria-pressed', String(item === filter)));
            filterActivities();
        });
    });

    activitySearch?.addEventListener('input', filterActivities);
}
