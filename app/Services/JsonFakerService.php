<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
class JsonFakerService
{
    private $baseUrl = 'https://jsonplaceholder.typicode.com';

    public function getTAllodos()
    {
        $response = Http::baseUrl($this->baseUrl)->get("todos/");
        return $response->json();
    }
    public function getTodos($user_id)
    {
        $response = Http::baseUrl($this->baseUrl)->get("todos/?userId=$user_id");
        return $response->json();
    }

    public function getTodo($user_id,$id)
    {
        $response = Http::baseUrl($this->baseUrl)->get("todos/$id?userId=$user_id");
        $data = json_decode($response, true);
        if($data['userId']!==$user_id){
            return [];
        }
        return $data;
    }

    public function storeTodo($data,$user_id)
    {
        $response = Http::baseUrl($this->baseUrl)->post("todos?userId=$user_id",$data);
        
        return $response['id'];
    }

    public function updateTodo($data,$user_id)
    {
        $id = $data['id'];
        unset($data['id']);
        $response = Http::baseUrl($this->baseUrl)->put("todos/$id?userId=$user_id",$data);
        
        return $response->json();
    }

    public function deleteTodo($user_id,$id)
    {
        $response = Http::baseUrl($this->baseUrl)->delete("todos/$id?userId=$user_id");
        return $response->json();
    }
}