<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\Tratis;
 
trait ExporterQueryBuilderCustomization
{ 
    protected function SelectColumns($builder) : void
    {
        $builder->select(
                            [
                                'id',
                                'name',
                                'code',
                                'symbol',
                                'symbol_native',
                                'decimal_digits',
                                'rounding',
                                'name_plural',
                                'is_main',
                                "status"
                            ]
                        );
    }
    
}