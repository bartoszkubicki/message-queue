<?php

declare(strict_types=1);

/**
 * File: FilterQueueArgumentsFromBinding.php
 *
 * @author Bartosz Kubicki
 */

namespace BKubicki\MessageQueue\Plugin\Amqp;

use BKubicki\MessageQueue\Topology\FilteredBinding;
use Magento\Framework\Amqp\Topology\BindingInstallerType\Queue;
use Magento\Framework\MessageQueue\Topology\Config\ExchangeConfigItem\BindingInterface;
use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Since magento/magento2#26966 binding arguments are used to declare the queue, but core also sends the very same
 * arguments with queue_bind. Queue-only arguments (x-dead-letter-exchange, x-message-ttl, ...) are meaningless for
 * a binding and make the broker keep separate bindings whenever they change, so they are removed before binding.
 * Any other argument is still passed to queue_bind.
 */
class FilterQueueArgumentsFromBinding
{
    /**
     * @var string[]
     */
    private array $queueArgumentKeys;

    /**
     * @param string[] $queueArgumentKeys
     */
    public function __construct(array $queueArgumentKeys = [])
    {
        $this->queueArgumentKeys = array_values($queueArgumentKeys);
    }

    /**
     * @param Queue $subject
     * @param AMQPChannel $channel
     * @param BindingInterface $binding
     * @param string $exchangeName
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeInstall(Queue $subject, AMQPChannel $channel, BindingInterface $binding, $exchangeName): array
    {
        $arguments = array_diff_key((array)$binding->getArguments(), array_flip($this->queueArgumentKeys));

        return [$channel, new FilteredBinding($binding, $arguments), $exchangeName];
    }
}
