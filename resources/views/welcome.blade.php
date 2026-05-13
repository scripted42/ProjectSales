<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Setting::get('site_name', 'Hyundai Showroom') }} - Premium Sales Portal</title>

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
                @if($logo = \App\Models\Setting::get('site_logo'))
                    <img src="{{ asset('storage/' . $logo) }}" alt="{{ \App\Models\Setting::get('site_name') }}" class="h-6 md:h-10 w-auto">
                @else
                    <img src="{{ asset('assets/images/hyundai_logo.png') }}" alt="Hyundai" class="h-6 md:h-10 w-auto">
                @endif
                <span class="ml-2 md:ml-3 font-black text-sm md:text-2xl tracking-[0.2em] text-[#002c5f] uppercase truncate max-w-[120px] md:max-w-none">{{ \App\Models\Setting::get('site_name', 'Hyundai') }}</span>
            </div>
            <div class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-widest text-gray-500">
                <a href="#home" class="hover:text-[#002c5f] transition-colors">Home</a>
                <a href="#models" class="hover:text-[#002c5f] transition-colors">Models</a>
                <a href="#gallery" class="hover:text-[#002c5f] transition-colors">Gallery</a>
                <a href="#news" class="hover:text-[#002c5f] transition-colors">News</a>
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
                <a href="{{ route('track.wa') }}" class="bg-[#002c5f] text-white px-4 md:px-6 py-2 md:py-2.5 rounded-full text-[10px] md:text-sm font-bold hover:bg-blue-800 transition-all shadow-lg whitespace-nowrap">
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
                <img :src="car.banner" :alt="car.name" class="w-full h-full object-cover object-center">
            </div>
        </template>

        <!-- Gradient Overlays -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent z-10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent z-10"></div>

        <!-- Content -->
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-end pb-24 md:pb-20">
            <div class="max-w-2xl w-full">
                <div class="inline-block bg-white/10 backdrop-blur-md border border-white/20 px-4 py-1.5 rounded-full mb-6">
                    <span class="text-white text-[11px] font-black uppercase tracking-[0.2em]" x-text="cars[current].category">EV</span>
                </div>
                <h1 class="text-5xl md:text-8xl font-black text-white leading-[1.05] mb-4 transition-all duration-500" x-text="cars[current].name"></h1>
                <p class="text-lg text-white/50 mb-2 font-light">Harga OTR mulai dari</p>
                <p class="text-3xl md:text-4xl font-black text-white mb-10 transition-all duration-500" x-text="cars[current].price"></p>
                <div class="flex flex-col sm:flex-row gap-4 w-full">
                    <a :href="'/car/' + cars[current].slug" class="block w-full sm:w-[260px] bg-white text-[#002c5f] px-6 py-3.5 md:py-4 rounded-full font-bold text-center hover:bg-blue-50 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-0.5">
                        Lihat Detail
                    </a>
                    <a href="{{ route('track.wa') }}" class="block w-full sm:w-[260px] bg-white/10 backdrop-blur-md text-white border border-white/30 px-6 py-3.5 md:py-4 rounded-full font-bold text-center hover:bg-white/20 transition-all">
                        Dapatkan Penawaran
                    </a>
                </div>
            </div>
        </div>

        <!-- Slide Indicators (Absolute Bottom Center) -->
        <div class="absolute bottom-6 md:bottom-10 left-0 right-0 z-30 flex items-center justify-center gap-3">
            <template x-for="(car, index) in cars" :key="index">
                <button @click="go(index)" 
                        class="h-1.5 rounded-full transition-all duration-500 shadow-lg"
                        :class="current === index ? 'w-12 bg-white' : 'w-3 bg-white/40 hover:bg-white/70'"></button>
            </template>
            <span class="ml-4 text-[10px] font-black text-white/60 uppercase tracking-[0.3em]" x-text="(current + 1) + ' / ' + cars.length"></span>
        </div>
    </section>

    <!-- Promo Section (Livewire) -->
    <livewire:promo-section />

    <!-- Car Models - Break the Frame -->
    <section id="models" class="py-12 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-black text-gray-900 mb-4">{{ \App\Models\Setting::get('site_name', 'Hyundai') }} Models</h2>
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

    <!-- Service Tools Section (Test Drive & Credit) -->
    <section id="services" class="py-12 md:py-16 bg-gray-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
                
                <!-- Left: Test Drive -->
                <div class="flex flex-col w-full">
                    <!-- Header Locked Height -->
                    <div style="min-height: 90px;" class="mb-2">
                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] block mb-2">Experience</span>
                        <h2 class="text-3xl font-black text-gray-900 leading-tight">Booking Test Drive</h2>
                        <div class="h-1 w-16 bg-[#002c5f] rounded-full mt-3"></div>
                    </div>
                    <!-- Card Container -->
                    <div class="flex-grow flex flex-col h-full">
                        <livewire:test-drive-form />
                    </div>
                </div>

                <!-- Right: Credit Simulation -->
                <div class="flex flex-col w-full mt-12 md:mt-0">
                    <!-- Header Locked Height -->
                    <div style="min-height: 90px;" class="mb-2">
                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] block mb-2">Financing</span>
                        <h2 class="text-3xl font-black text-gray-900 leading-tight">Simulasi Kredit</h2>
                        <div class="h-1 w-16 bg-[#002c5f] rounded-full mt-3"></div>
                    </div>
                    <!-- Card Container -->
                    <div class="flex-grow flex flex-col h-full">
                        <livewire:credit-calculator />
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Consultant Profile -->
    @if($consultant)
    <section id="consultant" class="py-12 md:py-16 bg-white">
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

                    <a href="{{ route('track.wa') }}" class="bg-[#002c5f] text-white text-center py-4 rounded-xl font-bold text-lg hover:bg-blue-800 transition-all flex items-center justify-center gap-3">
                         Hubungi {{ explode(' ', $consultant->name ?? 'Konsultan')[0] }}
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Handover Gallery -->
    <section id="gallery" class="py-12 md:py-16 bg-gray-50">
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

    <!-- Blog / News Section -->
    <section id="news" class="py-12 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 gap-6">
                <div>
                    <h2 class="text-3xl md:text-5xl font-black mb-4">Auto Insight & Exclusive Updates</h2>
                    <p class="text-gray-400 uppercase tracking-widest text-sm max-w-2xl">Jelajahi dunia otomotif melalui kacamata Hyundai. Dapatkan tips eksklusif, berita teknologi terbaru, dan penawaran yang dipersonalisasi khusus untuk Anda.</p>
                    <div class="h-1.5 w-24 bg-[#002c5f] rounded-full mt-4"></div>
                </div>
                <a href="{{ route('posts.index') }}" class="flex items-center text-[#002c5f] font-bold hover:underline gap-2 group/all">
                    Explore All Insights
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover/all:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($posts as $post)
                    <article class="group bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-blue-100 hover:shadow-xl transition-all duration-500">
                        <a href="{{ route('posts.show', $post->slug) }}" class="block overflow-hidden aspect-video bg-gray-100">
                            @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" 
                                     alt="{{ $post->title }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mb-2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                    <span class="text-[10px] uppercase tracking-widest font-bold">Image Preview</span>
                                </div>
                            @endif
                        </a>
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="bg-blue-50 text-[#002c5f] text-[9px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider">
                                    {{ $post->category }}
                                </span>
                                <span class="text-gray-400 text-[9px] uppercase tracking-widest font-medium">
                                    {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}
                                </span>
                            </div>
                            <h3 class="text-base font-bold mb-2 line-clamp-2 group-hover:text-[#002c5f] transition-colors leading-snug">
                                <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            <p class="text-gray-500 text-xs line-clamp-2 mb-4 font-light leading-relaxed">
                                {{ Str::limit(strip_tags($post->content), 80) }}
                            </p>
                            <a href="{{ route('posts.show', $post->slug) }}" class="inline-flex items-center text-[11px] font-bold text-gray-900 group-hover:text-[#002c5f] gap-1.5 uppercase tracking-wider">
                                Read More
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </article>
                @empty
                    @for($i=1; $i<=4; $i++)
                        <div class="bg-gray-100 rounded-2xl aspect-video animate-pulse"></div>
                    @endfor
                @endforelse
            </div>
            
            <div class="mt-12 text-center md:hidden">
                <a href="{{ route('posts.index') }}" class="inline-flex items-center bg-gray-50 px-8 py-4 rounded-full text-[#002c5f] font-bold gap-2">
                    Lihat Semua Berita
                </a>
            </div>
        </div>
    </section>

    <!-- Video & Map -->
    <section class="py-12 md:py-16 bg-gray-50">
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
            <div class="rounded-3xl overflow-hidden shadow-lg h-full min-h-[400px]">
                @if($consultant && $consultant->maps_embed)
                    {!! $consultant->maps_embed !!}
                @else
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.6919013583567!2d112.6967817748402!3d-7.275883692731089!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fc6778f8447d%3A0x6794680879612f0f!2sHyundai%20Gowa%20Surabaya!5e0!3m2!1sid!2sid!4v1714876800000!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#002c5f] text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center mb-6">
                    @if($logo = \App\Models\Setting::get('site_logo'))
                        <img src="{{ asset('storage/' . $logo) }}" alt="{{ \App\Models\Setting::get('site_name') }}" class="h-8 w-auto brightness-0 invert">
                    @else
                        <img src="{{ asset('assets/images/hyundai_logo.png') }}" alt="Hyundai" class="h-8 w-auto brightness-0 invert">
                    @endif
                    <span class="ml-3 font-black text-xl tracking-[0.2em] uppercase">{{ \App\Models\Setting::get('site_name', 'Hyundai') }}</span>
                </div>
                <p class="text-blue-200 max-w-md mb-8">
                    Dealer Resmi Hyundai melayani penjualan unit baru, layanan purna jual, dan suku cadang asli dengan standar pelayanan profesional.
                </p>
                <div class="flex space-x-4">
                    @if(optional($consultant)->instagram)
                        <a href="https://instagram.com/{{ $consultant->instagram }}" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-pink-600 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                        </a>
                    @endif
                    @if(optional($consultant)->facebook)
                        <a href="https://facebook.com/{{ $consultant->facebook }}" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-blue-600 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                    @endif
                    @if(optional($consultant)->tiktok)
                        <a href="https://tiktok.com/{{ '@' . $consultant->tiktok }}" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-black transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg>
                        </a>
                    @endif
                </div>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6 uppercase tracking-widest text-blue-100">Layanan</h4>
                <ul class="space-y-4 text-blue-200 text-sm font-medium">
                    <li><a href="#models" class="hover:text-white transition-colors">Penjualan Unit Baru</a></li>
                    <li><a href="#booking" class="hover:text-white transition-colors">Test Drive Gratis</a></li>
                    <li><a href="{{ route('track.wa', ['text' => 'Halo, saya ingin tanya program Tukar Tambah']) }}" class="hover:text-white transition-colors">Tukar Tambah (Trade-in)</a></li>
                    <li><a href="{{ route('track.wa', ['text' => 'Halo, saya ingin tanya info Bengkel']) }}" class="hover:text-white transition-colors">Bengkel & Suku Cadang</a></li>
                    <li><a href="{{ route('track.wa', ['text' => 'Halo, saya ingin dibuatkan Simulasi Kredit']) }}" class="hover:text-white transition-colors">Simulasi Kredit</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6 uppercase tracking-widest text-blue-100">Kontak</h4>
                <p class="text-xs text-blue-300 uppercase tracking-widest mb-2">Jam Operasional:</p>
                <p class="text-sm font-bold mb-6">Senin - Minggu<br>08.00 - 20.00 WIB</p>
                <p class="text-xs text-blue-300 uppercase tracking-widest mb-2">Support Email:</p>
                <p class="text-sm font-bold text-white hover:text-blue-200 transition-colors">
                    <a href="mailto:{{ optional($consultant)->email ?? 'sales@hyundai.id' }}">{{ optional($consultant)->email ?? 'sales@hyundai.id' }}</a>
                </p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 pt-8 border-t border-white/10 text-center text-[10px] uppercase tracking-[0.2em] text-blue-400">
            &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Hyundai') }} Dealer. All Rights Reserved.
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    <a href="{{ route('track.wa') }}" class="fixed bottom-8 right-8 z-50 bg-green-500 text-white p-4 rounded-full shadow-2xl hover:bg-green-600 transition-all transform hover:scale-110 flex items-center justify-center animate-bounce">
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
