import type { Directive } from 'vue';

const observers = new WeakMap<Element, IntersectionObserver>();

/**
 * v-reveal — animasi keluar/masuk komponen saat di-scroll.
 *
 * - Masuk viewport: elemen fade + slide ke atas.
 * - Keluar viewport: elemen fade out kembali.
 *
 * Pakai: <div v-reveal>...</div>
 * Matikan: v-reveal="false"
 */
export const vReveal: Directive<HTMLElement, boolean | undefined> = {
    beforeMount(el, binding) {
        if (binding.value === false || prefersReducedMotion()) {
            return;
        }
        el.classList.add('reveal');
    },

    mounted(el, binding) {
        if (binding.value === false || prefersReducedMotion()) {
            return;
        }

        const observer = new IntersectionObserver(
            (entries) => {
                for (const entry of entries) {
                    el.classList.toggle('is-visible', entry.isIntersecting);
                }
            },
            {
                // Root viewport dikecilkan: atas -12%, bawah -10%
                // → animasi "keluar" terlihat saat elemen mendekati tepi atas layar,
                //   animasi "masuk" mulai saat elemen sedikit masuk dari bawah.
                rootMargin: '-12% 0px -10% 0px',
                threshold: 0,
            },
        );

        observers.set(el, observer);
        observer.observe(el);
    },

    unmounted(el) {
        observers.get(el)?.disconnect();
        observers.delete(el);
    },
};

function prefersReducedMotion(): boolean {
    return (
        typeof window !== 'undefined' &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches
    );
}
