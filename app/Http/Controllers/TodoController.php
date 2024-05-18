<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Todo;
use App\Services\JsonFakerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TodoController extends Controller
{
    protected $todo;
    protected $jsonFakerService;
    public function __construct(Todo $todo, JsonFakerService $jsonFakerService)
    {
        $this->todo = $todo;
        $this->jsonFakerService = $jsonFakerService;
    }

    public function index(Request $request)
    {
        $user = $this->checkUser($request->header('Authorization'));
        $user_id = $user->id;
        if(env('APP_MODE')=="mock"){
            $todos = $this->jsonFakerService->getTodos($user_id);
        }else{
            $todos = $this->todo->getAllTodos($user_id);
        }
        return $this->sendResponse($todos,'Data fetched successfully',200);
    }

    public function show(Request $request,$id)
    {
        $user = $this->checkUser($request->header('Authorization'));
        $user_id = $user->id;
        if(env('APP_MODE')=="mock"){
            $todo = $this->jsonFakerService->getTodo($user_id,$id);
        }else{
            $todo = $this->todo->findTodoById($user_id,$id);
        }
        if(!$todo)
        {
            return $this->sendError('No data found', [],404);
        }
        return $this->sendResponse($todo,'Data fetched successfully',200);
    }

    public function store(Request $request)
    {
        $user = $this->checkUser($request->header('Authorization'));
        $user_id = $user->id;
        $validator = Validator::make($request->all(),[
            'title'=> ['required', 'string', 'min:4'],
        ]);

        if($validator->fails())
        {
            return $this->sendError('Invalid detail', $validator->errors(),400);
        }

        if(env('APP_MODE')=="mock"){
            $todo_id = $this->jsonFakerService->storeTodo($request->all(),$user_id);
        }else{
            $data = $request->all();
            $data['userId'] = $user_id;
            $todo_id = $this->todo->createTodo($data);
        }

        $todo = ['id'=>$todo_id,'title'=>$request->title,'completed'=>false];
        return $this->sendResponse($todo,'Data created successfully',201);
    }

    public function update(Request $request,$id)
    {
        $user = $this->checkUser($request->header('Authorization'));
        $user_id = $user->id;

        $data = $request->all();
        $data['id']=$id;
        
        $validator = Validator::make($data,[
            'id'=> ['required', 'int'],
            'title'=> ['required', 'string', 'min:4'],
            'completed'=> ['required', 'boolean']
        ]);

        if($validator->fails())
        {
            return $this->sendError('Invalid Details', $validator->errors(),400);
        }

        if(env('APP_MODE')=="mock"){
            $todo = $this->jsonFakerService->getTodo($user_id,$id);
        }else{
            $todo = $this->todo->findTodoById($user_id,$request->id);
        }

        if(count($todo)==0)
        {
            return $this->sendError('No data found', [],404);
        }

        if(env('APP_MODE')=="mock"){
            $todo = $this->jsonFakerService->updateTodo($data,$user_id);
        }else{
            $todo = $this->todo->updateTodo($user_id,$data);
        }
        return $this->sendResponse($todo,'Data updated successfully',200);
    }

    public function destroy(Request $request,$id)
    {
        $token = ($request->header('Authorization'))? $request->header('Authorization'):$request['Authorization'];
        $user = $this->checkUser($token);
        $user_id = $user->id;

        if(env('APP_MODE')=="mock"){
            $todo = $this->jsonFakerService->getTodo($user_id,$id);
        }else{
            $todo = $this->todo->findTodoById($user_id,$id);
        }

        if(count($todo)==0)
        {
            return $this->sendError('No data found', [],404);
        }

        if(env('APP_MODE')=="mock"){
            $todo = $this->jsonFakerService->deleteTodo($user_id,$id);
        }else{
            $todo = Todo::find($id)->delete();
        }

        
        return $this->sendResponse(null,'Data deleted successfully',200);
    }
}
