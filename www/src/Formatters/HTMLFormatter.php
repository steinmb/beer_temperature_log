<?php declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\Onewire\EntityInterface;
use steinmb\Onewire\Temperature;

final class HTMLFormatter extends NormaliseFormatter
{
    public function format($record): string
    {
        $element = parent::format($record);
        return $this->listItem($element);
    }

    private function listItem(string $element): string
    {
        return '<li>' . $element . '</li>';
    }

    public function unorderedList(Temperature $sensor, EntityInterface $entity): string
    {
        return $this->unordered(
            $entity->id(),
            [
                $entity->timeStamp(),
                $sensor->temperature(),
            ]
        );
    }

    public function trendList(string $trend, int $minutes, string $lastMeasurement, EntityInterface $entity): string
    {
        $elements = explode(', ', $lastMeasurement);
        $elements[] = 'Trend: ' . $trend . ' the last ' . $minutes . 'min';

        return $this->unordered(
            $entity->id(),
            $elements
        );
    }

    private function unordered(string $title, array $listElements): string
    {
        $elements = '';
        foreach ($listElements as $index => $listElement) {
            if ($index !== 0) {
                $elements .= PHP_EOL;
            }
            $elements .= $this->listItem($listElement);
        }

        $htmlUnorderedList = [
            'prefix' => '<div class="block">',
            'title' => '<h2 class="title">' . $title . '</h2><ul>',
            'elements' => $elements,
            'suffix' => '</ul></div>',
        ];

        return implode(PHP_EOL, $htmlUnorderedList);
    }
}
