<?php


use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Database\Models\Domain;
use App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists\CountriesController;
use Illuminate\Http\JsonResponse;

// Route::prefix('admin')->group(function () {
//     requirePhpFiles(__DIR__ . '/APIs/SystemAdminPanel/WorkSector/CompanyModule/CompanyManagement');
// //    requirePhpFiles(__DIR__ . '/APIs/SystemAdminPanel/WorkSector');
// });
// requirePhpFiles(__DIR__ . '/APIs/SystemAdminPanel/WorkSector/CompanyModule/CompanyAuth');

Route::get('/', function (): JsonResponse
{

    return response()->json([
        'status' => 200,
        'message' => 'Welcome to the API',
    ]);
})->middleware('reqLimit');



Route::get('Unauthorized', function ()
{
    return response()->json(['message' => 'Unauthorized'], 401);
})->middleware('reqLimit');
 
 