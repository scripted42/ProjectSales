<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Favicon -->
    @if($logo = \App\Models\Setting::get('site_logo'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $logo) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('assets/images/hyundai_logo.png') }}">
    @endif

    <title>Daftar Harga OTR Hyundai Surabaya Terbaru - {{ date('F Y') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Outfit', sans-serif;
        }

        /* Dotted Leader Line Spacing */
        .leader-line {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .leader-text-left {
            background-color: white;
            padding-right: 8px;
            z-index: 10;
            position: relative;
        }

        .leader-text-right {
            background-color: white;
            padding-left: 8px;
            z-index: 10;
            position: relative;
        }

        .leader-dots {
            flex-grow: 1;
            border-bottom: 2px dotted #e5e7eb;
            height: 1px;
            margin: 0 4px;
            transform: translateY(4px);
        }

        /* Default: Hide Print Header on Screen view */
        .print-header-block {
            display: none;
        }

        /* High-Definition Print Stylesheet overrides */
        @media print {
            @page {
                size: A4 portrait;
                margin: 0 !important; /* Automatically suppresses browser's default print headers & footers */
            }

            body {
                background-color: white !important;
                color: black !important;
                padding: 12mm 15mm 15mm 15mm !important; /* Pristine content spacing inside the page */
                font-size: 11px !important;
            }

            /* Hide all interactive screen elements */
            nav, 
            footer, 
            .no-print,
            .floating-cta,
            .consultant-cta-card {
                display: none !important;
            }

            /* Unhide and style the PDF Header */
            .print-header-block {
                display: block !important;
                border-bottom: 2px solid #002c5f !important;
                padding-bottom: 12px !important;
                margin-bottom: 20px !important;
            }

            .print-grid {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 12px !important;
            }

            /* Default Layout Toggles */
            .only-print {
                display: none !important;
            }

            @media print {
                .only-print {
                    display: flex !important;
                }

                /* Compact footnote row spacing and horizontal layout */
                .print-card-avoid .mt-6 {
                    margin-top: 10px !important;
                    padding-top: 8px !important;
                    display: flex !important;
                    flex-direction: row !important;
                    justify-content: space-between !important;
                    width: 100% !important;
                }

                .print-card-avoid .mt-6 > div {
                    max-width: none !important;
                    width: auto !important;
                    white-space: nowrap !important;
                }
            }

            /* Compact and elegant card styles for print layout */
            .print-card-avoid {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
                box-shadow: none !important;
                border: none !important;
                background-color: white !important;
                border-radius: 1rem !important;
                padding: 16px !important;
            }

            /* Force side-by-side layout: car image on the left, variant text on the right */
            .print-card-avoid-grid {
                display: grid !important;
                grid-template-columns: 3.8fr 8.2fr !important;
                gap: 12px !important;
                align-items: center !important;
            }

            .print-card-avoid-grid > div {
                grid-column: auto !important;
            }

            /* Hide absolute glows */
            .print-card-avoid .absolute {
                display: none !important;
            }

            /* Make car image compact and crisp in print */
            .print-card-avoid img {
                max-height: 105px !important;
                width: auto !important;
                margin: 0 auto !important;
            }

            /* Make car titles compact */
            .print-card-avoid h3 {
                font-size: 15px !important;
                margin-top: 4px !important;
                margin-bottom: 8px !important;
                line-height: 1.2 !important;
            }

            /* Make variant items tighter */
            .print-card-avoid .space-y-3 {
                margin-bottom: 0 !important;
            }
            .print-card-avoid .space-y-3 > * + * {
                margin-top: 4px !important;
            }
            .print-card-avoid .leader-text-left {
                font-size: 8.5px !important;
                white-space: nowrap !important;
                background-color: white !important;
            }
            .print-card-avoid .leader-text-right {
                font-size: 8.5px !important;
                white-space: nowrap !important;
                background-color: white !important;
            }

            /* Compact footnote row */
            .print-card-avoid .mt-6 {
                margin-top: 10px !important;
                padding-top: 8px !important;
            }

            .leader-text-left, 
            .leader-text-right {
                background-color: white !important;
            }
        }
    </style>
    @if($googleAdsId = env('GOOGLE_ADS_ID'))
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAdsId }}"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', '{{ $googleAdsId }}');
        </script>
    @endif
</head>

<body class="bg-white text-gray-900 antialiased overflow-x-hidden pt-24">

    <!-- Sticky Navbar component -->
    <x-navbar />

    <!-- Print Only Header (Visible only when PDF export / Print preview triggered) -->
    <div class="print-header-block max-w-7xl mx-auto px-6 text-center">
        <h1 class="text-2xl font-black text-[#002c5f] tracking-widest uppercase">HYUNDAI PRICELIST</h1>
        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-1">Harga OTR Surabaya & Wilayah Jawa Timur - Periode {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('F Y') }}</p>
    </div>

    <!-- Elegant, Integrated White Header (Simple, Clean & Elegant) -->
    <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6 no-print">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-gray-100 pb-8">
            <div class="max-w-2xl">
                <span class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] block mb-2">Official Price Sheet</span>
                <h1 class="text-3xl md:text-5xl font-black text-[#002c5f] tracking-tight uppercase leading-tight">
                    Daftar Harga OTR
                </h1>
                <p class="text-gray-500 text-xs md:text-sm font-semibold mt-3 leading-relaxed">
                    Daftar harga resmi kendaraan Hyundai terlengkap untuk wilayah Surabaya, Sidoarjo, Gresik, dan seluruh Provinsi Jawa Timur. Berlaku per <span class="text-[#002c5f] font-bold">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('F Y') }}</span>.
                </p>
            </div>

            <!-- Sleek Minimalist Action triggers -->
            <div class="flex flex-row gap-3">
                <button onclick="window.print()" class="bg-[#002c5f] text-white hover:bg-blue-900 px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-sm flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v5"/><rect width="12" height="8" x="6" y="14" rx="1" ry="1"/></svg>
                    Cetak / PDF
                </button>
                @if($consultant)
                    <a href="{{ route('track.wa', ['text' => 'Halo sales Hyundai, saya ingin menanyakan simulasi kredit/harga terbaik pricelist terupdate']) }}" class="border border-green-500 text-green-500 hover:bg-green-50/50 px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                        Konsultasi WA
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main dynamic Grid content list -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 print-grid">
            @foreach($cars as $car)
                <div class="print-card-avoid bg-white rounded-[2.5rem] p-8 md:p-10 border border-gray-100/80 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_50px_rgba(0,44,95,0.06)] hover:border-blue-100/50 transition-all duration-500 flex flex-col justify-between group">
                    
                    <!-- Title block placed at the top-left, above both photo and variant list -->
                    <div class="mb-6">
                        <span class="text-[9px] font-black text-blue-600 bg-blue-50/60 px-2.5 py-1 rounded-md uppercase tracking-wider">
                            {{ $car->category }}
                        </span>
                        <h3 class="text-2xl md:text-3xl font-black text-[#002c5f] uppercase tracking-tight mt-2.5">
                            {{ $car->name }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center print-card-avoid-grid">
                        
                        <!-- Left Column: Large, floating transparent car profile image -->
                        <div class="md:col-span-5 flex justify-center items-center relative">
                            <!-- Subtle backdrop radiant glow -->
                            <div class="absolute w-40 h-40 bg-blue-500/5 rounded-full blur-2xl transition-transform duration-750 group-hover:scale-150"></div>
                            
                            @if($car->image)
                                <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->name }}" class="relative z-10 w-full h-auto max-h-[140px] md:max-h-[170px] object-contain transform hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="relative z-10 text-gray-300 py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>
                                </div>
                            @endif
                        </div>

                        <!-- Right Column: Variant lists -->
                        <div class="md:col-span-7 flex flex-col justify-between h-full">
                            <!-- Variant Price List with Dotted Leaders -->
                            <div class="space-y-3">
                                @if($car->variants && count($car->variants) > 0)
                                    @foreach($car->variants as $variant)
                                        <div class="leader-line">
                                            <span class="leader-text-left text-xs md:text-sm font-bold text-gray-800 uppercase bg-white">{{ $variant['name'] }}</span>
                                            <div class="leader-dots"></div>
                                            <span class="leader-text-right text-xs md:text-sm font-black text-[#002c5f] whitespace-nowrap bg-white">Rp {{ number_format($variant['price'], 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="leader-line">
                                        <span class="leader-text-left text-xs md:text-sm font-bold text-gray-800 uppercase bg-white">Standard</span>
                                        <div class="leader-dots"></div>
                                        <span class="leader-text-right text-xs md:text-sm font-black text-[#002c5f] whitespace-nowrap bg-white">Rp {{ number_format($car->price, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- Bottom Row: Surcharges & Spek Link -->
                    <div class="mt-6 pt-6 border-t border-dashed border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="text-[9px] text-gray-400 font-bold uppercase tracking-wider leading-relaxed max-w-xl">
                            @if($car->category === 'MPV' || $car->category === 'Luxury MPV')
                                <span>• Captain Seat: +Rp 1.000.000 &nbsp;|&nbsp; • Two-Tone: +Rp 1.500.000</span>
                            @elseif($car->category === 'EV' || $car->category === 'Electric Vehicle')
                                <span>• Matte Paint Color: +Rp 3.500.000</span>
                            @else
                                <span>• Pilihan warna khusus & BBN tidak mengikat</span>
                            @endif
                        </div>
                        
                        <a href="{{ route('car.show', $car->slug) }}" class="no-print border border-[#002c5f]/15 hover:border-[#002c5f] text-[#002c5f] hover:bg-[#002c5f] hover:text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2 group w-full sm:w-auto">
                            Lihat Spek
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="transition-transform group-hover:translate-x-1"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Dynamic Sales Lead CTA Banner Card -->
        @if($consultant)
            <div class="consultant-cta-card bg-gradient-to-tr from-[#002c5f]/5 to-indigo-500/5 border border-blue-100 rounded-[2.5rem] p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8 mt-16 no-print">
                <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                    <div class="relative w-20 h-20 rounded-full overflow-hidden border-2 border-[#002c5f] flex-shrink-0 shadow-lg">
                        @if($consultant->photo)
                            <img src="{{ asset('storage/' . $consultant->photo) }}" alt="{{ $consultant->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-[#002c5f] flex items-center justify-center text-white font-black text-xl">
                                {{ substr($consultant->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-blue-600 uppercase tracking-widest block mb-1">Sales Advisor</span>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">{{ $consultant->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1 font-semibold">{{ $consultant->office_address }}</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto text-center">
                    <a href="{{ route('track.wa', ['text' => 'Halo sales Hyundai ' . $consultant->name . ', saya ingin bertanya simulasi kredit/promo unit terupdate dari pricelist']) }}" class="bg-[#002c5f] text-white px-8 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-800 transition-all shadow-xl flex items-center justify-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                        Hubungi {{ $consultant->name }}
                    </a>
                </div>
            </div>
        @endif

        <!-- Print-Only Catalog Footer (visible only when printing) -->
        @if($consultant)
            <div class="only-print mt-12 border-t border-gray-200 pt-4 flex flex-row items-center justify-between w-full">
                <!-- Left: Dealer Tagline -->
                <div class="text-left">
                    <span class="text-[9px] font-black text-[#002c5f] uppercase tracking-wider block">HYUNDAI SURABAYA</span>
                    <span class="text-[7.5px] text-gray-400 font-bold uppercase tracking-widest leading-none mt-1.5 block">Printed on: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>

                <!-- Center: physical address -->
                <div class="text-center">
                    <p class="text-[9px] font-black text-gray-900 leading-tight">Hyundai HR Muhammad</p>
                    <p class="text-[8px] text-gray-500 font-semibold leading-tight mt-1">{{ $consultant->office_address }}</p>
                </div>

                <!-- Right: Advisor Contact -->
                <div class="text-right">
                    <span class="text-[9px] font-black text-[#002c5f] uppercase block">{{ $consultant->name }}</span>
                    <span class="text-[8.5px] text-blue-600 font-extrabold tracking-wider leading-none mt-1.5 block">{{ $consultant->formatted_phone }}</span>
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-[#002c5f] text-white py-16 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex justify-center items-center mb-8">
                @if($logo = \App\Models\Setting::get('site_logo'))
                    <img src="{{ asset('storage/' . $logo) }}" alt="Hyundai" class="h-6 w-auto brightness-0 invert">
                @else
                    <img src="{{ asset('assets/images/hyundai_logo.png') }}" alt="Hyundai" class="h-6 w-auto brightness-0 invert">
                @endif
                <span class="ml-3 font-black text-lg tracking-[0.2em] uppercase">{{ \App\Models\Setting::get('site_name', 'Hyundai') }}</span>
            </div>
            <p class="text-blue-200 text-[10px] mb-8 tracking-[0.2em] uppercase font-bold">&copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Hyundai Showroom') }}. Semua Hak Dilindungi.</p>
            <div class="flex justify-center gap-8">
                <a href="/" class="text-xs font-bold text-blue-100 hover:text-white transition-colors">Home</a>
                <a href="/#models" class="text-xs font-bold text-blue-100 hover:text-white transition-colors">Models</a>
                <a href="{{ route('pricelist') }}" class="text-xs font-bold text-white transition-colors underline underline-offset-8 decoration-2">Pricelist</a>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
