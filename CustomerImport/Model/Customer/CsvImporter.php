<?php
namespace Zagam\CustomerImport\Model\Customer;
use Zagam\CustomerImport\Api\ImportInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;


class CsvImporter implements ImportInterface
{
    protected $keys;

    protected $csv;
    /**
     * @var File
     */
    private $file;
    /**
     * @var LoggerInterface
     */
    private $logger;


    /**
     * CsvImporter constructor.
     * @param File $file
     * @param Csv $csv
     * @param LoggerInterface $logger
     *
     */
    public function __construct(
        File $file,
        Csv $csv,
        LoggerInterface $logger
    ) {
        $this->csv = $csv;
        $this->file = $file;
        $this->logger = $logger;
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
     * @throws LocalizedException
     */
    public function readData(string $file): array
    {
        try {
            if (!$this->file->isExists($file)) {
                throw new LocalizedException(__('Invalid file path or no file found.'));
            }
            $this->csv->setDelimiter(",");
            $data = $this->csv->getData($file);
            $this->logger->info('CSV file is parsed');
        } catch (FileSystemException $e) {
            $this->logger->info($e->getMessage());
            throw new LocalizedException(__('File system exception' . $e->getMessage()));
        }

        return $this->formatData($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function formatData($data): array
    {
        //Removing headers
        $this->keys = array_shift($data);
        array_walk($data, function (&$v) {
            $v = array_combine($this->keys, $v);
        });
       return $data;
        /*echo '-------';
        echo '<pre>';
        print_r($data);
        */
    }
}
