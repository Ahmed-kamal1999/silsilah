<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use Filament\Resources\Pages\ViewRecord;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        // Opening a message marks it as read.
        if (! $this->getRecord()->read_at) {
            $this->getRecord()->update(['read_at' => now()]);
        }
    }
}
