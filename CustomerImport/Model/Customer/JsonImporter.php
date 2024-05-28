<?php
namespace Zagam\CustomerImport\Model\Customer;
use Zagam\CustomerImport\Api\ImportInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;

class JsonImporter implements ImportInterface
{
    /**
     * @var File
     */
    private $file;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * CsvImporter constructor.
     * @param File $file
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        File $file,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ) {
        $this->file = $file;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }
    /**
     * @inheritDoc
     */
    public function getImportData(InputInterface $input): array
    {
        $file = $input->getArgument(ImportInterface::SOURCE);
        return $this->readData($file);
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     * @throws Exception
     */
    public function readData(string $file): array
    {
        try {
            if (!$this->file->isExists($file)) {
                throw new LocalizedException(__('Invalid file path or no file found.'));
            }
            $data = $this->file->fileGetContents($file);
            $this->logger->info('JSON file is parsed');
        } catch (FileSystemException $e) {
            $this->logger->info($e->getMessage());
            throw new LocalizedException(__('File system exception' . $e->getMessage()));
        }

        return $this->formatData($data);
    }

    public function formatData($data): array
    {
        return $this->serializer->unserialize($data);
    }
}
