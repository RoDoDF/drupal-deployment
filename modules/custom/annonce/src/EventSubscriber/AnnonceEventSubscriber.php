<?php

namespace Drupal\annonce\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\ResettableStackedRouteMatchInterface;
use Drupal\Core\Database\Connection;
use Drupal\Component\Datetime\TimeInterface;

class AnnonceEventSubscriber implements EventSubscriberInterface {
  protected $current_user;
  protected $messenger;
  protected $current_route_match;
  protected $database;
  protected $time;


  public function __construct(AccountProxyInterface $current_user, MessengerInterface $messenger, $current_route_match, Connection $database, TimeInterface $time) {
    $this->current_user = $current_user;
    $this->messenger = $messenger;
    $this->current_route_match = $current_route_match;
    $this->database = $database;
    $this->time = $time;
  }

  static function getSubscribedEvents() {
    $events['kernel.request'] = [['display_username'], ['display_annonce']];
    return $events;
  }

  public function display_username(Event $event) {
    $this->messenger->addMessage('Event for '.$this->current_user->getDisplayName()); 
  }

  public function display_annonce(Event $event) {
    if($this->current_route_match->getRouteName() == 'entity.annonce.canonical'){
      $this->database->insert('annonce_history')->fields(array(
          'aid' => $this->current_route_match->getParameter('annonce')->id(),
          'uid' => $this->current_user->id(),
          'date' => $this->time->getCurrentTime(),
        ))->execute();
    $this->messenger->addMessage('Entité annonce'); 
    };
  }


}
