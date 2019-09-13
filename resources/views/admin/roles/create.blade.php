@extends('layouts.layout-admin')

@section('title', 'Add Role')

@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">

    <h1><i class='fa fa-key'></i> Add Role</h1>
    <hr>

    {{ Form::open(array('url' => 'admin/roles')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>

    <h5><b>Assign Permissions</b></h5>

    <div class='form-group'>
        @foreach ($permissions as $permission)
            {{ Form::checkbox('permissions[]',  $permission->id ) }}
            {{ Form::label($permission->name, ucfirst($permission->name)) }}<br>

        @endforeach
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
