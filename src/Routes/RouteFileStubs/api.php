<?php


use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Database\Models\Domain;
use App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists\CountriesController;


// Route::prefix('admin')->group(function () {
//     requirePhpFiles(__DIR__ . '/APIs/SystemAdminPanel/WorkSector/CompanyModule/CompanyManagement');
// //    requirePhpFiles(__DIR__ . '/APIs/SystemAdminPanel/WorkSector');
// });
// requirePhpFiles(__DIR__ . '/APIs/SystemAdminPanel/WorkSector/CompanyModule/CompanyAuth');




Route::get('Unauthorized', function () {
    return response()->json(['message' => 'Unauthorized'], 401);
})->middleware('reqLimit');
 
 