<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceCategoryResource\Pages;
use App\Filament\Support\LocaleTabs;
use App\Filament\Support\ResourceLabels;
use App\Models\ServiceCategory;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ServiceCategoryResource extends Resource
{
    use ResourceLabels;

    protected static ?string $model = ServiceCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?int $navigationSort = 10;

    protected static function labelKey(): string
    {
        return 'service_categories';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament.sections.general'))->description(__('filament.sections.general_desc'))->schema([
                LocaleTabs::make(fn (string $l) => [
                    TextInput::make("name.$l")->label(__('filament.labels.name'))->required($l === 'ar')->maxLength(150),
                    Textarea::make("description.$l")->label(__('filament.labels.description'))->rows(3),
                ]),
                TextInput::make('slug')->label(__('filament.labels.slug'))->required()->alphaDash()->unique(ignoreRecord: true),
                Toggle::make('is_active')->label(__('filament.labels.is_active'))->default(true),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('filament.labels.name'))->searchable(),
                TextColumn::make('slug')->label(__('filament.labels.slug')),
                TextColumn::make('services_count')->counts('services')->label(__('filament.NavBar.services')),
                ToggleColumn::make('is_active')->label(__('filament.labels.is_active')),
            ])
            ->reorderable('sort')->defaultSort('sort')
            ->recordActions([EditAction::make()->label(__('filament.actions.edit')), DeleteAction::make()->label(__('filament.actions.delete'))->modalDescription(__('filament.actions.delete_confirm'))])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()->label(__('filament.actions.delete'))])])
            ->emptyStateHeading(__('filament.empty.service_categories'))->emptyStateDescription(__('filament.empty.service_categories_desc'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceCategories::route('/'),
            'create' => Pages\CreateServiceCategory::route('/create'),
            'edit' => Pages\EditServiceCategory::route('/{record}/edit'),
        ];
    }
}
