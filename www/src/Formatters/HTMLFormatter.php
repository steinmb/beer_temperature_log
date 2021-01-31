<?php declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\Onewire\EntityInterface;
use steinmb\Onewire\Temperature;
use steinmb\Utils\Calculate;

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
        $content = '<div class="block">';
        $content .= '<h2 class="title">' . $this->entity->id() . '</h2>';
        $content .= '<ul>';
        $content .= $this->listItem($this->entity->timeStamp());
        $content .= $this->listItem($sensor->temperature());
        $content .= '</ul></div>';

        return $content;
    }

    public function trendList(Calculate $calculator, int $minutes, string $lastMeasurement): string
    {
        $table = explode(', ', $lastMeasurement);
        $content = '';
        $content .= '<div class="block">';
        $content .= '<h2 class="title">' . $this->entity->id() . '</h2>';
        $content .= '<ul>';
        $content .= $this->listItem($table[0]);
        $content .= $this->listItem($table[1]);
        $content .= $this->listItem('Trend: ' . $calculator->calculateTrend(
            $minutes,
            $lastMeasurement
            ));
        $content .= '</ul>';
        $content .= '</div>';

        return $content;
    }
}
