@extends('layouts.auth')

@section('title', 'Daily Reports')

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
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Daily Reports</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-body border-bottom py-3">
            <div class="d-flex">
              <div class="text-muted">
                <a href="{{ route('export.excel') }}" class="btn btn-primary btn-sm btn-loader">Export</a>
              </div>
              <div class="ms-auto text-muted">
                <form>
                  <div class="ms-2 d-inline-block">
                    <input type="text" name="search" value="{{ Request::get('search') }}" class="form-control form-control-sm" aria-label="Search">
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm btn-loader">Search</button>
                </form>
              </div>
            </div>
          </div>
      <div class="table-responsive text-nowrap">
        <table class="table">
          <thead>
            <tr>
              <th class="w-1">No</th>
              <th>Name</th>
              <th>Date</th>
              <th>Clockin</th>
              <th>Clockout</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach ($datas as $key => $data)
            <tr>
                @php
                    $no = $key + 1;
                    if(Request::has('page'))
                    {
                        $no += (Request::get('page') - 1) * 10;
                    }
                @endphp
                <td><span class="text-muted">{{ $no }}</span></td>
                <td>{{ $data->user->name}}</td>
                <td>{{ $data->date}}</td>
                <td>{{ $data->clockin}}</td>
                <td>{{ $data->clockout}}</td>
                <td>
                  @foreach($datas as $stat)
                    @php
                        if($stat->is_on_time == 1){
                            $status = 'Hadir';
                            $check = 'success';
                        }else{
                            $status = 'Telambat';
                            $check = 'warning';
                        }
                    @endphp
                    <span class="badge bg-label-{{ $check }} me-1">{{ $status }}</span>
                  @endforeach
                </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="card-footer d-flex align-items-center">
        <div class="pagination m-0 ms-auto">
          {{ $datas->links('vendor.pagination.custom') }}
        </div>
      </div>
    </div>
    <div class="modal modal-blur fade" id="modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div class="modal-title">Are you sure?</div>
            <div>Menghapus data akan menghilangkan data secara permanen.</div>
          </div>
          <div class="modal-footer">
            <form method="post" id="delete-cta">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="DELETE">
              <a class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</a>
              <button class="btn btn-danger btn-loader">Ya, hapus data</button>
            </form>
          </div>
        </div>
      </div>
    </div>
@endsection
@section('js')
<script>
  $(".btn-delete").each(function(index) {
    $(this).attr('data-bs-target', '#modal-delete');
    $(this).attr('data-bs-toggle', 'modal');

    $(this).click(function() {
      href = $(this).attr('data-href');
      $('#delete-cta').attr('action', href);
    });
  });

  $("#delete-cta").click(function() {
    $("#modal-delete").modal('hide');
  });
</script>
@endsection
