@extends('layouts.layout-admin')

@section('title', 'Edit Permission')

@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">

    <h1><i class='fa fa-key'></i> Edit {{$permission->name}}</h1>
    <br>
    {{ Form::model($permission, array('route' => array('permissions.update', $permission->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with permission data --}}

    <div class="form-group">
        {{ Form::label('name', 'Permission Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>
    <br>
    {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

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
