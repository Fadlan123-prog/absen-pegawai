<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
}
