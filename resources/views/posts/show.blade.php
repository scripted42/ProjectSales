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

    <title>{{ $post->title }} - Hyundai Showroom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #ffffff; }
        .prose img { border-radius: 2rem; margin: 3rem 0; box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.12); }
        .prose h2 { font-weight: 800; color: #002c5f; margin-top: 3.5rem; font-size: 1.75rem; line-height: 1.2; letter-spacing: -0.02em; }
        .prose p { margin-bottom: 2rem; color: #374151; line-height: 2; font-weight: 300; font-size: 1.1rem; }
        .prose blockquote { border-left: 4px solid #002c5f; padding-left: 2rem; font-style: italic; color: #1f2937; font-size: 1.3rem; margin: 3rem 0; font-weight: 500; }
        
        .article-shadow { box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.08); }
        
        @media (min-width: 1024px) {
            .proper-grid { display: grid; grid-template-columns: 1fr 380px; gap: 5rem; align-items: flex-start; }
            .proper-sidebar { position: sticky; top: 120px; }
        }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased overflow-x-hidden pt-24">
    
    <x-navbar />

    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Article Header -->
            <div class="max-w-4xl mb-12">
                <div class="flex items-center gap-4 mb-6">
                    <span class="bg-[#002c5f] text-white text-[10px] font-black px-4 py-1.5 rounded-lg uppercase tracking-[0.15em]">
                        {{ $post->category }}
                    </span>
                    <span class="text-gray-400 text-[10px] uppercase tracking-[0.15em] font-bold">
                        Published on {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                    </span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 leading-tight tracking-tight mb-8">{{ $post->title }}</h1>
            </div>

            <!-- Proper Layout Grid -->
            <div class="proper-grid">
                
                <!-- Column 1: News Content -->
                <div class="article-content">
                    @if($post->image)
                    <div class="mb-12 rounded-[2.5rem] overflow-hidden article-shadow">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover">
                    </div>
                    @endif

                    <div class="prose prose-xl max-w-none">
                        {!! $post->content !!}
                    </div>

                    <!-- Share & Navigation -->
                    <div class="mt-20 pt-10 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-8">
                        <div class="flex items-center gap-6">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Share Insight</span>
                            <div class="flex gap-3">
                                <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-600 hover:text-white transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                                </a>
                            </div>
                        </div>
                        <a href="{{ route('posts.index') }}" class="group flex items-center gap-4 text-xs font-black uppercase tracking-widest text-[#002c5f]">
                            <span class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center group-hover:bg-[#002c5f] group-hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            </span>
                            Semua Berita
                        </a>
                    </div>
                </div>

                <!-- Column 2: Sticky Sidebar -->
                <aside class="proper-sidebar space-y-24">
                    
                    <!-- Consultant Expert Card -->
                    <div class="bg-white border border-gray-100 rounded-[2rem] p-8 text-gray-900 relative overflow-hidden shadow-xl shadow-gray-100">
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="{{ $consultant->photo ? asset('storage/'.$consultant->photo) : '' }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-gray-100 shadow-sm">
                                <div>
                                    <h4 class="font-bold text-lg leading-tight">{{ $consultant->name ?? 'Sales Expert' }}</h4>
                                    <p class="text-blue-600 text-[9px] font-black uppercase tracking-[0.15em]">Hyundai Expert</p>
                                </div>
                            </div>
                            <p class="text-gray-500 text-xs font-light mb-8 leading-relaxed italic">"Punya pertanyaan seputar unit ini? Mari kita diskusikan solusi terbaik untuk mobilitas keluarga Anda."</p>
                            <a href="{{ route('track.wa', ['text' => 'Halo, saya sedang membaca artikel ' . $post->title . ' dan ingin bertanya...']) }}" class="block w-full bg-[#002c5f] text-white text-center py-4 rounded-xl font-black hover:bg-blue-800 transition-all text-sm shadow-lg shadow-blue-900/20">
                                Konsultasi Gratis
                            </a>
                        </div>
                    </div>

                    <!-- Related Stories -->
                    <div class="mt-12">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-900 mb-8 flex items-center gap-3">
                            <span class="w-8 h-1 bg-[#002c5f] rounded-full"></span>
                            Trending News
                        </h4>
                        <div class="space-y-8">
                            @foreach($relatedPosts as $related)
                                <a href="{{ route('posts.show', $related->slug) }}" class="group block">
                                    <div class="flex gap-4">
                                        <div class="w-20 h-20 flex-shrink-0 rounded-2xl overflow-hidden bg-gray-100">
                                            <img src="{{ $related->image ? asset('storage/'.$related->image) : asset('assets/images/no-image.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        </div>
                                        <div class="flex flex-col justify-center">
                                            <span class="text-[8px] font-black text-blue-600 uppercase tracking-[0.1em] mb-1">{{ $related->category }}</span>
                                            <h5 class="text-xs font-bold leading-snug group-hover:text-[#002c5f] transition-colors line-clamp-2">{{ $related->title }}</h5>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                </aside>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex justify-center items-center mb-8">
                <img src="{{ asset('assets/images/hyundai_logo.png') }}" alt="Hyundai" class="h-6 w-auto opacity-50">
                <span class="ml-3 font-black text-sm tracking-[0.2em] text-gray-300 uppercase">Hyundai Showroom</span>
            </div>
            <p class="text-gray-400 text-[9px] font-bold uppercase tracking-[0.15em] mb-8">&copy; {{ date('Y') }} Premium Sales Portal. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>