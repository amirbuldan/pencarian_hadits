@extends('layouts.layout-admin')

@section('title', '| Create User')

@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">

    <h1><i class='fa fa-user-plus'></i> Add User</h1>
    <hr>

    {{ Form::open(array('url' => 'admin/users')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', '', array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', '', array('class' => 'form-control')) }}
    </div>

    <div class='form-group'>
        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id, false, array('data-toggle' => 'toggle')) }}
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

    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

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
