<x-filament-widgets::widget>
    <div class="upgrade-promo-container upgrade-promo-widget">
        <style>
            .upgrade-promo-container {
                font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                margin-bottom: 2rem;
                width: 100%;
            }
            .upgrade-promo-header {
                text-align: center;
                margin-bottom: 2.5rem;
                padding: 0 1rem;
            }
            .upgrade-promo-title {
                font-size: 1.75rem;
                font-weight: 900;
                color: #111827;
                margin-bottom: 0.5rem;
                letter-spacing: -0.025em;
                line-height: 1.25;
            }
            .dark .upgrade-promo-title {
                color: #ffffff;
            }
            .upgrade-promo-subtitle {
                color: #6b7280;
                font-size: 0.95rem;
                max-width: 600px;
                margin: 0 auto;
                line-height: 1.5;
            }
            .dark .upgrade-promo-subtitle {
                color: #9ca3af;
            }
            .upgrade-promo-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(310px, 1fr));
                gap: 2rem;
                max-width: 900px;
                margin: 0 auto;
                padding: 0 1rem;
                box-sizing: border-box;
            }
            .upgrade-card {
                background-color: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 1.25rem;
                padding: 2rem;
                display: flex;
                flex-direction: column;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
                position: relative;
                box-sizing: border-box;
                width: 100%;
                transition: all 0.3s ease;
            }
            .dark .upgrade-card {
                background-color: #111827;
                border-color: #1f2937;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            }
            .upgrade-card.pro-card {
                border: 2px solid #f59e0b; /* Amber 500 */
                box-shadow: 0 10px 20px -3px rgba(245, 158, 11, 0.08), 0 4px 6px -2px rgba(245, 158, 11, 0.04);
            }
            .dark .upgrade-card.pro-card {
                border-color: #fbbf24; /* Amber 400 */
                box-shadow: 0 10px 25px -3px rgba(245, 158, 11, 0.15), 0 4px 10px -2px rgba(245, 158, 11, 0.08);
            }
            .upgrade-badge-active {
                display: inline-block;
                background-color: #f3f4f6;
                color: #4b5563;
                font-size: 0.75rem;
                font-weight: 800;
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                text-transform: uppercase;
                width: fit-content;
                letter-spacing: 0.05em;
            }
            .dark .upgrade-badge-active {
                background-color: #1f2937;
                color: #9ca3af;
            }
            .upgrade-badge-recommend {
                position: absolute;
                top: -14px;
                left: 24px;
                background-color: #f59e0b; /* Amber 500 */
                color: #000000;
                font-size: 0.72rem;
                font-weight: 900;
                padding: 0.35rem 0.85rem;
                border-radius: 9999px;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                z-index: 2;
            }
            .upgrade-plan-name {
                font-size: 1.5rem;
                font-weight: 900;
                color: #111827;
                margin-top: 0.75rem;
                margin-bottom: 0.5rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                letter-spacing: -0.02em;
            }
            .dark .upgrade-plan-name {
                color: #ffffff;
            }
            .upgrade-price-box {
                padding-bottom: 1.25rem;
                margin-bottom: 1.25rem;
                border-bottom: 1px solid #f3f4f6;
            }
            .dark .upgrade-price-box {
                border-color: #1f2937;
            }
            .upgrade-price-value {
                font-size: 2rem;
                font-weight: 900;
                color: #111827;
                letter-spacing: -0.03em;
                line-height: 1;
            }
            .dark .upgrade-price-value {
                color: #ffffff;
            }
            .upgrade-card.pro-card .upgrade-price-value {
                color: #d97706; /* Amber 600 */
            }
            .dark .upgrade-card.pro-card .upgrade-price-value {
                color: #fbbf24; /* Amber 400 */
            }
            .upgrade-price-label {
                font-size: 0.75rem;
                font-weight: 700;
                color: #6b7280;
                margin-top: 0.25rem;
            }
            .dark .upgrade-price-label {
                color: #9ca3af;
            }
            .upgrade-price-sub {
                font-size: 0.75rem;
                color: #6b7280;
                margin-top: 0.5rem;
                line-height: 1.5;
            }
            .dark .upgrade-price-sub {
                color: #9ca3af;
            }
            .upgrade-feature-list {
                list-style: none !important;
                padding: 0 !important;
                margin: 0 0 1.5rem 0 !important;
                flex-grow: 1;
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }
            .upgrade-feature-item {
                display: flex !important;
                align-items: flex-start !important;
                gap: 0.65rem !important;
                font-size: 0.85rem !important;
                color: #4b5563 !important;
                line-height: 1.4 !important;
                text-decoration: none !important;
            }
            .dark .upgrade-feature-item {
                color: #d1d5db !important;
            }
            .upgrade-feature-item.pro-only-feature {
                font-weight: 700 !important;
            }
            .upgrade-feature-item.locked-feature {
                color: #9ca3af !important;
                text-decoration: line-through !important;
            }
            .dark .upgrade-feature-item.locked-feature {
                color: #4b5563 !important;
            }
            .upgrade-feature-icon {
                width: 16px !important;
                height: 16px !important;
                min-width: 16px !important;
                min-height: 16px !important;
                max-width: 16px !important;
                max-height: 16px !important;
                margin-top: 2px !important;
                flex-shrink: 0 !important;
                display: inline-block !important;
            }
            .upgrade-feature-icon.icon-check {
                color: #10b981 !important; /* Emerald 500 */
            }
            .upgrade-feature-icon.icon-star {
                color: #f59e0b !important; /* Amber 500 */
            }
            .upgrade-feature-icon.icon-lock {
                color: #d1d5db !important; /* Gray 300 */
            }
            .dark .upgrade-feature-icon.icon-lock {
                color: #4b5563 !important; /* Gray 700 */
            }
            .upgrade-button {
                display: block;
                width: 100%;
                padding: 0.85rem;
                border-radius: 0.75rem;
                font-size: 0.85rem;
                font-weight: 900;
                text-align: center;
                text-decoration: none !important;
                box-sizing: border-box;
                margin-top: auto;
                transition: all 0.2s ease;
                border: none;
                cursor: pointer;
            }
            .upgrade-button-active {
                background-color: #f9fafb;
                color: #9ca3af;
                border: 1px solid #e5e7eb;
                cursor: not-allowed;
            }
            .dark .upgrade-button-active {
                background-color: #1f2937;
                color: #4b5563;
                border-color: #374151;
            }
            .upgrade-button-pro {
                background-color: #f59e0b; /* Amber 500 */
                color: #000000 !important;
                box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2);
            }
            .upgrade-button-pro:hover {
                background-color: #d97706; /* Amber 600 */
                transform: translateY(-1px);
                box-shadow: 0 6px 14px rgba(245, 158, 11, 0.3);
            }
            .upgrade-button-pro:active {
                transform: translateY(0);
            }
            .upgrade-footnote {
                text-align: center;
                font-size: 0.7rem;
                color: #9ca3af;
                margin-top: 0.65rem;
                font-weight: 500;
                line-height: 1.3;
            }
            .dark .upgrade-footnote {
                color: #4b5563;
            }
        </style>

        <!-- Header -->
        <div class="upgrade-promo-header">
            <h2 class="upgrade-promo-title">Pilih Paket Keunggulan Website Anda</h2>
            <p class="upgrade-promo-subtitle">Tingkatkan kinerja jualan dan akses fitur pintar untuk mengoptimalkan konversi pelanggan Anda.</p>
        </div>

        <!-- Grid -->
        <div class="upgrade-promo-grid">
            
            <!-- Card REGULER (Active) -->
            <div class="upgrade-card">
                <div>
                    <span class="upgrade-badge-active">Active Plan</span>
                    <h3 class="upgrade-plan-name">REGULER</h3>
                </div>

                <div class="upgrade-price-box">
                    <div class="upgrade-price-value">Rp 1.799.000</div>
                    <div class="upgrade-price-label">Investasi Awal (Tahun ke-1)</div>
                    <div class="upgrade-price-sub">
                        Perpanjangan: Rp 1.000.000 / tahun<br>
                        <strong>(Termasuk perpanjangan Hosting & Domain)</strong>
                    </div>
                </div>

                <ul class="upgrade-feature-list">
                    <li class="upgrade-feature-item">
                        <svg class="upgrade-feature-icon icon-check" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>Domain (.com/.id) & Keamanan SSL</span>
                    </li>
                    <li class="upgrade-feature-item">
                        <svg class="upgrade-feature-icon icon-check" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>Kapasitas Penyimpanan: <strong>2 GB SSD</strong></span>
                    </li>
                    <li class="upgrade-feature-item">
                        <svg class="upgrade-feature-icon icon-check" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>Kecepatan Website: <strong>Standard Speed</strong></span>
                    </li>
                    <li class="upgrade-feature-item">
                        <svg class="upgrade-feature-icon icon-check" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>Pencadangan Data: <strong>Bulanan</strong></span>
                    </li>
                    <li class="upgrade-feature-item">
                        <svg class="upgrade-feature-icon icon-check" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>Katalog Mobil & Tipe Varian</span>
                    </li>
                    <li class="upgrade-feature-item">
                        <svg class="upgrade-feature-icon icon-check" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>Form Booking Test Drive & Chat WA</span>
                    </li>
                    <li class="upgrade-feature-item">
                        <svg class="upgrade-feature-icon icon-check" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>Pengelolaan Admin, Nama & Logo</span>
                    </li>
                    
                    <!-- Locked Features -->
                    <li class="upgrade-feature-item locked-feature">
                        <svg class="upgrade-feature-icon icon-lock" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <span>Asisten Pintar AI Insights</span>
                    </li>
                    <li class="upgrade-feature-item locked-feature">
                        <svg class="upgrade-feature-icon icon-lock" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <span>Grafik Pengunjung Interaktif</span>
                    </li>
                    <li class="upgrade-feature-item locked-feature">
                        <svg class="upgrade-feature-icon icon-lock" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <span>Promosi Sosial Media Instan</span>
                    </li>
                    <li class="upgrade-feature-item locked-feature">
                        <svg class="upgrade-feature-icon icon-lock" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <span>Akun AI & API Key Mandiri</span>
                    </li>
                    <li class="upgrade-feature-item locked-feature">
                        <svg class="upgrade-feature-icon icon-lock" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <span>Update Fitur Terbaru</span>
                    </li>
                </ul>

                <button class="upgrade-button upgrade-button-active" disabled>
                    PAKET ANDA SAAT INI
                </button>
            </div>

            <!-- Card PRO (Upgrade) -->
            <div class="upgrade-card pro-card">
                <span class="upgrade-badge-recommend">Rekomendasi Upgrade</span>

                <div>
                    <h3 class="upgrade-plan-name">PRO 👑</h3>
                </div>

                <div class="upgrade-price-box">
                    <div class="upgrade-price-value">Rp 2.499.000</div>
                    <div class="upgrade-price-label">Investasi Awal (Tahun ke-1)</div>
                    <div class="upgrade-price-sub">
                        Perpanjangan: Rp 1.000.000 / tahun<br>
                        <strong style="color: #f59e0b;">(Termasuk perpanjangan Hosting & Domain)</strong>
                    </div>
                </div>

                <ul class="upgrade-feature-list">
                    <li class="upgrade-feature-item">
                        <svg class="upgrade-feature-icon icon-check" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>Domain (.com/.id) & Keamanan SSL</span>
                    </li>
                    <li class="upgrade-feature-item pro-only-feature">
                        <svg class="upgrade-feature-icon icon-star" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                        <span>Penyimpanan: <strong style="color: #f59e0b;">8 - 16 GB SSD</strong></span>
                    </li>
                    <li class="upgrade-feature-item pro-only-feature">
                        <svg class="upgrade-feature-icon icon-star" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                        <span>Kecepatan Website: <strong style="color: #f59e0b;">High Speed (Turbo)</strong></span>
                    </li>
                    <li class="upgrade-feature-item pro-only-feature">
                        <svg class="upgrade-feature-icon icon-star" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                        <span>Pencadangan Data: <strong style="color: #f59e0b;">Mingguan (Lebih Aman)</strong></span>
                    </li>
                    <li class="upgrade-feature-item">
                        <svg class="upgrade-feature-icon icon-check" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>Katalog Mobil & Tipe Varian</span>
                    </li>
                    <li class="upgrade-feature-item">
                        <svg class="upgrade-feature-icon icon-check" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>Form Booking Test Drive & Chat WA</span>
                    </li>
                    <li class="upgrade-feature-item">
                        <svg class="upgrade-feature-icon icon-check" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>Pengelolaan Admin, Nama & Logo</span>
                    </li>
                    
                    <!-- Pro Exclusive Features -->
                    <li class="upgrade-feature-item pro-only-feature">
                        <svg class="upgrade-feature-icon icon-star" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                        <span>Asisten Pintar AI Insights</span>
                    </li>
                    <li class="upgrade-feature-item pro-only-feature">
                        <svg class="upgrade-feature-icon icon-star" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                        <span>Grafik Pengunjung Interaktif</span>
                    </li>
                    <li class="upgrade-feature-item pro-only-feature">
                        <svg class="upgrade-feature-icon icon-star" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                        <span>Promosi Sosial Media Instan</span>
                    </li>
                    <li class="upgrade-feature-item pro-only-feature">
                        <svg class="upgrade-feature-icon icon-star" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                        <span>Akun AI & API Key Mandiri</span>
                    </li>
                    <li class="upgrade-feature-item pro-only-feature">
                        <svg class="upgrade-feature-icon icon-star" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                        <span style="color: #f59e0b;">Gratis Update Fitur Terbaru</span>
                    </li>
                </ul>

                <a href="https://wa.me/6281330135013?text=Halo%20Developer%2C%20saya%20tertarik%20upgrade%20ke%20paket%20PRO%20untuk%20aplikasi%20AutoShow%20Pro." 
                   target="_blank"
                   class="upgrade-button upgrade-button-pro">
                    UPGRADE SEKARANG 🚀
                </a>
                <div class="upgrade-footnote">
                    *Proses upgrade cepat & aman tanpa menghapus data katalog yang ada.
                </div>
            </div>

        </div>
    </div>
</x-filament-widgets::widget>
