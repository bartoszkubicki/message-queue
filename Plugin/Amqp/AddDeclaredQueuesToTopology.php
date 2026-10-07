<?php

declare(strict_types=1);

/**
 * File: AddDeclaredQueuesToTopology.php
 *
 * @author Bartosz Kubicki
 */

namespace BKubicki\MessageQueue\Plugin\Amqp;

use BKubicki\MessageQueue\Topology\QueueDeclaration\Registry;
use Magento\Framework\MessageQueue\Topology\Config\QueueConfigItem\DataMapper;

/**
 * Core creates queues from bindings and (since magento/magento2#26966) takes the queue arguments from the binding, so
 * the same arguments end up on the queue and on the binding. Here queues never get arguments from bindings: queues
 * derived from bindings are created without arguments, arguments are defined only by queues declared explicitly in
 * queue_declaration.xml, which also take precedence over the derived ones.
 */
class AddDeclaredQueuesToTopology
{
    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param DataMapper $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetMappedData(DataMapper $subject, array $result): array
    {
        foreach ($result as $key => $queue) {
            $result[$key]['arguments'] = [];
        }

        foreach ($this->registry->getAll() as $key => $queue) {
            $result[$key] = $queue;
        }

        return $result;
    }
}
