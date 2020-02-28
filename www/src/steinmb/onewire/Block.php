<?php
declare(strict_types=1);

namespace steinmb\onewire;

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
        $content .= '<li>' . $sample['Date'] . '</li>';
        $content .= '<li>' . $sample['Sensor'] . 'ºC' . '</li>';
        $content .= '<li>' . $minutes . 'min ' . $calculate->analyzeTrend() . ' (' . $trend . ')</li>';
        $content .= '</ul>';
        $content .= '</div>';

        return $content;
    }

}
