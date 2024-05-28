<?php
namespace Zagam\CustomerImport\Model\Import;

use Magento\CustomerImportExport\Model\Import\Customer;

class CustomerImport extends Customer
{
    public function importCustomerData(array $rowData)
    {
        $this->prepareCustomerData($rowData);
        $entitiesToCreate = [];
        $entitiesToUpdate = [];
        $entitiesToDelete = [];
        $attributesToSave = [];

        $processedData = $this->_prepareDataForUpdate($rowData);
        $entitiesToCreate = array_merge($entitiesToCreate, $processedData[self::ENTITIES_TO_CREATE_KEY]);
        $entitiesToUpdate = array_merge($entitiesToUpdate, $processedData[self::ENTITIES_TO_UPDATE_KEY]);
        foreach ($processedData[self::ATTRIBUTES_TO_SAVE_KEY] as $tableName => $customerAttributes) {
            if (!isset($attributesToSave[$tableName])) {
                $attributesToSave[$tableName] = [];
            }
            $attributesToSave[$tableName] = array_diff_key(
                $attributesToSave[$tableName],
                $customerAttributes
            ) + $customerAttributes;
        }

        $this->updateItemsCounterStats($entitiesToCreate, $entitiesToUpdate, $entitiesToDelete);

        /**
        * Save prepared data
        */
        if ($entitiesToCreate || $entitiesToUpdate) {
            $this->_saveCustomerEntities($entitiesToCreate, $entitiesToUpdate);
        }
        if ($attributesToSave) {
            $this->_saveCustomerAttributes($attributesToSave);
        }

        return $entitiesToCreate[0]['entity_id'] ?? $entitiesToUpdate[0]['entity_id'] ?? null;
    }
}
