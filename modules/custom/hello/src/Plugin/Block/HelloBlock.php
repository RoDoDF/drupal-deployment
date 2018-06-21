<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Component\Datetime\Time;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a hello block.
 *
 * @Block(
 *  id = "hello_block",
 *  admin_label = @Translation("Hello!")
 * )
 */
class HelloBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * @var \Drupal\Component\Datetime\Time
   */
  protected $time;

  /**
   * {@inheritdoc}.
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              DateFormatterInterface $dateFormatter,
                              AccountProxyInterface $currentUser,
                              Time $time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->dateFormatter = $dateFormatter;
    $this->currentUser = $currentUser;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('date.formatter'),
      $container->get('current_user'),
      $container->get('datetime.time')
    );
  }

  /**
   * {@inheritdoc}.
   */
  public function build() {
    $build = [
      '#markup' => $this->t('Welcome %name. It is %time.', [
        '%name' => $this->currentUser->getAccountName(),
        '%time' => $this->dateFormatter->format($this->time->getCurrentTime(), 'custom', 'H:i s\s'),
      ]),
      '#cache' => [
        'keys' => ['hello:block'],
        //'contexts' => ['user'],
        'max-age' => '10',
      ],
    ];

    return $build;
  }

}
