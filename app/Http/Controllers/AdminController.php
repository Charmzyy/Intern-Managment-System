<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Attache_details;
use App\Models\Interview;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Supervisor_Attachee;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\NewSupervisorMail;
use App\Mail\InterviewEmail;
use App\Mail\AssignedInterview;
use App\Mail\CanceledInterview;
use App\Mail\UpdateAssignedInterview;
use App\Mail\UpdateInterviewEmail;
use App\Mail\RejectedMail;
use App\Mail\AcceptedMail;
use App\Mail\UnsuccsessfulMail;
use App\Mail\SupervisorMail;
use App\Mail\SupervisionMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     
     public function unassigned(){
        $users =User::where('role',2)->where('assigned',null)->get();
        if ($users->isEmpty()) {
            return response()->json(["Message"=>'No new interns'],404);
        } 
        $data = [];
        foreach ($users as $user) {
           
            
            // Add the supervisor's data to the $data array
            $data[] = [
                'id' =>$user->id,
                'name' => $user->name,
                'email'=>$user->email,
                'phone'=>$user->phone
               
                
            ];
        }
return response()->json($data,200);
        
        
     }
     public function delete_application($id){
        try {
            
            //code..
            $application = Attache_details::find($id);
        if(!$application){
            return response()->json(['Deleted Successfully'],204);
        }
        Attache_details::destroy($id);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()]);
        }
        
     }
    public function createSupervisor(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|numeric|min:10',
        
    ]);

    // Generate random password
    $password = Str::random(8);

    // Create supervisor user
    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'phone' => $validatedData['phone'],
        'password' => Hash::make($password),
        'role' => 0, // Assuming 2 is the role ID for supervisors
    ]);

    // Send email to supervisor with password
    $data = [
        'name' => $user->name,
        'email' => $user->email,
        'password' => $password,
    ];

    Mail::to($user->email)->send(new NewSupervisorMail($data));

    return response()->json(['message' => 'Supervisor created successfully.']);
}

public function destroysupervisor($id){
    try {
        //code...
        $supervisor = User::find($id);
        if(!$supervisor){
            return response()->json(['Message'=>'No such Supervisor']);
        }
        User::destroy($id);
        return response()->json(['Message'=>'Supervisor Deleted'],204);
    } catch (\Throwable $th) {
        //throw $th;
        return response()->json('Deleted',204);
    }
  
    
}
    public function applications(){
        try {
            //code...
            $current_year = now()->year;
            $current_year_applications = Attache_details::whereYear('created_at',$current_year)->get()->count();
            $previous_year_applications = Attache_details::whereYear('created_at',$current_year-1)->get()->count();
            $total_applications = Attache_details::get()->count();
            if($previous_year_applications>0){
                $percentage_change= ($current_year_applications-$previous_year_applications)/$previous_year_applications*100;
            }
            else{
                $percentage_change = 0;
            }
            $data =[
               'current_year'=>$current_year,
               'current_year_applications'=>$current_year_applications,
               'previous_year_applications'=>$previous_year_applications,
               'total_applications'=>$total_applications,
               'percentage_change'=>$percentage_change
            ];
            return response()->json($data);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json($th->getMessage());
        }
       
       
    }
    public function interview_conducted(){
        try {
            //code... percentage change
            $current_year=now()->year;
            $current_year_interviews=Interview::where('conducted',1)->whereYear('created_at',$current_year)->get()->count();
            $previous_year_interviews=Interview::where('conducted',1)->whereYear('created_at',$current_year-1)->get()->count();
            $total_interviews =Interview::where('conducted',1)->get()->count();
            if($previous_year_interviews>0){
                $percentage_change = ($current_year_interviews-$previous_year_interviews)/$previous_year_interviews*100;
            }
            else{
                $percentage_change=0;
            }
            $data = [
                'total_interviews'=>$total_interviews,
                'current_year'=>$current_year,
                'current_year_interviews'=>$current_year_interviews,
                'previous_year_interviews'=>$previous_year_interviews,
                
                'percentage_change'=>$percentage_change
            ];
            return response()->json($data,200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()]);
        }
       
    }
   public function interns(){
    try {
        //code...
        $users = User::where('role',2)->get()->count();
        $current_year = now()->year;
        $current_interns =  User::whereYear('created_at',$current_year)->where('role',2)->get()->count();
        $previous_interns = User::whereYear('created_at',$current_year-1)->where('role',2)->get()->count();
     
        if($previous_interns>0){
            $percentage_change = ($current_interns - $previous_interns)/$previous_interns *100;
        }
        else{
            $percentage_change=0;
        }
        $data = [
            'users'=>$users,
            'current_year'=>$current_year,
            'previous_year'=>$previous_interns,
            'current_interns'=>$current_interns,
            'previous_interns'=>$previous_interns,
            'percentage_change'=>$percentage_change
        ];
        return response()->json($data,200);
    } catch (\Throwable $th) {
        //throw $th;
        return response()->json($th->getMessage());
    }
   

   }
    public function getroles(){
        try {
            //code...
            $roles =Role::get();
            if(!$roles){
                return response()->json(["Message"=>"No roles available for selection"]);
            }
            $data =[
                'roles'=>$roles
            ];
            return response()->json($data,200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                $th->getMessage()
            ]);
        }
       
       
    }
   
    public function create(Request $request){
        try {
            //code...
             $validateData = $request->validate([
            'role'=>'required',
            'description'=>'required'
        ]);

        $role = Role::create([
               'role'=>$validateData['role'],
                'description'=>$validateData['description']
        ]);
        $data =[
            'role'=>$role,
            'Message'=>'Role created'
        ];
        return response()->json($data,201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                $th->getMessage()
            ]);
        }
       
    }


    
    public function index()
    {//get all supervisors
       

        try {
           
            $supervisors = User::where('role',0)->get();
            if(!$supervisors){
                return response()->json(['Message'=>"No Supervisors Exist"],404);
            }
            $data = [];
            foreach ($supervisors as $supervisor) {
                // Get the count of assigned interns for each supervisor
                $assignedInternsCount = $supervisor->interns()->count();
                
                // Add the supervisor's data to the $data array
                $data[] = [
                    'supervisor_id'=>$supervisor->id,
                    'supervisor_name' => $supervisor->name,
                    'supervisor_email'=>$supervisor->email,
                    'phone'=>$supervisor->phone,
                    'supervisor_job'=>$supervisor->position,
                    'assignedinterns' => $assignedInternsCount,
                ];
            }
            
            return response()->json(['supervisors' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(["Message"=>"Error Bad Request",500]);
        }
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function attache(){
        $attachees = Attache_details::where('is_accepted',null)->where('chosen',null)->get();
        try {
            if($attachees->isEmpty()){
                return response()->json(['No New applicants found'], 404);
            }
            $data =[];
            foreach ($attachees as $attachee) {
                $role = $attachee->role ? $attachee->role->role : null; // check if the role is null and set the value accordingly
                $data[]= [
                    'id' => $attachee->id,
                    'fullname' => $attachee->fullname,
                    'email' => $attachee->email,
                    'phone' => $attachee->phone,
                    'academic' => $attachee->academic,
                    'role' => $role,
                    'duration' => $attachee->duration,
                    'cv' => Storage::url($attachee->cv),
                ];
            }
            
            
            
            return response()->json([
                'attachee' => $data,
                'message' => 'All applicants'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)

    {  
       try {
        //code...
       
         $attachee = Attache_details::findOrfail( $request->input(['attachee_id']));
     
         if($attachee->is_scheduled){
            return response()->json(['message' => 'Interview already scheduled for this applicant.'],400);
         }
         else{
    
        
        $today = Carbon::now();
        $supervisor = User::findOrFail($request->input(['user_id']));
                 $validateData = $request->validate([
                   'name'=>'required',
                   'venue'=>'required',
                   'interview_date'=>'required|date|after_or_equal:'.$today->toDateString(),
                   'interview_time'=>'required|date_format:H:i',
                   'attachee_id'=>['required', Rule::exists('attache_details', 'id')->where('is_accepted',1)->where('is_scheduled',null,0)->where(function ($query) use ($request) {
                    return $query->where('id', $request->input(['attachee_id']));
                })],
                'user_id' => [
                    'required',
                    Rule::exists('users', 'id')->where('role',0)->where(function ($query) use ($request) {
                        return $query->where('id', $request->input(['user_id']));
                    }),
                ],
            ]);

           
        
                 $interview = Interview::create([
                    'name'=>$validateData['name'],
        'venue'=>$validateData['venue'],
        'interview_date'=>$validateData['interview_date'],
        'interview_time'=>$validateData['interview_time'],
                    'attachee_id'=>$request->attachee_id,
                    'user_id'=>$request->user_id
            
                 ]);
                 if (!$interview) {
                    return response()->json([
                        'Error creating interview',
                    ], 404);
                }
                
                $attachee->is_scheduled = true;
                $attachee->save();
                

                
                  $data = [
                   
                    'interview'=>$interview,
                    'attachee'=>$attachee,
                    'supervisor'=>$supervisor,
                    'Message'=>"Interview Scheduled"
                  ];
                  $attachee->new_interview_id =$interview->id;
                  $attachee->save();
               
                   
                    if (! Mail::to($supervisor->email)->send(new AssignedInterview($data))) {
                        // Email failed to send
                        Log::warning('Failed to send email to '.$supervisor->email);
                        return response('Failed to Send');                                                                              
                    }
                    
                    if(!Mail::to($attachee->email)->send(new InterviewEmail($data))){
                        Log::warning('Failed to send email to '.$attachee->email);
                        return response('Failed to Send');
                    }
                     return response()->json(
                        $data, 201);}
       } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
            $th->getMessage()
        ],500);
       }
       

    
        
        
       
      
    }
    public function scheduled_interviews(){
        try {
            //code...
            $interviews =Interview::where('conducted',null)->get();
            if($interviews->isEmpty()){
                return response()->json(['Message'=>'No Interviews Scheduled'],404);
            }
            $data = [];
            foreach ($interviews as $interview) {
                # code...
                $role = $interview->attachee->role ? $interview->attachee->role->role : null;
                $data[] = [
                    'id'=>$interview->id,
                    'attachee'=>$interview->attachee->fullname,
                    'role'=>$role,
                    'rating'=>$interview->rating,
                    'comments'=>$interview->comments
                ];
            }
           
           return response()->json($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()]);
        }
       
    }
    public function getinterviews(){
        //get interviewed candidates details
        
try {
    //code...
    
    $interviews =Interview::where('conducted',1)->where('admin_reviewed',null)->get();
   
    if($interviews->isEmpty()){
       return response()->json(['Message'=>'No Interviews Scheduled'],404);
    }
$data = [];
   foreach ($interviews as $interview) {
    # code...
    $role = null;
    if ($interview->attachee) {
        $role = $interview->attachee->role ? $interview->attachee->role->role : null;
    }
    
    $data[] = [
        'id' => $interview->attachee->id,
        'attachee' => $interview->attachee->fullname,
        'role' => $role,
        'rating' => $interview->rating,
        'comments' => $interview->comments
    ];
    

   }
  

     return response()->json($data, 200);
    
    
   
} catch (\Throwable $th) {
    //throw $th;
    return response()->json([$th->getMessage()],500);
}
       
    }
    public function isaccepted($id){
        //first selection
try {
    //code...
    $attachee = Attache_details::find($id);
    if(!$attachee){
        return response()->json(['Message'=>'Applicant Not found'],404);
    }
    $attachee->is_accepted =true;
    $attachee->save();
    $data =[
'attachee'=>$attachee,
'Message'=>"Applicant Selected For Interviews"
    ];
    return response()->json(
        $data
    ,201);
 
} catch (\Throwable $th) {
    //throw $th;
    return response()->json([ $th->getMessage()],500);
   
}
       
    }
    public function isrejected(Request $request, $id){
        //first rejection
try {
    //code...
    $attachee = Attache_details::findOrfail($id);
    if(!$attachee){
        return response()->json([
            'Message'=>'Applicant Not found'
        ],404);
    }
  

        $attachee->is_accepted =false;
        $attachee->reason =  $request->input(['reason']);
        $attachee->save();
        $data =[
'attachee'=>$attachee,
'Message'=>"Rejected Applicant"
        ];
        Mail::to($attachee->email)->send(new RejectedMail($data));
        
      return response()->json($data,201);

} catch (\Throwable $th) {
    //throw $th;
    return response()->json([$th->getMessage()],500);
}
        
    

    }
    public function getaccepted(){
        //retireve all attachees succesful 1st selection

       
        try {
            //code...
            $attachees =Attache_details::get()->where('is_accepted',1)->where('is_scheduled',null);
        if($attachees->isEmpty()){
return response()->json(["No Selected Candidates For Interviews",404]);
        }
       
        $data = [];
        foreach ($attachees as $attachee) {
            $role = $attachee->role ? $attachee->role->role : null;
         
            $data[] = [
                'id'=>$attachee->id,
                'attachee'=>$attachee->fullname,
                'email' => $attachee->email,
                'role'=>$role,
               
            ];
                
        } 
        return response()->json($data,200);}
        catch (\Throwable $th) {
            //thro
            return response()->json([$th->getMessage('Server Error')],500);
        }
        
    }

    public function accept($id){
        try {
            $attachee = Attache_details::where('id',$id)->where('is_accepted',1)->first();
            if(!$attachee){
                return response()->json(['Message'=>'Interviewee Not Found']);
            }
          
            $password = Str::random(8);
            $user = User::create([
                'name'=>$attachee->fullname,
                'email'=>$attachee->email,
                'password'=>Hash::make($password),
                'position'=>$attachee->role_id,
                'phone'=>$attachee->phone
            ]);
            $user->role = 2;
            $user->save();

            $attachee->user_id =$user->id;
            $attachee->save();
            $attachee->chosen = true;
            $attachee->save();
    
           // $this->assign(new Request(['intern_id' => $id, 'supervisor_id' => auth()->user()->id]));//calling assign function
       
           $interview = $attachee->interview->exists() ? $attachee->interview->first() : null;
           if ($interview) {
               $interview->admin_reviewed = true;
               $interview->save();
           } else {
               // Handle case where interview record is not found
               return response()->json(['Error updating interview record']);
           }
           
            
            $data = [
                'attachee' => $attachee,

                'password' =>$password, //array to string conversion
                'message' => "Applicant accepted to organization",
                'interview'=>$interview
            ];
    
             Mail::to($attachee->email)->send(new AcceptedMail($data));
            
    
            return response()->json(
                $data
            , 201);
        } catch (\Throwable $th) {
            return response()->json([$th->getMessage()],500);
        }
    }
    
       
    public function reject(Request $request,$id){
        //second rejection
        try {
            //code...
            $attachee = Attache_details::where('id', $id)->where('is_accepted', 1)->first();

            if(!$attachee){
                return response()->json([
    "Message"=>"Interviewee Not Found"
                ],404);
    
                
            }
                       
            $attachee->chosen = false;
            $attachee->reason = $request->input(['reason']);
            $attachee->save();
            $role = $attachee->role ? $attachee->role->role : null;
                   
            $interview = $attachee->interview;
           if (!$interview) {
            return response()->json(['Error updating interview record']);
           } else {
               // Handle case where interview record is not found
             
               $interview->admin_reviewed = true;
               $interview->save();
           }
            $data =[
                'attachee'=>$attachee->fullname,
                'role'=>$role,
                'reason'=>$attachee->reason,
                'Message'=>'Applicant Rejected After Interview',
                'interview'=>$interview
                         ];
           
            Mail::to($attachee->email)->send(new UnsuccsessfulMail($data));
            
            
            return response()->json(
                $data
            ,201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                $th->getMessage()
            ],500);
        }
       
        
    }
    public function getnew(){
        
        try {
            //code...
            $users =User::where('role',2)->get();
            
                        if ($users->isEmpty()) {
                            return response()->json(["Message"=>'No new interns'],404);
                        } 
                        $data = [];
                        foreach ($users as $user) {
                           
                            
                            // Add the supervisor's data to the $data array
                            $data[] = [
                                'id' =>$user->id,
                                'name' => $user->name,
                                'email'=>$user->email,
                                'phone'=>$user->phone
                               
                                
                            ];
                        }
return response()->json($data,200);
                        
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()],500);
        }
      
        
        }
    

    public function assign(Request $request)
{    
    try {
        $intern = User::find($request->input('intern_id'));
          
        $validator = Validator::make($request->all(), [
            'intern_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 2)->where('id', $request->input('intern_id')),
            ],
            'supervisor_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 1),
            ],
        ]);
        
        $validator->after(function ($validator) use ($request) {
            $supervisor = User::find($request->input('supervisor_id'));
            $assignedInternsCount = Supervisor_Attachee::where('supervisor_id', $supervisor->id)->count();
        
            if ($assignedInternsCount >= 5) {
                $validator->errors()->add('supervisor_id', 'The supervisor is already assigned to the maximum number of interns.');
            }
        });
        
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
            
        $supervisor = User::find($request->input('supervisor_id'));
        if (!$supervisor) {
            return response()->json(['message' => 'No supervisor found'], 404);
        }
        
        $supervisor_attachee = Supervisor_Attachee::create([
            'intern_id' => $request->input('intern_id'),
            'supervisor_id' => $request->input('supervisor_id'),
        ]);
        
        $intern->assigned = true;
        $intern->save();
    
        $data = [
            'supervisor_attachee' => $supervisor_attachee,
            'attachee' => $intern->name,
            'role' => $intern->attachee->role->role,
            'description' => $intern->attachee->role->description,
            'supervisor' => $supervisor,
            'message' => 'Supervisor and assigned intern',
        ];
           
        Mail::to($intern->email)->send(new SupervisorMail($data));
        Mail::to($supervisor->email)->send(new SupervisionMail($data));
    
        return response()->json($data, 201);
    } catch (\Throwable $th) {
        return response()->json(['message' => $th->getMessage()], 500);
    }
}

   

    public function getassignments(){
        try {
            //code...
            $supervisor_attachee = Supervisor_Attachee::get();
        if(!$supervisor_attachee){
            return response()->json(["Message"=>"No Supervisor Attache Assignments Made"]);
        }
        $data =[
             'supervisor_attachee'=>$supervisor_attachee,
             "Message"=>"Supervisor Attachee Assignments List"
        ];
        return response()->json($data,200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()],500);
        }
        
    }
    public function rejected_applicants(){
        try {
            //code...
$attachee = Attache_details::get()->where('is_accepted',0);
if(!$attachee){
    return response()->json(["Message"=>"No Rejected Applicants Yet"]);
   
}
$data =[
    'attachee'=>$attachee,
    'Message'=>"Rejected Applicants From 1st Selection"
];
return response()->json([$data],200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage(),
            500]);
        }
    }
    public function rejected_interviewees(){
        try {
            //code...
$attachee = Attache_details::get()->where('is_accepted',1)->where('chosen',0);
if(!$attachee){
    return response()->json(["Message"=>"No Rejected Interviewees Yet"]);
   
}
$data =[
    'attachee'=>$attachee,
    'Message'=>"Rejected Interviewees"
];
return response()->json([$data],200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage(),
            500]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {//destroy roles
        try {
            //code...
            $role = Role::find($id);
          
            if(!$role){
                return response()->json(["Message"=>"Role Not Found"],500);
            }
            Role::destroy($id);
            return response()->json(['Message'=>"Role Deleted"],204);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()]);

        }
      
       
    }
    public function updaterole(Request $request,$id){

        try {
            //code...
            $role = Role::find($id);
           
            if(!$role){
                return response()->json([
"Message"=>"No role like that exists"
                ],404);
            }
            $validateData= $request->validate([
                'role'=>'sometimes',
                'description'=>'sometimes'
            ]);
    
            $data = [
                'role' => $validateData['role'] ?? $role->role, //if input not present use existing value
                'description' => $validateData(['description']) ?? $role->description,
            ];
            
        Role::where('id',$id)->update($data);
        return response()->json($data,201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()]);
        }
        
    }
    public function cancelInterview($id){
        try {
            //code...
            $interview = Interview::find($id);

 if(!$interview){
    return response()->json(['Message'=>"Interview Not Found"],404);
 }
 if($interview->conducted){
    return response()->json(['Message'=>"This Interview is already conducted you cannot cancel it"]);
 }
 else{
 $applicant = Attache_details::find($interview->attachee_id);
 $applicant->is_scheduled = false;
 $applicant->save();
 }
   $data = [
    'attachee'=>$applicant,
    'interview'=>$interview,
'Message'=>'Interview Canceled'
   ];
   Mail::to($applicant->email)->send(new CanceledInterview($data));

 return response()->json($data,201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()]);
        }
 
 
    }
    public function destroy_interview($id){

        try {
            //code...
            $interview =Interview::find($id);
            if(!$interview){
                return response()->json(['message'=>'Interview Not Found'],404);
            }
            if($interview->conducted){
                return response()->json(['Message'=>"This Interview is already conducted you cannot delete it"]);
             }
             else{
                $applicant = Attache_details::find($interview->attachee_id);
                $applicant->is_scheduled=false;
                Interview::destroy($id);
                return response()->json(['Message'=>'Interview Deleted']);
             }
           
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()],204);
        }

        
    }
    public function update_interview(Request $request,$id){
        try {
            //code...
            $interview = Interview::find($id);
            if(!$interview){
                return response()->json(['Message'=>'Interview Not Found'],404);
            }
            if($interview->conducted){
                return response()->json(['Message'=>"This Interview is already conducted you cannot delete it"]);
             }
             else{
            $attachee = Attache_details::findOrfail( $interview->attachee->id);
            $supervisor = User::findOrFail($interview->user->id);
    
            $today = Carbon::now();
            $validateData= $request->validate([
                'name'=>'sometimes',
                'venue'=>'sometimes',
                'interview_date'=>'sometimes|date|after_or_equal:'.$today->toDateString(),
                'interview_time'=>'sometimes|date_format:H:i',
                'attachee_id'=>['sometimes', Rule::exists('attache_details', 'id')->where('is_accepted',1)->where(function ($query) use ($request) {
                 return $query->where('id', $request->input(['attachee_id']));
             })],
             'user_id' => [
                 'sometimes',
                 Rule::exists('users', 'id')->where('role',0)->where(function ($query) use ($request) {
                     return $query->where('id', $request->input(['user_id']));
                 }),
             ],
            ]);
            $data = [
                'name'=>$validateData['name'] ?? $interview->name,
                'venue'=>$validateData['venue'] ?? $interview->venue,
                'interview_date'=>$validateData['interview_date'] ?? $interview->interview_date,
                'interview_time'=>$validateData['interview_time'] ?? $interview->interview_time,
                'attachee_id'=>$validateData['attachee_id']??$interview->attachee_id,
                'user_id'=>$validateData['user_id']??$interview->user_id,
                'user'=>$interview->user->name,
                'attachee'=>$interview->attachee->fullname
            ];
            // Interview::where('id',$id)->update($data);
            if (! Mail::to($supervisor->email)->send(new UpdateAssignedInterview($data))) {
                // Email failed to send
                Log::warning('Failed to send email to '.$supervisor->email);
                return response('Failed to Send');                                                                              
            }
            
            if(!Mail::to($attachee->email)->send(new UpdateInterviewEmail($data))){
                Log::warning('Failed to send email to '.$attachee->email);
                return response('Failed to Send');
            }
             return response()->json(
                $data, 200);}
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()],500);
        }
      
       }


    }


