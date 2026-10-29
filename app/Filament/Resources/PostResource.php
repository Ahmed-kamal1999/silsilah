<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Support\LocaleTabs;
use App\Filament\Support\ResourceLabels;
use App\Models\Post;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    use ResourceLabels;

    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?int $navigationSort = 20;

    protected static function labelKey(): string
    {
        return 'posts';
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
                ]),
            ])->columnSpanFull(),
            Section::make(__('filament.sections.publishing'))->description(__('filament.sections.publishing_desc'))->schema([
                TextInput::make('slug')->label(__('filament.labels.slug'))->required()->alphaDash()->unique(ignoreRecord: true),
                Select::make('category_id')->label(__('filament.labels.category'))->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)->preload(),
                Select::make('author_id')->label(__('filament.labels.author'))->relationship('author', 'name')->default(fn () => auth()->id())->preload(),
                DateTimePicker::make('published_at')->label(__('filament.labels.published_at'))->default(now()),
                Toggle::make('is_published')->label(__('filament.labels.status_published')),
            ])->columns(2)->columnSpanFull(),
            Section::make(__('filament.sections.media'))->description(__('filament.sections.media_desc'))->schema([
                SpatieMediaLibraryFileUpload::make('cover')->label(__('filament.labels.cover'))->collection('cover')->image()->imageEditor(),
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
                IconColumn::make('is_published')->label(__('filament.labels.status_published'))->boolean(),
                TextColumn::make('published_at')->label(__('filament.labels.published_at'))->dateTime()->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([TernaryFilter::make('is_published')->label(__('filament.labels.status_published'))
                ->trueLabel(__('filament.labels.status_published'))->falseLabel(__('filament.labels.status_draft'))])
            ->recordActions([EditAction::make()->label(__('filament.actions.edit')), DeleteAction::make()->label(__('filament.actions.delete'))->modalDescription(__('filament.actions.delete_confirm'))])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()->label(__('filament.actions.delete'))])])
            ->emptyStateHeading(__('filament.empty.posts'))->emptyStateDescription(__('filament.empty.posts_desc'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
