@extends('layouts.layout-admin')

@section('title', 'Permissions')

@section('content')
<style media="screen">
  #example1_filter{
        float: right;
  }
  #example1_paginate{
        float: right;
  }
</style>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h1><i class="fa fa-key"></i>Available Permissions
            <a href="{{ URL::to('admin/permissions/create') }}" class="btn btn-success">Add Permission</a>
            <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Users</a>
            <a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a></h1>
            <hr>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Permissions</th>
                <th>Operation</th>
              </tr>
              </thead>
              <tbody>
                  @foreach ($permissions as $permission)
                  <tr>
                      <td>{{ $permission->name }}</td>
                      <td>
                      <a href="{{ URL::to('admin/permissions/'.$permission->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                      {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id] ]) !!}
                      {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                      {!! Form::close() !!}

                      </td>
                  </tr>
                  @endforeach
              </tbody>
              <tfoot>
              <tr>
                <th>Permissions</th>
                <th>Operation</th>
              </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
</div>
@endsection
