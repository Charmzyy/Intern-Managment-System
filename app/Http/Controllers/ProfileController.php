<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
   
  
    public function profile(Request $request){
       // dd($request->all());
        try {
            $user = User::where('id',auth()->user()->id)->first();
            if(!$user){
                return response()->json(['Message'=>'User Not Found']);
            }
             if($request->hasFile('avatar')){
                $file = $request->file('avatar');
                $avatar_path = $file->store('avatars','public');
                $user->avatar= $avatar_path;
             }

         $user->position = $request->input('position');
         $user->bio =$request->input('bio');
         $user->save();


          
           
              return response()->json($user,200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json($th->getMessage(),500);
        }
    

    }
    public function updateprofile(Request $request,$id){
         //code...should update my profile only
            $validatedData = $request->validate([
                'position' => 'sometimes',
                'bio' => 'sometimes',
                'avatar' => 'sometimes',
                
            ]);
            $profile = User::find($id);
            if(!$profile){
                return response()->json(['Message'=>'User Not found']);
            }
           
            if($request->hasFile('avatar')){
                $file = $request->file('avatar');
                $avatar_path = $file->store('avatars','public');
                $profile->avatar= $avatar_path;
             }
          
             $data = [
                'position' => $validatedData['position'] ?? null, //if input not present use existing value
                'bio' => $validatedData['bio'] ?? null,
                'avatar'=>$validatedData['avatar']??null,
            ];
            User::where('id',$id)->update($data);
    }
    public function myprofile(){
        $user = User::where('id',auth()->user()->id)->first();
        $position = $user->position ?? null;
        $bio = $user->bio ??null;
        $avatar =$user->avatar ?? null;

        $data = [
            'name' =>$user->name,
            'position'=>$position ,
           'bio'=>$bio,
        'avatar'=>Storage::url($avatar)
        ];
        return response()->json($data,200);
    }
}
