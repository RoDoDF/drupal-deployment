<?php

namespace Drupal\annonce;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Annonce entity.
 *
 * @see \Drupal\annonce\Entity\Annonce.
 */
class AnnonceAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\annonce\Entity\AnnonceInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished annonce entities')->addCacheableDependency($entity);
        }
        return AccessResult::allowedIfHasPermission($account, 'view published annonce entities')->addCacheableDependency($entity);
        break;

      case 'update':
        if (($account->hasPermission('edit own annonce entities') && $account->id() == $entity->getOwnerId())
          || $account->hasPermission('edit any annonce entities')) {
          return AccessResult::allowed()->cachePerPermissions()->cachePerUser();
        }
        else AccessResult::forbidden()->cachePerPermissions()->cachePerUser();
        break;

      case 'delete':
        if (($account->hasPermission('delete own annonce entities') && $account->id() == $entity->getOwnerId())
          || $account->hasPermission('delete any annonce entities')) {
          return AccessResult::allowed()->cachePerPermissions()->cachePerUser();
        }
        else AccessResult::forbidden()->cachePerPermissions()->cachePerUser();
        break;
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add annonce entities');
  }

}
