<?php

namespace App\Service;

class DinoStatsService
{
    private ?array $rawData = null;

    private const string ALL_DINOS = 'all';

    private const array COLORS = [
        'de3232',
        'de6732',
        'dede32',
        '67de32',
        '32de32',
        '32de67',
        '32dede',
        '3267de',
        '3232de',
        '6732de',
        'de32de',
        'de3267',
    ];

    public static function getAllTypes(): array
    {
        return [
            self::ALL_DINOS,
            'sauropod',
            'large theropod',
            'small theropod',
            'ceratopsian',
            'euornithopod',
            'armoured dinosaur',
        ];
    }

    public function fetchData(int $start, int $end, array $types): array
    {
        $start = abs($start);
        $end = abs($end);

        $steps = 10;
        $step = round(($start - $end) / $steps);

        $labels = [];
        for ($i = 0; $i < $steps; ++$i) {
            $current = $start - ($i * $step);
            // generate a random rgb color

            $labels[] = $current.' mya';
        }

        $datasets = [];

        $colors = self::COLORS;
        shuffle($colors);

        foreach ($types as $type) {
            $color = '#'.(next($colors) ?: reset($colors));

            $datasets[] = [
                'label' => ucwords($type),
                'data' => $this->getSpeciesCounts($start, $steps, $step, $type),
                'borderColor' => $color,
                'backgroundColor' => $color,
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    private function getRawData(): array
    {
        if (null === $this->rawData) {
            $this->rawData = json_decode(file_get_contents(__DIR__.'/data/dino-stats.json'), true);
        }

        return $this->rawData;
    }

    private function getSpeciesCounts(int $start, int $steps, int $step, string $type): array
    {
        $counts = [];
        for ($i = 0; $i < $steps; ++$i) {
            $current = round($start - ($i * $step));
            $counts[] = $this->countSpeciesAt($current, $type);
        }

        return $counts;
    }

    private function countSpeciesAt(int $currentYear, string $type): int
    {
        $count = 0;
        foreach ($this->getRawData() as $dino) {
            if ((self::ALL_DINOS !== $type) && $dino['type'] !== $type) {
                continue;
            }

            if ($dino['from'] >= $currentYear && $dino['to'] <= $currentYear) {
                ++$count;
            }
        }

        return $count;
    }
}
