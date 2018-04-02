<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Category $category,Request $request,Topic $topic){
        $topics = $topic->withOrder($request->order)            //排序方式
                        ->where('category_id',$category->id)    //特定分类id的topic
                        ->paginate(20);
        return view('topics.index',compact('topics','category'));
    }
}
