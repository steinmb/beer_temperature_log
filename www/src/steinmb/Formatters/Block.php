<?php declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\Logger\Logger;
use steinmb\onewire\DataEntity;
use steinmb\onewire\Temperature;
use steinmb\Utils\Calculate;

/**
 * @file Block.php
 */

class Block
{
    private $entity;
    private $temperature;

    public function __construct(DataEntity $entity, Temperature $temperature)
    {
        $this->entity = $entity;
        $this->temperature = $temperature;
    }

    public function listCurrent(): string
    {
        $content = '<div class="block">';
        $content .= '<h2 class="title">' . $this->entity->id() . '</h2>';
        $content .= '<ul>';
        $content .= "<li>{$this->entity->timeStamp()}</li>";
        $content .= "<li>{$this->temperature->temperature()}</li>";
        $content .= '</ul></div>';

        return $content;
    }

    public function listHistoric(
      int $minutes,
      string $sample,
      Calculate $calculate,
      Logger $logger
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
