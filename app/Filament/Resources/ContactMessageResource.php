<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Filament\Support\ResourceLabels;
use App\Models\ContactMessage;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ContactMessageResource extends Resource
{
    use ResourceLabels;

    protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static ?int $navigationSort = 1;

    protected static function labelKey(): string
    {
        return 'contact_messages';
    }

    protected static function navGroup(): string
    {
        return 'group_inbox';
    }

    public static function getNavigationBadge(): ?string
    {
        $n = ContactMessage::whereNull('read_at')->count();

        return $n ? (string) $n : null;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament.sections.message'))->description(__('filament.sections.message_desc'))->schema([
                TextEntry::make('name')->label(__('filament.labels.name')),
                TextEntry::make('email')->label(__('filament.labels.email'))->copyable(),
                TextEntry::make('phone')->label(__('filament.labels.phone'))->copyable(),
                TextEntry::make('created_at')->label(__('filament.labels.created_at'))->dateTime(),
                TextEntry::make('message')->label(__('filament.labels.message'))->columnSpanFull()->placeholder('—'),
            ])->columns(2)->columnSpanFull(),
            Section::make(__('filament.sections.technical'))->description(__('filament.sections.technical_desc'))->schema([
                TextEntry::make('ip')->label(__('filament.labels.ip')),
                TextEntry::make('user_agent')->label(__('filament.labels.user_agent')),
            ])->columns(2)->collapsed()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('read_at')->label(__('filament.labels.status_read'))
                    ->icon(fn ($state) => $state ? Heroicon::OutlinedEnvelopeOpen : Heroicon::Envelope)
                    ->color(fn ($state) => $state ? 'gray' : 'warning'),
                TextColumn::make('name')->label(__('filament.labels.name'))->searchable()->weight(fn ($record) => $record->read_at ? null : 'bold'),
                TextColumn::make('email')->label(__('filament.labels.email'))->searchable(),
                TextColumn::make('phone')->label(__('filament.labels.phone')),
                TextColumn::make('message')->label(__('filament.labels.message'))->limit(40),
                TextColumn::make('created_at')->label(__('filament.labels.created_at'))->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([TernaryFilter::make('read_at')->label(__('filament.labels.status_read'))->nullable()
                ->trueLabel(__('filament.labels.status_read'))->falseLabel(__('filament.labels.status_unread'))])
            ->headerActions([
                Action::make('export')->label(__('filament.actions.export_csv'))->icon(Heroicon::OutlinedArrowDownTray)
                    ->action(fn () => static::exportCsv(ContactMessage::query()->latest()->get())),
            ])
            ->recordActions([
                ViewAction::make()->label(__('filament.actions.view')),
                Action::make('toggle_read')
                    ->label(fn ($record) => $record->read_at ? __('filament.actions.mark_unread') : __('filament.actions.mark_read'))
                    ->icon(Heroicon::OutlinedEnvelopeOpen)
                    ->action(fn ($record) => $record->update(['read_at' => $record->read_at ? null : now()])),
                DeleteAction::make()->label(__('filament.actions.delete'))->modalDescription(__('filament.actions.delete_confirm')),
            ])
            ->toolbarActions([BulkActionGroup::make([
                BulkAction::make('mark_read')->label(__('filament.actions.mark_read'))->icon(Heroicon::OutlinedEnvelopeOpen)
                    ->requiresConfirmation()->modalDescription(__('filament.actions.mark_read_confirm'))
                    ->action(function (Collection $records) {
                        $records->each->update(['read_at' => now()]);
                        Notification::make()->title(__('filament.actions.mark_read_done'))->success()->send();
                    }),
                BulkAction::make('export_selected')->label(__('filament.actions.export_csv'))->icon(Heroicon::OutlinedArrowDownTray)
                    ->action(fn (Collection $records) => static::exportCsv($records)),
                DeleteBulkAction::make()->label(__('filament.actions.delete')),
            ])])
            ->emptyStateHeading(__('filament.empty.contact_messages'))->emptyStateDescription(__('filament.empty.contact_messages_desc'));
    }

    /** Streams a UTF-8 (BOM, for Excel Arabic support) CSV. Cells are neutralised against formula injection. */
    protected static function exportCsv(Collection $records)
    {
        $safe = fn ($v) => preg_match('/^[=+\-@\t\r]/', (string) $v) ? "'".$v : (string) $v;

        return response()->streamDownload(function () use ($records, $safe) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['ID', 'Name', 'Email', 'Phone', 'Message', 'IP', 'Read', 'Created']);
            foreach ($records as $r) {
                fputcsv($out, [$r->id, $safe($r->name), $safe($r->email), $safe($r->phone), $safe($r->message), $r->ip, $r->read_at?->toDateTimeString(), $r->created_at->toDateTimeString()]);
            }
            fclose($out);
        }, 'contact-messages-'.now()->format('Ymd-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'view' => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
}
