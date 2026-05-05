<?php

namespace App\Livewire;

use App\Models\Lead;
use App\Models\Promo;
use Livewire\Component;

class PromoSection extends Component
{
    public $name;
    public $whatsapp;
    public $email;
    public $showModal = false;
    public $claimedCode = null;

    public function submit()
    {
        $this->validate([
            'name' => 'required|min:3',
            'whatsapp' => 'required|numeric|min:10',
            'email' => 'nullable|email',
        ]);

        $activePromo = Promo::where('is_active', true)
            ->where('end_date', '>', now())
            ->latest()
            ->first();

        if ($activePromo) {
            Lead::create([
                'name' => $this->name,
                'whatsapp' => $this->whatsapp,
                'email' => $this->email,
                'promo_id' => $activePromo->id,
            ]);

            $this->claimedCode = $activePromo->code;
            $this->showModal = true;
        }

        $this->reset(['name', 'whatsapp', 'email']);
    }

    public function render()
    {
        $promo = Promo::where('is_active', true)
            ->where('end_date', '>', now())
            ->latest()
            ->first();

        return view('livewire.promo-section', compact('promo'));
    }
}
