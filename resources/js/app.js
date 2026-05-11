import './bootstrap';

window.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('[data-sidebar]');
    const backdrop = document.querySelector('[data-sidebar-backdrop]');
    const openButton = document.querySelector('[data-sidebar-open]');

    if (sidebar && backdrop && openButton) {
        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        };

        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
        };

        openButton.addEventListener('click', openSidebar);
        backdrop.addEventListener('click', closeSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
    }

    const marketingPage = document.querySelector('[data-marketing-page]');

    if (!marketingPage) {
        return;
    }

    const revealElements = Array.from(marketingPage.querySelectorAll('[data-reveal]'));

    if (!revealElements.length) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        revealElements.forEach((element) => element.classList.add('is-visible'));
        return;
    }

    const revealObserver = new IntersectionObserver(
        (entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        {
            threshold: 0.16,
            rootMargin: '0px 0px -10% 0px',
        },
    );

    revealElements.forEach((element) => {
        const delay = element.getAttribute('data-delay');

        if (delay) {
            element.style.transitionDelay = `${delay}ms`;
        }

        revealObserver.observe(element);
    });
});
