@extends('layouts.layout-admin')

@section('title', 'Edit hadits')

@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-xs-6">
        <div class="box">
          <div class="box-header">

        <h1><i class='fa fa-map'></i> Edit Hadits</h1>
        <hr>

        {{ Form::model($hadits, array('route' => array('haditss.update', $hadits->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with hadits data --}}

        <div class="form-group">
          {{ Form::label('nomor', 'Nomor Hadits') }}
          {{ Form::number('nomor', null, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
          {{ Form::label('kitab', 'Kitab') }}
          {{ Form::text('kitab', null, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
          {{ Form::label('bab', 'Bab') }}
          {{ Form::text('bab', null, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
          {{ Form::label('isi', 'Isi') }}
          {{ Form::text('isi', null, array('class' => 'form-control')) }}
        </div>

        {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}

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
