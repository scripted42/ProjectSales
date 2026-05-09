@if(!\App\Models\Setting::isLicenseActive())
<div style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(10px); z-index: 9999; display: flex; align-items: center; justify-content: center; color: white; font-family: sans-serif; padding: 20px;">
    <div style="max-width: 500px; text-align: center; background: #1e293b; padding: 3rem; border-radius: 2rem; border: 1px solid #334155; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
        <div style="background: #ef4444; width: 80px; h-eight: 80px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; box-shadow: 0 0 20px rgba(239, 68, 68, 0.4);">
            <svg style="width: 40px; height: 40px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <h1 style="font-size: 2rem; font-weight: 900; margin-bottom: 1rem;">Layanan Ditangguhkan</h1>
        <p style="color: #94a3b8; font-size: 1.125rem; margin-bottom: 2rem; line-height: 1.6;">Masa aktif lisensi aplikasi AutoShow Pro Anda telah berakhir. Silakan hubungi pengembang untuk melakukan perpanjangan layanan.</p>
        
        <div style="background: #0f172a; padding: 1.5rem; border-radius: 1rem; margin-bottom: 2rem; text-align: left;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: #64748b; font-size: 0.875rem;">Domain:</span>
                <span style="font-family: monospace; color: #cbd5e1;">127.0.0.1:8000</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #64748b; font-size: 0.875rem;">Status:</span>
                <span style="color: #ef4444; font-weight: 700; font-size: 0.875rem;">EXPIRED</span>
            </div>
        </div>

        <a href="https://wa.me/6281330135013?text=Halo%20Developer%2C%20lisensi%20AutoShow%20Pro%20saya%20sudah%20expired.%20Mohon%20bantuannya." 
           style="display: block; width: 100%; background: #4f46e5; color: white; padding: 1rem; border-radius: 1rem; font-weight: 800; text-decoration: none; transition: 0.2s;"
           onmouseover="this.style.background='#4338ca'"
           onmouseout="this.style.background='#4f46e5'">
            Hubungi Developer (WhatsApp)
        </a>
    </div>
</div>
@endif
