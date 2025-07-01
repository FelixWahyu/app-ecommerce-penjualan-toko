<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class OrderStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Trend Penjualan';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    protected static string $color = 'warning';

    protected static ?int $sort = 2;

    public ?string $filter = 'year';

    protected function getData(): array
    {
        $start = now();
        $end = now();
        $interval = 'perDay';

        switch ($this->filter) {
            case 'today':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                $interval = 'perHour';
                break;

            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $interval = 'perDay';
                break;

            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $interval = 'perDay';
                break;

            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $interval = 'perMonth';
                break;
        }

        $data = Trend::model(Order::class)
            ->between(
                start: $start,
                end: $end,
            )
            ->{$interval}()
            ->sum('grand_total');

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate)->toArray(),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $this->formatLabel($value->date))->toArray(),
        ];
    }

    protected function formatLabel(string $date): string
    {
        return match ($this->filter) {
            'today' => Carbon::parse($date)->format('D H:i'),
            'week', 'month' => Carbon::parse($date)->format('d M'),
            'year' => Carbon::parse($date)->format('M Y'),
            default => $date,
        };
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This week',
            'month' => 'This month',
            'year' => 'This year',
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
