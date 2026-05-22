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
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        @keyframes marquee-reverse {
            0% { transform: translateX(-50%); }
            100% { transform: translateX(0); }
        }
        .animate-marquee {
            animation: marquee 40s linear infinite;
        }
        .animate-marquee-reverse {
            animation: marquee-reverse 40s linear infinite;
        }
        .animate-marquee:hover, .animate-marquee-reverse:hover {
            animation-play-state: paused;
        }
    </style>
    @livewireStyles
</head>
<body class="bg-white text-gray-900 antialiased overflow-x-hidden">
    
    <x-navbar />

    <!-- Hero Section with Banner Slideshow -->
    <section id="home" class="relative h-screen overflow-hidden"
             x-data="{
                current: 0,
                cars: [
                    @forelse($cars as $index => $car)
                    {
                        name: '{{ $car->name }}',
                        category: '{{ $car->category }}',
                        price: 'Rp {{ number_format($car->price, 0, ',', '.') }}',
                        banner: '{{ $car->hero_image ? asset('storage/'.$car->hero_image) : ($car->image ? asset('storage/'.$car->image) : '') }}',
                        slug: '{{ $car->slug }}'
                    }{{ !$loop->last ? ',' : '' }}
                    @empty
                    {
                        name: '{{ \App\Models\Setting::get('site_name', 'Hyundai Showroom') }}',
                        category: 'WELCOME',
                        price: 'Hubungi Kami',
                        banner: '{{ asset('assets/images/hyundai_logo.png') }}',
                        slug: '#'
                    }
                    @endforelse
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

        <!-- Content Layer -->
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center">
            <div class="w-full max-w-3xl text-center md:text-left">
                <!-- Badge -->
                <div class="inline-block bg-white/10 backdrop-blur-md border border-white/20 px-4 py-1.5 rounded-full mb-6">
                    <span class="text-white text-[10px] font-black uppercase tracking-[0.3em]" x-text="cars[current].category"></span>
                </div>

                <!-- Car Title -->
                <h1 class="text-5xl md:text-8xl font-black text-white leading-[1.05] mb-4 drop-shadow-2xl" x-text="cars[current].name"></h1>
                
                <!-- Price Label -->
                <div class="mb-10">
                    <p class="text-sm md:text-lg text-white/50 mb-1 font-light tracking-wide">Harga OTR mulai dari</p>
                    <p class="text-3xl md:text-5xl font-black text-white" x-text="cars[current].price"></p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start gap-4">
                    <a :href="'/car/' + cars[current].slug" 
                       class="inline-flex items-center justify-center px-12 py-4 bg-white text-[#002c5f] rounded-full font-bold text-[13px] uppercase tracking-widest hover:bg-blue-50 transition-all shadow-xl hover:-translate-y-1">
                        Lihat Detail
                    </a>
                    <a href="{{ route('track.wa') }}" 
                       class="inline-flex items-center justify-center px-12 py-4 bg-white/10 backdrop-blur-md text-white border border-white/30 rounded-full font-bold text-[13px] uppercase tracking-widest hover:bg-white/20 transition-all">
                        Dapatkan Penawaran
                    </a>
                </div>

                <!-- Slide Indicators (Classic Morphing Pills) -->
                <div class="mt-16 flex items-center justify-center md:justify-start gap-3">
                    <template x-for="(car, index) in cars" :key="index">
                        <button @click="go(index)" 
                                class="h-1 rounded-full transition-all duration-500 shadow-lg"
                                :class="current === index ? 'w-12 bg-white' : 'w-3 bg-white/30 hover:bg-white/50'"></button>
                    </template>
                </div>
            </div>
        </div>

    </section>

    <!-- Promo Section (Livewire) -->
    <livewire:promo-section />

    <!-- Car Models - Break the Frame -->
    <section id="models" class="py-12 md:py-16 bg-white" x-data="{ activeTab: 'ALL' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-5xl font-black text-gray-900 mb-4">{{ \App\Models\Setting::get('site_name', 'Hyundai') }} Models</h2>
                <div class="h-1.5 w-24 bg-[#002c5f] mx-auto rounded-full"></div>
            </div>

            <!-- Visual Category Tabs Selector -->
            <div class="flex flex-wrap items-center justify-center gap-2 md:gap-3 mb-16 max-w-4xl mx-auto">
                <button @click="activeTab = 'ALL'" 
                        :class="activeTab === 'ALL' ? 'bg-[#002c5f] text-white shadow-xl shadow-blue-900/20 scale-105' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                        class="px-5 py-3 rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-300 focus:outline-none">
                    Semua
                </button>
                <button @click="activeTab = 'MPV'" 
                        :class="activeTab === 'MPV' ? 'bg-[#002c5f] text-white shadow-xl shadow-blue-900/20 scale-105' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                        class="px-5 py-3 rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-300 focus:outline-none">
                    MPV
                </button>
                <button @click="activeTab = 'SUV'" 
                        :class="activeTab === 'SUV' ? 'bg-[#002c5f] text-white shadow-xl shadow-blue-900/20 scale-105' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                        class="px-5 py-3 rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-300 focus:outline-none">
                    SUV
                </button>
                <button @click="activeTab = 'EV'" 
                        :class="activeTab === 'EV' ? 'bg-[#002c5f] text-white shadow-xl shadow-blue-900/20 scale-105' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                        class="px-5 py-3 rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-300 focus:outline-none">
                    Electric Vehicle
                </button>
                <button @click="activeTab = 'Crossover'" 
                        :class="activeTab === 'Crossover' ? 'bg-[#002c5f] text-white shadow-xl shadow-blue-900/20 scale-105' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                        class="px-5 py-3 rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-300 focus:outline-none">
                    Crossover
                </button>
                <button @click="activeTab = 'Luxury MPV'" 
                        :class="activeTab === 'Luxury MPV' ? 'bg-[#002c5f] text-white shadow-xl shadow-blue-900/20 scale-105' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                        class="px-5 py-3 rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-300 focus:outline-none">
                    Luxury MPV
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12 justify-items-center">
                @foreach($cars as $car)
                    <div x-show="activeTab === 'ALL' || activeTab === '{{ $car->category }}'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 scale-90 translate-y-6"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-90 translate-y-6"
                         class="w-full max-w-[280px]">
                        <a href="{{ route('car.show', $car->slug) }}" class="group block w-full">
                            <div class="relative overflow-visible">
                                <!-- Card with 9:11 aspect ratio -->
                                <div class="relative bg-gray-50 rounded-[2rem] border border-gray-100 group-hover:border-blue-200 group-hover:shadow-2xl transition-all duration-500" style="aspect-ratio: 9/11;">
                                    
                                    <!-- Car Image - top 58%, breaks out of frame -->
                                    <div class="absolute inset-x-0 top-0 flex items-center justify-center pointer-events-none" style="height: 58%; overflow: visible;">
                                        @if($car->image)
                                            <img src="{{ asset('storage/'.$car->image) }}" 
                                                 alt="{{ $car->name }}" 
                                                 class="w-[115%] max-w-none h-auto object-contain drop-shadow-[0_20px_40px_rgba(0,0,0,0.15)] transition-all duration-500 group-hover:scale-110 group-hover:-translate-y-2">
                                        @else
                                            <div class="w-[80%] h-[80%] bg-gray-200 rounded-2xl flex items-center justify-center text-gray-400 text-xs font-bold uppercase tracking-widest">No Image</div>
                                        @endif
                                    </div>

                                    <!-- Bottom Content - 42% height, all inside card -->
                                    <div class="absolute inset-x-0 bottom-0 px-5 pb-5 flex flex-col items-center justify-end" style="height: 42%;">
                                        <!-- Name Badge -->
                                        <span class="bg-[#002c5f] text-white text-[11px] font-black px-5 py-2 rounded-xl uppercase tracking-wider shadow-lg mb-2">
                                            {{ $car->name }}
                                        </span>

                                        <!-- Category -->
                                        <p class="text-[10px] text-gray-400 uppercase tracking-[0.15em] font-bold mb-1">
                                            @if($car->category === 'EV') Electric Vehicle @else {{ $car->category }} @endif
                                        </p>

                                        <!-- Price -->
                                        <p class="text-sm font-black text-gray-900 mb-3">Rp {{ number_format($car->price, 0, ',', '.') }}</p>

                                        <!-- CTA Button -->
                                        <span class="bg-[#002c5f] text-white text-[11px] font-bold px-6 py-2 rounded-full uppercase tracking-wider group-hover:bg-blue-700 transition-all inline-flex items-center gap-1.5">
                                            Lihat Detail
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-300 group-hover:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
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
    @if(isset($testimonials) && $testimonials->count() > 0)
    <!-- Testimonials Section -->
    <section id="testimonials" class="py-16 md:py-24 bg-white overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12 text-center">
            <h2 class="text-3xl md:text-5xl font-black mb-4 text-gray-900">Words of praise from others<br>about our presence.</h2>
            <div class="h-1.5 w-24 bg-[#002c5f] mx-auto rounded-full mt-6"></div>
        </div>

        <div class="relative w-full flex flex-col gap-6" style="overflow: hidden;">
            <!-- Left Gradient Mask -->
            <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
            <!-- Right Gradient Mask -->
            <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>

            <!-- Top Row (Right to Left) -->
            <div class="flex animate-marquee gap-6 hover:cursor-grab active:cursor-grabbing" style="width: 200%; white-space: nowrap;">
                @php
                    $scrollingItems = $testimonials->merge($testimonials)->merge($testimonials);
                @endphp
                @foreach($scrollingItems as $testimonial)
                    <div class="flex flex-col justify-between p-8 bg-gray-50 border border-gray-100 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 flex-shrink-0" style="width: 350px; white-space: normal;">
                        <div>
                            <svg class="w-8 h-8 text-blue-500 mb-6" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                            <p class="text-gray-700 text-[15px] font-medium leading-relaxed mb-8">
                                {{ $testimonial->quote }}
                            </p>
                        </div>
                        <div class="flex items-center gap-4 mt-auto">
                            @if($testimonial->image)
                                <img src="{{ asset('storage/' . $testimonial->image) }}" 
                                     alt="{{ $testimonial->name }}" 
                                     class="w-12 h-12 rounded-full object-cover shadow-sm"
                                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($testimonial->name) }}&background=EBF8FF&color=2B6CB0';">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($testimonial->name) }}&background=EBF8FF&color=2B6CB0" 
                                     alt="{{ $testimonial->name }}" 
                                     class="w-12 h-12 rounded-full object-cover shadow-sm">
                            @endif
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">{{ $testimonial->name }}</h4>
                                @if($testimonial->title)
                                    <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $testimonial->title }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bottom Row (Left to Right) -->
            @if($testimonials->count() > 3)
            <div class="flex animate-marquee-reverse gap-6 hover:cursor-grab active:cursor-grabbing" style="width: 200%; white-space: nowrap; margin-left: -50%;">
                @php
                    $scrollingItemsReverse = $testimonials->reverse()->merge($testimonials->reverse())->merge($testimonials->reverse());
                @endphp
                @foreach($scrollingItemsReverse as $testimonial)
                    <div class="flex flex-col justify-between p-8 bg-gray-50 border border-gray-100 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 flex-shrink-0" style="width: 350px; white-space: normal;">
                        <div>
                            <svg class="w-8 h-8 text-blue-500 mb-6" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                            <p class="text-gray-700 text-[15px] font-medium leading-relaxed mb-8">
                                {{ $testimonial->quote }}
                            </p>
                        </div>
                        <div class="flex items-center gap-4 mt-auto">
                            @if($testimonial->image)
                                <img src="{{ asset('storage/' . $testimonial->image) }}" 
                                     alt="{{ $testimonial->name }}" 
                                     class="w-12 h-12 rounded-full object-cover shadow-sm"
                                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($testimonial->name) }}&background=EBF8FF&color=2B6CB0';">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($testimonial->name) }}&background=EBF8FF&color=2B6CB0" 
                                     alt="{{ $testimonial->name }}" 
                                     class="w-12 h-12 rounded-full object-cover shadow-sm">
                            @endif
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">{{ $testimonial->name }}</h4>
                                @if($testimonial->title)
                                    <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $testimonial->title }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
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

    <!-- Video Popup Promotion -->
    @if($popupVideo && ($popupVideo->video_path || $popupVideo->external_video_url))
    @php
        $popupUrl = $popupVideo->video_path ? asset('storage/' . $popupVideo->video_path) : $popupVideo->external_video_url;
        $isYouTube = preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $popupUrl, $matches);
        $youtubeId = $isYouTube ? $matches[1] : null;
    @endphp
    <div x-data="{ 
            showPopup: false, 
            isMuted: false,
            isYoutube: {{ $isYouTube ? 'true' : 'false' }},
            init() {
                setTimeout(() => {
                    this.showPopup = true;
                }, 1000);
            },
            closePopup() {
                this.showPopup = false;
                if (!this.isYoutube && this.$refs.promoVideo) {
                    this.$refs.promoVideo.pause();
                }
            },
            toggleMute() {
                this.isMuted = !this.isMuted;
                if (this.$refs.promoVideo) {
                    this.$refs.promoVideo.muted = this.isMuted;
                }
            }
         }" 
         x-show="showPopup"
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="closePopup()"></div>

        <!-- Popup Content -->
        <div class="relative bg-white rounded-3xl overflow-hidden shadow-2xl w-full max-w-3xl transform transition-all"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 scale-90 translate-y-10"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <!-- Close Button -->
            <button @click="closePopup()" class="absolute top-4 right-4 z-20 bg-black/50 text-white p-2 rounded-full hover:bg-black transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>

            <!-- Video Container -->
            <div class="relative bg-black aspect-video">
                <template x-if="showPopup">
                    <div class="w-full h-full">
                        @if($isYouTube)
                            <iframe 
                                class="w-full h-full"
                                src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&mute=1&loop=1&playlist={{ $youtubeId }}&controls=1&rel=0&enablejsapi=1" 
                                title="{{ $popupVideo->title }}" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen>
                            </iframe>
                        @else
                            <video x-ref="promoVideo" 
                                   src="{{ $popupUrl }}" 
                                   class="w-full h-full object-contain"
                                   autoplay 
                                   loop 
                                   playsinline
                                   x-init="$nextTick(() => { 
                                       $el.play().catch(() => {
                                           $el.muted = true;
                                           $el.play();
                                           isMuted = true;
                                       });
                                   })"></video>
                            
                            <!-- Unmute Button (for non-youtube) -->
                            <button @click="toggleMute()" 
                                    class="absolute bottom-6 right-6 z-20 bg-white/20 backdrop-blur-md border border-white/30 text-white px-4 py-2 rounded-full flex items-center gap-2 hover:bg-white/40 transition-all text-[10px] font-bold uppercase tracking-widest">
                                <template x-if="isMuted">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5L6 9H2v6h4l5 4V5z"/><line x1="23" y1="9" x2="17" y2="15"/><line x1="17" y1="9" x2="23" y2="15"/></svg>
                                        <span>Unmute</span>
                                    </div>
                                </template>
                                <template x-if="!isMuted">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5L6 9H2v6h4l5 4V5z"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/></svg>
                                        <span>Mute</span>
                                    </div>
                                </template>
                            </button>
                        @endif
                    </div>
                </template>
            </div>

            <!-- Info Section -->
            <div class="p-6 bg-white flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-center sm:text-left">
                    <h4 class="text-xl font-black text-[#002c5f] uppercase tracking-tight">{{ $popupVideo->title ?? 'Promo Eksklusif' }}</h4>
                    <p class="text-gray-400 text-[10px] uppercase tracking-widest font-bold mt-1">Special Announcement</p>
                </div>
                <a href="{{ route('track.wa') }}" class="bg-[#002c5f] text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/20">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
    @endif

    @livewireScripts
</body>
</html>
