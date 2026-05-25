import './bootstrap';
import Sortable from 'sortablejs';
import { autoMountBackgrounds } from './three-bg';
import { initAllMotion } from './motion';
import { mountRevenueChart } from './revenue-chart';

window.addEventListener('DOMContentLoaded', () => {
    autoMountBackgrounds();
    initAllMotion();
    mountRevenueChart();

    // -----------------------------------------------------------------------
    // Tasks panel (projects/show)
    // -----------------------------------------------------------------------
    const tasksPanel = document.querySelector('[data-tasks-panel]');

    if (tasksPanel) {
        // Toggle "add task" / "paste from meeting" panes
        tasksPanel.querySelectorAll('[data-tasks-toggle]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const key = btn.getAttribute('data-tasks-toggle');
                const pane = tasksPanel.querySelector(`[data-tasks-pane="${key}"]`);
                if (pane) {
                    pane.classList.toggle('hidden');
                    if (!pane.classList.contains('hidden')) {
                        const firstInput = pane.querySelector('input, textarea');
                        if (firstInput) firstInput.focus();
                    }
                }
            });
        });

        // Per-task edit reveal
        tasksPanel.querySelectorAll('[data-task-edit-toggle]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-task-edit-toggle');
                const form = document.getElementById(id);
                if (form) form.classList.toggle('hidden');
            });
        });
        tasksPanel.querySelectorAll('[data-task-edit-cancel]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-task-edit-cancel');
                const form = document.getElementById(id);
                if (form) form.classList.add('hidden');
            });
        });

        // AJAX toggle done — falls back to normal submit on failure
        tasksPanel.querySelectorAll('form[data-task-toggle]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const tokenInput = form.querySelector('input[name="_token"]');
                if (!tokenInput) {
                    form.submit();
                    return;
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': tokenInput.value,
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({ _method: 'PATCH', _token: tokenInput.value }),
                        credentials: 'same-origin',
                    });

                    if (!response.ok) throw new Error('toggle failed');
                    const data = await response.json();

                    const row = form.closest('.group');
                    if (!row) return;

                    const isDone = data.status === 'done';
                    const checkbox = form.querySelector('button[type="submit"]');
                    const title = row.querySelector('p.text-sm.font-semibold');

                    if (checkbox) {
                        checkbox.className = 'flex h-5 w-5 items-center justify-center rounded-md border-2 transition '
                            + (isDone
                                ? 'border-emerald-500 bg-emerald-500 text-white'
                                : 'border-slate-300 bg-white hover:border-slate-500');
                        checkbox.innerHTML = isDone
                            ? '<svg viewBox="0 0 24 24" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/></svg>'
                            : '';
                    }
                    if (title) {
                        title.classList.toggle('line-through', isDone);
                        title.classList.toggle('opacity-60', isDone);
                    }
                } catch (err) {
                    form.submit();
                }
            });
        });

        // Auto-open form panes if server-side validation failed and they had input
        if (tasksPanel.querySelector('[data-tasks-pane="newTaskForm"] .text-rose-600')) {
            tasksPanel.querySelector('[data-tasks-pane="newTaskForm"]')?.classList.remove('hidden');
        }
        if (tasksPanel.querySelector('[data-tasks-pane="pasteForm"] .text-rose-600')) {
            tasksPanel.querySelector('[data-tasks-pane="pasteForm"]')?.classList.remove('hidden');
        }

        // Kanban drag-and-drop -----------------------------------------------
        const kanban = tasksPanel.querySelector('[data-tasks-kanban]');
        if (kanban) {
            const projectId = kanban.getAttribute('data-project-id');
            const reorderUrl = kanban.getAttribute('data-reorder-url');
            const csrf = kanban.getAttribute('data-csrf');
            const groupName = `kanban-${projectId}`;
            const lists = kanban.querySelectorAll('[data-kanban-list]');

            const updateEmptyState = () => {
                lists.forEach((list) => {
                    const empty = list.querySelector('.kanban-empty');
                    if (!empty) return;
                    const hasCard = list.querySelector('[data-task-card]');
                    empty.classList.toggle('hidden', !!hasCard);
                });
            };

            const persist = async () => {
                const items = [];
                lists.forEach((list) => {
                    const status = list.getAttribute('data-status');
                    list.querySelectorAll('[data-task-card]').forEach((card, idx) => {
                        items.push({
                            id: Number(card.getAttribute('data-task-id')),
                            status,
                            position: idx,
                        });
                    });
                });

                try {
                    const response = await fetch(reorderUrl, {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({ items }),
                        credentials: 'same-origin',
                    });
                    if (!response.ok) throw new Error(`reorder failed: ${response.status}`);
                } catch (err) {
                    console.error('Kanban reorder failed', err);
                    // Reload to resync state on failure
                    window.location.reload();
                }
            };

            lists.forEach((list) => {
                Sortable.create(list, {
                    group: groupName,
                    animation: 160,
                    handle: '.kanban-handle',
                    draggable: '[data-task-card]',
                    ghostClass: 'kanban-card-ghost',
                    chosenClass: 'kanban-card-chosen',
                    dragClass: 'kanban-card-drag',
                    forceFallback: false,
                    onEnd: () => {
                        updateEmptyState();
                        persist();
                    },
                });
            });

            updateEmptyState();
        }
    }

    // -----------------------------------------------------------------------
    // Admin sidebar (existing)
    // -----------------------------------------------------------------------
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

    // -----------------------------------------------------------------------
    // Marketing page enhancements
    // -----------------------------------------------------------------------
    const marketingPage = document.querySelector('[data-marketing-page]');

    if (!marketingPage) {
        return;
    }

    // Mobile nav toggle -----------------------------------------------------
    const menuToggle = marketingPage.querySelector('[data-menu-toggle]');
    const mobileMenu = marketingPage.querySelector('[data-menu]');

    if (menuToggle && mobileMenu) {
        const setMenuState = (open) => {
            menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (open) {
                mobileMenu.removeAttribute('hidden');
            } else {
                mobileMenu.setAttribute('hidden', '');
            }
        };

        menuToggle.addEventListener('click', () => {
            const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
            setMenuState(!isOpen);
        });

        // Close when a nav link is tapped
        mobileMenu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => setMenuState(false));
        });

        // Reset on resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                setMenuState(false);
            }
        });
    }

    // Scroll-aware header ---------------------------------------------------
    const siteHeader = marketingPage.querySelector('[data-site-header]');

    if (siteHeader) {
        const updateHeader = () => {
            if (window.scrollY > 8) {
                siteHeader.classList.add('is-scrolled');
            } else {
                siteHeader.classList.remove('is-scrolled');
            }
        };

        updateHeader();
        window.addEventListener('scroll', updateHeader, { passive: true });
    }

    // Reveal on scroll ------------------------------------------------------
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
