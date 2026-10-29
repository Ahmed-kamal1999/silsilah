<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostCategoryResource\Pages;
use App\Filament\Support\LocaleTabs;
use App\Filament\Support\ResourceLabels;
use App\Models\PostCategory;
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

class PostCategoryResource extends Resource
{
    use ResourceLabels;

    protected static ?string $model = PostCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?int $navigationSort = 21;

    protected static function labelKey(): string
    {
        return 'post_categories';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament.sections.general'))->description(__('filament.sections.general_desc'))->schema([
                LocaleTabs::make(fn (string $l) => [
                    TextInput::make("name.$l")->label(__('filament.labels.name'))->required($l === 'ar')->maxLength(150),
                ]),
                TextInput::make('slug')->label(__('filament.labels.slug'))->required()->alphaDash()->unique(ignoreRecord: true),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('filament.labels.name'))->searchable(),
                TextColumn::make('slug')->label(__('filament.labels.slug')),
                TextColumn::make('posts_count')->counts('posts')->label(__('filament.NavBar.posts')),
            ])
            ->recordActions([EditAction::make()->label(__('filament.actions.edit')), DeleteAction::make()->label(__('filament.actions.delete'))->modalDescription(__('filament.actions.delete_confirm'))])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()->label(__('filament.actions.delete'))])])
            ->emptyStateHeading(__('filament.empty.post_categories'))->emptyStateDescription(__('filament.empty.post_categories_desc'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPostCategories::route('/'),
            'create' => Pages\CreatePostCategory::route('/create'),
            'edit' => Pages\EditPostCategory::route('/{record}/edit'),
        ];
    }
}
