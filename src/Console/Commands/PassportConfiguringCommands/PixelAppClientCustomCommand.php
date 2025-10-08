<?php

namespace PixelApp\Console\Commands\PassportConfiguringCommands;

use Illuminate\Console\Command;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use PixelApp\CustomLibs\PixelCycleManagers\PixelPassportManager\PixelPassportManager;

class PixelAppClientCustomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pixel-passport:create-clients {--name= : The name of the client}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a client + personal access client for issuing access tokens & creates a client credentials client (for machine connection) on admin panel , tenant apps';

    /**
     * Execute the console command.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @return void
     */
    public function handle(ClientRepository $clients)
    {
        $this->createPersonalClient($clients);
        $this->createClientCredentialsClient($clients);
    }

    /**
     * Create a new personal access client.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @return void
     */
    protected function createPersonalClient(ClientRepository $clients)
    {
        $name = $this->option('name') ?: config('app.name').' Personal Access Client';

        $client = $clients->createPersonalAccessClient(
            null, $name, 'http://localhost'
        );

        $this->writePersonalClientToConfig($client);

        $this->info('Personal access client created & saved in config file successfully.');

    }

    protected function writePersonalClientToConfig(Client $client) : void
    {
        $clientConfigValue = $this->composeClientConfigsArray($client);
        PixelPassportManager::writeToConfig("personal_access_client" , $clientConfigValue);
    }
     
    protected function doesItSupportMachineClientCredentialsGrant() : bool
    {
        return PixelPassportManager::doesItSupportMachineClientCredentialsGrant();
    }

    /**
     * Create a client credentials grant client.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @return void
     */
    protected function createClientCredentialsClient(ClientRepository $clients)
    {
        if(! $this->doesItSupportMachineClientCredentialsGrant())
        {
            return ;
        }

        $name = $this->option('name') ?: config('app.name').' ClientCredentials Grant Client';

        $client = $clients->create(
            null, $name, ''
        );

        $this->writeMachineCredentialsClientToConfig($client);

        $configKeyName = $this->getMachineCredentialsClientConfigKey();

        $this->info(
            'New client Credentials created successfully ! , You shold use its info during connecting the system from another system context , I is saved in passport config file under key = ' . $configKeyName
        );
    }

    protected function getMachineCredentialsClientConfigKey() : string
    {
        return "machine_client_credentials_client";
    }

    protected function writeMachineCredentialsClientToConfig(Client $client) : void
    {
        $clientConfigValue = $this->composeClientConfigsArray($client);
        PixelPassportManager::writeToConfig($this->getMachineCredentialsClientConfigKey() , $clientConfigValue);
    }

    protected function composeClientConfigsArray(Client $client) : array
    {
        return [
            PixelPassportManager::getClientIdKeyName() => $client->id,
            PixelPassportManager::getClientSecretKeyName() => $client->plainSecret
        ];
    }
}
