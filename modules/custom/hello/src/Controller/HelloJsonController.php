<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

class HelloJsonController extends ControllerBase {

  public function content() {
    // Afin de passer outre le thème et renvoyer des données brute, on retourne
    // un objet de type Symfony\Component\HttpFoundation\JsonResponse.
    $response = new JsonResponse(['key' => ['value1', 'value2']]);

    return $response;
  }

}
