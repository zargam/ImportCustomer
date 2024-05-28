<?php
namespace Zagam\CustomerImport\Model\Import;
use Magento\CustomerImportExport\Model\Import\Address;

class CustomerAddressImport extends Address
{
    public function importCustomerAddressData(array $rowData)
    {
        $this->prepareCustomerData($rowData);
        $newRows = [];
        $updateRows = [];
        $attributes = [];
        $defaults = [];
        $addUpdateResult = $this->_prepareDataForUpdate($rowData);
        if ($addUpdateResult['entity_row_new']) {
            $newRows[] = $addUpdateResult['entity_row_new'];
        }
        if ($addUpdateResult['entity_row_update']) {
            $updateRows[] = $addUpdateResult['entity_row_update'];
        }
        $attributes = $this->_mergeEntityAttributes($addUpdateResult['attributes'], $attributes);
        $defaults = $this->_mergeEntityAttributes($addUpdateResult['defaults'], $defaults);

        $this->_saveAddressEntities($newRows, $updateRows)
        ->_saveAddressAttributes($attributes)
        ->_saveCustomerDefaults($defaults);
        return true;
   }
}
