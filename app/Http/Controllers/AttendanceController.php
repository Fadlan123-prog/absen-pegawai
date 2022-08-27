<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendances;
use App\Models\User;
use Carbon\Carbon;
use Lang, Str, Image, Storage, Log, Auth, Session, Validator;

class AttendanceController extends Controller
{
    public function index()
    {
      return view('page.attendance.index');
    }

    public function distance(Request $request) {
        $lat1 = -6.742311364754348;
        $lon1 = 106.8065822442326;
        $lat2 = $request['latitude'];
        $lon2 = $request['longitude'];
        $unit = "K";
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          $location = 0;
        }
        else {
          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;

          if ($unit == "K") {
            $location = ($miles * 1.609344);
          }
        }

        if($location <= 0.1){
          return redirect()->route('attendance.checkin');
        }else{
          return redirect()->route('attendance.checkin');
          // return abort(401, 'Pastikan lokasi anda tidak jauh dari kantor anda');
        }

    }

    public function checkin(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'status_in' => 'required',
        'status_out' => 'required',
        'name' => 'required',
        'date' => 'required',
        'clockin' => 'required',
        'clockout' => 'required',
        'lng' => 'required',
        'lat' => 'required',
        'attendance_photo_url' => 'required',
        'attendance_photo_path' => 'required',
        'is_on_time' => 'required',
      ]);

      $userAttend = Attendances::where('user_id', Auth::user()->id)->where('date',Carbon::now()->format('Y-m-d'))->get();
      if(count($userAttend) > 0 ){
        Session::flash('message', Lang::get('web.attend-already'));
        return redirect()->back();
      }

        $data = new Attendances;
        $data->status_in = 'in';
        $data->date = Carbon::now();
        $data->clockin = Carbon::now()->format('H:i');
          if( Carbon::now()->gt(Carbon::createFromFormat('H:i', '00:00')) && !( Carbon::now()->gt(Carbon::createFromFormat('H:i',  '05:59')) ) ){
            return abort('404', 'Belum Waktunya Absen');
          }elseif( Carbon::now()->gt(Carbon::createFromFormat('H:i', '06:00')) && !( Carbon::now()->gt(Carbon::createFromFormat('H:i', '08:00')) ) ){
            $data->is_on_time = true;
          }elseif( Carbon::now()->gt(Carbon::createFromFormat('H:i', '10:00')) && !( Carbon::now()->gt(Carbon::createFromFormat('H:i', '14:59')) ) ){
            $data->is_on_time = false;
          }else{
            return abort('404', 'Waktu Absen Sudah Habis`');
          }
        $data->user_name = Auth::user()->name;
        $data->user_id = Auth::user()->id;
        $data->lat = $request['latitude'];
        $data->lng = $request['longitude'];

        $this->saveImage($request->image, $data);

        try{
          $data->save();
        }catch(\Exception $errors){
          return redirect()->back()
          ->withInput()->withErrors(['message' => Lang::get('web.attend-failed') . $errors->getMessage()]);
        }

        Session::flash('message', Lang::get('web.attend-success'));
        return redirect()->route('attendance.index');
      }

      public function checkout(Request $request){
        $validator = Validator::make($request->all(), [
            'clockout' => 'required',
            'status_out' => 'required',
        ]);
        if( Carbon::now()->gt(Carbon::createFromFormat('H:i', '06:00')) && !( Carbon::now()->gt(Carbon::createFromFormat('H:i', '15:00')) ) ){
            Session::flash('message', Lang::get('web.not-time-checkout'));
            return redirect()->back();
        }elseif( Carbon::now()->gt(Carbon::createFromFormat('H:i', '15:00')) && !(Carbon::now()->gt(Carbon::createFromFormat('H:i', '23:59')) )){
            $userAttend = Attendances::where('user_id', Auth::user()->id)->where('date',Carbon::now()->format('Y-m-d'))->get();
            if(count($userAttend) >= 1 ){
                $data->clockout = Carbon::now()->format('H:i');
                $data->status_out = 'out';
            }elseif(count($userAttend) <= 0){
                Session::flash('message', Lang::get('web.not-attend-yet'));
                return redirect()->back();
            }
        }
        try{
            $data->save();
          }catch(\Exception $errors){
            return redirect()->back()
            ->withInput()->withErrors(['message' => Lang::get('web.attend-failed') . $errors->getMessage()]);
          }

          Session::flash('message', Lang::get('web.attend-checkout-success'));
          return redirect()->route('attendance.index');
      }

    public function saveImage($img, $data)
    {
        $folderPath = "public/image/attendance-picture/";

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';

        $file = $folderPath . $fileName;
        Storage::put($file, $image_base64);

        $data->attendance_photo_url = asset(Storage::url($file));
        $data->attendance_photo_path = $file;
    }
}
