<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Service;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('filament.labels.messages_this_week'), ContactMessage::where('created_at', '>=', now()->startOfWeek())->count())
                ->icon(Heroicon::OutlinedInbox)
                ->description(__('filament.labels.unread').': '.ContactMessage::whereNull('read_at')->count()),
            Stat::make(__('filament.labels.published_posts'), Post::where('is_published', true)->count())
                ->icon(Heroicon::OutlinedNewspaper),
            Stat::make(__('filament.labels.services_count'), Service::count())
                ->icon(Heroicon::OutlinedShieldCheck),
        ];
    }
}
