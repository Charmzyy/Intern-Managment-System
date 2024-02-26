<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Support\Facades\Mail;

class InternController extends Controller
{
    public function dashboard(){

        return response()->json(['message'=>'intern dashboard']);
    }
    public function mytasks(){
        // view my tasks
        try {
            //code...
           
            $tasks = Task::where('user_id',auth()->user()->id)->where('accepted',null)->get();
            $today = Carbon::now();
        if($tasks->isEmpty()){
            return response()->json(["Message"=>"No tasks Available"]);
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
                'status' => $task->status,
                'deadline'=>$deadline
            ];
        }

        return response()->json($data,200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                $th->getMessage()
            ],500);
        }
        
    }
    public function task($id){
        //get an individual task
       
        $task = Task::findOrFail($id);
        $comments = Comment::where('task_id', $task->id)->get();
        
        $this->authorize('view',$task);
      //  $comments = $task->comments;
        if(!$task){
            return response()->json([
                "Message"=>"This task does not exists"
            ],404);
        }
       
        try {
            //code...
            // if(!$comments){
            //     return response()->json(["Message"=>"No Comments for this task yet"]);
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
    public function accept($id){
        
     
        try {
            //code...
          
            $task = Task::findOrFail($id);
            $this->authorize('update',$task);
            
          
    if(!$task){
        return response()->json([
            "Message"=>"No such task assigned to you"
        ]);
    }
           $data = [
            'accepted'=>true
            //try update with string value 
           ];

           $task->update($data);
           return response()->json(["Message"=>"Task has been accepted"]);
    
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()],500);
        }
        
    

    }
    public function reject(Request $request,$id){
        try {
            //code...
            // $this->authorize('update',Task::class);
            $task = Task::find($id);

            if(!$task){
                return response()->json(["Message"=>"No such task assigned to you"]);
    
            }
            
    
            $data = [
                'accepted'=>false,
                'reason'=>$request->input('reason')
            ];
    
            $task->where('id', $id)->update($data);
    
            return response()->json([
                'Message'=>"Task has been rejected"
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                $th->getMessage()
            ],500);
        }
       
    }
    public function complete($id){
        $task = Task::find($id);
        try {
            //code...
            $this->authorize('update',$task);
            if(!$task){
                return response()->json([
                    "Message"=>"This task does not exist"
                ]);
            }
                 $data = [
                    'status'=>true,
                   // 'Message'=>"Task Completed"
                 ];
                 $task->update($data);
                 return response()->json($data,201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()]);
        }
       
       
    }
  
   
    
   
 
  
    

public function accepted_tasks(){

    try {
        //code...
       
        $tasks = Task::where('user_id',auth()->user()->id)->where('accepted',1)->get();
        $today = Carbon::now();
    if(!$tasks){
        return response()->json(["Message"=>"No tasks Available"]);
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
            'status' => $task->status,
            'deadline'=>$deadline
        ];
    }

    return response()->json($data,200);
    } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
            $th->getMessage()
        ],500);
    }
}
}
