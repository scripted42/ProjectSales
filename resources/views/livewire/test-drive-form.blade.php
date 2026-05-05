<div id="booking" class="py-24 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-[2.5rem] p-8 md:p-16 shadow-2xl border border-gray-100">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-5xl font-black text-[#002c5f] mb-4">Booking Test Drive</h2>
                <p class="text-gray-500 font-medium">Rasakan sensasi berkendara dengan Hyundai pilihan Anda. Tentukan jadwalnya sekarang.</p>
                <div class="h-1.5 w-24 bg-[#002c5f] mx-auto rounded-full mt-6"></div>
            </div>

            @if($successMessage)
                <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span class="font-bold">{{ $successMessage }}</span>
                </div>
            @endif

            <form wire:submit.prevent="submit" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Nama Sesuai KTP -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase text-gray-400 tracking-widest ml-1">Nama Lengkap (Sesuai KTP)</label>
                        <input type="text" wire:model="name" placeholder="Masukkan nama lengkap Anda"
                               class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 text-sm focus:border-[#002c5f] focus:bg-white focus:ring-0 transition-all">
                        @error('name') <span class="text-red-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase text-gray-400 tracking-widest ml-1">Nomor WhatsApp</label>
                        <input type="tel" wire:model="phone" placeholder="Contoh: 0812xxxxxxxx"
                               class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 text-sm focus:border-[#002c5f] focus:bg-white focus:ring-0 transition-all">
                        @error('phone') <span class="text-red-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase text-gray-400 tracking-widest ml-1">Email</label>
                        <input type="email" wire:model="email" placeholder="Masukkan alamat email Anda"
                               class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 text-sm focus:border-[#002c5f] focus:bg-white focus:ring-0 transition-all">
                        @error('email') <span class="text-red-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Pilih Produk -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase text-gray-400 tracking-widest ml-1">Pilih Unit Mobil</label>
                        <select wire:model="car_id" 
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 text-sm focus:border-[#002c5f] focus:bg-white focus:ring-0 transition-all">
                            <option value="">Pilih Unit Hyundai</option>
                            @foreach($cars as $car)
                                <option value="{{ $car->id }}">{{ $car->name }}</option>
                            @endforeach
                        </select>
                        @error('car_id') <span class="text-red-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Booking -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-xs font-black uppercase text-gray-400 tracking-widest ml-1">Rencana Tanggal Test Drive</label>
                        <input type="date" wire:model="booking_date"
                               class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 text-sm focus:border-[#002c5f] focus:bg-white focus:ring-0 transition-all">
                        @error('booking_date') <span class="text-red-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-xs font-black uppercase text-gray-400 tracking-widest ml-1">Catatan Tambahan (Opsional)</label>
                        <textarea wire:model="notes" rows="3" placeholder="Berikan catatan jika ada permintaan khusus"
                                  class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 text-sm focus:border-[#002c5f] focus:bg-white focus:ring-0 transition-all"></textarea>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" 
                            class="w-full bg-[#002c5f] text-white py-5 rounded-[1.5rem] font-black uppercase tracking-widest hover:bg-blue-900 transition-all shadow-xl hover:shadow-2xl flex items-center justify-center gap-3 group">
                        <span>Konfirmasi Booking</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="group-hover:translate-x-1 transition-transform"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                    <p class="text-center text-[10px] text-gray-400 mt-6 uppercase tracking-widest">Data Anda aman dan hanya akan digunakan untuk keperluan konfirmasi test drive.</p>
                </div>
            </form>
        </div>
    </div>
</div>
