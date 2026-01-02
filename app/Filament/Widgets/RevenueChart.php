<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Auth;

class RevenueChart extends ChartWidget
{
    protected static ?int $sort =2;
    protected ?string $heading = 'Revenue Chart';
    public ?string $filter = 'week';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $data = Trend::model(Order::class)
            ->between(
                start: match($activeFilter) {
                    'week' => now()->subWeek(),
                    'month' => now()->subMonth(),
                    'year' => now()->subYear(),
                    default => now()->subWeek(),
                },
                end: now()
            )
            ->perWeek()
            ->sum('total');

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data->map(fn (TrendValue $item) => $item->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $item) => $item->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): array|null
    {
        return[
            'week' =>'Last Week',
            'month' =>'Last Month',
            'year' =>'Last Year',
        ];
    }
}
