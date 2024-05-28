# Zagam_CustomerImport

### Type 1: Zip file

 - Unzip the zip file in `app/code`
 - Enable the module by running `php bin/magento module:enable Zagam_CustomerImport`
 - Apply database updates by running `php bin/magento setup:upgrade`
 - Flush the cache by running `php bin/magento cache:flush`

***************************************************************

## Specifications and Usage(Customer Import):
- Console Command
 - JSON profile - Place json inside var/import/ folder -   
    ## php bin/magento customer:import sample-json var/import/sample.json

 - CSV profile - Place CSV inside var/import/ folder -     
    ## php bin/magento customer:import sample-csv var/import/sample.csv

 - After customer import run script, we also need to make sure to re-index the Customer Grid indexer - 
    ## php bin/magento indexer:reindex customer_grid 

****************************************************************

 ## Specifications and Usage(Customer Address Import):
- Console Command
 - JSON profile - Place json inside var/import/ folder -   
    ## php bin/magento customer:address:import sample-address-json var/import/sample_address.json

 - CSV profile - Place CSV inside var/import/ folder -     
    ## bin/magento customer:address:import sample-address-csv var/import/sample_address.csv

 - After customer import run script, we also need to make sure to re-index the Customer Grid indexer - 
    ## php bin/magento indexer:reindex customer_grid 

***************************************************************** 

