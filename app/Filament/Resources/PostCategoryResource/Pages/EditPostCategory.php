<?php

namespace App\Filament\Resources\PostCategoryResource\Pages;

use App\Filament\Resources\PostCategoryResource;
use App\Filament\Support\TranslatesRecords;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPostCategory extends EditRecord
{
    use TranslatesRecords;

    protected static string $resource = PostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->label(__('filament.actions.delete'))->modalDescription(__('filament.actions.delete_confirm'))];
    }
}
