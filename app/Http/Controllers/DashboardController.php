<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index() {
        $users = User::all();
        return view("page.dashboard.index")->with('users', $users);
    }
}
