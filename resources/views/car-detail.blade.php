<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
</head>
<body class="bg-white text-gray-900 antialiased">
    
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl border-b border-gray-100 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <a href="/" class="flex items-center text-[#002c5f] font-bold text-sm uppercase tracking-widest hover:opacity-70 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m15 18-6-6 6-6"/></svg>
                Kembali
            </a>
            <div class="text-sm font-black text-gray-400 uppercase tracking-widest">{{ $car->category }}</div>
        </div>
    </nav>

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
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-[#002c5f] mb-4">{{ $car->name }}</h2>
                <div class="flex items-center gap-4 mb-8">
                    <span class="bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest">
                        {{ $car->category }}
                    </span>
                    <span class="text-2xl font-extrabold text-[#002c5f]">
                        Rp {{ number_format($car->price, 0, ',', '.') }}
                    </span>
                </div>

                <div class="prose max-w-none mb-12 text-gray-500 leading-relaxed">
                    {!! $car->description !!}
                </div>

                <!-- Features -->
                @if($car->features && count($car->features) > 0)
                <div class="mb-12">
                    <h3 class="text-lg font-black mb-6 uppercase tracking-widest text-gray-900 border-b border-gray-100 pb-3">Key Features</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($car->features as $feature)
                            <div class="flex items-center text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 mr-3 flex-shrink-0"><path d="M20 6 9 17l-5-5"/></svg>
                                {{ $feature }}
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div x-data="{ promoCode: '' }" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 tracking-widest">Punya Kode Promo?</label>
                        <input type="text" x-model="promoCode" placeholder="Masukkan kode promo di sini..." 
                               class="w-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-4 text-sm focus:border-blue-500 focus:ring-0 transition-all uppercase font-mono tracking-widest">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a :href="'https://api.whatsapp.com/send/?phone={{ $consultant->formatted_phone ?? '6281330135013' }}&text=' + encodeURIComponent('Halo, saya tertarik dengan unit {{ $car->name }}.' + (promoCode ? ' Saya memiliki kode promo: ' + promoCode : ''))" 
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
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 py-12 border-t border-gray-100 mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} Hyundai Showroom. Semua Hak Dilindungi.
        </div>
    </footer>

    @livewireScripts
</body>
</html>
