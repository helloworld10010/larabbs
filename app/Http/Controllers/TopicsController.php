<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller {
    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(Request $request,Topic $topic) {
        /*
         * 方法 with() 提前加载了我们后面需要用到的关联属性 user 和 category，并做了缓存。
         * 后面即使是在遍历数据时使用到这两个关联属性，数据已经被预加载并缓存，因此不会再产生多余的 SQL 查询：
         *
         * $request->order 是获取 URI http://larabbs.app/topics?order=recent 中的 order 参数。
         */
        $topics = $topic->withOrder($request->order)->paginate(20);
        return view('topics.index', compact('topics'));
    }

    public function show(Topic $topic) {
        return view('topics.show', compact('topic'));
    }

    public function create(Topic $topic) {
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic','categories'));
    }

    public function store(TopicRequest $request,Topic $topic) {
        // store() 方法的第二个参数，会创建一个空白的 $topic 实例；
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();
        return redirect()->route('topics.show', $topic->id)->with('message', 'Created successfully.');
    }

    public function edit(Topic $topic) {
        $this->authorize('update', $topic);
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic','categories'));
    }

    public function update(TopicRequest $request, Topic $topic) {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return redirect()->route('topics.show', $topic->id)->with('message', 'Updated successfully.');
    }

    public function destroy(Topic $topic) {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return redirect()->route('topics.index')->with('message', 'Deleted successfully.');
    }

    /*
     * 在 Laravel 的控制器方法中，如果直接返回数组，将会被自动解析为 JSON。
     * 接下来我们编写控制器的逻辑，根据第一步设置的路由在话题控制器中新增 uploadImage 方法：
     */

    public function uploadImage(Request $request, ImageUploadHandler $uploader) {
        // 初始化返回数据，默认是失败的
        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];
        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return $data;
    }
}