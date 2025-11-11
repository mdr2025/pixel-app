<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations;

use Illuminate\Http\Request;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\Repositories\RepositryInterfaces\SystemConfigurationRepositryInterfaces\BranchRepositoryInterfaces\BranchRepositoryInterface;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ExportingServices\CSVExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ImportingFunc\BranchesImporter;

class BranchService
{

    public function __construct()
    {
    }

    protected function initBranchRepository() : BranchRepositoryInterface
    {
        return app(BranchRepositoryInterface::class);
    }

    public function getBranches(): array
    {
        // Return the list of branches
        return [
            'list' => $this->initBranchRepository()->getBranches()
        ];
    }
    public function getBranchesTeams(): array
    {

        return [
            'list' => $this->initBranchRepository()->getBranchesTeams()
        ];
    }

    public function getFirstParentBranch()
    {
        return $this->initBranchRepository()->getFirstParentBranch();
    }

    public function getCountActiveBranches()
    {
        return $this->initBranchRepository()->getCountActiveBranches();
    }

    public function getListBranches()
    {
        return $this->initBranchRepository()->getListBranches();
    }

    public function getSubBranches()
    {
        return $this->initBranchRepository()->getSubBranches();
    }

    public function store()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(BranchStoringService::class);
        return (new $service())->create();
    }
    
    public function fetchBranchById(int $branchId) : ?Branch
    {
        return $this->initBranchRepository()->fetchBranchById($branchId);
    }
    
    public function fetchBranchByIdOrFail(int $branchId) : Branch
    {
        return $this->initBranchRepository()->fetchBranchByIdOrFail($branchId);
    }

    public function update(int $branchId)
    {
        $branch = $this->initBranchRepository()->fetchBranchByIdOrFail($branchId);

        $service = PixelServiceManager::getServiceForServiceBaseType(BranchUpdatingService::class);

        return (new $service($branch))->update();
    }

    public function destroy(int $branchId)
    {
        $branch = $this->initBranchRepository()->fetchBranchByIdOrFail($branchId);
        $service = PixelServiceManager::getServiceForServiceBaseType(BranchDeletingService::class);

        return (new $service($branch))->delete();
    }

    public function addTeam()
    {
        return $this->initBranchRepository()->addMembersToDepartment();
    }

    public function import()
    {
        $importer = PixelServiceManager::getServiceForServiceBaseType(BranchesImporter::class);
        return (new $importer())->import();
    }

    public function export()
    {
        $exporter = PixelServiceManager::getServiceForServiceBaseType(BranchesImporter::class);
        return (new $exporter())->exportUsingInternalFormatName();
    }

    public function downloadFileFormat()
    {
        $importer = PixelServiceManager::getServiceForServiceBaseType(BranchesImporter::class);
        return (new $importer())->downloadFormat();
    }
}
