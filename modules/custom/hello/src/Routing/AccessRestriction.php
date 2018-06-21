<?php

namespace Drupal\hello\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class AccessRestriction extends RouteSubscriberBase {

  /**
   * @param \Symfony\Component\Routing\RouteCollection $collection
   */
  function alterRoutes(RouteCollection $collection) {
    $route = $collection->get('system.modules_list');
    // $route->setRequirements(['_onlysuperadmin' => 'TRUE']);
    $route = $collection->get('system.modules_uninstall');
    //$route->setRequirements(['_access' => 'FALSE']);
    $route = $collection->get('user.admin_permissions');
    //$route->setRequirements(['_access' => 'FALSE']);
  }

}
