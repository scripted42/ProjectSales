<div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-2xl shadow-gray-200/50 border border-gray-100 h-full flex flex-col justify-between">
    <div class="space-y-6">
        @if($successMessage)
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl text-xs font-bold flex items-center gap-3 animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ $successMessage }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Nama Lengkap</label>
                <input type="text" wire:model="name" placeholder="Sesuai KTP" 
                       class="w-full bg-gray-50 border-transparent rounded-2xl p-4 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Nomor WhatsApp</label>
                <input type="tel" wire:model="phone" placeholder="0812xxxx" 
                       class="w-full bg-gray-50 border-transparent rounded-2xl p-4 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Pilih Unit Mobil</label>
                <select wire:model="car_id" class="w-full bg-gray-50 border-transparent rounded-2xl p-4 text-sm font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                    <option value="">-- Pilih Unit --</option>
                    @foreach($cars as $car)
                        <option value="{{ $car->id }}">{{ $car->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Rencana Tanggal</label>
                <input type="date" wire:model="booking_date" 
                       class="w-full bg-gray-50 border-transparent rounded-2xl p-4 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Catatan Tambahan (Opsional)</label>
            <textarea wire:model="notes" placeholder="Tulis permintaan khusus Anda di sini..." 
                      class="w-full bg-gray-50 border-transparent rounded-2xl p-4 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all h-24 shadow-sm"></textarea>
        </div>
    </div>

    <button wire:click="submit" 
            wire:loading.attr="disabled"
            class="mt-8 w-full bg-[#002c5f] text-white py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-blue-800 transition-all shadow-xl shadow-blue-900/20 flex items-center justify-center gap-3 group disabled:opacity-70 disabled:cursor-not-allowed cursor-pointer">
        <span wire:loading.remove>Konfirmasi Booking</span>
        <span wire:loading>Memproses...</span>
        <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        <svg wire:loading class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg>
    </button>
</div>
