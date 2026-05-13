<?php

namespace App\Livewire;

use App\Models\Car;
use Livewire\Component;

class CreditCalculator extends Component
{
    public $cars;
    public $selectedCarId = null;
    public $otr = 0;
    public $carName = '';
    public $dpPercent = 20;
    public $tenor = 36;
    public $insuranceType = 'all_risk';
    
    public $dpAmount = 0;
    public $monthlyInstallment = 0;

    public function mount($car_id = null)
    {
        $this->cars = Car::where('is_available', true)->orderBy('price', 'asc')->get();
        
        if ($car_id) {
            $this->selectedCarId = $car_id;
        } elseif ($this->cars->count() > 0) {
            $this->selectedCarId = $this->cars->first()->id;
        }

        $this->updateCarData();
    }

    public function updatedSelectedCarId()
    {
        $this->updateCarData();
    }

    public function updated($propertyName)
    {
        $this->calculate();
    }

    public function updateCarData()
    {
        $car = Car::find($this->selectedCarId);
        if ($car) {
            $this->otr = $car->price;
            $this->carName = $car->name;
            $this->calculate();
        }
    }

    public function calculate()
    {
        $this->dpAmount = ($this->dpPercent / 100) * $this->otr;
        $principal = $this->otr - $this->dpAmount;
        
        $interestRate = match(true) {
            $this->tenor <= 12 => 0.03,
            $this->tenor <= 24 => 0.04,
            $this->tenor <= 36 => 0.05,
            $this->tenor <= 48 => 0.06,
            default => 0.07,
        };

        $years = $this->tenor / 12;
        $totalInterest = $principal * $interestRate * $years;
        $totalLoan = $principal + $totalInterest;
        
        $this->monthlyInstallment = $this->tenor > 0 ? $totalLoan / $this->tenor : 0;
    }

    public function render()
    {
        return view('livewire.credit-calculator');
    }
}
