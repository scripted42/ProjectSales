<?php

namespace App\Filament\Resources\TestDriveBookingResource\Pages;

use App\Filament\Resources\TestDriveBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestDriveBookings extends ListRecords
{
    protected static string $resource = TestDriveBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
