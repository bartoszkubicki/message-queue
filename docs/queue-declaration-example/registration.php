<?php

declare(strict_types=1);

/**
 * @author Bartosz Kubicki
 */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'BKubicki_QueueDeclarationExample',
    __DIR__
);
