<?php

namespace App\Filament\Resources\StatResource\Pages;

use App\Filament\Resources\StatResource;
use App\Filament\Support\TranslatesRecords;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStat extends EditRecord
{
    use TranslatesRecords;

    protected static string $resource = StatResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->label(__('filament.actions.delete'))->modalDescription(__('filament.actions.delete_confirm'))];
    }
}
