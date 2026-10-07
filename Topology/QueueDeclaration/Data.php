<?php

declare(strict_types=1);

/**
 * File: Data.php
 *
 * @author Bartosz Kubicki
 */

namespace BKubicki\MessageQueue\Topology\QueueDeclaration;

use BKubicki\MessageQueue\Topology\QueueDeclaration\Xml\Reader;
use Magento\Framework\Config\CacheInterface;
use Magento\Framework\Config\Data as ConfigData;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Cached, merged content of all queue_declaration.xml files.
 */
class Data extends ConfigData
{
    /**
     * @param Reader $reader
     * @param CacheInterface $cache
     * @param string $cacheId
     * @param SerializerInterface|null $serializer
     */
    public function __construct(
        Reader $reader,
        CacheInterface $cache,
        $cacheId = 'message_queue_queue_declaration_config_cache',
        ?SerializerInterface $serializer = null
    ) {
        parent::__construct($reader, $cache, $cacheId, $serializer);
    }
}
