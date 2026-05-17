<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Favicon -->
    @if($logo = \App\Models\Setting::get('site_logo'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $logo) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('assets/images/hyundai_logo.png') }}">
    @endif

    <title>Website Suspended</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full text-center">
        <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200/50 p-10 border border-slate-100">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-slate-900 mb-3">Layanan Ditangguhkan</h1>
            <p class="text-slate-500 mb-8 leading-relaxed">
                Mohon maaf, website ini sementara tidak dapat diakses karena masa aktif layanan telah berakhir atau terdapat kendala administrasi.
            </p>
            
            <div class="space-y-3">
                <a href="https://wa.me/628123456789" class="block w-full bg-slate-900 text-white font-semibold py-4 rounded-2xl hover:bg-slate-800 transition-all active:scale-[0.98]">
                    Hubungi Administrator
                </a>
                <p class="text-xs text-slate-400">
                    AutoShow Pro Central Manager
                </p>
            </div>
        </div>
        
        <p class="mt-8 text-sm text-slate-400">
            &copy; {{ date('Y') }} AutoShow Pro. All rights reserved.
        </p>
    </div>
</body>
</html>
