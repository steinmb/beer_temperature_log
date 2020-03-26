<?php declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\Logger\LoggerInterface;
use steinmb\onewire\Temperature;
use steinmb\steinmb\Formatters\HTMLFormatter;
use steinmb\Utils\Calculate;

/**
 * @file Block.php
 */

class Block
{
    private $temperature;
    private $formatter;

    public function __construct(Temperature $temperature, HTMLFormatter $formatter)
    {
        $this->temperature = $temperature;
        $this->formatter = $formatter;
    }

    public function unorderedlist(): string
    {
        return $this->formatter->unorderedList($this->temperature);
    }

    public function listHistoric(
      int $minutes,
      string $sample,
      Calculate $calculate,
      LoggerInterface $logger
    ): string
    {
        $content = '';
        $trend = $calculate->calculateTrend($minutes, $sample);
        $content .= '<div class="block">';
        $content .= '<h2 class="title">' . $this->entity->id() . '</h2>';
        $content .= '<ul>';
        $content .= '<li>' . $sample[0] . '</li>';
        $content .= '<li>' . $sample[1] . 'ºC' . '</li>';
        $content .= '<li>' . $minutes . 'min ' . $calculate->analyzeTrend() . ' (' . $trend . ')</li>';
        $content .= '</ul>';
        $content .= '</div>';

        return $content;
    }

}
