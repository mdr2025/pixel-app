<?php

namespace PixelApp\Console\Commands;
 
class CreateRepositoryClass extends FileFactoryCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command for create Repository class pattern';

    /**
     * Execute the console command.
     */

    function setFilePath(): string
    {
        return "App\\Repository\\";
    }
    function setStubName(): string
    {
        return "repository";
    }
    function setSuffix(): string
    {
        return "Repo";
    }
}
