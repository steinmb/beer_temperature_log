<?php declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\Onewire\DataEntity;
use steinmb\Onewire\Temperature;
use steinmb\Utils\Calculate;

class HTMLFormatter implements FormatterInterface
{
    private $entity;

    public function __construct(DataEntity $entity)
    {
        $this->entity = $entity;
    }

    public function unorderedList(Temperature $sensor): string
    {
        $content = '<div class="block">';
        $content .= '<h2 class="title">' . $this->entity->id() . '</h2>';
        $content .= '<ul>';
        $content .= "<li>{$this->entity->timeStamp()}</li>";
        $content .= "<li>{$sensor->temperature()}</li>";
        $content .= '</ul></div>';

        return $content;
    }

    public function trendList(Calculate $calculator, int $minutes, string $lastMeasurement): string
    {
        $trend = $calculator->calculateTrend($minutes, $lastMeasurement);
        $table = explode(', ', $lastMeasurement);
        $content = '';
        $content .= '<div class="block">';
        $content .= '<h2 class="title">' . $this->entity->id() . '</h2>';
        $content .= '<ul>';
        $content .= '<li>' . $table[0] . '</li>';
        $content .= '<li>' . $table[1] . 'ºC' . '</li>';
        $content .= '<li>' . $minutes . 'min ' . $calculator->analyzeTrend() . ' (' . $trend . ')</li>';
        $content .= '</ul>';
        $content .= '</div>';

        return $content;
    }
}
