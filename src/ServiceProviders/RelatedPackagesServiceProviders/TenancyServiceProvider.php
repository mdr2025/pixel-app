<?php

declare(strict_types=1);

namespace PixelApp\ServiceProviders\RelatedPackagesServiceProviders;
 
use Illuminate\Support\Facades\Event; 
use Illuminate\Support\ServiceProvider; 
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients\PixelAdminPanelAppClient;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppsConnectionManager;
use PixelApp\CustomLibs\Tenancy\DomainTenantResolvers\CustomDomainTenantResolver;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager ;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\FromCentralSideEvents\TenantDBDataSyncingEvent;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\FromTenantSideEvents\MonolithAppEvents\CentralDBDataSyncingEvent;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\FromTenantSideEvents\SeparatedTenantAppEvents\AdminPanelDBDataSyncingEvent;
use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApprovingEvents\ApprovedByAdminPanel;
use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApprovingEvents\ApprovedByCentralApp;
use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApprovingEvents\RequestTenantAppToConfigureApprovedTenant;
use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApprovingEvents\TenantConfiguringCancelingEvent;
use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyRegistered;
use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantDefaultAdminNewEmailHavingEvent;
use PixelApp\Listeners\TenancyListeners\DataSyncingListeners\FromCentralSideListeners\TenantDBDataSyncingListener;
use PixelApp\Listeners\TenancyListeners\DataSyncingListeners\FromTenantSideListeners\MonolithAppListeners\CentralDBDataSyncingListener;
use PixelApp\Listeners\TenancyListeners\DataSyncingListeners\FromTenantSideListeners\SeparatedTenantAppListeners\AdminPanelDBDataSyncingListener;
use PixelApp\Listeners\TenancyListeners\TenantCompanyEventListeners\NewTenantDefaultAdminEmailVerificationSenderListener;
use PixelApp\Listeners\TenancyListeners\TenantCompanyEventListeners\TenantCompanyApprovingListeners\AdminPanelTenantCompanyApprovingListener;
use PixelApp\Listeners\TenancyListeners\TenantCompanyEventListeners\TenantCompanyApprovingListeners\CentralAppTenantCompanyApprovingListener;
use PixelApp\Listeners\TenancyListeners\TenantCompanyEventListeners\TenantCompanyApprovingListeners\TenantConfiguringCancelingEventListener;
use PixelApp\Listeners\TenancyListeners\TenantCompanyEventListeners\TenantCompanyApprovingListeners\TenantConfiguringRequestListener;
use PixelApp\Listeners\TenancyListeners\TenantCompanyEventListeners\TenantDefaultAdminChangedEmailVerificationSenderListener;
use PixelApp\Listeners\TenancyListeners\TenantCompanyEventListeners\TenantRegisteringListener;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Listeners; 
use Stancl\Tenancy\Resolvers\DomainTenantResolver;

class TenancyServiceProvider extends ServiceProvider
{
    // By default, no namespace is used to support the callable array syntax.
    // public static string $controllerNamespace = '';

    public function register()
    { 
        $this->changeDomainTenantResolver(); 
        $this->registerConnectionManagmentClasses();
    }

    public function boot()
    { 
        $this->bootEvents(); 

        $this->makeTenancyMiddlewareHighestPriority(); 
    }

    public function events()
    {
        return [
            // Tenant events
            Events\CreatingTenant::class => [],
            Events\TenantCreated::class => [],
            Events\SavingTenant::class => [],
            Events\TenantSaved::class => [],
            TenantCompanyRegistered::class => [
                TenantRegisteringListener::class,
                NewTenantDefaultAdminEmailVerificationSenderListener::class
            ],
            TenantDefaultAdminNewEmailHavingEvent::class =>
            [
                TenantDefaultAdminChangedEmailVerificationSenderListener::class
            ],
            Events\UpdatingTenant::class => [],
            Events\TenantUpdated::class => [],
            ApprovedByAdminPanel::class => [
                AdminPanelTenantCompanyApprovingListener::class,
            ],
            ApprovedByCentralApp::class => [
                CentralAppTenantCompanyApprovingListener::class
            ],
            RequestTenantAppToConfigureApprovedTenant::class => [
                TenantConfiguringRequestListener::class
            ],
            TenantConfiguringCancelingEvent::class => [
                TenantConfiguringCancelingEventListener::class
            ],
            Events\DeletingTenant::class => [],
            Events\TenantDeleted::class => [],

            // Domain events
            Events\CreatingDomain::class => [],
            Events\DomainCreated::class => [],
            Events\SavingDomain::class => [],
            Events\DomainSaved::class => [],
            Events\UpdatingDomain::class => [],
            Events\DomainUpdated::class => [],
            Events\DeletingDomain::class => [],
            Events\DomainDeleted::class => [],

            // Database events
            Events\DatabaseCreated::class => [],
            Events\DatabaseMigrated::class => [],
            Events\DatabaseSeeded::class => [],
            Events\DatabaseRolledBack::class => [],
            Events\DatabaseDeleted::class => [],

            // Tenancy events
            Events\InitializingTenancy::class => [],
            Events\TenancyInitialized::class => [
                Listeners\BootstrapTenancy::class,
            ],

            Events\EndingTenancy::class => [],
            Events\TenancyEnded::class => [
                Listeners\RevertToCentralContext::class,
            ],

            Events\BootstrappingTenancy::class => [],
            Events\TenancyBootstrapped::class => [],
            Events\RevertingToCentralContext::class => [],
            Events\RevertedToCentralContext::class => [],

            // Resource syncing
            Events\SyncedResourceSaved::class => [
//                Listeners\UpdateSyncedResource::class,
            ],
            TenantDBDataSyncingEvent::class => [
                TenantDBDataSyncingListener::class
            ],
            CentralDBDataSyncingEvent::class => [
                CentralDBDataSyncingListener::class
            ],
            AdminPanelDBDataSyncingEvent::class => [
                AdminPanelDBDataSyncingListener::class
            ],
            // Fired only when a synced resource is changed in a different DB than the origin DB (to avoid infinite loops)
            Events\SyncedResourceChangedInForeignDatabase::class => [],
        ];
    }

    /** Overriding DomainTenantResolver defined by the package ith our custom one*/
    protected function changeDomainTenantResolver()  : void
    {
        $this->app->bind(DomainTenantResolver::class, function ()
        {
            return app(CustomDomainTenantResolver::class);
        });
    }

    protected function doesItNeedAdminPanelConnection() : bool
    {
        return !PixelTenancyManager::isItAdminPanelApp() 
               &&
               PixelTenancyManager::isItTenancySupporterApp();
    }

    protected function registerConnectionManagmentClasses() : void
    {
        $this->registerAdminPanelAppClient();
    }

    protected function initPixelAppsConnectionManager() : PixelAppsConnectionManager
    {
        return PixelAppsConnectionManager::Singleton();
    }

    protected function registerAdminPanelAppClient() : void
    { 
        if($this->doesItNeedAdminPanelConnection())
        { 
            $this->initPixelAppsConnectionManager()
                 ->registerPixelAppClient(
                                        PixelAdminPanelAppClient::class ,
                                        PixelAdminPanelAppClient::getClientName()
                                     );
        }
    }

    protected function bootEvents()
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof JobPipeline) {
                    $listener = $listener->toListener();
                }

                Event::listen($event, $listener);
            }
        }
    } 

    protected function makeTenancyMiddlewareHighestPriority()
    {
        $tenancyMiddleware = PixelTenancyManager::getTenantDefaultMiddlewares(); 

        foreach (array_reverse($tenancyMiddleware) as $middleware) {
            $this->app[\Illuminate\Contracts\Http\Kernel::class]->prependToMiddlewarePriority($middleware);
        }
    }
}
