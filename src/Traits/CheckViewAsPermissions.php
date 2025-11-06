<?php

namespace PixelApp\Traits;

trait CheckViewAsPermissions
{
    protected function checkViewAsPermissions(array $defaultOptions = []): bool
    {
        $viewAs = request()->query('view_as') ?? request()->input('view_as');
        return in_array($viewAs, $defaultOptions, true);
    }
}
