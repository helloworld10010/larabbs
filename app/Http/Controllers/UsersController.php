<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Handlers\ImageUploadHandler;
/**
 * Class UsersController
 * @package App\Http\Controllers
 *
 *
 * UserRequest
 *  表单请求验证（FormRequest） 是 Laravel 框架提供的用户表单数据验证方案，此方案相比手工调用 validator 来说，
 * 能处理更为复杂的验证逻辑，更加适用于大型程序。在本课程中，我们将统一使用 表单请求验证来处理表单验证逻辑。
 */
class UsersController extends Controller
{
    public function show(User $user){
        return view('users.show',compact('user'));
    }

    public function edit(User $user){
        return view('users.edit',compact('user'));
    }

    public function update(UserRequest $request,ImageUploadHandler $upload,User $user){
        // 把全部数据取出来，方便验证头像有效性
        $data = $request->all();
        if($request->avatar){
            $result = $upload->save($request->avatar,'avatar',$user->id,362);
            // 通过后缀名验证
            if($result){
                $data['avatar'] = $result['path'];
            }
        }
        $user->update($data);
        return redirect()->route('users.show',$user->id)->with('success','个人资料更新成功');
    }
}
