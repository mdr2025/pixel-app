<?php

namespace PixelApp\Console\Commands\PassportConfiguringCommands;

use Illuminate\Console\Command; 
use PixelApp\CustomLibs\PixelCycleManagers\PixelPassportManager\PixelPassportManager;

class PixelPassportConfiguringCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pixel-passport:configure';

    /**
     * The console command description.
     * @todo later
     * @var string
     */
    protected $description = 'Configuring passport for pixel app whatever app type it is !';
 

    protected function askForSetupType() : bool
    {
        $answer = $this->choice(
            "Do you want to setup passport for the first time for this project ! ... (If the project is in production or it has been set up before you should select false )",
            ['true' => 'true' , 'false' => 'false'],
            'true'
        );

        return $answer === 'true';
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $setupForFirstTime = $this->askForSetupType();
        PixelPassportManager::setupPassport($setupForFirstTime , $this);
    }
}
