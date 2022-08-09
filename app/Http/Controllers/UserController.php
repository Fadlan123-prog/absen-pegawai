<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Validator, Lang, Session, Hash, Storage, Image, Str;

class UserController extends Controller
{
    public function index(Request $request){
        $search = $request->search;

        $datas = User::when($search, function($query, $search){
            return $query->where('name', 'like', "%$search%");
        })
        ->orderBy('id', 'DESC')
        ->paginate(10);

        return view("page.user.index")->with('datas', $datas);
    }

    public function create()
    {
        $roles = Role::all();

        return view("page.user.create", compact('roles'));
    }

    public function store(Request $request)
    {
        $messages = [
            'username.required' => Lang::get('web.username-required'),
            'username.unique' => Lang::get('web.username-unique'),
            'password.required' => Lang::get('web.password-required'),
            'name.required' => Lang::get('web.name-required'),
            'role_id.required' => Lang::get('web.role_id-required'),
            'role_id.integer' => Lang::get('web.role_id-integer'),
            'confirmPassword.required' => Lang::get('web.confirm-password-required'),
            'confirmPassword.same' => Lang::get('web.confirm-password-same'),
            'file.mimes' => Lang::get('web.file-mimes'),
        ];

        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'name' => 'required',
            'role_id' => 'required|integer',
            'password' => 'required',
            'confirmPassword' => 'required|same:password',
            'file'  => 'nullable|mimes:jpg',
        ], $messages);
        
        if($validator->fails())
        {
            $validator->errors()->add('message', Lang::get('web.create-failed'));
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = new User;
        $data->username = $request->username;
        $data->name = $request->name;
        $data->password = Hash::make($request->password);
        $data->phone = $request->phone;

        if($request->hasFile('photo_url')) {
            $this->saveImage($request->file('photo_url'), $data);
        }

        try {
            $role = Role::find($request->role_id);
            
            $data->save();
            $data->assignRole($role->name);
        } catch (\Exception $errors){
            return redirect()->back()
            ->withInput()->withErros(['message' => Lang::get('web.create-failed') . $errors->getMessage()]);
        }

        Session::flash('message', Lang::get('web.create-success'));
        return redirect()->route('user.index');
    }

    public function saveImage($picture, $data)
  {
    $pathSave = "public/image/user-photo/";
    $name = Str::slug($data->username) . ".jpg";
    $picturePath = $pathSave . $name;

    $img = Image::make($picture);
    $img->encode('jpg', 75);

    $imgStream = $img->stream();
    Storage::put($picturePath, $imgStream->__toString());

    $data->signature_url = asset(Storage::url($picturePath));
    $data->signature_path = $picturePath;
  }

  public function deleteImage($picturePath)
  {
    if(!is_null($picturePath) && Storage::exists($picturePath))
    Storage::delete($picturePath);
  }
}
