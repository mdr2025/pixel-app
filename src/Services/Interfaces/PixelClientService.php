<?php

namespace PixelApp\Services\Interfaces;

use Illuminate\Http\JsonResponse;

interface PixelClientService
{
    public function getResponse() : JsonResponse;
}