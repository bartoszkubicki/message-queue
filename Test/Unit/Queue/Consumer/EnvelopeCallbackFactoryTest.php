<?php

declare(strict_types=1);

/**
 * File: EnvelopeCallbackFactoryTest.php
 *
 * @author Bartosz Kubicki
 */

namespace BKubicki\MessageQueue\Test\Unit\Queue\Consumer;

use InvalidArgumentException;
use BKubicki\MessageQueue\Queue\Consumer\EnvelopeCallbackFactory;
use BKubicki\MessageQueue\Test\Unit\Stub\Queue\Consumer\EnvelopeCallbackStub;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\MessageQueue\ConsumerConfigurationInterface as UsedConsumerConfig;

/**
 * Class EnvelopeCallbackFactoryTest
 * @package BKubicki\MessageQueue\Test\Unit\Queue\Consumer
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class EnvelopeCallbackFactoryTest extends TestCase
{
    /**
     * @var EnvelopeCallbackFactory
     */
    private $envelopeCallbackFactory;

    /**
     * @var UsedConsumerConfig|MockObject
     */
    private $usedConsumerConfig;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $objectManager = ObjectManager::getInstance();
        $this->usedConsumerConfig = $this->getMockBuilder(UsedConsumerConfig::class)->getMock();

        $this->envelopeCallbackFactory = new EnvelopeCallbackFactory($objectManager);
    }

    /**
     * @return void
     */
    public function testCreateWhenRequestObjectIsOfIncorrectType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->envelopeCallbackFactory->create(get_class($this), $this->usedConsumerConfig);
    }

    /**
     * @return void
     */
    public function testCreateWhenRequestObjectIsOfCorrectType(): void
    {
        $this->envelopeCallbackFactory->create(EnvelopeCallbackStub::class, $this->usedConsumerConfig);
    }
}
