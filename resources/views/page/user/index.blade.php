@extends('layouts.auth')

@section('content')
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
                    <span class="badge btn-{{ $check }}">{{ $role->name }}</span>
                  @endforeach
                </td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);"
                            ><i class="bx bx-edit-alt me-1"></i> Edit</a
                        >
                        <a class="dropdown-item" href="javascript:void(0);"
                            ><i class="bx bx-trash me-1"></i> Delete</a
                        >
                        </div>
                        </div>
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
@endsection