import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

/** True saat halaman pertama kali dibuka (splash screen sebelum app siap). */
export const isBooting = ref(true);

/** True saat ada navigasi penuh antar halaman/menu. */
export const isNavigating = ref(false);

/** Gabungan state: splash awal + navigasi. */
export const isGlobalLoading = computed(
    () => isBooting.value || isNavigating.value,
);

/** Durasi minimum overlay tampil (ms) agar tidak berkedip pada respons cepat. */
const MIN_VISIBLE_MS = 350;

let initialized = false;
let navToken = 0;
let navStartedAt = 0;

/**
 * Abaikan visit yang bukan "perpindahan menu" penuh:
 * - prefetch (pemanasan halaman)
 * - partial reload (router.reload dengan `only`)
 * - GET + preserveState (search/filter yang tetap di halaman yang sama)
 */
function isFullPageVisit(visit: {
    method: string;
    only: string[];
    preserveState: unknown;
    prefetch: boolean;
}): boolean {
    if (visit.prefetch || visit.only.length > 0) {
        return false;
    }

    return !(visit.method === 'get' && Boolean(visit.preserveState));
}

export function initGlobalLoading(): void {
    if (initialized) {
        return;
    }
    initialized = true;

    router.on('start', (event) => {
        if (!isFullPageVisit(event.detail.visit)) {
            return;
        }

        navToken += 1;
        navStartedAt = performance.now();
        isNavigating.value = true;
    });

    router.on('finish', (event) => {
        if (!isFullPageVisit(event.detail.visit)) {
            return;
        }

        const token = navToken;
        const elapsed = performance.now() - navStartedAt;
        const remaining = Math.max(0, MIN_VISIBLE_MS - elapsed);

        window.setTimeout(() => {
            if (navToken === token) {
                isNavigating.value = false;
            }
        }, remaining);
    });

    const hideBoot = () => {
        isBooting.value = false;
    };

    // Tunggu halaman pertama benar-benar siap sebelum menyembunyikan splash.
    if (document.readyState === 'complete') {
        requestAnimationFrame(() => requestAnimationFrame(hideBoot));
    } else {
        window.addEventListener(
            'load',
            () => requestAnimationFrame(() => requestAnimationFrame(hideBoot)),
            { once: true },
        );
    }

    // Fallback keamanan agar splash tidak pernah menggantung.
    window.setTimeout(hideBoot, 5000);
}
