@extends('layouts.layout-admin')

@section('title', 'Roles')

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
            <h1><i class="fa fa-key"></i> Roles
            <a href="{{ URL::to('admin/roles/create') }}" class="btn btn-success">Add Role</a>
            <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Users</a>
            <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
            <hr>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Role</th>
                <th>Permissions</th>
                <th>Operation</th>
              </tr>
              </thead>
              <tbody>
                  @foreach ($roles as $role)
                  <tr>

                      <td>{{ $role->name }}</td>

                      <td>{{ str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')) }}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                      <td>
                      <a href="{{ URL::to('admin/roles/'.$role->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                      {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id] ]) !!}
                      {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                      {!! Form::close() !!}

                      </td>
                  </tr>
                  @endforeach
              </tbody>
              <tfoot>
              <tr>
                <th>Role</th>
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
