<?php

namespace PixelApp\Jobs\TenantCompanyJobs\TenantConfiguringProcessJobs;


use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laravel\Passport\Client;
use Laravel\Passport\PersonalAccessClient;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ClientSideFailingProcessJobs\TenantConfiguringCancelingJob;
use PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ServerSideFailingProcessJobs\TenantApprovingCancelingJob;
use PixelApp\Models\CompanyModule\TenantCompany;
use Throwable;

class TenantPassportClientsSeederJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected TenantCompany $tenant;

    public function __construct(TenantCompany $tenant )
    {
        $this->tenant = $tenant;
    }

    /**
     * @param Throwable $exception
     * @return void
     * @throws Exception
     *
     * When failed ... this method will be called by JobPipeLine object
     * Tenant database will be deleted if the user can't be seeded
     */
    public function failed(\Throwable $exception) : void
    {
        if(PixelTenancyManager::isItMonolithTenancyApp())
        {

            TenantApprovingCancelingJob::dispatch($this->tenant , $exception);

        }elseif(PixelTenancyManager::isItTenantApp())
        {
            TenantConfiguringCancelingJob::dispatch($this->tenant , $exception);
        }


        // TenantDeletingDatabaseCustomJob::dispatch($this->tenant);
        // TenantApprovingCancelingJob::dispatch($this->tenant);
        // throw new Exception($exception->getMessage());
    }

    /**
     * @param Client $client
     * @return void
     * @throws Exception
     */
    protected function createPersonalAccessClient(Client $client) : void
    {
        if(! PersonalAccessClient::create([ 'client_id' => $client->id, ]) )
        {
            throw new Exception("Failed to create tenant passport personal client !");
        }

    }
    protected function getPersonalHost() : string
    {
        return 'http://localhost';
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getCentralAppClientSecret() : string
    {
        return config('passport.personal_access_client.secret') ??
               throw new Exception("Failed to create tenant passport client ... no central secret is found in passport config or env files!");

    }

    /**
     * @return Client
     * @throws Exception
     */
    protected function createNewClient() : Client
    {
        $client = new Client();
            $client->name = $this->tenant->name;
            $client->secret = $this->getCentralAppClientSecret();
            $client->redirect = $this->getPersonalHost();
            $client->personal_access_client = 1;
            $client->password_client = 0;
            $client->revoked = 0;
        $client->save();

        return $client ?? throw new Exception("Failed to create tenant passport client !");
    }
    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->tenant->run(function (){
            $client = $this->createNewClient();
            $this->createPersonalAccessClient($client);
        });

    }
}
