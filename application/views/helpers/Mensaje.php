<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
class Mensaje extends AbstractHelper {
 
    // Menu items array.
  protected $items = [];

  // Active item's ID.
  protected $activeItemId = '';

  // Constructor.
  public function __construct($items=[])
  {
    $this->items = $items;
  }

// Sets menu items.
  public function setItems($items)
  {
    $this->items = $items;
  }

  // Sets ID of the active items.
  public function setActiveItemId($activeItemId)
  {
    $this->activeItemId = $activeItemId;
  }
}

