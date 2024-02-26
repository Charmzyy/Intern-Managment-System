<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    //comment for both supervisor and intern
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function store(Request $request){

        try {
           // code...
            if(!$request->input('parent_id')){

                $comment =Comment::create([
                     'user_id'=>auth()->user()->id,
                     'task_id'=>$request->task_id,
                     'comment'=>$request->comment
    
                ]);
            }
            
             $comment = Comment::create([
               
                'user_id'=>auth()->user()->id,
                'task_id'=>$request->task_id,
                'comment'=>$request->comment,
                'parent_id'=>$request->parent_id
             ]);
    
             $data =[
    'comment'=>$comment,
    'message'=>'comment added'
             ];
             return response()->json([$data],201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th->getMessage()],500);
        }
       
    }
}
