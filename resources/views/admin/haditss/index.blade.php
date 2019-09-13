@extends('layouts.layout-admin')

@section('title', 'haditss')

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
            <h1><i class="fa fa-map"></i> Hadits
              @role('admin')
            <a href="{{ URL::to('admin/haditss/create') }}" class="btn btn-success">Add</a>
            @endrole
            
            <hr>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Nomor</th>
                <th>Kitab</th>
                <th>Bab</th>
                <th>Isi</th>
                <th>Operation</th>
              </tr>
              </thead>
              <tbody>
                  @foreach ($haditss as $key=>$hadits)
                  <tr>
                      <td>{{$hadits->nomor}}</td>
                      <td>{{ $hadits->kitab }}</td>
                      <td>{{ $hadits->bab }}</td>
                      <td>{{ $hadits->isi }}</td>
                      <td>
                      <a href="{{ URL::to('admin/haditss/'.$hadits->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                      {!! Form::open(['method' => 'DELETE', 'route' => ['haditss.destroy', $hadits->id] ]) !!}
                      {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                      {!! Form::close() !!}

                      </td>
                  </tr>
                  @endforeach
              </tbody>
              <tfoot>
              <tr>
                <th>Nomor</th>
                <th>Kitab</th>
                <th>Bab</th>
                <th>Isi</th>
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
