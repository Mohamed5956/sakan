<?php

namespace App\Filament\Widgets;

use App\Models\Property;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('إجمالي العقارات', Property::count())
                ->description('كل العقارات المسجلة')
                ->descriptionIcon('heroicon-o-home')
                ->color('primary'),

            Stat::make('العقارات النشطة', Property::active()->count())
                ->description('المنشورة حالياً')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('المستخدمون', User::where('is_admin', false)->count())
                ->description('مستخدم مسجل')
                ->descriptionIcon('heroicon-o-users')
                ->color('info'),

            Stat::make('إجمالي المشاهدات', Property::sum('views'))
                ->description('مشاهدة للعقارات')
                ->descriptionIcon('heroicon-o-eye')
                ->color('warning'),
        ];
    }
}
