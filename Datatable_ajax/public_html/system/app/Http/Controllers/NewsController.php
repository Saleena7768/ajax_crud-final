<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Validator;

class NewsController extends Controller
{
    protected $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function index()
    {
        $data = $this->news->all();
        return view('news.index')->with(compact('data'));
    }

    public function all()
    {
        $data = $this->news->all();
        $response = [
            "status" => 200,
            "data"=>json_encode($data)
        ];
        return response($response);
    }

    public function add(Request $request)
    {
        $rules = [
            "title" => "required",
            "description" => "required"
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            $verrors = [];
            $message = $validator->messages();
            foreach ($rules as $key => $value){
                $verrors[$key] = $message->first($key);
            }
            $response = [
                "status" => 200,
                "data" => $verrors,
                "message" => "errors"
            ];
            return response($response);
        }
        $data = $request->input();

//        $data = [
//            "title"=>$request->input('title'),
//        ]
        $this->news->create($data);

        return response('flash_success');
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $data = $this->news->find($id);
        $response = [
            "status" => 200,
            "data" => json_encode($data)
        ];
        return response($response);
    }
    public function saveUpdate(Request $request)
    {
        $data = [
            "title"=>$request->input('title'),
            "description"=>$request->input('description'),
            "status"=>$request->input('status')
        ];
        $this->news->find($request->input('id'))->update($data);
        $response = [
            "status" => 200,
            "successfully edited"
        ];
        return response($response);
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        $data = $this->news->find($id);
        $data->delete();
        $response = [
            "status"=>200,
            "success"
        ];
        return response($response);
    }
}
