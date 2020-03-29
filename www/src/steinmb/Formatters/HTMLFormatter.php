<?php declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\onewire\Temperature;
use steinmb\Utils\Calculate;

class HTMLFormatter implements FormatterInterface
{

    public function unorderedList(Temperature $sensor): string
    {
        $content = '<div class="block">';
        $content .= '<h2 class="title">' . $sensor->entity->id() . '</h2>';
        $content .= '<ul>';
        $content .= "<li>{$sensor->entity->timeStamp()}</li>";
        $content .= "<li>{$sensor->temperature()}</li>";
        $content .= '</ul></div>';

        return $content;
    }

    public function trendList(Calculate $calculator, $minutes, $sample): string
    {
        $content = '';
        $trend = $calculator->calculateTrend($minutes, $sample);
        $content .= '<div class="block">';
        $content .= '<h2 class="title">' . $this->entity->id() . '</h2>';
        $content .= '<ul>';
        $content .= '<li>' . $sample[0] . '</li>';
        $content .= '<li>' . $sample[1] . 'ºC' . '</li>';
        $content .= '<li>' . $minutes . 'min ' . $calculator->analyzeTrend() . ' (' . $trend . ')</li>';
        $content .= '</ul>';
        $content .= '</div>';

        return $content;
    }
}
