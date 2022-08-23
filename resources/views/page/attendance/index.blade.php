@extends('layouts.auth')

@section('content')
@if($errors->first('message'))
<div class="col-12">
  <div class="alert alert-danger" role="alert">
    {{ $errors->first('message') }}
  </div>
</div>
@endif
@if(Session::has('message'))
<div class="col-12">
  <div class="alert alert-info" role="alert">
    {{ Session::get('message') }}
  </div>
</div>
@endif
<div class="row mb-5">
<div class="col-md-6 col-lg-6 mb-3">
    <div class="card text-center">
      <div class="card-header pb-0">Checkin</div>
      <div class="card-body">
        <img
            src="{{ asset('assets/auth/img/illustrations/Nerd-amico.png')}}"
            height="340"
            alt="View Badge User"
            data-app-dark-img="illustrations/man-with-laptop-dark.png"
            data-app-light-img="illustrations/man-with-laptop-light.png"
        />
        <div>
          <a href="#" data-href="{{route('attendance.index')}}" id="btn-camera" onclick="getLocation()" class="btn btn-primary btn-camera" >Checkin</a>
        </div>
      </div>
    </div>
  </div>
<div class="col-md-6 col-lg-6 mb-3">
    <div class="card text-center">
      <div class="card-header pb-0">Checkout</div>
      <div class="card-body">
        <img
            src="{{ asset('assets/auth/img/illustrations/Webinar-pana.png')}}"
            height="340"
            alt="View Badge User"
            data-app-dark-img="illustrations/man-with-laptop-dark.png"
            data-app-light-img="illustrations/man-with-laptop-light.png"
        />
        <div>
          <a href="javascript:void(0)" class="btn btn-primary">Checkout</a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal modal-blur fade" id="modal-camera" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">            
          <form method="POST" action="{{ route('attendance.checkin') }}">
            @csrf
                <div class="col-md-6">
                    <div id="my_camera"></div>
                    <br/>
                    <input type="hidden" name="image" class="image-tag">
                </div>
                <div class="col-md-6">
                    <div id="results">Your captured image will appear here...</div>
                </div>
                <div class="col-md-12 text-end">
                    <br/>
                    <a class="btn btn-link link-secondary me-auto text-start" id="btn-cancel" data-bs-dismiss="modal">Cancel</a>
                    <input class="btn btn-success" type="button" value="Take Snapshot" onClick="take_snapshot()">
                    <input id="latitude" type="hidden" name="latitude" value="">
                    <input id="longitude" type="hidden" name="longitude" value="">
                    <button class="btn btn-primary" >Submit</button>
                </div>
        </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js" integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(".btn-camera").each(function(index) {
      $(this).attr('data-bs-target', '#modal-camera');
      $(this).attr('data-bs-toggle', 'modal');
  
      $(this).click(function() {
        href = $(this).attr('data-href');
        $('#camera-cta').attr('action', href);
      });
    });
  
    $("#camera-cta").click(function() {
      $("#modal-camera").modal('hide');
    });
</script>
<script>
Webcam.set({
  width: 510,
  height: 350,
  image_format: 'jpeg',
  jpeg_quality: 90
});

let camera_button = document.querySelector('#btn-camera');
let cancel_button = document.querySelector('#btn-cancel');

camera_button.addEventListener('click', function() {
  Webcam.attach( '#my_camera' );
});

cancel_button.addEventListener('click', function() {
  webcam.reset('#my_camera');
});

function take_snapshot() {
Webcam.snap( function(data_uri) {
    $(".image-tag").val(data_uri);
    document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
});

}
</script>
<script>

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function showPosition(position) {
      document.getElementById("latitude").value = position.coords.latitude;
      document.getElementById("longitude").value = position.coords.longitude;
    }, showError);
  } else { 
    alert("Geolocation is not supported by this browser.") ;
  }
}

function showError(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      alert("User denied the request for Geolocation.") 
      break;
    case error.POSITION_UNAVAILABLE:
      alert("Location information is unavailable.") 
      break;
    case error.TIMEOUT:
      alert("The request to get user location timed out.") 
      break;
    case error.UNKNOWN_ERROR:
      alert("An unknown error occurred.") 
      break;
  }
}
        $.ajaxSetup({
          headers: {
            'X-CSSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    
        $.ajax({
          data: JSON.stringify({
              'latitude' : latitude,
              'longitude' : longitude,
          }),
          success: function(data){
              console.log("device control succeeded");
          },
          error: function(){
              console.log("Device control failed");
          },
          processData: false,
          type: 'POST',
          url: '/attendance'
        });
</script>
@endsection