<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloCalculatorResult extends ControllerBase {

  public function content($result) {
    $message = $this->t('Result: %result', ['%result' => $result]);

    return ['#markup' => $message];
  }

}
