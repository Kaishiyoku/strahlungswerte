<?php

namespace App\Libraries\Odl\Models;

use Illuminate\Support\Collection;

class ArchiveDataContainer
{
    /**
     * @var string
     */
    private $directoryName;

    /**
     * @var string
     */
    private $locationFilePath;

    /**
     * @var string
     */
    private $statisticsFilePath;

    /**
     * @var Collection
     */
    private $measurementSiteFilePaths;

    /**
     * @param string $directoryName
     * @param string $locationFilePath
     * @param string $statisticsFilePath
     * @param Collection $measurementSiteFilePaths
     */
    public function __construct(string $directoryName, string $locationFilePath, string $statisticsFilePath, Collection $measurementSiteFilePaths)
    {
        $this->directoryName = $directoryName;
        $this->locationFilePath = $locationFilePath;
        $this->statisticsFilePath = $statisticsFilePath;
        $this->measurementSiteFilePaths = $measurementSiteFilePaths;
    }

    /**
     * @return string
     */
    public function getDirectoryName(): string
    {
        return $this->directoryName;
    }

    /**
     * @return string
     */
    public function getLocationFilePath(): string
    {
        return $this->locationFilePath;
    }

    /**
     * @return string
     */
    public function getStatisticsFilePath(): string
    {
        return $this->statisticsFilePath;
    }

    /**
     * @return Collection
     */
    public function getMeasurementSiteFilePaths(): Collection
    {
        return $this->measurementSiteFilePaths;
    }
}
