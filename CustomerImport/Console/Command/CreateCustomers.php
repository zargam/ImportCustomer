<?php
namespace Zagam\CustomerImport\Console\Command;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Filesystem;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Zagam\CustomerImport\Api\ImportInterface;
use Zagam\CustomerImport\Model\Customer\ProfileFactory;
use Zagam\CustomerImport\Model\Customer;
use Magento\Framework\Exception\LocalizedException;

class CreateCustomers extends Command
{
    protected $importer;

    protected $profileFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Customer
     */

    private $customer;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var State
     */
    private $state;

    /**
     * CustomerImport constructor.
     * @param ProfileFactory $profileFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ProfileFactory $profileFactory,
        Customer $customer,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        State $state
    ) {
        parent::__construct();

        $this->profileFactory = $profileFactory;
        $this->customer = $customer;
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->state = $state;
    }

    /**
    * {@inheritdoc}
    */
    protected function configure(): void
    {

        $this->setName("customer:import");
        $this->setDescription("Importing Customers via CSV & JSON");
        $this->setHelp('Run this command to importing Customers via CSV & JSON.');
        $this->setDefinition([
            new InputArgument(ImportInterface::PROFILE_NAME, InputArgument::REQUIRED, "Profile Name: sample-csv"),
            new InputArgument(ImportInterface::SOURCE, InputArgument::REQUIRED, "Source: sample.csv")
        ]);
        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $profileType             = $input->getArgument(ImportInterface::PROFILE_NAME);
        $filePath                = $input->getArgument(ImportInterface::SOURCE);
        $file_extension          = pathinfo($filePath, PATHINFO_EXTENSION);
        $file_path_end_extension = explode('-', $profileType);

        try {
           if($file_extension !== end($file_path_end_extension)){
                throw new LocalizedException(__('Profile name and Source extension should be same eg. Profile Name : *-csv, Source : /*/*.csv'));
           }
            $this->state->setAreaCode(Area::AREA_GLOBAL);

            if ($importData = $this->getImporterInstance($profileType)->getImportData($input)) {
                $storeId = $this->storeManager->getStore()->getId();
                $websiteId = $this->storeManager->getStore($storeId)->getWebsiteId();
                $progressBar = new ProgressBar($output, count($importData));
                foreach ($importData as $data) {
                    $this->customer->createCustomer($data, $websiteId, $storeId);
                    $progressBar->advance();
                }
                $progressBar->finish();
                $output->writeln(__("<info> Completed!</info>"));
                return Command::SUCCESS;
            }

            return Command::FAILURE;

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $output->writeln("<error>$msg</error>", OutputInterface::OUTPUT_NORMAL);
            return Command::FAILURE;
        }
    }

    /**
     * @param $profileType
     * @return ImportInterface
     */
    protected function getImporterInstance($profileType): ImportInterface
    {
        if (!($this->importer instanceof ImportInterface)) {
            $this->importer = $this->profileFactory->create($profileType);
        }
        return $this->importer;
    }
}
