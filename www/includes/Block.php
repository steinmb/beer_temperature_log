<?php
/**
 * File block.php.
 *
 * Create HTML markup of a data entity.
 */

class Block
{
  private $entity;
  public $render;

  /**
   * Block constructor.
   * @param object $entity
   *
   */
  public function __construct($entity)
  {
    $this->entity = $entity;
    $this->render = $this->renderBlock();
  }

  public function renderBlock()
  {
    $minutes = 10;
    $result = '';
    $this->entity->calculateTrend($minutes);
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
