<?php

namespace Oro\Bundle\ChannelBundle\Event;

/**
 * This event dispatched when channel status changed
 */
class ChannelChangeStatusEvent extends AbstractEvent
{
    const EVENT_NAME = 'oro_channel.channel.status_change';
}
