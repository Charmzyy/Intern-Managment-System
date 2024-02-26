<?php

namespace App\Http\Controllers;

use App\Models\Attache_details;
use Illuminate\Support\Facades\DB;

use App\Models\Task;
use App\Models\User;
use App\Models\Comment;
use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\DeletedTask;
use App\Mail\TaskMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(){
        return response()->json(['message'=>'supervisor dashboard']);
    }
    public function index()
    {
        //view all interns under them
      
    
    try {
        $interns = DB::table('supervisor_attachee')
            ->join('users', 'users.id', '=', 'supervisor_attachee.intern_id')
            ->leftJoin('roles', 'roles.id', '=', 'users.position')
            ->where('supervisor_attachee.supervisor_id', auth()->user()->id)
            ->get(['users.id', 'users.name', 'users.email', 'roles.role']);
    
        if($interns->isEmpty()){
            return response()->json('No interns assigned to you',404);
        }
    
        $data = [];
    
        foreach ($interns as $intern) {
            $data[] = [
                'id' => $intern->id,
                'name' => $intern->name,
                'email' => $intern->email,
                'position' => $intern->role,
            ];
        }
    
        return response()->json($data, 200);
    } catch (\Throwable $th) {
        // Handle the exception
        return response()->json([$th->getMessage()]);
    }
    
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    
    {
        //creating a task
      
        
        try {
            //code...
            // $this->authorize('create',Task::class);

            $supervisor = auth()->user();
            $user = User::findOrfail( $request->input(['user_id']));
            if(!$user){
                return response()->json(["Message"=>"Intern Not found"],404);
            }
            $today = Carbon::now();
            $validateData = $request->validate([
                'user_id' => [
                    'required', 
                    Rule::exists('users', 'id')->where('role', 2)->where(function ($query) use ($supervisor, $request) {
                        return $query
                            ->where('id', $request->input('user_id'))
                            ->whereExists(function ($query) use ($supervisor, $request) {
                                return $query
                                    ->from('supervisor_attachee')
                                    ->where('intern_id', $request->input('user_id'))
                                    ->where('supervisor_id', $supervisor->id);
                            });
                    })
                ],
                'name' => 'required',
                'description' => 'required',
                'assigned' => 'required|date|after_or_equal:'.$today->toDateString(),
                'due' => 'required|date'
            ]);
            
           
            $task = Task::create([
              'user_id'=>$request->input(['user_id']),
              'name'=>$validateData['name'],
              'description'=>$validateData['description'],
              'assigned'=>$validateData['assigned'],//not less thant current data
              'due'=>$validateData['due']
    
            ]);
             
           

// $assigned = Carbon::parse($request->input('assigned'));
// $due = Carbon::parse($request->input('due'));

// $deadline = $assigned->diffInDays($due);

            
            $data = [
                'task'=>$task,
                'user'=>$user,
                // 'deadline'=>$deadline
               
            ];
    
            Mail::to($user->email)->send(new TaskMail($data));
            
            return response()->json($data,201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                $th->getMessage()
            ],500);
        }
      

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function tasks()
{ // loop through to retrieve property of user for each task
    //retrieve all task given
    try {
        $tasks = Task::with('user')->get();

        if($tasks->isEmpty()){
            return response()->json([
                'Message'=>"You have not yet assigned any task"
            ]);
        }

  
        $data = [];
        foreach($tasks as $task) {
            $assigned = Carbon::parse($task->assigned);
            $due = Carbon::parse($task->due);
            
            $deadline = $assigned->diffInDays($due);
            $data[] = [
                'id' => $task->id,
                'name' => $task->name,
                'description' => $task->description,
                'assigned' => $task->assigned,
                'due' => $task->due,
                'accepted' => $task->accepted,
                'username' => $task->user ? $task->user->name : null,
                //'position' => $task->user ? $task->user->position->position : null,
                'deadline'=>$deadline
            ];
        }

        return response()->json($data, 200);
    } catch (\Throwable $th) {
        return response()->json([$th->getMessage()]);
    }
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { //show task of one user
        //show comments
       

            $task = Task::findOrFail($id);
            $comments = Comment::where('task_id', $task->id)->get();
            
           
            if(!$task){
                return response()->json([
                    "Message"=>"This task does not exists"
                ],404);
            }
           
            try {
                //code...
                // if($comments->isEmpty()){
                    //return response()->json(["Message"=>"No Comments for this task yet"]);
               // }
                $data = [
                    'task'=>$task,
                    'comments'=>$comments
                ];
                return response()->json($data,200);
           
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()],500);
        }
       

    }

    public function rate_task(Request $request,$id){
        try {
            //code...
            $task = Task::where('id',$id)->where('status',1);
            if(!$task){
                return response()->json(['Message','No such Task'],404);
            }
            
           $data = [
            'rate'=>$request->input('rate')
           ];
           $task->update($data);
            return response()->json(['Message'=>'Task Rated']);
            
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()]);
        }

    }
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
    public function update(Request $request, $id)
    { //notify the intern
        //updating a task
        try {
            //code...
            $this->authorize('updatetask',Task::class);
            $validateData = $request->validate([
                'user_id'=>['required', Rule::exists('users', 'id')->where('role',2)->where(function ($query) use ($request) {
                    return $query->where('id', $request->input(['user_id']));
                })],
              'name'=>'required',
              'description'=>'required',
              'assigned'=>'required|date_format:Y-m-d',
              'due'=>'required|date_format:Y-m-d'
    
            ]);
            $data = [
                'user_id'=>$request->input(['user_id']),
                  'name'=>$validateData['name'],
                  'description'=>$validateData['description'],
                  'assigned'=>$validateData['assigned'],
                  'due'=>$validateData['due']
            ];
    
           Task::where('id',$id)->update($data);
           return response()->json([$data],201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()],500);
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //find the task 
        try {
        $task = Task::find($id);
        if(!$task){
            return response()->json(["Message"=>"Task Not found"],404);
        }
        $this->authorize('delete',Task::class);
        $user =$task->user;

        $data =[
'task'=>$task,
'user'=>$user
        ];
        //delete task

      
            //code...
            
            Task::destroy($id);
            return response()->json(['Message'=>'Task Deleted'],204);
            if(!Mail::to($user->email)->send(new DeletedTask($data))){
                Log::warning('Failed to send email to '.$user->email);
                return response('Failed to Send');
            }
            
            
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['Task Not Deleted',500]);
            
        }
      
       
    }
    public function panel(){
        //view interviews to attend
        try {
            //code...
            $interviews =Interview::where('user_id',auth()->user()->id)->where('conducted',null)->orderByRaw('ABS(DATEDIFF(interview_date, NOW())) ASC')
            ->get();

            if ($interviews->isEmpty()) {
                return response()->json([
                    'Message' => 'You have not yet been invited to an Interview Panel'
                ], 404);
            }
            
           $data = [];
            foreach ($interviews as $interview) {
                # code...
                $data[] = [
                    'id'=>$interview->id,
                    'attachee'=>$interview->attachee->fullname,
                    'venue'=>$interview->venue,
                    'date'=>$interview->interview_date,
                    'time'=>$interview->interview_time,
                ];
               


            }
            return response()->json(
                $data
            ,200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
$th->getMessage()
            ],500);
        }
       

    }
    public function rate(Request $request,$id ){
//update rating of the interview
        try {
            //code...
            $interview = Interview::findOrFail($id);
            if(!$interview){
                return response()->json(['Message'=>"This Interview Does Not exits"],404);
    
            }
            $validateData = $request->validate([
                'rating'=>'required',
                'comments'=>'required'
            ]);
            $data=[
                'rating'=>$validateData['rating'],
                'comments'=>$validateData['comments'],
                'conducted'=>true
            ];
            Interview::where('id',$id)->update($data);
            return response()->json([$data],201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                $th->getMessage()
            ],500);
        }
       
    }
   
}
