<?php
namespace Zagam\CustomerImport\Model;
use Magento\Framework\Exception;
use Magento\Framework\Filesystem\Io\File;
use Magento\Store\Model\StoreManagerInterface;
use Zagam\CustomerImport\Model\Import\CustomerAddressImport;

class Address
{
    private $file;
    private $storeManagerInterface;
    private $customerAddressImport;
    private $output;

    public function __construct(
        File $file,
        StoreManagerInterface $storeManagerInterface,
        CustomerAddressImport $customerAddressImport
    ) {
        $this->file = $file;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->customerAddressImport = $customerAddressImport;
    }

    /**
     * @param array $data
     * @param int $websiteId
     * @param int $storeId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createCustomerAddress(array $data, int $websiteId, int $storeId): void
    {

        try {
            // collect the Address data
            $addressData = [
                '_email'                      => (isset($data['_email']) ? $data['_email'] : null),
                '_website'                    => 'base',
                '_entity_id'                  => (isset($data['_entity_id']) ? $data['_entity_id'] : null),
                'city'                        => (isset($data['city']) ? $data['city'] : null),
                'company'                     => (isset($data['company']) ? $data['company'] : null),
                'country_id'                  => (isset($data['country_id']) ? $data['country_id'] : null),
                'fax'                         => (isset($data['fax']) ? $data['fax'] : null),
                'firstname'                   => (isset($data['firstname']) ? $data['firstname'] : null),
                'lastname'                    => (isset($data['lastname']) ? $data['lastname'] : null),
                'postcode'                    => (isset($data['postcode']) ? $data['postcode'] : null),
                'prefix'                      => (isset($data['prefix']) ? $data['prefix'] : null),
                'region'                      => (isset($data['region']) ? $data['region'] : null),
                'region_id'                   => (isset($data['region_id']) ? $data['region_id'] : null),
                'street'                      => (isset($data['street'])? $data['street'] : null),
                'suffix'                      => (isset($data['suffix']) ? $data['suffix'] : null),
                'telephone'                   => (isset($data['telephone']) ? $data['telephone'] : null),
                'vat_id'                      => (isset($data['vat_id']) ? $data['vat_id'] : null),
                'vat_is_valid'                => (isset($data['vat_is_valid']) ? $data['vat_is_valid'] : null),
                'vat_request_date'            => (isset($data['vat_request_date']) ? $data['vat_request_date'] : null),
                'vat_request_id'              => (isset($data['vat_request_id']) ? $data['vat_request_id'] : null),
                'vat_request_success'         => (isset($data['vat_request_success']) ? $data['vat_request_success'] : null),
                '_address_default_billing_'   => (isset($data['_address_default_billing_']) ? $data['_address_default_billing_'] : null),
                '_address_default_shipping_'  => (isset($data['_address_default_shipping_']) ? $data['_address_default_shipping_'] : null),

        ];
            // save the customer Address
            $this->customerAddressImport->importCustomerAddressData($addressData);
        } catch (Exception $e) {
            $this->output->writeln(
                '<error>'. $e->getMessage() .'</error>',
                OutputInterface::OUTPUT_NORMAL
            );
        }
    }
}
