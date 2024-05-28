<?php
namespace Zagam\CustomerImport\Model\Customer;
use Zagam\CustomerImport\Api\ImportInterface;
use Magento\Framework\ObjectManagerInterface;

class ProfileAdressFactory
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

        if ($type === "sample-address-csv" ) {
            $class = CsvImporter::class;
        } elseif ($type === "sample-address-json") {
            $class = JsonImporter::class;
        } else {
            throw new \Exception("Unsupported Profile type,Your profile type should be sample-address-csv or sample-address-json");
        }
        return $this->objectManager->create($class);
    }
}
