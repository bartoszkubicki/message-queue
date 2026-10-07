<?php

declare(strict_types=1);

/**
 * File: SchemaLocator.php
 *
 * @author Bartosz Kubicki
 */

namespace BKubicki\MessageQueue\Topology\QueueDeclaration\Xml;

use Magento\Framework\Config\Dom\UrnResolver;
use Magento\Framework\Config\SchemaLocatorInterface;

/**
 * Schema locator for queue_declaration.xml, the same schema validates single files and the merged config.
 */
class SchemaLocator implements SchemaLocatorInterface
{
    /**
     * @var string
     */
    private const SCHEMA_URN = 'urn:magento:module:BKubicki_MessageQueue:etc/queue_declaration.xsd';

    /**
     * @var string
     */
    private string $schema;

    /**
     * @param UrnResolver $urnResolver
     */
    public function __construct(UrnResolver $urnResolver)
    {
        $this->schema = $urnResolver->getRealPath(self::SCHEMA_URN);
    }

    /**
     * @inheritDoc
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @inheritDoc
     */
    public function getPerFileSchema()
    {
        return $this->schema;
    }
}
