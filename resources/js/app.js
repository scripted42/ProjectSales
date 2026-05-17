import './bootstrap';

// Inisialisasi Lenis Smooth Scroll Dinamis untuk Semua Halaman Frontend
if (!window.location.pathname.startsWith('/admin')) {
    document.addEventListener('DOMContentLoaded', () => {
        // Jika Lenis sudah diinisialisasi secara lokal (seperti di welcome.blade.php), hentikan agar tidak double load
        if (window.lenis) return;

        // Cek jika library belum di-load ke window, buat tag script dinamis
        if (!window.Lenis) {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.29/bundled/lenis.min.js';
            script.onload = () => {
                initLenis();
            };
            document.head.appendChild(script);
        } else {
            initLenis();
        }

        function initLenis() {
            const lenis = new Lenis({
                duration: 1.2,
                easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                direction: 'vertical',
                gestureDirection: 'vertical',
                smooth: true,
                mouseMultiplier: 1,
                smoothTouch: false,
                touchMultiplier: 2,
                infinite: false,
            });

            function raf(time) {
                lenis.raf(time);
                requestAnimationFrame(raf);
            }
            requestAnimationFrame(raf);
            
            window.lenis = lenis; // Expose secara global
        }
    });
}
