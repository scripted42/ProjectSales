<div x-data="{ open: @entangle('showModal') }" class="relative">
    @if($promo)
        <section class="py-12 md:py-16 bg-[#002c5f] text-white overflow-hidden relative">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-500 rounded-full blur-[120px] opacity-10 -mr-64 -mt-64"></div>
            
            <!-- Decorative Promo Image -->
            @if($promo->image)
                <div class="absolute bottom-0 right-0 h-full w-full md:w-1/2 overflow-hidden pointer-events-none z-0">
                    <img src="{{ asset('storage/' . $promo->image) }}" alt="Promo Background" 
                         class="absolute bottom-0 right-0 h-[80%] md:h-[110%] w-auto object-contain object-bottom opacity-90 transform md:-translate-x-12 translate-y-4 select-none">
                </div>
            @endif

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col md:flex-row items-center justify-between gap-12">
                    <div class="max-w-2xl text-center md:text-left">
                        <div class="inline-block bg-blue-600/30 backdrop-blur-md px-4 py-1.5 rounded-full border border-blue-400/20 mb-6">
                            <span class="text-blue-200 text-[10px] font-black uppercase tracking-[0.2em]">Penawaran Terbatas</span>
                        </div>
                        <h3 class="text-4xl md:text-6xl font-bold mb-6 leading-[1.1]">{{ $promo->title }}</h3>
                        <p class="text-lg text-blue-100/70 mb-10 font-light italic">"{{ $promo->hook }}"</p>
                        
                        <div x-data="{
                            expiry: new Date('{{ $promo->end_date->format('Y-m-d H:i:s') }}').getTime(),
                            days: '00', hours: '00', minutes: '00', seconds: '00',
                            update() {
                                let now = new Date().getTime();
                                let distance = this.expiry - now;
                                if (distance < 0) return;
                                this.days = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
                                this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
                                this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
                                this.seconds = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
                            }
                        }" x-init="update(); setInterval(() => update(), 1000)" class="flex justify-center md:justify-start gap-4 mb-12">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 md:w-20 md:h-20 bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 flex items-center justify-center mb-2">
                                    <span class="text-2xl md:text-3xl font-bold" x-text="days"></span>
                                </div>
                                <span class="text-[9px] uppercase tracking-widest opacity-40">Hari</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 md:w-20 md:h-20 bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 flex items-center justify-center mb-2">
                                    <span class="text-2xl md:text-3xl font-bold" x-text="hours"></span>
                                </div>
                                <span class="text-[9px] uppercase tracking-widest opacity-40">Jam</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 md:w-20 md:h-20 bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 flex items-center justify-center mb-2">
                                    <span class="text-2xl md:text-3xl font-bold" x-text="minutes"></span>
                                </div>
                                <span class="text-[9px] uppercase tracking-widest opacity-40">Menit</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 md:w-20 md:h-20 bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 flex items-center justify-center mb-2">
                                    <span class="text-2xl md:text-3xl font-bold" x-text="seconds"></span>
                                </div>
                                <span class="text-[9px] uppercase tracking-widest opacity-40">Detik</span>
                            </div>
                        </div>

                        <button @click="open = true" class="group bg-white text-[#002c5f] px-12 py-5 rounded-full font-black text-lg hover:bg-blue-50 transition-all shadow-2xl flex items-center gap-4 mx-auto md:mx-0 cursor-pointer">
                            AMBIL PROMO
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="group-hover:translate-x-1 transition-transform"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </button>
                    </div>

                </div>
            </div>
        </section>
    @endif

    <!-- Lead Modal -->
    <div x-show="open" 
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/90 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        
        <!-- Backdrop Close Trigger -->
        <div class="absolute inset-0 z-0" @click="open = false; $wire.set('claimedCode', null)"></div>

        <div class="bg-white dark:bg-zinc-900 w-full max-w-lg rounded-[2.5rem] overflow-hidden shadow-[0_0_100px_rgba(0,0,0,0.5)] relative z-10" 
             @click.stop>
            
            <!-- Close Button -->
            <button @click="open = false; $wire.set('claimedCode', null)" 
                    class="absolute top-8 right-8 z-20 text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>

            @if(!$claimedCode)
                <div class="p-12">
                    <div class="mb-10 text-center">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Verifikasi Data</h3>
                        <p class="text-gray-500 text-sm">Lengkapi data untuk mendapatkan kode penawaran.</p>
                    </div>

                    <form wire:submit.prevent="submit" class="space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Nama Lengkap</label>
                            <input type="text" wire:model="name" class="w-full bg-gray-50 text-gray-900 rounded-2xl p-5 text-sm outline-none border-2 border-transparent focus:border-blue-600">
                            @error('name') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">No. WhatsApp</label>
                            <input type="text" wire:model="whatsapp" class="w-full bg-gray-50 text-gray-900 rounded-2xl p-5 text-sm outline-none border-2 border-transparent focus:border-blue-600">
                            @error('whatsapp') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5 pb-4">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Email (Opsional)</label>
                            <input type="email" wire:model="email" class="w-full bg-gray-50 text-gray-900 rounded-2xl p-5 text-sm outline-none border-2 border-transparent focus:border-blue-600">
                        </div>

                        <button type="submit" class="w-full bg-[#002c5f] text-white py-5 rounded-2xl font-black text-sm hover:shadow-xl transition-all cursor-pointer">
                             <span wire:loading.remove>DAPATKAN KODE</span>
                             <span wire:loading>MEMPROSES...</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-2">Berhasil!</h3>
                    <p class="text-gray-500 text-sm mb-10">Salin kode ini dan gunakan saat pemesanan.</p>

                    <div class="bg-gray-50 dark:bg-zinc-800 border-2 border-dashed border-blue-200 p-8 rounded-[2rem] mb-12 relative group" x-data="{ copied: false }">
                        <span class="text-4xl font-black tracking-[0.3em] text-blue-700 dark:text-blue-400">{{ $claimedCode }}</span>
                        <button @click="navigator.clipboard.writeText('{{ $claimedCode }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-white dark:bg-zinc-700 border border-gray-200 px-4 py-1.5 rounded-full text-[10px] font-bold shadow-lg cursor-pointer">
                             <span x-text="copied ? 'TERSALIN!' : 'KLIK UNTUK SALIN'"></span>
                        </button>
                    </div>

                    <button @click="open = false; $wire.set('claimedCode', null)" 
                            class="w-full bg-gray-900 dark:bg-white dark:text-gray-900 text-white py-5 rounded-2xl font-black text-sm cursor-pointer">
                        KEMBALI KE HALAMAN
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
