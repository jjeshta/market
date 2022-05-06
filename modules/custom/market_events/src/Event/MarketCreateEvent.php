<?php

namespace Drupal\market_events\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Drupal\user\UserInterface;

class MarketCreateEvent extends Event
{
    const CREATE_EVENT = 'create_market_event';

    /**
     * The user account.
     *
     * @var \Drupal\user\UserInterface
     */
    public $account;
    
    /**
     * Constructs the object.
     *
     * @param \Drupal\user\UserInterface $account
     *   The account of the user logged in.
     */
    public function __construct(UserInterface $account) {
        $this->account = $account;
    }
}