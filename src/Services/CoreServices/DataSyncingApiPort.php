<?php

namespace PixelApp\Services\CoreServices;

use Illuminate\Http\JsonResponse;

abstract class DataSyncingApiPort
{

    abstract public function sync() : JsonResponse;

}