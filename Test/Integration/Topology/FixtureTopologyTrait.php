<?php

declare(strict_types=1);

/**
 * File: FixtureTopologyTrait.php
 *
 * @author Bartosz Kubicki
 */

namespace BKubicki\MessageQueue\Test\Integration\Topology;

use BKubicki\MessageQueue\Plugin\Amqp\AddDeclaredQueuesToTopology;
use BKubicki\MessageQueue\Topology\QueueDeclaration\Data as QueueDeclarationData;
use BKubicki\MessageQueue\Topology\QueueDeclaration\Registry;
use BKubicki\MessageQueue\Topology\QueueDeclaration\Xml\Reader as QueueDeclarationReader;
use Magento\Framework\Config\FileResolverInterface;
use Magento\Framework\MessageQueue\Topology\Config\Data as TopologyData;
use Magento\Framework\MessageQueue\Topology\Config\QueueConfigItem\DataMapper;
use Magento\Framework\MessageQueue\Topology\Config\Xml\Reader as TopologyReader;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Builds the real Magento topology config (readers, converters, data mapper, plugins) on top of fixture files, so the
 * module's behaviour can be checked without touching the topology of the installed modules.
 */
trait FixtureTopologyTrait
{
    /**
     * @return ObjectManagerInterface
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function objectManager(): ObjectManagerInterface
    {
        return Bootstrap::getObjectManager();
    }

    /**
     * @param string $fileName
     * @param string|null $fixture fixture file name, null for no files at all
     * @param string $fixtureDir sub directory of _files
     * @return FileResolverInterface
     */
    private function createFileResolver(
        string $fileName,
        ?string $fixture,
        string $fixtureDir = ''
    ): FileResolverInterface {
        $files = $fixture === null
            ? []
            : [$fixture => file_get_contents(__DIR__ . '/../_files/' . $fixtureDir . '/' . $fixture)];

        return $this->createFileResolverWithContents($fileName, $files);
    }

    /**
     * @param string $fileName
     * @param array $files file contents indexed by path
     * @return FileResolverInterface
     */
    private function createFileResolverWithContents(string $fileName, array $files): FileResolverInterface
    {
        return new class ($fileName, $files) implements FileResolverInterface {
            /**
             * @var string
             */
            private string $fileName;

            /**
             * @var array
             */
            private array $files;

            /**
             * @param string $fileName
             * @param array $files
             */
            public function __construct(string $fileName, array $files)
            {
                $this->fileName = $fileName;
                $this->files = $files;
            }

            /**
             * @inheritDoc
             */
            public function get($filename, $scope)
            {
                return $filename === $this->fileName ? $this->files : [];
            }
        };
    }

    /**
     * @param string $fixtureDir sub directory of _files
     * @return TopologyData
     */
    private function createTopologyData(string $fixtureDir = ''): TopologyData
    {
        $reader = $this->objectManager()->create(
            TopologyReader::class,
            ['fileResolver' => $this->createFileResolver('queue_topology.xml', 'queue_topology.xml', $fixtureDir)]
        );

        return $this->objectManager()->create(
            TopologyData::class,
            ['reader' => $reader, 'cacheId' => 'mqi_test_topology_' . uniqid('', true)]
        );
    }

    /**
     * @param bool $withDeclaration whether queue_declaration.xml exists at all
     * @param string $fixtureDir sub directory of _files
     * @return Registry
     */
    private function createRegistry(bool $withDeclaration = true, string $fixtureDir = ''): Registry
    {
        $fileResolver = $this->createFileResolver(
            'queue_declaration.xml',
            $withDeclaration ? 'queue_declaration.xml' : null,
            $fixtureDir
        );

        return $this->createRegistryFromReader($this->createDeclarationReader($fileResolver));
    }

    /**
     * @param FileResolverInterface $fileResolver
     * @return QueueDeclarationReader
     */
    private function createDeclarationReader(FileResolverInterface $fileResolver): QueueDeclarationReader
    {
        return $this->objectManager()->create(QueueDeclarationReader::class, ['fileResolver' => $fileResolver]);
    }

    /**
     * @param QueueDeclarationReader $reader
     * @return Registry
     */
    private function createRegistryFromReader(QueueDeclarationReader $reader): Registry
    {
        $data = $this->objectManager()->create(
            QueueDeclarationData::class,
            ['reader' => $reader, 'cacheId' => 'mqi_test_declaration_' . uniqid('', true)]
        );

        return new Registry($data);
    }

    /**
     * Data mapper wired the way Magento does it (interceptor with the module's plugin), with fixture config.
     *
     * @param TopologyData $topologyData
     * @param Registry $registry
     * @return DataMapper
     */
    private function createDataMapper(TopologyData $topologyData, Registry $registry): DataMapper
    {
        $this->objectManager()->addSharedInstance(
            new AddDeclaredQueuesToTopology($registry),
            AddDeclaredQueuesToTopology::class
        );

        return $this->objectManager()->create(DataMapper::class, ['configData' => $topologyData]);
    }
}
