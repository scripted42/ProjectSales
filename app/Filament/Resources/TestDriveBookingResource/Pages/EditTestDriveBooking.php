<?php

namespace App\Filament\Resources\TestDriveBookingResource\Pages;

use App\Filament\Resources\TestDriveBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestDriveBooking extends EditRecord
{
    protected static string $resource = TestDriveBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
