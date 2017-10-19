<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\AdminUser;

class UserController extends Controller
{
    /*
     * 用户列表
     */
    public function index()
    {
        $users = \App\AdminUser::paginate(10);
        return view('/admin/user/index', compact('users'));
    }

    /*
     * 创建用户
     */
    public function create()
    {
        return view('/admin/user/add');
    }

    /*
     * 创建用户
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:3',
            'password' => 'required|string|min:5|max:12'
        ]);

        $name = request('name');
        $password = bcrypt(request('password'));
        \App\AdminUser::create(compact('name', 'password'));
        return redirect('/admin/users');
    }
    /**
     * @brief 删除用户
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(AdminUser $user)
    {
        $adminuser=AdminUser::find($user->id);
        $adminuser->delete();
        return back();
    }

    /*
     * 角色的权限
     */
    public function role(\App\AdminUser $user)
    {
        $roles = \App\AdminRole::all();
        $myRoles = $user->roles;
        return view('/admin/user/role', compact('roles', 'myRoles', 'user'));
    }

    /*
     * 保存用户角色
     */
    public function storeRole(\App\AdminUser $user)
    {
        $this->validate(request(),[
            'roles' => 'required|array'
        ]);

        $roles = \App\AdminRole::findMany(request('roles'));
        $myRoles = $user->roles;

        // 对新的角色添加
        $addRoles = $roles->diff($myRoles);
        foreach ($addRoles as $role) {
            $user->roles()->save($role);
        }
        //对取消的角色进行删除
        $deleteRoles = $myRoles->diff($roles);
        foreach ($deleteRoles as $role) {
            $user->deleteRole($role);
        }
        return redirect()->route('adminUserList');
    }
}
