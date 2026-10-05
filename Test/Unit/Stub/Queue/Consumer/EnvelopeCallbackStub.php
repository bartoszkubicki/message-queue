<?php

declare(strict_types=1);

/**
 * File: EnvelopeCallbackStub.php
 *
 * @author Bartosz Kubicki
 */

namespace BartoszKubicki\MessageQueue\Test\Unit\Stub\Queue\Consumer;

use BartoszKubicki\MessageQueue\Queue\Consumer\EnvelopeCallback\EnvelopeCallbackInterface;
use Magento\Framework\MessageQueue\EnvelopeInterface;

/**
 * Class EnvelopeCallbackStub
 * @package BartoszKubicki\MessageQueue\Test\Unit\Stub\Queue\Consumer
 */
class EnvelopeCallbackStub implements EnvelopeCallbackInterface
{
    /**
     * @param EnvelopeInterface $message
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(EnvelopeInterface $message): void
    {
    }
}
