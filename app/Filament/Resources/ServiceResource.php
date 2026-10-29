<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Support\LocaleTabs;
use App\Filament\Support\ResourceLabels;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    use ResourceLabels;

    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?int $navigationSort = 11;

    protected static function labelKey(): string
    {
        return 'services';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament.sections.content'))->description(__('filament.sections.content_desc'))->schema([
                LocaleTabs::make(fn (string $l) => [
                    TextInput::make("title.$l")->label(__('filament.labels.title'))->required($l === 'ar')->maxLength(200)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, $set, $get) use ($l) {
                            if ($l === 'en' && blank($get('slug'))) {
                                $set('slug', Str::slug((string) $state));
                            }
                        }),
                    Textarea::make("excerpt.$l")->label(__('filament.labels.excerpt'))->rows(3)->maxLength(400),
                    RichEditor::make("body.$l")->label(__('filament.labels.body')),
                    Textarea::make("deliverables.$l")->label(__('filament.labels.deliverables'))->helperText(__('filament.labels.one_per_line'))->rows(5),
                    Textarea::make("methodology.$l")->label(__('filament.labels.methodology'))->helperText(__('filament.labels.one_per_line'))->rows(5),
                ]),
            ])->columnSpanFull(),
            Section::make(__('filament.sections.general'))->description(__('filament.sections.general_desc'))->schema([
                Select::make('category_id')->label(__('filament.labels.category'))->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)->required()->preload(),
                TextInput::make('slug')->label(__('filament.labels.slug'))->required()->alphaDash()->unique(ignoreRecord: true),
                TextInput::make('icon')->label(__('filament.labels.icon'))->maxLength(40),
                Toggle::make('is_active')->label(__('filament.labels.is_active'))->default(true),
            ])->columns(2)->columnSpanFull(),
            Section::make(__('filament.sections.media'))->description(__('filament.sections.media_desc'))->schema([
                SpatieMediaLibraryFileUpload::make('image')->label(__('filament.labels.image'))->collection('image')->image()->imageEditor(),
            ])->columnSpanFull(),
            Section::make(__('filament.sections.seo'))->description(__('filament.sections.seo_desc'))->schema([
                LocaleTabs::make(fn (string $l) => [
                    TextInput::make("seo.$l.title")->label(__('filament.labels.seo_title'))->maxLength(70),
                    Textarea::make("seo.$l.description")->label(__('filament.labels.seo_description'))->rows(2)->maxLength(170),
                ]),
            ])->collapsed()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label(__('filament.labels.title'))->searchable()->limit(50),
                TextColumn::make('category.name')->label(__('filament.labels.category')),
                ToggleColumn::make('is_active')->label(__('filament.labels.is_active')),
            ])
            ->reorderable('sort')->defaultSort('sort')
            ->filters([SelectFilter::make('category_id')->label(__('filament.labels.category'))->relationship('category', 'name')
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)])
            ->recordActions([EditAction::make()->label(__('filament.actions.edit')), DeleteAction::make()->label(__('filament.actions.delete'))->modalDescription(__('filament.actions.delete_confirm'))])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()->label(__('filament.actions.delete'))])])
            ->emptyStateHeading(__('filament.empty.services'))->emptyStateDescription(__('filament.empty.services_desc'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
