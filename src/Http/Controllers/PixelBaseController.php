<?php

namespace PixelApp\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use PixelApp\Traits\LoggingOperationsWithoutTransactions;
use PixelApp\Traits\ReadingOperationsLogging;
use PixelApp\Traits\TransactionLogging;

class PixelBaseController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests , TransactionLogging , LoggingOperationsWithoutTransactions;
 
  

    public function paginateCollection($items, $perPage = 15, $page = null, $options = [],$type=null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        $lap = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        if($type)
        {
            return [
                'data'=>[
                    'list'=>[
                        'current_page' => $lap->currentPage(),
                        'data' => $lap ->values(),
                        'first_page_url' => $lap ->url(1),
                        'from' => $lap->firstItem(),
                        'last_page' => $lap->lastPage(),
                        'last_page_url' => $lap->url($lap->lastPage()),
                        'next_page_url' => $lap->nextPageUrl(),
                        'per_page' => $lap->perPage(),
                        'prev_page_url' => $lap->previousPageUrl(),
                        'to' => $lap->lastItem(),
                        'total' => $lap->total()
                    ],'statistics'=>[]
                ]
            ];

        }
        return [
            'data'=>[
                'list'=>[
                    'current_page' => $lap->currentPage(),
                    'data' => $lap ->values(),
                    'first_page_url' => $lap ->url(1),
                    'from' => $lap->firstItem(),
                    'last_page' => $lap->lastPage(),
                    'last_page_url' => $lap->url($lap->lastPage()),
                    'next_page_url' => $lap->nextPageUrl(),
                    'per_page' => $lap->perPage(),
                    'prev_page_url' => $lap->previousPageUrl(),
                    'to' => $lap->lastItem(),
                    'total' => $lap->total()
                ]
            ]
        ];
    }

}
