<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Support\ResourceLabels;
use App\Models\Client;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    use ResourceLabels;

    protected static ?string $model = Client::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?int $navigationSort = 30;

    protected static function labelKey(): string
    {
        return 'clients';
    }

    protected static function navGroup(): string
    {
        return 'group_site';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament.sections.general'))->description(__('filament.sections.general_desc'))->schema([
                TextInput::make('name')->label(__('filament.labels.name'))->required()->maxLength(150),
                TextInput::make('url')->label(__('filament.labels.url'))->url()->maxLength(255),
                Toggle::make('is_active')->label(__('filament.labels.is_active'))->default(true),
            ])->columnSpanFull(),
            Section::make(__('filament.sections.media'))->description(__('filament.sections.media_desc'))->schema([
                SpatieMediaLibraryFileUpload::make('logo')->label(__('filament.labels.logo'))->collection('logo')->image()
                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml']),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('logo')->label(__('filament.labels.logo'))->collection('logo'),
                TextColumn::make('name')->label(__('filament.labels.name'))->searchable(),
                ToggleColumn::make('is_active')->label(__('filament.labels.is_active')),
            ])
            ->reorderable('sort')->defaultSort('sort')
            ->recordActions([EditAction::make()->label(__('filament.actions.edit')), DeleteAction::make()->label(__('filament.actions.delete'))->modalDescription(__('filament.actions.delete_confirm'))])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()->label(__('filament.actions.delete'))])])
            ->emptyStateHeading(__('filament.empty.clients'))->emptyStateDescription(__('filament.empty.clients_desc'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
