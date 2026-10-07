<?php

declare(strict_types=1);

/**
 * File: Reader.php
 *
 * @author Bartosz Kubicki
 */

namespace BKubicki\MessageQueue\Topology\QueueDeclaration\Xml;

use Magento\Framework\Config\Dom;
use Magento\Framework\Config\FileResolverInterface;
use Magento\Framework\Config\Reader\Filesystem;
use Magento\Framework\Config\ValidationStateInterface;

/**
 * Reads and merges queue_declaration.xml files of all modules.
 */
class Reader extends Filesystem
{
    /**
     * @var array
     */
    protected $_idAttributes = [
        '/config/queue' => ['name', 'connection'],
        '/config/queue/arguments/argument' => 'name',
        '/config/queue/arguments/argument(/item)+' => 'name',
    ];

    /**
     * @param FileResolverInterface $fileResolver
     * @param Converter $converter
     * @param SchemaLocator $schemaLocator
     * @param ValidationStateInterface $validationState
     * @param string $fileName
     * @param array $idAttributes
     * @param string $domDocumentClass
     * @param string $defaultScope
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        FileResolverInterface $fileResolver,
        Converter $converter,
        SchemaLocator $schemaLocator,
        ValidationStateInterface $validationState,
        $fileName = 'queue_declaration.xml',
        $idAttributes = [],
        $domDocumentClass = Dom::class,
        $defaultScope = 'global'
    ) {
        parent::__construct(
            $fileResolver,
            $converter,
            $schemaLocator,
            $validationState,
            $fileName,
            $idAttributes,
            $domDocumentClass,
            $defaultScope
        );
    }
}
