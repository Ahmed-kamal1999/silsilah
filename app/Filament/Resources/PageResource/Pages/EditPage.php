<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Filament\Support\TranslatesRecords;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    use TranslatesRecords;

    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
