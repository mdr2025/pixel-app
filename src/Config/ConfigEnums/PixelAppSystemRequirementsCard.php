<?php

namespace PixelApp\Config\ConfigEnums;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppInstallingManagers\PixelAppInstallingManager;

class PixelAppSystemRequirementsCard
{
    protected static ?PixelAppSystemRequirementsCard $instance = null;
    protected ?string $systemType = null;
    protected bool $departmentsFuncRequired = false;
    protected bool $branchesFuncRequired = false;
    protected bool $citiesFuncRequired = false;
    protected bool $areasFuncRequired = false;
    protected bool $currenciesFuncRequired = false;
    protected bool $userSignatureFuncRequired = false;

    private function __construct()
    {
        // $this->setDefaultSystemType();
    }

    public static function Singleton(): PixelAppSystemRequirementsCard
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    protected function getDefaultAppType() : string
    {
        return PixelAppInstallingManager::getDefaultAppType();
    }

    protected function setDefaultSystemType(): void
    {
        $this->setSystemType( $this->getDefaultAppType() );
    }

    public function setSystemType(string $systemType): self
    {
        $this->systemType = $systemType;
        return $this;
    }

    public function getSystemType(): ?string
    {
        return $this->systemType;
    }

    // Departments functions
    public function requireDepartmentsFunc(): self
    {
        $this->departmentsFuncRequired = true;
        return $this;
    }

    public function isDepartmentsFuncRequired(): bool
    {
        return $this->departmentsFuncRequired;
    }

    // Branches functions
    public function requireBranchesFunc(): self
    {
        $this->branchesFuncRequired = true;
        return $this;
    }

    public function isBranchesFuncRequired(): bool
    {
        return $this->branchesFuncRequired;
    }

    // Cities functions
    public function requireCitiesFunc(): self
    {
        $this->citiesFuncRequired = true;
        return $this;
    }

    public function isCitiesFuncRequired(): bool
    {
        return $this->citiesFuncRequired;
    }

    // Areas functions
    public function requireAreasFunc(): self
    {
        $this->areasFuncRequired = true;
        return $this;
    }

    public function isAreasFuncRequired(): bool
    {
        return $this->areasFuncRequired;
    }

    // Currencies functions
    public function requireCurrenciesFunc(): self
    {
        $this->currenciesFuncRequired = true;
        return $this;
    }

    public function isCurrenciesFuncRequired(): bool
    {
        return $this->currenciesFuncRequired;
    }

    // User Signature functions
    public function requireUserSignatureFunc(): self
    {
        $this->userSignatureFuncRequired = true;
        return $this;
    }

    public function isUserSignatureFuncRequired(): bool
    {
        return $this->userSignatureFuncRequired;
    }
}