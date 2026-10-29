<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatResource\Pages;
use App\Filament\Support\LocaleTabs;
use App\Filament\Support\ResourceLabels;
use App\Models\Stat;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StatResource extends Resource
{
    use ResourceLabels;

    protected static ?string $model = Stat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?int $navigationSort = 32;

    protected static function labelKey(): string
    {
        return 'stats';
    }

    protected static function navGroup(): string
    {
        return 'group_site';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament.sections.general'))->description(__('filament.sections.general_desc'))->schema([
                LocaleTabs::make(fn (string $l) => [
                    TextInput::make("label.$l")->label(__('filament.labels.label'))->required($l === 'ar')->maxLength(100),
                ]),
                TextInput::make('value')->label(__('filament.labels.value'))->numeric()->minValue(0)->required(),
                TextInput::make('suffix')->label(__('filament.labels.suffix'))->maxLength(8),
            ])->columns(2)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')->label(__('filament.labels.label')),
                TextColumn::make('value')->label(__('filament.labels.value'))->numeric(),
                TextColumn::make('suffix')->label(__('filament.labels.suffix')),
            ])
            ->reorderable('sort')->defaultSort('sort')
            ->recordActions([EditAction::make()->label(__('filament.actions.edit')), DeleteAction::make()->label(__('filament.actions.delete'))->modalDescription(__('filament.actions.delete_confirm'))])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()->label(__('filament.actions.delete'))])])
            ->emptyStateHeading(__('filament.empty.stats'))->emptyStateDescription(__('filament.empty.stats_desc'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStats::route('/'),
            'create' => Pages\CreateStat::route('/create'),
            'edit' => Pages\EditStat::route('/{record}/edit'),
        ];
    }
}
