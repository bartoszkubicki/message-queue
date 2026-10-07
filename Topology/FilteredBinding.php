<?php

declare(strict_types=1);

/**
 * File: FilteredBinding.php
 *
 * @author Bartosz Kubicki
 */

namespace BKubicki\MessageQueue\Topology;

use Magento\Framework\MessageQueue\Topology\Config\ExchangeConfigItem\BindingInterface;

/**
 * Binding decorator exposing a replaced set of arguments, everything else is delegated to the decorated binding.
 */
class FilteredBinding implements BindingInterface
{
    /**
     * @var BindingInterface
     */
    private BindingInterface $binding;

    /**
     * @var array
     */
    private array $arguments;

    /**
     * @param BindingInterface $binding
     * @param array $arguments
     */
    public function __construct(BindingInterface $binding, array $arguments)
    {
        $this->binding = $binding;
        $this->arguments = $arguments;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->binding->getId();
    }

    /**
     * @inheritDoc
     */
    public function getDestinationType()
    {
        return $this->binding->getDestinationType();
    }

    /**
     * @inheritDoc
     */
    public function getDestination()
    {
        return $this->binding->getDestination();
    }

    /**
     * @inheritDoc
     */
    public function isDisabled()
    {
        return $this->binding->isDisabled();
    }

    /**
     * @inheritDoc
     */
    public function getTopic()
    {
        return $this->binding->getTopic();
    }

    /**
     * @inheritDoc
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}
