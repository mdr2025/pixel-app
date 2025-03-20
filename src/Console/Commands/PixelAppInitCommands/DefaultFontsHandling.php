<?php

namespace PixelApp\Console\Commands\PixelAppInitCommands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DefaultFontsHandling extends Command
{

    protected $name = 'Sets pixel-app default fonts';
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature  = 'pixel-app:handle-default-fonts'; 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is responsible to run the required packages commands';

    protected function installDefaultFonts() : void
    {
        if(class_exists(\PixelDomPdf\DomPdfExntendingCode\PixelDomPdf::class))
        {
            Artisan::call("pixel-dom-pdf:register-fonts");
        }
    }
    
    public function handle()
    { 
        $this->installDefaultFonts();
    }
}