<?php

namespace App\Filament\Resources\FormResponses\Pages;

use App\Filament\Resources\FormResponses\FormResponseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFormResponse extends ViewRecord
{
    protected static string $resource = FormResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
