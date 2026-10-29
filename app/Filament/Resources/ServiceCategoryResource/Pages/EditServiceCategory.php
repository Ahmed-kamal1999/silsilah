<?php

namespace App\Filament\Resources\ServiceCategoryResource\Pages;

use App\Filament\Resources\ServiceCategoryResource;
use App\Filament\Support\TranslatesRecords;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceCategory extends EditRecord
{
    use TranslatesRecords;

    protected static string $resource = ServiceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->label(__('filament.actions.delete'))->modalDescription(__('filament.actions.delete_confirm'))];
    }
}
