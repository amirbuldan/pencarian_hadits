@extends('layouts.layout-admin')

@section('title', 'Edit User')

@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
      <div class='col-md-12'>

        <h1><i class='fa fa-user-plus'></i> Edit {{$user->name}}</h1>
        <hr>

        {{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with user data --}}

        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('email', 'Email') }}
            {{ Form::email('email', null, array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
          {{ Form::checkbox('active', $user->active, '1', array('data-toggle' => 'toggle') ) }}
          {{ Form::label('active', 'Active') }}
        </div>

        <h5><b>Give Role</b></h5>

        <div class='form-group'>
            @foreach ($roles as $role)
                {{ Form::checkbox('roles[]',  $role->id, $user->roles, array('data-toggle' => 'toggle') ) }}
                {{ Form::label($role->name, ucfirst($role->name)) }}<br>

            @endforeach
        </div>

        <div class="form-group">
            {{ Form::label('password', 'Password') }}<br>
            {{ Form::password('password', array('class' => 'form-control')) }}

        </div>

        <div class="form-group">
            {{ Form::label('password', 'Confirm Password') }}<br>
            {{ Form::password('password_confirmation', array('class' => 'form-control')) }}

        </div>

        {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}

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
