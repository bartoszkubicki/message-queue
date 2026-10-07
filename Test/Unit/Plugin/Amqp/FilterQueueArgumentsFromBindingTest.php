<?php

declare(strict_types=1);

/**
 * File: FilterQueueArgumentsFromBindingTest.php
 *
 * @author Bartosz Kubicki
 */

namespace BKubicki\MessageQueue\Test\Unit\Plugin\Amqp;

use BKubicki\MessageQueue\Plugin\Amqp\FilterQueueArgumentsFromBinding;
use Magento\Framework\Amqp\Topology\BindingInstallerType\Queue;
use Magento\Framework\MessageQueue\Topology\Config\ExchangeConfigItem\BindingInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PHPUnit\Framework\TestCase;

/**
 * Class FilterQueueArgumentsFromBindingTest
 * @package BKubicki\MessageQueue\Test\Unit\Plugin\Amqp
 */
class FilterQueueArgumentsFromBindingTest extends TestCase
{
    /**
     * @return void
     */
    public function testQueueArgumentsAreRemovedAndOthersKept(): void
    {
        $binding = $this->createMock(BindingInterface::class);
        $binding->method('getArguments')->willReturn([
            'x-dead-letter-exchange' => 'dlx',
            'x-message-ttl' => 1000,
            'x-match' => 'all',
        ]);
        $binding->method('getTopic')->willReturn('entity.create');
        $channel = $this->createMock(AMQPChannel::class);

        $plugin = new FilterQueueArgumentsFromBinding(['x-dead-letter-exchange', 'x-message-ttl']);
        [$resultChannel, $resultBinding, $exchange] = $plugin->beforeInstall(
            $this->createMock(Queue::class),
            $channel,
            $binding,
            'entity'
        );

        $this->assertSame($channel, $resultChannel);
        $this->assertSame('entity', $exchange);
        $this->assertSame(['x-match' => 'all'], $resultBinding->getArguments());
        $this->assertSame('entity.create', $resultBinding->getTopic());
    }

    /**
     * @return void
     */
    public function testBindingWithoutArgumentsStaysEmpty(): void
    {
        $binding = $this->createMock(BindingInterface::class);
        $binding->method('getArguments')->willReturn([]);

        $plugin = new FilterQueueArgumentsFromBinding(['x-message-ttl']);
        [, $resultBinding] = $plugin->beforeInstall(
            $this->createMock(Queue::class),
            $this->createMock(AMQPChannel::class),
            $binding,
            'entity'
        );

        $this->assertSame([], $resultBinding->getArguments());
    }
}
