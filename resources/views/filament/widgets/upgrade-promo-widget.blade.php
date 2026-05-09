<x-filament-widgets::widget>
    <div class="upgrade-promo-widget" style="position: relative; z-index: 99; margin-bottom: 2rem; font-family: sans-serif;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2 style="font-size: 2.25rem; font-weight: 900; color: #1e1b4b; margin-bottom: 0.5rem;">Pilih Paket Keunggulan Anda</h2>
            @php $count = \App\Models\Car::count(); @endphp
            @if($count >= 3)
                <p style="color: #e11d48; font-size: 1.125rem; font-weight: 700;" class="animate-pulse">⚠️ Anda telah mencapai batas {{ $count }}/3 unit untuk Paket Regular.</p>
            @else
                <p style="color: #64748b; font-size: 1.125rem;">Tingkatkan performa penjualan Anda dengan fitur intelligence.</p>
            @endif
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; max-width: 1000px; margin: 0 auto;">
            
            <!-- Paket Regular -->
            <div style="background: white; border: 2px solid #e2e8f0; border-radius: 2rem; padding: 2.5rem; display: flex; flex-direction: column; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
                <div style="margin-bottom: 1.5rem;">
                    <span style="background: #f1f5f9; color: #475569; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">Basic Plan</span>
                    <h3 style="font-size: 2rem; font-weight: 900; color: #1e293b; margin-top: 0.5rem;">REGULAR</h3>
                </div>
                
                <ul style="flex-grow: 1; margin-bottom: 2rem; list-style: none; padding: 0; display: flex; flex-direction: column; gap: 1rem;">
                    <li style="display: flex; align-items: center; gap: 0.75rem; color: #475569;">
                        <span style="color: #10b981;">✔</span> Katalog Produk Premium
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; color: #475569;">
                        <span style="color: #10b981;">✔</span> Booking Test Drive Standar
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; color: #475569;">
                        <span style="color: #10b981;">✔</span> Galeri Handover (Limit)
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; color: #cbd5e1; text-decoration: line-through;">
                        <span>🔒</span> Analytics Lanjutan
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; color: #cbd5e1; text-decoration: line-through;">
                        <span>🔒</span> Identifikasi "Hot Lead"
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; color: #cbd5e1; text-decoration: line-through;">
                        <span>🔒</span> Source Tracking Iklan
                    </li>
                </ul>
                
                <button disabled style="width: 100%; padding: 1rem; border-radius: 1rem; border: 2px solid #e2e8f0; background: #f8fafc; color: #94a3b8; font-weight: 800; cursor: not-allowed;">
                    PAKET AKTIF
                </button>
            </div>

            <!-- Paket PRO -->
            <div style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border-radius: 2rem; padding: 2.5rem; display: flex; flex-direction: column; color: white; transform: scale(1.05); box-shadow: 0 25px 50px -12px rgba(79, 70, 229, 0.4); position: relative; border: 4px solid #facc15;">
                <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: #facc15; color: #1e1b4b; padding: 0.25rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase;">Highly Recommended</div>
                
                <div style="margin-bottom: 1.5rem;">
                    <span style="background: rgba(255,255,255,0.2); color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">Intelligence Plan</span>
                    <h3 style="font-size: 2rem; font-weight: 900; color: white; margin-top: 0.5rem;">PRO MAX</h3>
                </div>
                
                <ul style="flex-grow: 1; margin-bottom: 2rem; list-style: none; padding: 0; display: flex; flex-direction: column; gap: 1rem;">
                    <li style="display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
                        <span style="color: #facc15;">★</span> Katalog Produk UNLIMITED
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
                        <span style="color: #facc15;">★</span> Analytics & Grafik Tren
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
                        <span style="color: #facc15;">★</span> Identifikasi "Hot Lead" Otomatis
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
                        <span style="color: #facc15;">★</span> Source Tracking (FB/IG/Google)
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
                        <span style="color: #facc15;">★</span> Smart Follow-up Dashboard
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
                        <span style="color: #facc15;">★</span> Prioritas Support Developer
                    </li>
                </ul>
                
                <a href="https://wa.me/6281330135013?text=Halo%20Developer%2C%20saya%20tertarik%20upgrade%20ke%20PRO%20MAX%20untuk%20AutoShow%20Pro." 
                   target="_blank"
                   style="width: 100%; text-align: center; padding: 1rem; border-radius: 1rem; background: #facc15; color: #1e1b4b; font-weight: 900; text-decoration: none; transition: transform 0.2s; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);">
                    UPGRADE SEKARANG 🚀
                </a>
            </div>

        </div>
    </div>
</x-filament-widgets::widget>
