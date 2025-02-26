<?php

namespace PixelApp\Console\Commands;

class CreateTrait extends FileFactoryCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {classname}'; // without service Ex: Dashboard , resault => DashboardServide

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command for create trait';

    /**
     * Execute the console command.
     */

    function setFilePath(): string
    {
        return "App\\Traits\\";
    }
    function setStubName(): string
    {
        return "trait";
    }
    function setSuffix(): string
    {
        return "Trait";
    }
}
