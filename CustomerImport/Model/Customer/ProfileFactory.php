<?php
namespace Zagam\CustomerImport\Model\Customer;
use Zagam\CustomerImport\Api\ImportInterface;
use Magento\Framework\ObjectManagerInterface;

class ProfileFactory
{
    /**
     * Object manager
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * Create class instance with specified parameters
     * @throws \Exception
     */
    public function create(string $type): ImportInterface
    {

        if ($type === "sample-csv" ) {
            $class = CsvImporter::class;
        } elseif ($type === "sample-json") {
            $class = JsonImporter::class;
        } else {
            throw new \Exception("Unsupported Profile type,Your profile type should be sample-csv or sample-json");
        }
        return $this->objectManager->create($class);
    }
}
