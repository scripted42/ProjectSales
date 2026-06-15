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

    <title>{{ $car->name }} - Hyundai Showroom</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @livewireStyles
    @if($googleAdsId = config('services.google.ads_id'))
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
<body class="bg-white text-gray-900 antialiased">
    
    <x-navbar />

    <!-- Hero Section for Product -->
    <section class="relative h-[60vh] md:h-[70vh] flex items-end overflow-hidden mt-[56px]">
        @if($car->hero_image)
            <img src="{{ asset('storage/' . $car->hero_image) }}" alt="{{ $car->name }}" class="absolute inset-0 w-full h-full object-cover">
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-[#002c5f] via-[#003d7a] to-[#001a3a]"></div>
            @if($car->image)
                <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->name }}" class="absolute right-0 bottom-0 h-[85%] w-auto object-contain opacity-30">
            @endif
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pb-16">
            <div class="inline-block bg-white/10 backdrop-blur-md border border-white/20 px-4 py-1.5 rounded-full mb-4">
                <span class="text-white text-[11px] font-black uppercase tracking-[0.2em]">{{ $car->category }}</span>
            </div>
            <h1 class="text-5xl md:text-7xl font-black text-white leading-[1.05] mb-4">{{ $car->name }}</h1>
            <p class="text-2xl md:text-3xl font-black text-blue-300">Rp {{ number_format($car->price, 0, ',', '.') }}</p>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            
            <!-- Gallery Section (Only gallery photos, NOT thumbnail) -->
            @if($car->images && count($car->images) > 0)
            <div x-data="{ 
                activeIndex: 0,
                images: [
                    @foreach($car->images as $img)
                        '{{ asset('storage/' . $img) }}',
                    @endforeach
                ],
                get activeImage() { return this.images[this.activeIndex]; },
                init() {
                    if (this.images.length > 1) {
                        setInterval(() => {
                            this.activeIndex = (this.activeIndex + 1) % this.images.length;
                        }, 4000);
                    }
                }
            }">
                <div class="aspect-[4/3] rounded-3xl overflow-hidden bg-gray-50 mb-6 shadow-lg relative group">
                    <img :src="activeImage" alt="Car Gallery" class="w-full h-full object-cover transition-all duration-700">
                    
                    <!-- Slide Counter -->
                    <div class="absolute top-6 right-6 bg-black/50 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-full border border-white/10 tracking-widest uppercase">
                         <span x-text="activeIndex + 1"></span> / <span x-text="images.length"></span>
                    </div>

                    <!-- Dot Indicators -->
                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 px-3 py-2 bg-black/20 backdrop-blur-sm rounded-full">
                        <template x-for="(img, index) in images" :key="index">
                            <button @click="activeIndex = index" 
                                    class="h-1.5 rounded-full transition-all duration-500"
                                    :class="activeIndex === index ? 'w-6 bg-blue-500' : 'w-1.5 bg-white/50 hover:bg-white'"></button>
                        </template>
                    </div>
                </div>
                
                <!-- Thumbnails -->
                <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                    <template x-for="(img, index) in images" :key="index">
                        <button @click="activeIndex = index" 
                                class="aspect-square rounded-xl overflow-hidden border-2 transition-all"
                                :class="activeIndex === index ? 'border-blue-600 scale-95 shadow-inner' : 'border-transparent opacity-60 hover:opacity-100'">
                            <img :src="img" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>
            </div>
            @else
            <div class="flex items-center justify-center bg-gray-50 rounded-3xl aspect-[4/3]">
                <p class="text-gray-300 text-lg">Belum ada foto galeri</p>
            </div>
            @endif

            <!-- Content Section -->
            <div x-data="{ 
                hasVariants: {{ ($car->variants && count($car->variants) > 0) ? 'true' : 'false' }},
                selectedVariant: 0,
                variants: [
                    @if($car->variants && count($car->variants) > 0)
                        @foreach($car->variants as $variant)
                            {
                                name: '{{ $variant['name'] }}',
                                price: 'Rp {{ number_format($variant['price'], 0, ',', '.') }}',
                                transmission: '{{ $variant['transmission'] ?? '-' }}',
                                engine: '{{ $variant['engine'] ?? '-' }}',
                                features: [
                                    @if(isset($variant['key_features']) && is_array($variant['key_features']))
                                        @foreach($variant['key_features'] as $f)
                                            '{{ $f }}',
                                        @endforeach
                                    @endif
                                ]
                            }{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    @endif
                ]
            }">
                <h2 class="text-3xl md:text-4xl font-black text-[#002c5f] mb-4">{{ $car->name }}</h2>
                <div class="flex items-center gap-4 mb-8">
                    <span class="bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest">
                        {{ $car->category }}
                    </span>
                    <span class="text-2xl font-extrabold text-[#002c5f]">
                        <template x-if="hasVariants">
                            <span x-text="variants[selectedVariant].price"></span>
                        </template>
                        <template x-if="!hasVariants">
                            <span>Rp {{ number_format($car->price, 0, ',', '.') }}</span>
                        </template>
                    </span>
                </div>

                <!-- Variant Button Selector -->
                <div x-show="hasVariants" x-cloak class="mb-8 bg-gray-50 border border-gray-100 p-6 rounded-3xl">
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 tracking-widest">Pilih Tipe / Varian</label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(v, index) in variants" :key="index">
                            <button @click="selectedVariant = index"
                                    :class="selectedVariant === index ? 'bg-[#002c5f] text-white border-[#002c5f] shadow-lg shadow-blue-900/10' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-100 hover:text-gray-900'"
                                    class="px-4 py-2.5 rounded-xl border text-[11px] font-black uppercase tracking-widest transition-all duration-200">
                                <span x-text="v.name"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Variant Specifications Box -->
                <div x-show="hasVariants" x-cloak class="grid grid-cols-2 gap-4 bg-blue-50/50 p-5 rounded-2xl border border-blue-100/50 mb-8"
                     x-transition:enter="transition ease-out duration-300">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-[#002c5f]/60 mb-0.5">Transmisi</p>
                        <p class="text-xs font-bold text-gray-900" x-text="variants[selectedVariant].transmission"></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-[#002c5f]/60 mb-0.5">Mesin / Kapasitas</p>
                        <p class="text-xs font-bold text-gray-900" x-text="variants[selectedVariant].engine"></p>
                    </div>
                </div>

                <div class="prose max-w-none mb-12 text-gray-500 leading-relaxed text-sm">
                    {!! $car->description !!}
                </div>

                <!-- Features -->
                <!-- Dynamic Variant Features -->
                <div x-show="hasVariants" x-cloak class="mb-12">
                    <h3 class="text-xs font-black mb-6 uppercase tracking-widest text-gray-900 border-b border-gray-100 pb-3">Key Features (Tipe <span class="text-blue-600" x-text="variants[selectedVariant].name"></span>)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <template x-for="(feature, idx) in variants[selectedVariant].features" :key="idx">
                            <div class="flex items-center text-xs text-gray-600" x-transition:enter="transition ease-out duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 mr-3 flex-shrink-0"><path d="M20 6 9 17l-5-5"/></svg>
                                <span x-text="feature"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Static Features (Fallback) -->
                <div x-show="!hasVariants" class="mb-12">
                    @if($car->features && count($car->features) > 0)
                    <div>
                        <h3 class="text-xs font-black mb-6 uppercase tracking-widest text-gray-900 border-b border-gray-100 pb-3">Key Features</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($car->features as $feature)
                                <div class="flex items-center text-xs text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 mr-3 flex-shrink-0"><path d="M20 6 9 17l-5-5"/></svg>
                                    {{ $feature }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div x-data="{ promoCode: '' }" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 tracking-widest">Punya Kode Promo?</label>
                        <input type="text" x-model="promoCode" placeholder="Masukkan kode promo di sini..." 
                               class="w-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-4 text-sm focus:border-blue-500 focus:ring-0 transition-all uppercase font-mono tracking-widest">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a :href="'/track-wa?car_id={{ $car->id }}&text=' + encodeURIComponent('Halo, saya tertarik dengan unit {{ $car->name }}.' + (promoCode ? ' Saya memiliki kode promo: ' + promoCode : ''))" 
                           class="flex-1 bg-[#002c5f] text-white text-center py-4 rounded-2xl font-bold hover:bg-blue-800 transition-all shadow-xl flex items-center justify-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                            Hubungi Sales
                        </a>
                        
                        @if($car->flyer)
                            <a href="{{ asset('storage/' . $car->flyer) }}" target="_blank"
                               class="flex-1 bg-white border-2 border-[#002c5f] text-[#002c5f] text-center py-4 rounded-2xl font-bold hover:bg-gray-50 transition-all flex items-center justify-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                                Download Flyer (PDF)
                            </a>
                        @endif
                    </div>
        </div>
    </main>

    <!-- Harga On The Road Section -->
    <section class="py-16 bg-white border-t border-gray-100 pb-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left: Car Thumbnail Profile Image -->
                <div class="lg:col-span-5 flex justify-center items-center">
                    <div class="relative w-full max-w-md group">
                        <!-- Soft Radiant Glow -->
                        <div class="absolute -inset-4 bg-gradient-to-tr from-blue-500/10 to-indigo-500/10 rounded-[3rem] blur-2xl group-hover:scale-105 transition-all duration-700"></div>
                        
                        @if($car->image)
                            <img src="{{ asset('storage/' . $car->image) }}" 
                                 alt="{{ $car->name }}" 
                                 class="relative z-10 w-full h-auto object-contain transform hover:scale-105 hover:-translate-y-2 transition-all duration-700 select-none drop-shadow-[0_20px_50px_rgba(0,44,95,0.15)]">
                        @else
                            <div class="relative z-10 w-full aspect-[4/3] bg-gray-50 border border-gray-100 rounded-3xl flex items-center justify-center text-gray-300">
                                No Profile Photo
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: OTR Price Table & Disclaimer -->
                <div class="lg:col-span-7">
                    <div class="mb-6">
                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] block mb-2">Price List</span>
                        <h3 class="text-3xl font-black text-[#002c5f] uppercase tracking-tight">Harga On The Road</h3>
                        <p class="text-gray-400 text-xs mt-1 font-semibold">Daftar harga OTR Hyundai {{ $car->name }} terbaru</p>
                    </div>

                    <!-- Price Table -->
                    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-xl overflow-hidden mb-8">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#002c5f] text-white">
                                    <th class="p-5 text-xs font-black uppercase tracking-wider w-1/2">Type / Varian</th>
                                    <th class="p-5 text-xs font-black uppercase tracking-wider text-left w-1/2">Harga OTR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($car->variants && count($car->variants) > 0)
                                    @foreach($car->variants as $variant)
                                        <tr class="border-b border-gray-100 hover:bg-blue-50/40 transition-colors duration-200">
                                            <td class="p-5 text-xs font-bold text-gray-900 uppercase tracking-wide w-1/2">{{ $variant['name'] }}</td>
                                            <td class="p-5 text-left w-1/2">
                                                <span class="text-sm font-black text-[#002c5f]">Rp {{ number_format($variant['price'], 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="border-b border-gray-100">
                                        <td class="p-5 text-xs font-bold text-gray-900 uppercase tracking-wide w-1/2">Standard</td>
                                        <td class="p-5 text-left w-1/2">
                                            <span class="text-sm font-black text-[#002c5f]">Rp {{ number_format($car->price, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Disclaimer -->
                    <div class="bg-gray-50 border border-gray-100 p-6 rounded-3xl">
                        <h4 class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-3">Disclaimer / Catatan</h4>
                        <ul class="space-y-2 text-[10px] text-gray-400 font-semibold leading-relaxed">
                            <li class="flex items-start">
                                <span class="text-blue-500 mr-2 flex-shrink-0">•</span>
                                <span>Harga dan spesifikasi dapat berubah sewaktu-waktu tanpa pemberitahuan terlebih dahulu.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-500 mr-2 flex-shrink-0">•</span>
                                <span>Harga berlaku On The Road Surabaya, Sidoarjo, Gresik, dan wilayah Jawa Timur.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-500 mr-2 flex-shrink-0">•</span>
                                <span>Harga BBN tidak mengikat, apabila terjadi selisih BBN sepenuhnya menjadi beban konsumen.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-500 mr-2 flex-shrink-0">•</span>
                                <span>Harga BBN yang tertera hanya berlaku untuk kepemilikan kendaraan pertama (I).</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Side-by-Side Comparison Table Section -->
    @if($car->variants && count($car->variants) > 0)
    <section class="py-16 bg-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] block mb-2">Lineup</span>
                <h3 class="text-3xl font-black text-[#002c5f]">Perbandingan Spesifikasi & Fitur</h3>
                <p class="text-gray-400 text-xs mt-2 font-medium">Bandingkan harga dan kelengkapan fitur dari setiap tipe {{ $car->name }}</p>
                <div class="h-1 w-16 bg-[#002c5f] mx-auto rounded-full mt-4"></div>
            </div>

            <!-- Comparison Table Container -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-gray-400 w-[200px]">Fitur / Spesifikasi</th>
                                @foreach($car->variants as $variant)
                                    <th class="p-6 text-center border-l border-gray-100 min-w-[150px]" style="width: calc((100% - 200px) / {{ count($car->variants) }})">
                                        <span class="text-[9px] font-black text-blue-600 uppercase tracking-widest block mb-1">Tipe</span>
                                        <h4 class="text-base font-black text-gray-900 uppercase">{{ $variant['name'] }}</h4>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Harga OTR -->
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="p-6 text-[11px] font-bold text-gray-900 uppercase tracking-wider">Harga OTR mulai</td>
                                @foreach($car->variants as $variant)
                                    <td class="p-6 text-center border-l border-gray-100">
                                        <span class="text-base font-black text-[#002c5f]">Rp {{ number_format($variant['price'], 0, ',', '.') }}</span>
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Transmisi -->
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="p-6 text-[11px] font-bold text-gray-900 uppercase tracking-wider">Transmisi</td>
                                @foreach($car->variants as $variant)
                                    <td class="p-6 text-center border-l border-gray-100 text-xs font-semibold text-gray-600">
                                        {{ $variant['transmission'] ?? '-' }}
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Mesin -->
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="p-6 text-[11px] font-bold text-gray-900 uppercase tracking-wider">Mesin / Kapasitas</td>
                                @foreach($car->variants as $variant)
                                    <td class="p-6 text-center border-l border-gray-100 text-xs font-semibold text-gray-600">
                                        {{ $variant['engine'] ?? '-' }}
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Fitur Utama -->
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="p-6 text-[11px] font-bold text-gray-900 uppercase tracking-wider align-top">Fitur Unggulan</td>
                                @foreach($car->variants as $variant)
                                    <td class="p-6 border-l border-gray-100 align-top">
                                        <ul class="space-y-2">
                                            @if(isset($variant['key_features']) && is_array($variant['key_features']))
                                                @foreach($variant['key_features'] as $feature)
                                                    <li class="flex items-start text-[11px] text-gray-600 text-left">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 mr-2 flex-shrink-0 mt-0.5"><path d="M20 6 9 17l-5-5"/></svg>
                                                        <span>{{ $feature }}</span>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="text-xs text-gray-400 italic text-center">-</li>
                                            @endif
                                        </ul>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-12 flex justify-center">
                <a href="{{ route('track.wa', ['car_id' => $car->id, 'text' => 'Halo sales Hyundai, saya ingin melakukan pemesanan/tanya tipe Pilihan untuk unit ' . $car->name]) }}" 
                   class="bg-[#002c5f] text-white px-10 py-4 rounded-2xl font-bold hover:bg-blue-800 transition-all shadow-xl flex items-center justify-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                    Pesan Tipe Pilihan Sekarang
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="bg-gray-50 py-12 border-t border-gray-100 mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} Hyundai Showroom. Semua Hak Dilindungi.
        </div>
    </footer>

    @livewireScripts
</body>
</html>
