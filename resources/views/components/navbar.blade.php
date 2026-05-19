<nav x-data="{ mobileOpen: false }" class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-xl border-b border-gray-100 py-4 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center cursor-pointer select-none" onclick="window.location='{{ url('/') }}'">
            @if($logo = \App\Models\Setting::get('site_logo'))
                <img src="{{ asset('storage/' . $logo) }}" alt="{{ \App\Models\Setting::get('site_name') }}" class="h-6 md:h-10 w-auto">
            @else
                <img src="{{ asset('assets/images/hyundai_logo.png') }}" alt="Hyundai" class="h-6 md:h-10 w-auto">
            @endif
            <span class="ml-2 md:ml-3 font-black text-sm md:text-2xl tracking-[0.2em] text-[#002c5f] uppercase truncate max-w-[120px] md:max-w-none">{{ \App\Models\Setting::get('site_name', 'Hyundai') }}</span>
        </div>

        @php
            $navCars = \App\Models\Car::where('is_available', true)->get()->groupBy('category');
            $categoryLabels = [
                'MPV' => 'MPV',
                'SUV' => 'SUV',
                'EV' => 'Electric Vehicle',
                'Crossover' => 'Crossover',
                'Luxury MPV' => 'Luxury MPV'
            ];
        @endphp

        <!-- Desktop Navigation Menu -->
        <div class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-widest text-gray-500 items-center">
            <a href="{{ url('/#home') }}" class="hover:text-[#002c5f] transition-colors">Home</a>
            
            <!-- Dynamic Mega Menu (positioned relative to navbar) -->
            <div class="py-2" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button @click="open = !open" class="flex items-center gap-1 hover:text-[#002c5f] transition-colors focus:outline-none uppercase tracking-widest text-sm font-semibold">
                    <span>Models</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="transition-transform duration-300" :class="open ? 'rotate-180 text-[#002c5f]' : ''"><path d="m6 9 6 6 6-6"/></svg>
                </button>

                <!-- Mega Menu Content Panel -->
                <div x-show="open" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-350"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-250"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-4"
                     class="absolute left-0 right-0 top-full w-full pt-4 z-50">
                     
                     <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                         <div class="bg-white border border-gray-100 rounded-[2rem] shadow-2xl p-10 grid grid-cols-5 gap-8">
                             @foreach($categoryLabels as $key => $label)
                                @php $categoryCars = $navCars->get($key); @endphp
                                <div class="flex flex-col">
                                    <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-4 pb-2 border-b border-gray-100">{{ $label }}</h4>
                                    <ul class="space-y-2">
                                        @if($categoryCars && $categoryCars->count() > 0)
                                            @foreach($categoryCars as $car)
                                                <li>
                                                    <a href="{{ route('car.show', $car->slug) }}" class="group flex flex-col hover:bg-blue-50/50 p-2 -mx-2 rounded-xl transition-all">
                                                        <span class="text-xs font-bold text-gray-900 group-hover:text-[#002c5f] transition-colors">{{ $car->name }}</span>
                                                        <span class="text-[9px] text-gray-400 font-semibold mt-0.5">Mulai Rp {{ number_format($car->price/1000000, 0, ',', '.') }} Jt</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="text-[9px] text-gray-400 italic px-1">Segera Hadir</li>
                                        @endif
                                    </ul>
                                </div>
                             @endforeach
                         </div>
                     </div>
                </div>
            </div>

            <a href="{{ route('pricelist') }}" class="hover:text-[#002c5f] transition-colors {{ request()->routeIs('pricelist') ? 'text-[#002c5f] font-bold' : '' }}">Pricelist</a>
            <a href="{{ url('/#gallery') }}" class="hover:text-[#002c5f] transition-colors">Gallery</a>
            <a href="{{ route('posts.index') }}" class="hover:text-[#002c5f] transition-colors {{ request()->routeIs('posts.*') ? 'text-[#002c5f] font-bold' : '' }}">News</a>
            <a href="{{ url('/#consultant') }}" class="hover:text-[#002c5f] transition-colors">Consultant</a>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center space-x-3 md:space-x-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/admin') }}" class="hidden md:inline-block text-sm font-medium text-blue-600 hover:underline">Admin</a>
                @else
                    <a href="{{ route('login') }}" class="hidden md:inline-block text-sm font-medium text-gray-400 hover:text-[#002c5f] transition-colors">Login</a>
                @endauth
            @endif
            
            <a href="{{ route('track.wa') }}" class="bg-[#002c5f] text-white px-4 md:px-6 py-2 md:py-2.5 rounded-full text-[10px] md:text-sm font-bold hover:bg-blue-800 transition-all shadow-lg whitespace-nowrap">
                Hubungi Kami
            </a>

            <!-- Mobile Hamburger Toggle -->
            <button @click="mobileOpen = !mobileOpen" class="md:hidden flex items-center justify-center p-2 text-gray-500 hover:text-[#002c5f] focus:outline-none rounded-xl hover:bg-gray-50 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-300" :class="mobileOpen ? 'rotate-90 text-[#002c5f]' : ''">
                    <path x-show="!mobileOpen" d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round"/>
                    <path x-show="mobileOpen" d="M18 6 6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" x-cloak/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer Panel (Slide-down with Blur) -->
    <div x-show="mobileOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden absolute top-full left-0 w-full bg-white/95 backdrop-blur-2xl border-b border-gray-100 shadow-2xl p-6 z-40 max-h-[85vh] overflow-y-auto">
         
         <div class="space-y-6">
             <div class="flex flex-col space-y-3 text-sm font-bold uppercase tracking-wider text-gray-500">
                 <a href="{{ url('/#home') }}" @click="mobileOpen = false" class="hover:text-[#002c5f] transition-colors py-2 border-b border-gray-50">Home</a>
                 
                 <!-- Mobile Accordion for Models -->
                 <div x-data="{ expanded: false }" class="py-2 border-b border-gray-50">
                     <button @click="expanded = !expanded" class="w-full flex justify-between items-center uppercase tracking-wider font-bold text-gray-500 hover:text-[#002c5f] focus:outline-none">
                         <span>Models</span>
                         <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-300" :class="expanded ? 'rotate-180 text-[#002c5f]' : ''"><path d="m6 9 6 6 6-6"/></svg>
                     </button>
                     
                     <div x-show="expanded" x-cloak class="mt-4 pl-3 space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                         @foreach($categoryLabels as $key => $label)
                             @php $categoryCars = $navCars->get($key); @endphp
                             <div class="border-l-2 border-gray-100 pl-3">
                                 <h5 class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-2">{{ $label }}</h5>
                                 <div class="grid grid-cols-2 gap-2">
                                     @if($categoryCars && $categoryCars->count() > 0)
                                         @foreach($categoryCars as $car)
                                             <a href="{{ route('car.show', $car->slug) }}" @click="mobileOpen = false" class="bg-gray-50 hover:bg-blue-50/50 p-2.5 rounded-xl flex flex-col">
                                                 <span class="text-[11px] font-bold text-gray-900">{{ $car->name }}</span>
                                                 <span class="text-[8px] text-gray-400 font-semibold mt-0.5">Rp {{ number_format($car->price/1000000, 0, ',', '.') }} Jt</span>
                                             </a>
                                         @endforeach
                                     @else
                                         <span class="text-[9px] text-gray-400 italic">Segera Hadir</span>
                                     @endif
                                 </div>
                             </div>
                         @endforeach
                     </div>
                 </div>

                  <a href="{{ route('pricelist') }}" @click="mobileOpen = false" class="hover:text-[#002c5f] transition-colors py-2 border-b border-gray-50 {{ request()->routeIs('pricelist') ? 'text-[#002c5f] font-bold' : '' }}">Pricelist</a>
                  <a href="{{ url('/#gallery') }}" @click="mobileOpen = false" class="hover:text-[#002c5f] transition-colors py-2 border-b border-gray-50">Gallery</a>
                 <a href="{{ route('posts.index') }}" @click="mobileOpen = false" class="hover:text-[#002c5f] transition-colors py-2 border-b border-gray-50 {{ request()->routeIs('posts.*') ? 'text-[#002c5f] font-bold' : '' }}">News</a>
                 <a href="{{ url('/#consultant') }}" @click="mobileOpen = false" class="hover:text-[#002c5f] transition-colors py-2">Consultant</a>
             </div>
             
             @if (Route::has('login'))
                 <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                     @auth
                         <a href="{{ url('/admin') }}" @click="mobileOpen = false" class="text-xs font-bold text-blue-600 uppercase tracking-wider">Admin Panel</a>
                     @else
                         <a href="{{ route('login') }}" @click="mobileOpen = false" class="text-xs font-bold text-gray-400 hover:text-[#002c5f] transition-colors uppercase tracking-wider">Login</a>
                     @endauth
                 </div>
             @endif
         </div>
    </div>
</nav>

<script>
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('nav');
        if (nav) {
            if (window.scrollY > 50) {
                nav.classList.add('shadow-lg', 'py-2');
                nav.classList.remove('py-4');
            } else {
                nav.classList.remove('shadow-lg', 'py-2');
                nav.classList.add('py-4');
            }
        }
    });
</script>
