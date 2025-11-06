<?php

namespace PixelApp\Services\CoreServices;

use PixelApp\Interfaces\MustLogInterfaceFields;
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\StoringServices\SingleRowStoringService;

Abstract class StoringSingleRowServiceWithLog extends SingleRowStoringService
{
    // public function __construct()
    // {
    //     parent::__construct();
    // }

    // private function checkIsModelImplementLog():void
    // {
    //         if ($this->Model instanceof MustLogInterfaceFields) {
    //             LogDataCreatingObjectModel::logModelCreation($this->Model);
    //         }
    // }
    // public function __destruct()
    // {
    //     $this->checkIsModelImplementLog();
    // }
}