@extends('layouts.layout-admin')

@section('title', 'Result')

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
    @include('includes.notification')
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h1><i class="fa fa-search"></i> Pencarian
          </div>
          <!-- /.box-header -->
          <div class="box-body">
             {{ Form::open(array('url' => 'admin/result')) }}

                <div class="form-group">
                  {{ Form::label('query', 'Masukkan Kata') }}
                  {{ Form::text('query', '', array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                  {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
                </div>

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
