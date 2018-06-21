<?php


namespace Drupal\hello\Controller;

use GeoApiFr\GeoApiFr;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class GeoController.
 */
class GeoController extends ControllerBase {

  public function get_commune($code_commune) {
      $cache = &drupal_static(__FUNCTION__);
      $commune = $cache[$code_commune];
      if(!isset($commune)){
      $data = GeoApiFr::getInstance()->get('communes', ['code' => $code_commune]);
      $commune = current($data);
      }
  return $commune;
  }

  public function commune_title($code_commune) {
      $commune = $this->get_commune($code_commune);
  return $commune->nom;
  }

  public function commune($code_commune) {
    $build = [];
    
    if($code_commune){
      $commune = $this->get_commune($code_commune);
      $build[] = ['#markup' => $commune->nom.' ('.$commune->code.') <br />'];
      $build[] = ['#markup' => $commune->population.' habitants<br />'];
    }

    $build[] = \Drupal::formBuilder()->getForm('Drupal\hello\Form\RegionSelector');
    return $build;
  }












  public function cities() {
    $data = GeoApiFr::getInstance()->get('communes', ['codeRegion' => 27]);
    kint($data);

    $client = \Drupal::httpClient();
    $request = $client->get('https://geo.api.gouv.fr/communes/89024?fields=nom,code,codesPostaux,codeDepartement,codeRegion,population&format=json&geometry=centre');
    $response = $request->getBody()->getContents();


    kint($response);

    return [
      '#theme' => 'table',
      '#header' => ['nom'],
      '#rows' => ['hello', 1]
    ];
  }

  public function cities_per_region($code_region) {
    $data = GeoApiFr::getInstance()->get('communes', ['codeRegion' => $code_region]);
    kint($data);

    return [
      '#theme' => 'table',
      '#header' => ['nom'],
      '#rows' => ['hello', 1]
    ];
  }

}
