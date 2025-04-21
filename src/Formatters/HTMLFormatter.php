<?php

declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\Utils\Trend;

final class HTMLFormatter extends NormaliseFormatter
{
    public function formatMultiple(string $title, array $records): string
    {
        $elements = [];

        foreach ($records as $record) {
            $elements[] = parent::format($record);
        }

        return $this->unordered($title, $elements);
    }

    public function format($record): string
    {
        $element = parent::format($record);

        return '<p>' . $element . '</p>';
    }

    private function unordered(string $title, array $listElements): string
    {
        $htmlUnorderedList = [
            'prefix' => '<div class="block">',
            'title' => '<h2 class="title">' . $title . '</h2><ul>',
            'elements' => $this->listElements($listElements),
            'suffix' => '</ul></div>',
        ];

        return implode(PHP_EOL, $htmlUnorderedList);
    }

    private function listElements(array $list): string
    {
        $elements = '';

        foreach ($list as $index => $listElement) {
            if ($index !== 0) {
                $elements .= PHP_EOL;
            }

            $elements .= $this->listItem($listElement);
        }

        return $elements;
    }

    private function listItem(string $element): string
    {
        return '<li>' . $element . '</li>';
    }

    public function unorderedList(string $sensorId, $temperature, string $dateTime): string
    {
        return $this->unordered(
            $sensorId,
            [
                $dateTime,
                $temperature,
            ],
        );
    }

    public function trendList(
        Trend $trend,
        int $minutes,
        string $lastMeasurement,
        string $sensor_id
    ): string {
        $elements = explode(', ', $lastMeasurement);
        $elements[] = 'Trend: ' . $trend->trend . ' the last ' . $minutes . ' min';

        return $this->unordered(
            $sensor_id,
            $elements,
        );
    }
}
