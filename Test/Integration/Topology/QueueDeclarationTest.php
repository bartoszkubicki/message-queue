<?php

declare(strict_types=1);

/**
 * File: QueueDeclarationTest.php
 *
 * @author Bartosz Kubicki
 */

namespace BKubicki\MessageQueue\Test\Integration\Topology;

use Magento\Framework\MessageQueue\Topology\Config\QueueConfigItem\DataMapper;
use PHPUnit\Framework\TestCase;

/**
 * Class QueueDeclarationTest
 * @package BKubicki\MessageQueue\Test\Integration\Topology
 * @magentoAppIsolation enabled
 */
class QueueDeclarationTest extends TestCase
{
    use FixtureTopologyTrait;

    /**
     * @return void
     */
    public function testDataMapperIsInterceptedByTheModulePlugin(): void
    {
        $mapper = $this->createDataMapper($this->createTopologyData(), $this->createRegistry());

        $this->assertInstanceOf(DataMapper::class, $mapper);
        $this->assertStringContainsString('Interceptor', get_class($mapper));
    }

    /**
     * @return void
     */
    public function testDeclaredQueueGetsOnlyDeclaredArguments(): void
    {
        $queues = $this->getQueues(true);

        $this->assertEquals(
            ['x-dead-letter-exchange' => 'mqi.dead_letter', 'x-message-ttl' => 5000],
            $queues['mqi_declared--amqp']['arguments']
        );
        $this->assertArrayNotHasKey('binding-only', $queues['mqi_declared--amqp']['arguments']);
    }

    /**
     * @return void
     */
    public function testQueueWithoutDeclarationDoesNotInheritBindingArguments(): void
    {
        $queues = $this->getQueues(true);

        $this->assertArrayHasKey('mqi_undeclared--amqp', $queues);
        $this->assertSame([], $queues['mqi_undeclared--amqp']['arguments']);
    }

    /**
     * @return void
     */
    public function testDeclaredQueueWithoutBindingIsCreatedWithItsOwnSettings(): void
    {
        $queues = $this->getQueues(true);

        $this->assertArrayHasKey('mqi_unbound--amqp', $queues);
        $this->assertFalse($queues['mqi_unbound--amqp']['durable']);
        $this->assertTrue($queues['mqi_unbound--amqp']['autoDelete']);
    }

    /**
     * @return void
     */
    public function testWithoutDeclarationFileQueuesAreCreatedFromBindingsOnly(): void
    {
        $queues = $this->getQueues(false);

        $this->assertSame(['mqi_declared--amqp', 'mqi_undeclared--amqp'], array_keys($queues));
        $this->assertSame([], $queues['mqi_declared--amqp']['arguments']);
        $this->assertSame([], $queues['mqi_undeclared--amqp']['arguments']);
    }

    /**
     * @return void
     */
    public function testBindingArgumentsStayOnBindings(): void
    {
        $exchange = $this->createTopologyData()->get()['mqi.entity--amqp'];

        $this->assertSame(
            ['binding-only' => 'on-binding'],
            $exchange['bindings']['queue--mqi_declared--mqi.declared.#']['arguments']
        );
        $this->assertEquals(
            ['x-message-ttl' => 1000, 'x-dead-letter-exchange' => 'mqi.dead_letter'],
            $exchange['bindings']['queue--mqi_undeclared--mqi.undeclared.#']['arguments']
        );
    }

    /**
     * @param bool $withDeclaration
     * @return array
     */
    private function getQueues(bool $withDeclaration): array
    {
        return $this->createDataMapper(
            $this->createTopologyData(),
            $this->createRegistry($withDeclaration)
        )->getMappedData();
    }
}
