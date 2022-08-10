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
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-body border-bottom py-3">
            <div class="d-flex">
              <div class="text-muted">
                <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm btn-loader">Create</a>
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
              <th>Username</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Role</th>
              <th>Actions</th>
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
                <td>{{ $data->username}}</td>
                <td>{{ $data->name}}</td>
                <td>{{ $data->phone}}</td>
                <td>
                  @foreach($data->roles as $role)
                    @php
                      switch($role->name)
                      {
                        case 'admin': 
                          $check = 'success';
                          break;
                        default: 
                          $check = 'warning';
                      }
                    @endphp
                    <span class="badge bg-label-{{ $check }} me-1">{{ $role->name }}</span>
                  @endforeach
                </td>
                <td class="text-start">
                  <a href="{{ route('user.edit', $data->id) }}" class="btn badge bg-label-info btn-sm btn-loader">Edit</a>
                  <a href="#" data-href="{{ route('user.delete', $data->id) }}" class="btn badge bg-label-danger btn-sm btn-delete">Delete</a>
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