import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Reveal-on-scroll. Targets elements with [data-motion-reveal].
 * Variants via data-motion-variant: "up" (default), "fade", "scale", "tilt".
 */
export function initReveals(root = document) {
    if (prefersReduced) {
        root.querySelectorAll('[data-motion-reveal]').forEach((el) => {
            gsap.set(el, { opacity: 1, y: 0, scale: 1, rotationX: 0 });
        });
        return;
    }

    const els = root.querySelectorAll('[data-motion-reveal]');
    els.forEach((el) => {
        const variant = el.getAttribute('data-motion-variant') || 'up';
        const delay = parseFloat(el.getAttribute('data-motion-delay') || '0') / 1000;
        const stagger = el.hasAttribute('data-motion-stagger');

        const from = {
            opacity: 0,
            y: variant === 'fade' ? 0 : 32,
            scale: variant === 'scale' ? 0.94 : 1,
            rotationX: variant === 'tilt' ? -14 : 0,
            transformPerspective: 800,
            transformOrigin: 'center top',
        };
        const to = {
            opacity: 1,
            y: 0,
            scale: 1,
            rotationX: 0,
            duration: 0.85,
            ease: 'power3.out',
            delay,
        };

        if (stagger) {
            const children = el.children;
            gsap.set(children, from);
            ScrollTrigger.create({
                trigger: el,
                start: 'top 85%',
                once: true,
                onEnter: () => gsap.to(children, { ...to, stagger: 0.07 }),
            });
        } else {
            gsap.set(el, from);
            ScrollTrigger.create({
                trigger: el,
                start: 'top 88%',
                once: true,
                onEnter: () => gsap.to(el, to),
            });
        }
    });
}

/**
 * 3D tilt on hover. Targets [data-tilt]. Light, restrained — max ~8deg.
 */
export function initTilt(root = document) {
    if (prefersReduced) return;
    const cards = root.querySelectorAll('[data-tilt]');
    cards.forEach((card) => {
        const max = parseFloat(card.getAttribute('data-tilt-max') || '6');
        gsap.set(card, { transformPerspective: 900, transformStyle: 'preserve-3d' });

        let raf = 0;
        const state = { rx: 0, ry: 0, tx: 0, ty: 0 };

        const apply = () => {
            state.rx += (state.tx - state.rx) * 0.15;
            state.ry += (state.ty - state.ry) * 0.15;
            gsap.set(card, { rotationX: state.rx, rotationY: state.ry });
            if (Math.abs(state.tx - state.rx) > 0.01 || Math.abs(state.ty - state.ry) > 0.01) {
                raf = requestAnimationFrame(apply);
            } else {
                raf = 0;
            }
        };

        card.addEventListener('pointermove', (e) => {
            const r = card.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width - 0.5;
            const py = (e.clientY - r.top) / r.height - 0.5;
            state.ty = px * max * 2;
            state.tx = -py * max * 2;
            if (!raf) raf = requestAnimationFrame(apply);
        });
        card.addEventListener('pointerleave', () => {
            state.tx = 0;
            state.ty = 0;
            if (!raf) raf = requestAnimationFrame(apply);
        });
    });
}

/**
 * Magnetic effect on [data-magnetic] buttons — subtle pull toward cursor.
 */
export function initMagnetic(root = document) {
    if (prefersReduced) return;
    root.querySelectorAll('[data-magnetic]').forEach((el) => {
        const strength = parseFloat(el.getAttribute('data-magnetic-strength') || '0.25');
        el.addEventListener('pointermove', (e) => {
            const r = el.getBoundingClientRect();
            const x = (e.clientX - r.left - r.width / 2) * strength;
            const y = (e.clientY - r.top - r.height / 2) * strength;
            gsap.to(el, { x, y, duration: 0.4, ease: 'power2.out' });
        });
        el.addEventListener('pointerleave', () => {
            gsap.to(el, { x: 0, y: 0, duration: 0.5, ease: 'elastic.out(1,0.4)' });
        });
    });
}

/**
 * Number counter for stat cards — [data-count-to="123"].
 */
export function initCounters(root = document) {
    const els = root.querySelectorAll('[data-count-to]');
    els.forEach((el) => {
        const target = parseFloat(el.getAttribute('data-count-to') || '0');
        const prefix = el.getAttribute('data-count-prefix') || '';
        const suffix = el.getAttribute('data-count-suffix') || '';
        const decimals = parseInt(el.getAttribute('data-count-decimals') || '0', 10);

        if (prefersReduced) {
            el.textContent = prefix + target.toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) + suffix;
            return;
        }

        const state = { v: 0 };
        ScrollTrigger.create({
            trigger: el,
            start: 'top 90%',
            once: true,
            onEnter: () => {
                gsap.to(state, {
                    v: target,
                    duration: 1.4,
                    ease: 'power2.out',
                    onUpdate: () => {
                        el.textContent = prefix + state.v.toLocaleString(undefined, {
                            minimumFractionDigits: decimals,
                            maximumFractionDigits: decimals,
                        }) + suffix;
                    },
                });
            },
        });
    });
}

export function initAllMotion(root = document) {
    initReveals(root);
    initTilt(root);
    initMagnetic(root);
    initCounters(root);
}
