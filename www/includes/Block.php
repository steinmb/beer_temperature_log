<?php

declare(strict_types=1);

/**
 * @file
 *
 * Create HTML markup of a data entity.
 */

class Block
{
    private $entity;
    private $calculate;
    private $render;

    public function __construct(DataEntity $entity, Calculate $calculate)
    {
        $this->entity = $entity;
        $this->calculate = $calculate;
    }

    public function currentValue(): string
    {
        $content = '<div class="block">';
        $content .= '<h2 class="title">' . $this->entity->getId() . '</h2>';
        $content .= '<ul><li>' . $this->entity->getData() . '</li></ul></div>';

        return $content;
    }

    public function renderBlock(int $minutes): string
    {
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
