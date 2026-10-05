<?php

declare(strict_types=1);

/**
 * @author Bartosz Kubicki
 */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'BartoszKubicki_MessageQueue',
    __DIR__
);
