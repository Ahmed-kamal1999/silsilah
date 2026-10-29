// Livewire 3 bundles Alpine, so only register components here.
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function observeReveal(root = document) {
    const els = root.querySelectorAll('[data-reveal]:not(.is-visible)');
    if (reduceMotion || !('IntersectionObserver' in window)) {
        els.forEach((el) => el.classList.add('is-visible'));
        return;
    }
    const io = new IntersectionObserver((entries) => {
        entries.forEach((e) => {
            if (e.isIntersecting) {
                e.target.classList.add('is-visible');
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    els.forEach((el) => io.observe(el));
}

document.addEventListener('DOMContentLoaded', () => observeReveal());
document.addEventListener('livewire:navigated', () => observeReveal());

document.addEventListener('alpine:init', () => {
    Alpine.data('header', () => ({
        solid: false,
        open: false,
        init() {
            const onScroll = () => { this.solid = window.scrollY > 24; };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        },
    }));

    Alpine.data('heroSlider', (count) => ({
        i: 0,
        paused: false,
        init() {
            if (reduceMotion || count < 2) return;
            setInterval(() => { if (!this.paused && !document.hidden) this.i = (this.i + 1) % count; }, 7000);
        },
        go(n) { this.i = n; },
    }));

    Alpine.data('themeToggle', () => ({
        dark: document.documentElement.dataset.theme !== 'light',
        toggle() {
            this.dark = !this.dark;
            const t = this.dark ? 'dark' : 'light';
            document.documentElement.dataset.theme = t;
            try { localStorage.setItem('theme', t); } catch (e) {}
        },
    }));

    Alpine.data('counter', (target) => ({
        shown: 0,
        init() {
            if (reduceMotion) { this.shown = target; return; }
            const io = new IntersectionObserver(([entry]) => {
                if (!entry.isIntersecting) return;
                io.disconnect();
                const start = performance.now();
                const dur = 1800;
                const tick = (now) => {
                    const t = Math.min(1, (now - start) / dur);
                    this.shown = Math.round(target * (1 - Math.pow(1 - t, 3)));
                    if (t < 1) requestAnimationFrame(tick);
                };
                requestAnimationFrame(tick);
            }, { threshold: 0.4 });
            io.observe(this.$el);
        },
    }));

    Alpine.data('accordion', () => ({
        active: 0,
        toggle(i) { this.active = this.active === i ? null : i; },
    }));
});
