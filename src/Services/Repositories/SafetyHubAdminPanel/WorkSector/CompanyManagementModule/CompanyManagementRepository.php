<?php

namespace App\Repositories\SafetyHubAdminPanel\WorkSector\CompanyManagementModule;

use App\Models\SafetyHubAdminPanel\CompanyModule\Company;
use App\Models\SafetyHubAdminPanel\CompanyModule\TenantCompany;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyManagementRepository
{
    // public function index(array $filter = [])
    // {
    //     return QueryBuilder::for(Company::class)
    //         ->createdBy()
    //         ->with(['companySector'])
    //         ->allowedFilters([...$filter])
    //         ->datesFiltering()
    //         ->customOrdering()
    //         ->paginate(request()->pageSize ?? 10);
    // }
    // public function list()
    // {
    //     return QueryBuilder::for(Company::class)
    //         ->allowedFilters(['name'])
    //         ->customOrdering('created_at', 'desc')
    //         ->get(['id', 'amount']);
    // }
    // public function hide(TenantCompany $company)
    // {
    //     return $company->delete();
    // }
    // public function delete(TenantCompany $company)
    // {
    //     return $company->withTrashed()->forceDelete();
    // }

    // public function duplicate(TenantCompany $company, array $data)
    // {
    //     $copy = $company->replicate()->fill($data);
    //     return $copy->save();
    // }

    // public function changeStatus(TenantCompany $company, string $status)
    // {
    //     return $company->update(['registration_status' => $status]);
    // }

    // public function getProfile()
    // {
    //     return Company::where('company_domain', tenant('id'))->with('country')->first();
    // }

    // public function updateProfile(Company $company, array $data)
    // {
    //     return $company->update($data);
    // }

    // public function getSignupList(array $filters = [], $request)
    // {
    //     $query = QueryBuilder::for(Company::with('contacts'))
    //         ->allowedFilters([...$filters])
    //         ->where('registration_status', TenantCompany::REGISTRATIONS_DEFAULT_STATUS);

    //     $this->applyEmailVerificationFilter($query, $request);

    //     return $query->paginate(request()->pageSize ?? 10);
    // }

    // public function getCompanyList(array $filters = [])
    // {
    //     return QueryBuilder::for(Company::class)
    //         ->with('contacts')
    //         ->allowedFilters(
    //             [...$filters]
    //         )
    //         ->isApproved()
    //         ->paginate(request()->pageSize ?? 10);
    // }

    // private function applyEmailVerificationFilter($query, $request)
    // {
    //     $status = $request->input('filter.email_verified_at');

    //     $filters = [
    //         'verified' => fn() => $query->whereNotNull('email_verified_at'),
    //         'not verified' => fn() => $query->whereNull('email_verified_at'),
    //     ];

    //     $filters[$status]?->call($this);
    // }

    // public function updateCompanyEmail(TenantCompany $company, string $email)
    // {
    //     return $company->update(['admin_email' => $email]);
    // }

    // public function getCompanyByEmail(string $email)
    // {
    //     return TenantCompany::whereAdminEmail($email)->first();
    // }
}
