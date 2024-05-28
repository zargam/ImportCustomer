<?php
namespace Zagam\CustomerImport\Model;
use Magento\Framework\Exception;
use Magento\Framework\Filesystem\Io\File;
use Magento\Store\Model\StoreManagerInterface;
use Zagam\CustomerImport\Model\Import\CustomerImport;

class Customer
{
    private $file;
    private $storeManagerInterface;
    private $customerImport;
    private $output;

    public function __construct(
        File $file,
        StoreManagerInterface $storeManagerInterface,
        CustomerImport $customerImport
    ) {
        $this->file = $file;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->customerImport = $customerImport;
    }

    /**
     * @param array $data
     * @param int $websiteId
     * @param int $storeId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createCustomer(array $data, int $websiteId, int $storeId): void
    {

        try {
            // collect the customer data
            $customerData = [
                'email'         => (isset($data['emailaddress']) ? $data['emailaddress'] : null),
                '_website'      => 'base',
                '_store'        => 'default',
                'confirmation'  => (isset($data['confirmation']) ? $data['confirmation'] : null),
                'dob'           => (isset($data['dob']) ? $data['dob'] : null),
                'firstname'     => (isset($data['fname']) ? $data['fname'] : null),
                'gender'        => (isset($data['gender']) ? $data['gender'] : null),
                'group_id'      => (isset($data['group_id']) ? $data['group_id'] : 1),
                'lastname'      => (isset($data['lname']) ? $data['lname'] : null),
                'middlename'    => (isset($data['mname']) ? $data['mname'] : null),
                'password_hash' => (isset($data['password_hash']) ? $data['password_hash'] : null),
                'prefix'        => (isset($data['prefix']) ? $data['prefix'] : null),
                'store_id'      => $storeId,
                'website_id'    => $websiteId,
                'password'      => null,
                'disable_auto_group_change' => (isset($data['confirmation']) ? $data['confirmation'] : 0)
        ];
            // save the customer data
            $this->customerImport->importCustomerData($customerData);
        } catch (Exception $e) {
            $this->output->writeln(
                '<error>'. $e->getMessage() .'</error>',
                OutputInterface::OUTPUT_NORMAL
            );
        }
    }
}
