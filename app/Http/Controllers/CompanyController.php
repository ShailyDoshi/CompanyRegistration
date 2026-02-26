<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyFormRequest;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function index()
    {
        return Inertia::render('Company/Index');
    }

    public function form(Company $company)
    {

        return Inertia::render('Company/Form',['company'=>$company->exists ? $company : null]);
    }

    public function createOrUpdate(CompanyFormRequest $request, Company $company)
    {
        try {
            DB::beginTransaction();
            $data = $request->fields();
            if($request->hasFile('logo'))
            {
                if($company->exists && $company->logo){
                    Storage::disk('public')->delete($company->logo);
                }

                $data['logo'] = $request->file('logo')->store('company_logo','public'); 
            }
            $company->fill($data)->save();
            DB::commit();
            return response()->json(['message'=> "{$company->name} Had Been {$request->action()}  Successfully"]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage());
        }
    }

    public function getAllData(Request $request){
        try{
            $per_page = $request->get('perPage',5);
            $search = $request->get('search');
            $companies = Company::when($search,function ($query) use ($search){
                $query->where('name','like',"%{$search}%")
                    ->orWhere('email','like',"%{$search}%");
            })
            ->orderBy('id','desc')
            ->paginate($per_page);
            return response()->json(['data'=>$companies]);
        }
        catch(Exception $exception)
        {
            return response()->json($exception->getMessage());
        }
    }

    public function delete(Company $company)
    {
        try {
            DB::beginTransaction();
            if($company->logo)
            {
                Storage::disk('public')->delete($company->logo);            
            }
            $company->delete();
            DB::commit();
            return response()->json(['message'=> "{$company->name} Had Been Deleted Successfully"]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage());
        }
    }
}
