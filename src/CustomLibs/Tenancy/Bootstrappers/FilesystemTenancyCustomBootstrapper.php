<?php

namespace PixelApp\CustomLibs\Tenancy\Bootstrappers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper;
use Stancl\Tenancy\Contracts\TenancyBootstrapper;
use Stancl\Tenancy\Contracts\Tenant;
use Illuminate\Contracts\Foundation\Application;

class FilesystemTenancyCustomBootstrapper implements TenancyBootstrapper
{

    protected Application $app;
    protected Tenant $tenant;
    public array $tempOriginalPaths = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->tempOriginalPaths = [
            'disk_roots' => [],
            'disk_urls' => [],
        ];
    }

    /**
     * @param string $disk
     * @return string
     */
    protected function getTempOriginalURLPath(string $disk  ) : string
    {
        return $this->tempOriginalPaths['disk_urls'][$disk] ?? '';
    }
    /**
     * @param string $disk
     * @param string $url
     * @return string
     *
     * returns the url to reuse it
     */
    protected function addTempOriginalURLPath(string $disk , string $url = '') : string
    {
        $this->tempOriginalPaths['disk_urls'][$disk] = $url ;
        return $url;
    }

    /**
     * @param string $disk
     * @return string
     */
    protected function getTempOriginalRootPath(string $disk  ) : string
    {
        return $this->tempOriginalPaths['disk_roots'][$disk] ;
    }
    /**
     * @param string $disk
     * @param string $path
     * @return string
     *
     * returns the path to reuse it
     */
    protected function addTempOriginalRootPath(string $disk , string $path = '') : string
    {
        $this->tempOriginalPaths['disk_roots'][$disk] = $path;
        return $path;
    }

    protected function processTenantDiskURL( string $disk , string $filesystemSuffix = '') : string
    {
        $originalURL =  $this->getFilesystemsConfigDiskOriginalURL($disk);
        $this->addTempOriginalURLPath($disk ,$originalURL);
        return !$originalURL ? '' : rtrim($originalURL  , '/' ) . "/" . $filesystemSuffix;
    }

    protected function suffixTenantDiskRoot(string $diskRoot = '',  string $filesystemSuffix = '') : string
    {
        return $diskRoot ?
                         rtrim($diskRoot, '/') . '/'. $filesystemSuffix
                         : $filesystemSuffix;
    }
    protected function overwriteDiskRoot(string $disk) : string
    {
        return str_replace(
            ['%storage_path%', '%tenant%'],
            [storage_path(), $this->tenant->getTenantKey()],
            $this->app['config']["tenancy.filesystem.root_override.{$disk}"] ?? '',
        );
    }
    protected function processTenantDiskRootPath(string $disk ,  string $filesystemSuffix = '') : string
    {
        $originalRoot = $this->getFilesystemsConfigDiskOriginalRoot($disk);
        $this->addTempOriginalRootPath($disk , $originalRoot);
        $tenantDiskRootPath = $this->overwriteDiskRoot($disk);
        return $tenantDiskRootPath ?: $this->suffixTenantDiskRoot($originalRoot , $filesystemSuffix);
    }

    protected function setFilesystemsConfigDiskURL(string $disk , ?string $value) : void
    {
        $this->app['config']["filesystems.disks.{$disk}.url"] = $value ?? '';
    }
    protected function getFilesystemsConfigDiskOriginalURL(string $disk) : string
    {
        return $this->app['config']["filesystems.disks.{$disk}.url"] ?? '';
    }
    protected function setFilesystemsConfigDiskRoot(string $disk , string $value) : void
    {
        $this->app['config']["filesystems.disks.{$disk}.root"] = $value;
    }
    protected function getFilesystemsConfigDiskOriginalRoot(string $disk) : string
    {
        return $this->app['config']["filesystems.disks.{$disk}.root"] ?? '';
    }
    protected function getTenancyDisks() : array
    {
        return $this->app['config']['tenancy.filesystem.disks'];
    }
    protected function forgetStorageTenancyDisks() : void
    {
        Storage::forgetDisk($this->app['config']['tenancy.filesystem.disks']);
    }
    protected function convertStorageFacadeToTenantContext(string $suffix) : self
    {
        // Storage facade
        $this->forgetStorageTenancyDisks();

        foreach ($this->getTenancyDisks() as $disk)
        {
            $tenantDiskRoot = $this->processTenantDiskRootPath($disk  , $suffix);
            $tenantDiskURL = $this->processTenantDiskURL($disk , $suffix);

            $this->setFilesystemsConfigDiskRoot($disk , $tenantDiskRoot);
            $this->setFilesystemsConfigDiskURL($disk , $tenantDiskURL);
        }
        return $this;
    }
    protected function getFileSystemSuffix() : string
    {
        return $this->app['config']['tenancy.filesystem.suffix_base'] . $this->tenant->getTenantKey();
    }
    protected function getFileSuffix() : string
    {
        return '';
    }

    /**
     * @param Tenant $tenant
     * @return $this
     */
    public function setTenant(Tenant $tenant): self
    {
        $this->tenant = $tenant;
        return $this;
    }
    public function bootstrap(Tenant $tenant)
    {
        $this->setTenant($tenant);
        /**
         * Must be changed later because of the separting admin panel as a separate app
         */
        if (request()->is('api/company/*') ){
            $suffix = $this->getFileSuffix();
        }else{
            $suffix = $this->getFileSystemSuffix();
        }
        $this->convertStorageFacadeToTenantContext($suffix);
    }

    protected function revertStorageFacadeContext() : void
    {
        // Storage facade
        $this->forgetStorageTenancyDisks();
        foreach ($this->getTenancyDisks() as $disk)
        {
            $this->setFilesystemsConfigDiskRoot($disk , $this->getTempOriginalRootPath($disk));
            $this->setFilesystemsConfigDiskURL($disk , $this->getTempOriginalURLPath($disk) );
        }
    }

    public function revert()
    {
        $this->revertStorageFacadeContext();
    }
}
