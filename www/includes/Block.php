<?php

declare(strict_types=1);

/**
 * @file
 *
 * Create HTML markup of a data entity.
 */

require_once BREW_ROOT . '/includes/Calculate.php';

class Block
{
    private $entity;
    private $calculate;
    public $render;

    public function __construct(DataEntity $entity, Calculate $calculate)
    {
        $this->entity = $entity;
        $this->calculate = $calculate;
    }

    public function renderBlock(): string
    {
        $minutes = 10;
        $result = '';
        $this->calculate->calculateTrend($minutes);
        $sample = $this->entity->getLastReading();

        $result .= '<div class="block">';
        $result .= '<h2 class="title">Sensor ' . $this->entity->getId() . '</h2>';
        $result .= '<ul>';
        $result .= '<li>' . $sample['Date'] . '</li>';
        $result .= '<li>' . $sample['Sensor'] . 'ºC' . '</li>';
        $result .= '<li>' . $minutes . 'min ' . $this->entity->analyzeTrend() . ' (' . $this->entity->getTrend() . ')</li>';
        $result .= '</ul>';
        $result .= '</div>';

        return $result;
    }
}
