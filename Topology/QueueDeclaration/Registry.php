<?php

declare(strict_types=1);

/**
 * File: Registry.php
 *
 * @author Bartosz Kubicki
 */

namespace BKubicki\MessageQueue\Topology\QueueDeclaration;

/**
 * Access to explicitly declared queues (queue_declaration.xml).
 */
class Registry
{
    /**
     * @var Data
     */
    private Data $data;

    /**
     * @param Data $data
     */
    public function __construct(Data $data)
    {
        $this->data = $data;
    }

    /**
     * Declared queues indexed by "<name>--<connection>".
     *
     * @return array
     */
    public function getAll(): array
    {
        return (array)$this->data->get();
    }

    /**
     * Whether a queue of the given name is declared explicitly, regardless of connection.
     *
     * @param string $queueName
     * @return bool
     */
    public function isDeclared(string $queueName): bool
    {
        foreach ($this->getAll() as $queue) {
            if ($queue['name'] === $queueName) {
                return true;
            }
        }

        return false;
    }
}
