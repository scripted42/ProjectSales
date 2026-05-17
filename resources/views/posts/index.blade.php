<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Favicon -->
    @if($logo = \App\Models\Setting::get('site_logo'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $logo) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('assets/images/hyundai_logo.png') }}">
    @endif

    <title>News & Insights - Hyundai Showroom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="bg-white text-gray-900 antialiased overflow-x-hidden pt-24">

    <x-navbar />

    <header class="py-24 bg-gray-50 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-[#002c5f] rounded-full blur-3xl"></div>
            <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-[#002c5f] rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h1 class="text-5xl md:text-7xl font-black text-gray-900 mb-8 tracking-tight">Auto Insight & Updates</h1>
            <p class="text-gray-500 max-w-2xl mx-auto font-light text-lg leading-relaxed">Jelajahi dunia otomotif
                melalui kacamata Hyundai. Dapatkan tips eksklusif, berita teknologi terbaru, dan penawaran yang
                dipersonalisasi.</p>
            <div class="h-2 w-24 bg-[#002c5f] mx-auto rounded-full mt-10"></div>
        </div>
    </header>

    <main class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($posts as $post)
                    <article
                        class="group bg-white rounded-[2.5rem] overflow-hidden border border-gray-100 hover:border-blue-100 hover:shadow-[0_32px_64px_-12px_rgba(0,0,0,0.1)] transition-all duration-700">
                        <a href="{{ route('posts.show', $post->slug) }}" class="block overflow-hidden aspect-[16/10]">
                            @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="mb-2">
                                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                        <circle cx="9" cy="9" r="2" />
                                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                    </svg>
                                </div>
                            @endif
                        </a>
                        <div class="p-10">
                            <div class="flex items-center gap-4 mb-6">
                                <span
                                    class="bg-blue-50 text-[#002c5f] text-[10px] font-black px-4 py-1.5 rounded-lg uppercase tracking-widest">
                                    {{ $post->category }}
                                </span>
                                <span class="text-gray-400 text-[10px] uppercase tracking-widest font-bold">
                                    {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}
                                </span>
                            </div>
                            <h3
                                class="text-2xl font-bold mb-6 line-clamp-2 group-hover:text-[#002c5f] transition-colors leading-tight">
                                <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            <p class="text-gray-500 text-sm line-clamp-3 mb-8 font-light leading-relaxed">
                                {{ Str::limit(strip_tags($post->content), 140) }}
                            </p>
                            <a href="{{ route('posts.show', $post->slug) }}"
                                class="inline-flex items-center text-xs font-black text-gray-900 group-hover:text-[#002c5f] gap-2 uppercase tracking-widest">
                                Read Full Story
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round" class="transition-transform group-hover:translate-x-2">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-20">
                {{ $posts->links() }}
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#002c5f] text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex justify-center items-center mb-10">
                <img src="{{ asset('assets/images/hyundai_logo.png') }}" alt="Hyundai"
                    class="h-8 w-auto brightness-0 invert">
                <span class="ml-3 font-black text-xl tracking-[0.2em] uppercase">Hyundai</span>
            </div>
            <p class="text-blue-200 text-xs mb-10 tracking-[0.2em] uppercase font-bold">&copy; {{ date('Y') }} Hyundai
                Dealer. All Rights Reserved.</p>
            <div class="flex justify-center gap-10">
                <a href="/" class="text-sm font-bold text-blue-100 hover:text-white transition-colors">Home</a>
                <a href="/#models" class="text-sm font-bold text-blue-100 hover:text-white transition-colors">Models</a>
                <a href="{{ route('posts.index') }}"
                    class="text-sm font-bold text-white transition-colors underline underline-offset-8 decoration-2">News</a>
            </div>
        </div>
    </footer>

</body>

</html>