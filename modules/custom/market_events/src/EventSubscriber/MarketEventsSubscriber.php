<?php

namespace Drupal\market_events\EventSubscriber;
use Drupal\market_events\Event\MarketCreateEvent;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Messenger\MessengerInterface;

class MarketEventsSubscriber implements EventSubscriberInterface
{
    use StringTranslationTrait;

  /**
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
    private $messenger;

    public function __construct(MessengerInterface $messenger) {
        $this->messenger = $messenger;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        // specify our custom event class name
        return [
            MarketCreateEvent::CREATE_EVENT => 'onMarketEventCreation',
        ];
    }

    public function onMarketEventCreation(MarketCreateEvent $event) {
        $username = $event->account->getAccountName();
        $userRoles = $event->account->getRoles(); 
        $this->messenger
        ->addStatus($this->t('<strong>Hey there</strong>: %name.',
          [
            '%name' => $username,
          ]
        ))
        ->addStatus($this->t('<strong>Your roles</strong>: %user_roles',
          [
            '%user_roles' => $userRoles
          ]
        ));

    }
}