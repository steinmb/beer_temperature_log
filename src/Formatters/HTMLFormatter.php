<?php declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\Onewire\EntityInterface;
use steinmb\Onewire\Temperature;

final class HTMLFormatter extends NormaliseFormatter
{
    public function format($record): string
    {
        $element = parent::format($record);
        return '<p>' . $element . '</p>';
    }

    public function formatMultiple(string $title, array $records): string
    {
        $elements = [];
        foreach ($records as $record) {
            $elements[] = parent::format($record);
        }

        return $this->unordered($title, $elements);
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
}
