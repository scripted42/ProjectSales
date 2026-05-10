@if(!\App\Models\Setting::isPro())
<style>
    /* Memberikan efek blur pada semua widget kecuali kartu upgrade */
    .fi-wi-widget {
        transition: all 0.5s ease;
    }
    
    .fi-wi-widget:not(:has(.upgrade-promo-widget)):not(:has(.license-status-card)) {
        filter: blur(8px);
        pointer-events: none;
        user-select: none;
        opacity: 0.6;
    }

    /* Memastikan widget upgrade tetap tajam */
    .fi-wi-widget:has(.upgrade-promo-widget) {
        filter: none !important;
        opacity: 1 !important;
        pointer-events: auto !important;
        z-index: 10;
        position: relative;
    }
    
    /* Efek transisi saat nanti berubah jadi PRO */
    .fi-wi-widget {
        transition: filter 0.8s ease, opacity 0.8s ease;
    }
</style>
@endif
