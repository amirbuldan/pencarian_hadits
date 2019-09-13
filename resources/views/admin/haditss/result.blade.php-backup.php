
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
  table, th, td {
  border: 1px solid black !important;
}
</style>

<div class="content-wrapper">
  <section class="content">
    @include('includes.notification')
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h1><i class="fa fa-map"></i> Result
            <a class="btn btn-success" onclick="hide_rumus()" id="hide_rumus" style="display: none;">Sembunyikan Perhitungan</a>
            <a class="btn btn-danger" onclick="show_rumus()" id="show_rumus">Tampilkan Perhitungan</a>
            <hr>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
                <h3> Pencarian : "{{$kata_yang_dicari}}"</h3>
                <div id="rumus" style="display: none;">
                    <h2> Pembobotan Kata TFIDF </h2>
                    <table class="table table-bordered table-striped" style="border-style: 1px solid">
                      
                      <tr>
                        <th rowspan="2">Term</th>
                        <th colspan="{{$jumlah_hadits}}">TF</th><!-- sesuai jumlah ayat -->
                        <th rowspan="2">DF</th>
                        <th colspan="2">IDF</th>
                        <th colspan="{{$jumlah_hadits}}">W = TF * IDF </th> <!-- sesuai jumlah ayat -->
                      </tr>

                      <tr>
                        @for($i=1; $i<=$jumlah_hadits;$i++)
                        <td>d{{$i}}</td>
                        @endfor

                        <td>N/DF</td>
                        <td>Log(N/DF)</td>

                         @for($i=1; $i<=$jumlah_hadits;$i++)
                        <td>d{{$i}}</td>
                        @endfor
                      </tr>

                      @foreach($daftar_kata as $key => $kata)
                      <tr>
                          <td>{{$kata}}</td>

                          @foreach($haditss as $ayat => $hadits)
                          <td>@if($tf[$key][$ayat]){{$tf[$key][$ayat]}}@endif</td>
                          @endforeach

                          <td>{{$df[$key]}}</td>
                          <td>{{$jumlah_hadits/$df[$key]}}</td>
                          <td>{{$idf[$key]}}</td>

                           @foreach($haditss as $ayat => $hadits)
                          <td>@if($W[$key][$ayat]!=0){{$W[$key][$ayat]}}@endif</td>
                          @endforeach
                      </tr>
                      @endforeach
                    </table>

                    <br>
                    <hr>
                    <br>
                    <h2> Menghitung Cosine Similarity </h2>
                    <table class="table table-bordered table-striped" style="border-style: 1px solid">
                      
                      <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Term</th>
                        <th rowspan="2">Q<sup>2</sup></th>
                        <th colspan="{{$jumlah_hadits}}">W<sup>2</sup></th><!-- sesuai jumlah ayat -->
                        <th colspan="{{$jumlah_hadits}}">Q</th> <!-- sesuai jumlah ayat -->
                      </tr>

                      <tr> 
                        @for($i=1; $i<=$jumlah_hadits;$i++)
                        <td>d{{$i}}<sup>2</sup></td>
                        @endfor

                        @for($i=1; $i<=$jumlah_hadits;$i++)
                        <td>Q*d{{$i}}</td>
                        @endfor
                      </tr>

                      @foreach($daftar_kata as $key => $kata)
                      <tr>
                          <td>{{$key+1}}</td>
                          <td>{{$kata}}</td>
                          <td>@if(@$Qkuadrat[$key]){{$Qkuadrat[$key]}}@endif</td>


                          @foreach($haditss as $ayat => $hadits)
                            <td>@if($Wkuadrat[$key][$ayat]!=0){{$Wkuadrat[$key][$ayat]}}@endif</td>
                          @endforeach

                           @foreach($haditss as $ayat => $hadits)
                            <td>@if(@$Q[$key][$ayat] && $Q[$key][$ayat]!=0){{$Q[$key][$ayat]}}@endif</td>
                          @endforeach
                      </tr>
                      @endforeach
                      <tr>
                         <th colspan="2">SUM</th>
                         <th>{{$sumQkuadrat}}</th>
                          @foreach($haditss as $ayat => $hadits)
                            <th>@if($sumWkuadrat[$ayat]!=0){{$sumWkuadrat[$ayat]}}@endif</th>
                          @endforeach

                      </tr>
                      <tr>
                         <th colspan="2">ROOT</th>
                         <th>{{$root_sumQkuadrat}}</th>
                         @foreach($haditss as $ayat => $hadits)
                            <th>@if($root_sumWkuadrat[$ayat]!=0){{$root_sumWkuadrat[$ayat]}}@endif</th>
                         @endforeach
                      </tr>
                    </table>

                    <br>
                    <hr>
                    <br>
                    <table class="table table-bordered table-striped" style="border-style: 1px solid">
                      
                      <tr>
                        <th colspan="{{$jumlah_hadits}}">Cosine</th><!-- sesuai jumlah ayat -->
                      </tr>

                      <tr> 
                        @for($i=1; $i<=$jumlah_hadits;$i++)
                        <td>d{{$i}}</td>
                        @endfor
                      </tr>
                      <tr>
                         @foreach($haditss as $ayat => $hadits)
                            <th>@if($cosine[$ayat]!=0){{$cosine[$ayat]}} @else 0 @endif</th>
                         @endforeach
                      </tr>
                    </table>
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
