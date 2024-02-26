<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Models\Attache_details;

use Illuminate\Http\Request;
use App\Models\Role;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Mail\WelcomeMail;

use Carbon\Carbon;


class AttacheController extends Controller
{
    

       

    // ... rest of your controller code





    public function store(Request $request)
{try {
    //code...
    $validator = Validator::make($request->all(), [
        'fullname' => 'required',
        'email' => 'required|email|unique:attache_details',//accept only once
        'phone'=>'required|min:10|numeric',
        'academic' => 'required',
        'role_id'=> 
        [
            'required',
            Rule::exists('roles','id')
        ],
        'duration'=>'required|integer|min:90',
        'cv' => 'required|mimes:pdf,doc,docx|max:2048'
        
    ]);
      

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    $cv_path=$request->file('cv')->store('public/uploads');

    
    $attache_detail = Attache_details::create([
        'fullname'=>$request->fullname,
        'email'=>$request->email,
        'phone'=>$request->phone,
        'academic'=>$request->academic,
        'role_id'=>$request->role_id,
        'duration'=>$request->duration,
        'cv'=>$cv_path,
        
        
    ]);
    $data = [
        'attachee'=>$attache_detail
    ];
    Mail::to($request->input('email'))->send(new WelcomeMail($data));
    return response()->json($data, 201);
} catch (\Throwable $th) {
    //throw $th;
    return response()->json([$th->getMessage()]);
}
   


    
}




}
