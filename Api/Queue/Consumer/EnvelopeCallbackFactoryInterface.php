<?php

declare(strict_types=1);

/**
 * File: EnvelopeCallbackFactoryInterface.php
 *
 * @author Bartosz Kubicki
 */

namespace BartoszKubicki\MessageQueue\Api\Queue\Consumer;

use InvalidArgumentException;
use BartoszKubicki\MessageQueue\Queue\Consumer\EnvelopeCallback\EnvelopeCallbackInterface;
use Magento\Framework\MessageQueue\ConsumerConfigurationInterface as UsedConsumerConfig;

/**
 * Interface EnvelopeCallbackFactoryInterface
 * @package BartoszKubicki\MessageQueue\Api\Queue\Consumer
 */
interface EnvelopeCallbackFactoryInterface
{
    /**
     * @param string $type
     * @param UsedConsumerConfig $usedConsumerConfiguration
     * @return EnvelopeCallbackInterface
     * @throws InvalidArgumentException
     */
    public function create(string $type, UsedConsumerConfig $usedConsumerConfiguration): EnvelopeCallbackInterface;
}
