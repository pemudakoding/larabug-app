<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Exception;
use Filament\Widgets\Widget;

class ExceptionGrowth extends Widget
{
    public static $view = 'filament.widgets.exception-growth';

    public function render()
    {
        $previousMonthStart = Carbon::now()->subDays(60);
        $thisMonthStart = Carbon::now()->subDays(30);

        $previousMonth = Exception::query()
            ->where('created_at', '>=', $previousMonthStart)
            ->where('created_at', '<=', $thisMonthStart)
            ->get()
            ->count();

        $thisMonth = Exception::query()
            ->where('created_at', '>=', $thisMonthStart)
            ->get()
            ->count();

        $growth = round(($thisMonth - $previousMonth) / $previousMonth * 100);

        return view(static::$view, [
            'growth' => $growth,
        ]);
    }
}
