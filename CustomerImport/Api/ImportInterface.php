<?php
namespace Zagam\CustomerImport\Api;

use Symfony\Component\Console\Input\InputInterface;

interface ImportInterface
{
    public const PROFILE_NAME = "profile-name";
    public const SOURCE = "source";

    /**
     * @param InputInterface $input
     * @return array
     */
    public function getImportData(InputInterface $input): array;

    /**
     * @param string $data
     * @return array
     */
    public function readData(string $data): array;

    /**
     * @param mixed $data
     * @return array
     */
    public function formatData($data): array;


}
