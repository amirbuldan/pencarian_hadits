@extends('layouts.layout-admin')

@section('title', 'Create Hadits')

@section('content')
<div class="content-wrapper">
  <section class="content">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="row">
      <div class="col-xs-6">
        <div class="box">
          <div class="box-header">

    <h1><i class='fa fa-map'></i> Add Kriteria</h1>
    <hr>

    {{ Form::open(array('url' => 'admin/haditss')) }}

    <div class="form-group">
        {{ Form::label('nomor', 'Nomor Hadits') }}
        {{ Form::number('nomor', '', array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label('kitab', 'Kitab') }}
        {{ Form::text('kitab', '', array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label('bab', 'Bab') }}
        {{ Form::text('bab', '', array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label('isi', 'Isi') }}
        {{ Form::textarea('isi', '', array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
    </div>
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
