<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hyundai Showroom - Premium Sales Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        .car-card:hover .car-image { transform: scale(1.05); }
        .car-image { transition: transform 0.5s ease; }
    </style>
    @livewireStyles
</head>
<body class="bg-white text-gray-900 antialiased overflow-x-hidden">
    
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl border-b border-gray-100 py-4 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center">
                <img src="https://www.hyundai.com/content/dam/hyundai/ww/en/images/common/hyundai-logo-white-small.png" alt="Hyundai" class="h-6 invert">
                <span class="ml-3 font-bold text-xl tracking-wider text-[#002c5f] uppercase">Showroom</span>
            </div>
            <div class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-widest text-gray-500">
                <a href="#home" class="hover:text-[#002c5f] transition-colors">Home</a>
                <a href="#models" class="hover:text-[#002c5f] transition-colors">Models</a>
                <a href="#gallery" class="hover:text-[#002c5f] transition-colors">Gallery</a>
                <a href="#consultant" class="hover:text-[#002c5f] transition-colors">Consultant</a>
            </div>
            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/admin') }}" class="text-sm font-medium text-blue-600 hover:underline">Admin</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-400 hover:text-[#002c5f] transition-colors">Login</a>
                    @endauth
                @endif
                <a href="https://api.whatsapp.com/send/?phone={{ $consultant->formatted_phone ?? '#' }}" class="bg-[#002c5f] text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-blue-800 transition-all shadow-lg">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Banner Slideshow -->
    <section id="home" class="relative h-screen overflow-hidden"
             x-data="{
                current: 0,
                cars: [
                    @foreach($cars as $index => $car)
                    {
                        name: '{{ $car->name }}',
                        category: '{{ $car->category }}',
                        price: 'Rp {{ number_format($car->price, 0, ',', '.') }}',
                        banner: '{{ $car->hero_image ? asset('storage/'.$car->hero_image) : ($car->image ? asset('storage/'.$car->image) : '') }}',
                        slug: '{{ $car->slug }}'
                    }{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ],
                go(index) {
                    this.current = index;
                },
                init() {
                    setInterval(() => {
                        this.go((this.current + 1) % this.cars.length);
                    }, 6000);
                }
             }">

        <!-- Banner Images -->
        <template x-for="(car, index) in cars" :key="index">
            <div class="absolute inset-0 transition-all duration-[1200ms] ease-in-out"
                 :style="current === index ? 'opacity:1; transform: scale(1);' : 'opacity:0; transform: scale(1.05);'">
                <img :src="car.banner" :alt="car.name" class="w-full h-full object-cover">
            </div>
        </template>

        <!-- Gradient Overlays -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent z-10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent z-10"></div>

        <!-- Content -->
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-end pb-20">
            <div class="max-w-2xl">
                <div class="inline-block bg-white/10 backdrop-blur-md border border-white/20 px-4 py-1.5 rounded-full mb-6">
                    <span class="text-white text-[11px] font-black uppercase tracking-[0.2em]" x-text="cars[current].category">EV</span>
                </div>
                <h1 class="text-5xl md:text-8xl font-black text-white leading-[1.05] mb-4 transition-all duration-500" x-text="cars[current].name"></h1>
                <p class="text-lg text-white/50 mb-2 font-light">Harga OTR mulai dari</p>
                <p class="text-3xl md:text-4xl font-black text-white mb-10 transition-all duration-500" x-text="cars[current].price"></p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a :href="'/car/' + cars[current].slug" class="bg-white text-[#002c5f] px-10 py-4 rounded-full font-bold text-center hover:bg-blue-50 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-0.5">
                        Lihat Detail
                    </a>
                    <a href="https://api.whatsapp.com/send/?phone={{ $consultant->formatted_phone ?? '#' }}" class="bg-white/10 backdrop-blur-md text-white border border-white/30 px-10 py-4 rounded-full font-bold text-center hover:bg-white/20 transition-all">
                        Dapatkan Penawaran
                    </a>
                </div>

                <!-- Slide Indicators -->
                <div class="flex items-center gap-3 mt-12">
                    <template x-for="(car, index) in cars" :key="index">
                        <button @click="go(index)" 
                                class="h-1 rounded-full transition-all duration-500"
                                :class="current === index ? 'w-12 bg-white' : 'w-3 bg-white/30 hover:bg-white/50'"></button>
                    </template>
                    <span class="ml-4 text-[10px] font-bold text-white/40 uppercase tracking-widest" x-text="(current + 1) + ' / ' + cars.length"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Promo Section (Livewire) -->
    <livewire:promo-section />

    <!-- Car Models - Break the Frame -->
    <section id="models" class="py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-black text-gray-900 mb-4">Hyundai Models</h2>
                <div class="h-1.5 w-24 bg-[#002c5f] mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-10 justify-items-center">
                @foreach($cars as $car)
                    <a href="{{ route('car.show', $car->slug) }}" class="group block w-full max-w-[280px]">
                        <div class="relative overflow-visible">
                            <!-- Card with 9:11 aspect ratio -->
                            <div class="relative bg-gray-50 rounded-[2rem] border border-gray-100 group-hover:border-blue-200 group-hover:shadow-2xl transition-all duration-500" style="aspect-ratio: 9/11;">
                                
                                <!-- Car Image - top 60%, breaks out of frame -->
                                <div class="absolute inset-x-0 top-0 flex items-center justify-center pointer-events-none" style="height: 58%; overflow: visible;">
                                    <img src="{{ $car->image ? asset('storage/'.$car->image) : '' }}" 
                                         alt="{{ $car->name }}" 
                                         class="w-[115%] max-w-none h-auto object-contain drop-shadow-[0_20px_40px_rgba(0,0,0,0.15)] transition-all duration-500 group-hover:scale-110 group-hover:-translate-y-2">
                                </div>

                                <!-- Bottom Content - 42% height, all inside card -->
                                <div class="absolute inset-x-0 bottom-0 px-5 pb-5 flex flex-col items-center justify-end" style="height: 42%;">
                                    <!-- Name Badge -->
                                    <span class="bg-[#002c5f] text-white text-[11px] font-black px-5 py-2 rounded-xl uppercase tracking-wider shadow-lg mb-2">
                                        {{ $car->name }}
                                    </span>

                                    <!-- Category -->
                                    <p class="text-[10px] text-gray-400 uppercase tracking-[0.15em] font-semibold mb-1">{{ $car->category }}</p>

                                    <!-- Price -->
                                    <p class="text-base font-black text-gray-900 mb-3">Rp {{ number_format($car->price, 0, ',', '.') }}</p>

                                    <!-- CTA Button -->
                                    <span class="bg-[#002c5f] text-white text-[11px] font-bold px-6 py-2 rounded-full uppercase tracking-wider group-hover:bg-blue-700 transition-all inline-flex items-center gap-1.5">
                                        Lihat Detail
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-300 group-hover:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Test Drive Booking -->
    <livewire:test-drive-form />

    <!-- Consultant Profile -->
    <section id="consultant" class="py-24 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl overflow-hidden shadow-lg flex flex-col md:flex-row border border-gray-100">
                <div class="md:w-1/2 relative h-80 md:h-auto overflow-hidden">
                    <img src="{{ $consultant->photo ? asset('storage/'.$consultant->photo) : '' }}" 
                         alt="Consultant" 
                         class="w-full h-full object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6 text-white md:hidden">
                        <h3 class="text-2xl font-bold">{{ $consultant->name ?? 'Sales Consultant' }}</h3>
                        <p class="text-blue-400 text-sm tracking-widest uppercase">Expert Advisor</p>
                    </div>
                </div>
                <div class="md:w-1/2 p-10 md:p-16 flex flex-col justify-center">
                    <h2 class="text-blue-600 font-bold tracking-widest uppercase mb-4 text-sm">Sales Consultant</h2>
                    <h3 class="text-4xl font-black mb-6">{{ $consultant->name ?? 'Hubungi Konsultan Kami' }}</h3>
                    
                    <div class="space-y-6 mb-10">
                        <div class="flex items-start">
                            <div class="bg-blue-50 p-3 rounded-xl mr-4 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold uppercase tracking-wider text-gray-400">Lokasi Dealer</p>
                                <p class="text-gray-600">{{ $consultant->address ?? 'Jl. Mayjen HR. Muhammad No.35C, Surabaya' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-green-50 p-3 rounded-xl mr-4 text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold uppercase tracking-wider text-gray-400">WhatsApp</p>
                                <p class="text-gray-600">{{ $consultant->phone ?? '0812-3604-6363' }}</p>
                            </div>
                        </div>
                    </div>

                    <a href="https://api.whatsapp.com/send/?phone={{ $consultant->formatted_phone ?? '#' }}" class="bg-[#002c5f] text-white text-center py-4 rounded-xl font-bold text-lg hover:bg-blue-800 transition-all flex items-center justify-center gap-3">
                         Hubungi {{ explode(' ', $consultant->name ?? 'Konsultan')[0] }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Handover Gallery -->
    <section id="gallery" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-black mb-4">Galeri Serah Terima</h2>
                <p class="text-gray-400 uppercase tracking-widest text-sm">Kebahagiaan Pelanggan Kami</p>
                <div class="h-1.5 w-24 bg-[#002c5f] mx-auto rounded-full mt-4"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($galleries as $gallery)
                    <div class="relative group aspect-square overflow-hidden rounded-2xl bg-gray-50">
                        <img src="{{ asset('storage/'.$gallery->image) }}" alt="Handover" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                            <p class="text-white text-xs font-medium">{{ $gallery->caption }}</p>
                        </div>
                    </div>
                @empty
                    @for($i=1; $i<=8; $i++)
                        <div class="relative group aspect-square overflow-hidden rounded-2xl bg-gray-100 animate-pulse"></div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    <!-- Video & Map -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12">
            <div class="rounded-3xl overflow-hidden shadow-lg aspect-video bg-black">
                @php
                    $videoId = 'S2O79tGisX4';
                    if ($video && $video->url) {
                        if (preg_match('/(?:\?v=|&v=|be\/|embed\/|v\/|.*v=)([^"&?\/\s]{11})/', $video->url, $matches)) {
                            $videoId = $matches[1];
                        }
                    }
                @endphp
                <iframe class="w-full h-full" 
                        src="https://www.youtube.com/embed/{{ $videoId }}" 
                        title="{{ $video->title ?? 'Hyundai Video' }}" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen>
                </iframe>
            </div>
            <div class="rounded-3xl overflow-hidden shadow-lg h-full min-h-[300px]">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.6919013583567!2d112.6967817748402!3d-7.275883692731089!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fc6778f8447d%3A0x6794680879612f0f!2sHyundai%20Gowa%20Surabaya!5e0!3m2!1sid!2sid!4v1714876800000!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#002c5f] text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-2">
                <img src="https://www.hyundai.com/content/dam/hyundai/ww/en/images/common/hyundai-logo-white-small.png" alt="Hyundai" class="h-6 mb-6">
                <p class="text-blue-200 max-w-md mb-8">
                    Dealer Resmi Hyundai Surabaya menyediakan layanan penjualan unit, bengkel service, dan suku cadang asli Hyundai.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6 uppercase tracking-widest">Layanan</h4>
                <ul class="space-y-4 text-blue-200 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">Penjualan Unit Baru</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Test Drive Gratis</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Tukar Tambah (Trade-in)</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Bengkel & Suku Cadang</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Simulasi Kredit</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6 uppercase tracking-widest">Kontak</h4>
                <p class="text-sm text-blue-200 mb-2">Operational Hours:</p>
                <p class="text-sm font-bold mb-6">Senin - Minggu: 08.00 - 20.00 WIB</p>
                <p class="text-sm text-blue-200 mb-2">Support Email:</p>
                <p class="text-sm font-bold">cs@hyundai-surabaya.com</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 pt-8 border-t border-white/10 text-center text-xs text-blue-400">
            &copy; {{ date('Y') }} Hyundai Showroom Surabaya. All Rights Reserved.
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    <a href="https://api.whatsapp.com/send/?phone={{ $consultant->formatted_phone ?? '#' }}" class="fixed bottom-8 right-8 z-50 bg-green-500 text-white p-4 rounded-full shadow-2xl hover:bg-green-600 transition-all transform hover:scale-110 flex items-center justify-center animate-bounce">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
    </a>

    <script>
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-lg', 'py-2');
                nav.classList.remove('py-4');
            } else {
                nav.classList.remove('shadow-lg', 'py-2');
                nav.classList.add('py-4');
            }
        });
    </script>
    @livewireScripts
</body>
</html>
