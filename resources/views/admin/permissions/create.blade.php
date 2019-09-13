@extends('layouts.layout-admin')

@section('title', 'Create Permission')

@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">

    <h1><i class='fa fa-key'></i> Add Permission</h1>
    <br>

    {{ Form::open(array('url' => 'admin/permissions')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', '', array('class' => 'form-control')) }}
    </div><br>
    @if(!$roles->isEmpty())
        <h4>Assign Permission to Roles</h4>

        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id ) }}
            {{ Form::label($role->name, ucfirst($role->name)) }}<br>

        @endforeach
    @endif
    <br>
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
