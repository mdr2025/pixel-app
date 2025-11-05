<?php

namespace PixelApp\Services\Repositories\RepositryInterfaces\SystemConfigurationRepositryInterfaces\BranchRepositoryInterfaces;

use PixelApp\Models\SystemConfigurationModels\Branch;

interface BranchRepositoryInterface
{
   public function addMembersToDepartment();
   public function getSubBranches();
   public function getListBranches();
   public function getCountActiveBranches();
   public function getBranchesTeams();
   public function getFirstParentBranch();
   public function getBranches();
   public function fetchBranchById(int $id) : ?Branch;
   public function fetchBranchByIdOrFail(int $id) : Branch;
}