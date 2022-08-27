<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendances;
use App\Exports\AttendExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class DailyReportController extends Controller
{
    public function index(Request $request){
        $search = $request->search;

        $datas = Attendances::with('user')
        ->when($search, function($query, $search){
            return $query->where('name', 'like', "%$search%");
        })
        ->orderBy('id', 'DESC')
        ->paginate(10);

        return view("page.dailyreport.index")->with('datas', $datas);
    }

    public function export_excel()
    {
        $file_name = Auth::user()->name;
        return Excel::download(new AttendExport, $file_name . '.xlsx');
    }
}
