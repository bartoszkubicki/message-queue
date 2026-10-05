<?php

declare(strict_types=1);

/**
 * File: EnvelopeCallbackStub.php
 *
 * @author Bartosz Kubicki
 */

namespace BKubicki\MessageQueue\Test\Unit\Stub\Queue\Consumer;

use BKubicki\MessageQueue\Queue\Consumer\EnvelopeCallback\EnvelopeCallbackInterface;
use Magento\Framework\MessageQueue\EnvelopeInterface;

/**
 * Class EnvelopeCallbackStub
 * @package BKubicki\MessageQueue\Test\Unit\Stub\Queue\Consumer
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
