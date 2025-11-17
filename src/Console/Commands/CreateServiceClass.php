<?php

namespace PixelApp\Console\Commands;

class CreateServiceClass extends FileFactoryCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {classname}'; // without service Ex: Dashboard , resault => DashboardServide

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command for create service class pattern';

    /**
     * Execute the console command.
     */

    function setFilePath(): string
    {
        return "App\\Services\\";
    }
    function setStubName(): string
    {
        return "service";
    }
    function setSuffix(): string
    {
        return "Service";
    }
}
