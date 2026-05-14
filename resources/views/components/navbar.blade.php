<nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl border-b border-gray-100 py-4 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <div class="flex items-center cursor-pointer" onclick="window.location='{{ url('/') }}'">
            @if($logo = \App\Models\Setting::get('site_logo'))
                <img src="{{ asset('storage/' . $logo) }}" alt="{{ \App\Models\Setting::get('site_name') }}" class="h-6 md:h-10 w-auto">
            @else
                <img src="{{ asset('assets/images/hyundai_logo.png') }}" alt="Hyundai" class="h-6 md:h-10 w-auto">
            @endif
            <span class="ml-2 md:ml-3 font-black text-sm md:text-2xl tracking-[0.2em] text-[#002c5f] uppercase truncate max-w-[120px] md:max-w-none">{{ \App\Models\Setting::get('site_name', 'Hyundai') }}</span>
        </div>
        <div class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-widest text-gray-500">
            <a href="{{ url('/#home') }}" class="hover:text-[#002c5f] transition-colors">Home</a>
            <a href="{{ url('/#models') }}" class="hover:text-[#002c5f] transition-colors">Models</a>
            <a href="{{ url('/#gallery') }}" class="hover:text-[#002c5f] transition-colors">Gallery</a>
            <a href="{{ route('posts.index') }}" class="hover:text-[#002c5f] transition-colors {{ request()->routeIs('posts.*') ? 'text-[#002c5f] font-bold' : '' }}">News</a>
            <a href="{{ url('/#consultant') }}" class="hover:text-[#002c5f] transition-colors">Consultant</a>
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
