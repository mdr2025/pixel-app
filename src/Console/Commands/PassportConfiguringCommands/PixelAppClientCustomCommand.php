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
    protected $signature = 'pixel-passport:create-client {--name= : The name of the client}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a client + personal access client for issuing access tokens';

    /**
     * Execute the console command.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @return void
     */
    public function handle(ClientRepository $clients)
    {
        $this->createPersonalClient($clients);
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
        $clientConfigValue = $this->composePersonalClientConfigs($client);
        PixelPassportManager::writeToConfig("personal_access_client" , $clientConfigValue);
    }

    protected function composePersonalClientConfigs(Client $client) : array
    {
        return [
            "id" => $client->id,
            "secret" => $client->plainSecret
        ];
    }

}
