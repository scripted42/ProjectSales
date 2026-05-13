<div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-2xl shadow-gray-200/50 border border-gray-100 h-full flex flex-col justify-between">
    <div class="space-y-6">
        <!-- Car Selector -->
        <div class="space-y-2">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Pilih Unit Kendaraan</label>
            <select wire:model.live="selectedCarId" class="w-full bg-gray-50 border-transparent rounded-2xl p-4 text-sm font-black text-[#002c5f] focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                @foreach($cars as $car)
                    <option value="{{ $car->id }}">{{ $car->name }} - Rp {{ number_format($car->price, 0, ',', '.') }}</option>
                @endforeach
            </select>
        </div>

        <!-- DP Slider -->
        <div class="space-y-4 bg-blue-50/50 p-6 rounded-3xl border border-blue-100/50">
            <div class="flex justify-between items-center">
                <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Uang Muka (DP)</label>
                <span class="text-xs font-black text-blue-700 bg-white px-3 py-1 rounded-xl border border-blue-200 shadow-sm">{{ $dpPercent }}%</span>
            </div>
            <div class="text-2xl font-black text-[#002c5f] tracking-tight">
                Rp {{ number_format($dpAmount, 0, ',', '.') }}
            </div>
            <input type="range" wire:model.live="dpPercent" min="20" max="80" step="5" 
                   class="w-full h-2 bg-blue-100 rounded-lg appearance-none cursor-pointer accent-blue-600">
            <div class="flex justify-between text-[8px] font-bold text-blue-300 uppercase tracking-widest">
                <span>Min 20%</span>
                <span>Max 80%</span>
            </div>
        </div>

        <!-- Tenor & Insurance -->
        <div class="grid grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Tenor</label>
                <select wire:model.live="tenor" class="w-full bg-gray-50 border-transparent rounded-2xl p-4 text-xs font-bold text-[#002c5f] focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                    <option value="12">12 Bln</option>
                    <option value="24">24 Bln</option>
                    <option value="36">36 Bln</option>
                    <option value="48">48 Bln</option>
                    <option value="60">60 Bln</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Asuransi</label>
                <select wire:model.live="insuranceType" class="w-full bg-gray-50 border-transparent rounded-2xl p-4 text-xs font-bold text-[#002c5f] focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                    <option value="all_risk">All Risk</option>
                    <option value="kombinasi">Kombinasi</option>
                    <option value="tlo">TLO</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Result & Button Section -->
    <div class="mt-8 space-y-4">
        <div class="bg-[#002c5f] rounded-3xl p-6 text-center text-white shadow-xl shadow-blue-900/20 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-blue-300 text-[9px] font-black uppercase tracking-[0.2em] mb-1">Estimasi Angsuran</p>
                <h4 class="text-3xl font-black italic">Rp {{ number_format($monthlyInstallment, 0, ',', '.') }}<span class="text-[10px] not-italic text-blue-300 ml-1">/BLN</span></h4>
            </div>
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-white/5 rounded-full"></div>
        </div>

        <a href="https://wa.me/{{ \App\Models\Consultant::first()?->formatted_phone ?? '6281236046363' }}?text={{ urlencode("Halo, saya ingin tanya simulasi kredit unit *{$carName}*.\n\nDetail simulasi:\n- OTR: Rp " . number_format($otr, 0, ',', '.') . "\n- DP: Rp " . number_format($dpAmount, 0, ',', '.') . " ({$dpPercent}%)\n- Tenor: {$tenor} Bulan\n- Angsuran: Rp " . number_format($monthlyInstallment, 0, ',', '.') . "/bln\n- Asuransi: " . strtoupper($insuranceType) . "\n\nBisa bantu hitungkan diskonnya?") }}" 
           target="_blank"
           class="w-full bg-white border-2 border-gray-100 text-[#002c5f] py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-50 transition-all flex items-center justify-center gap-2 group shadow-sm">
            Konsultasi Kredit
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
    </div>
</div>
