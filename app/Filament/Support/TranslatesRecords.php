<?php

namespace App\Filament\Support;

/**
 * Lets Filament forms bind to spatie/laravel-translatable attributes as {ar, en} arrays.
 * Without this the form would only see the current locale's string.
 */
trait TranslatesRecords
{
    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->getRecord()->getTranslatableAttributes() as $attribute) {
            $data[$attribute] = $this->getRecord()->getTranslations($attribute);
        }

        return $data;
    }
}
