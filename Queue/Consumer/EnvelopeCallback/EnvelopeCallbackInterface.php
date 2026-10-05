<?php

declare(strict_types=1);

/**
 * File: EnvelopeCallbackInterface.php
 *
 * @author Bartosz Kubicki
 */

namespace BartoszKubicki\MessageQueue\Queue\Consumer\EnvelopeCallback;

use Magento\Framework\MessageQueue\EnvelopeInterface;

/**
 * Interface EnvelopeCallbackInterface
 * @package BartoszKubicki\MessageQueue\Queue\Consumer\EnvelopeCallback
 */
interface EnvelopeCallbackInterface
{
    /**
     * @param EnvelopeInterface $message
     * @return void
     */
    public function execute(EnvelopeInterface $message): void;
}
