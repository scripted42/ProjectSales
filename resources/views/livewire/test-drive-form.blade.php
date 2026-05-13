<div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-2xl shadow-gray-200/50 border border-gray-100 h-full flex flex-col justify-between">
    <div class="space-y-6">
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
            class="mt-8 w-full bg-[#002c5f] text-white py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-blue-800 transition-all shadow-xl shadow-blue-900/20 flex items-center justify-center gap-3 group">
        Konfirmasi Booking
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </button>
</div>
