<?php
declare(strict_types=1);

namespace steinmb\onewire;

/**
 * @file Block.php
 */

class Block
{
    private $calculate;
    private $entity;
    private $logger;
    private $render;

    public function __construct(
      DataEntity $entity,
      Calculate $calculate,
      Logger $logger
    ) {
        $this->calculate = $calculate;
        $this->entity = $entity;
        $this->logger = $logger;
    }

    public function listCurrent(): string
    {
        $content = '<div class="block">';
        $content .= '<h2 class="title">' . $this->entity->getId() . '</h2>';
        $content .= '<ul><li>' . $this->entity->getData() . '</li></ul></div>';

        return $content;
    }

    public function listHistoric(int $minutes): string
    {
        $content = '';
        $sample = $this->logger->getLastReading();
        $trend = $this->calculate->calculateTrend($minutes, $sample);

        if (!isset($sample['Date'])) {
            return $content;
        }

        $content .= '<div class="block">';
        $content .= '<h2 class="title">' . $this->entity->getId() . '</h2>';
        $content .= '<ul>';
        $content .= '<li>' . $sample['Date'] . '</li>';
        $content .= '<li>' . $sample['Sensor'] . 'ºC' . '</li>';
        $content .= '<li>' . $minutes . 'min ' . $this->calculate->analyzeTrend() . ' (' . $trend . ')</li>';
        $content .= '</ul>';
        $content .= '</div>';

        return $content;
    }

}
