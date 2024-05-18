<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['title','completed','userId'];

    public function getAllTodos($user_id)
    {
        return Self::where('userId',$user_id)->get();
    }

    public function findTodoById($user_id,$id)
    {
       return Self::where('userId',$user_id)->where('id',$id)->get();
    }

    public function createTodo($data)
    {
        $query = Self::create($data);
        return $query->id;
    }

    public function updateTodo($user_id,$data)
    {
        $query = Self::where('userId',$user_id)->where('id',$data['id'])->first();
        $query->update($data);
        return $query;
    }
}
