<?php declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\Onewire\EntityInterface;
use steinmb\Onewire\Temperature;

final class HTMLFormatter implements FormatterInterface
{
    private $entity;
    private $dateFormat;

    public function __construct(EntityInterface $entity, ?string $dateFormat = null)
    {
        $this->entity = $entity;
        $this->dateFormat = $dateFormat;
    }

    public function format($record)
    {
        return $record;
    }

    private function listItem(string $element): string
    {
        return '<li>' . $element . '</li>';
    }

    public function unorderedList(Temperature $sensor): string
    {
        return $this->unordered(
            $this->entity->id(),
            [
                $this->entity->timeStamp(),
                $sensor->temperature(),
            ]
        );
    }

    public function trendList(string $trend, int $minutes, string $lastMeasurement): string
    {
        $elements = explode(', ', $lastMeasurement);
        $elements[] = 'Trend: ' . $trend . ' the last ' . $minutes . 'min';

        return $this->unordered(
            $this->entity->id(),
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
