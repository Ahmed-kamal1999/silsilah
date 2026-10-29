<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Support\LocaleTabs;
use App\Filament\Support\ResourceLabels;
use App\Models\Page;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PageResource extends Resource
{
    use ResourceLabels;

    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?int $navigationSort = 33;

    protected static function labelKey(): string
    {
        return 'pages';
    }

    protected static function navGroup(): string
    {
        return 'group_site';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament.sections.content'))->description(__('filament.sections.content_desc'))->schema([
                LocaleTabs::make(fn (string $l) => [
                    TextInput::make("title.$l")->label(__('filament.labels.title'))->required($l === 'ar')->maxLength(200),
                    RichEditor::make("body.$l")->label(__('filament.labels.body')),
                ]),
                // The slug identifies system pages (about/privacy/terms) in routes, so it is read-only.
                TextInput::make('slug')->label(__('filament.labels.slug'))->required()->alphaDash()->unique(ignoreRecord: true)->disabledOn('edit'),
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
                TextColumn::make('title')->label(__('filament.labels.title'))->searchable(),
                TextColumn::make('slug')->label(__('filament.labels.slug')),
                TextColumn::make('updated_at')->label(__('filament.labels.updated_at'))->since(),
            ])
            ->recordActions([EditAction::make()->label(__('filament.actions.edit'))])
            ->emptyStateHeading(__('filament.empty.pages'))->emptyStateDescription(__('filament.empty.pages_desc'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
