<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Hadits;
use App\Hadits_pt;
use App\Daftar_kata_pt;
use App\Word_stoplist;
use App\Kata_dasar;
use DB;
use Auth;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Input;

//Enables us to output flash messaging
use Session;

class HaditsController extends Controller
{
  public function __construct()
  {
      // $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
  }

  public function result(Request $request)
  {
    // dd($request['query']);
    //------------------------Preprocessing hadits-------------------------------------------//
        $kata_yang_dicari = $request['query'];
        //lowercase
        $keyword = str_replace("-"," ", strtolower($request['query'])); 
        //menghilangkan simbol dan angka
        $keywordBaru = preg_replace("/[^a-z ]/i", "", $keyword);
        // dd($keywordBaru);

        //pecah keyword
        $query = explode(" ", $keywordBaru);
        // menghilangagkan simbol <br>
        foreach ($query as $key => $word) {
          if($word=="br"){
            unset($query[$key]); 
          }
        }
        // $query = array_unique($query); // menghilangkan kata yang sama
        $query = array_values($query); //after unique untuk minimalisir error karena nomor index yg dihampus
        // dd($query);
        $jml = count($query); //count query after explode
        // dd($jml);
        $queryHighlight = explode(" ", $keywordBaru);

        $stopword = array(); 

        // $filter=mysql_query('SELECT word_stoplist.word FROM word_stoplist'); ***CI***
        $filters = Word_stoplist::select('word')->get();
        // dd($filters);
        
        //***CI***
        // while ($row = @mysql_fetch_array($filter)) {
        //     $stopword[] = trim($row['word']);
        // }

        foreach ($filters as $filter) {
          array_push($stopword, $filter->word);
        }
        // dd($stopword);
        
        //menghilangkan keyword yg sama dengan stoplist
        for ($i = 0; $i < $jml; $i++) {
            if (in_array($query[$i], $stopword)) {
                unset($query[$i]); 
            }
            if (in_array($queryHighlight[$i], $stopword)) {
                unset($queryHighlight[$i]); 
            }

            if(!empty($query[$i])){
              $query[$i] = $this->hapusakhiran($this->hapusawalan2($this->hapusawalan1($this->hapuspp($this->hapuspartikel($query[$i])))));
            }
        }
        // dd($query);
        $query_implode = implode(" ", $query);
        // dd($query_implode);
        //-----------------------END Preprocessing Hadits---------------------------------//


        //---------------------------------------------- START TFIDF ---------------------------------------------------
        $haditss = Hadits::all();
        $pt_hadits = Hadits_pt::all();
        $pt_daftar_kata = Daftar_kata_pt::all();
        $daftar_kata = array();

        foreach ($pt_daftar_kata as $key => $value) {
            array_push($daftar_kata, $value->kata);
        }

        sort($daftar_kata);
        $daftar_kata = array_unique($daftar_kata); // menghilangkan kata yang sama
        $daftar_kata = array_values($daftar_kata);

        // dd($daftar_kata);
        $jumlah_hadits = count($haditss);

        $tf = array();
        $df = array();
        $idf = array();
        $Q = array();
        $Qkuadrat = array();

        foreach ($daftar_kata as $key => $kata) {
          $df[$key]=0;
          foreach ($haditss as $ayat => $hadits) {
            $term_frequency = Daftar_kata_pt::where('kata','=',$kata)->where('hadits_id','=',$hadits->id)->count();
            $tf[$key][$ayat] = $term_frequency;
            if($tf[$key][$ayat]>0){
              $df[$key]++;
            }
          }
          $idf[$key] = number_format(log($jumlah_hadits/$df[$key] , 10), 4);
          if(in_array($kata, $query)){
            $Qkuadrat[$key] = number_format(pow($idf[$key], 2), 4);
          }
        }

        $sumQkuadrat = array_sum($Qkuadrat);
        $root_sumQkuadrat = number_format(sqrt($sumQkuadrat),4);
       
        $W = array();
        $Wkuadrat = array();
        foreach ($daftar_kata as $key => $kata) {
          foreach ($haditss as $ayat => $hadits) {
            $W[$key][$ayat] = $tf[$key][$ayat] * $idf[$key];
            $Wkuadrat[$key][$ayat] = number_format(pow(($W[$key][$ayat]),2), 4);
            if(in_array($kata, $query)){
              $Q[$key][$ayat] = number_format(($idf[$key]*$W[$key][$ayat]), 4);
            }
          }
        }

      
        
        // $qword = array('kata1','kata2','kata3','kata4');
        // $haditzz = array(1,2,3);
        // $test[0] = array(1,2,3);
        // $test[1] = array(1,1,1);
        // $test[2] = array(1,1,1);
        // $test[3] = array(2,2,2);
        // $sumTest = array();
        // // dd($test);
        // foreach ($haditzz as $ayat => $hadits) {
        //       $sumTest[$ayat] = 0;
        // }

        // foreach ($qword as $key => $kata) {
        //   foreach ($haditzz as $ayat => $hadits) {
        //       $sumTest[$ayat] = $sumTest[$ayat]+$test[$key][$ayat];
        //     }
        // }
        
        $sumWkuadrat = array();
        $root_sumWkuadrat = array();

         foreach ($haditss as $ayat => $hadits) {
              $sumWkuadrat[$ayat] = 0;
        }
        foreach ($daftar_kata as $key => $kata) {
          foreach ($haditss as $ayat => $hadits) {
           $sumWkuadrat[$ayat] = number_format(($sumWkuadrat[$ayat]+$Wkuadrat[$key][$ayat]),4);
          }
        }

        foreach ($sumWkuadrat as $key => $value) {
          $root_sumWkuadrat[$key] = number_format(sqrt($sumWkuadrat[$key]), 4);
        }
        // dd($sumWkuadrat, $root_sumWkuadrat);
        
        $cosine = array();
        foreach ($daftar_kata as $key => $kata) {
          foreach ($haditss as $ayat => $hadits) {
            if(in_array($kata, $query)){
              $cosine[$ayat] = number_format(($Q[$key][$ayat]/($root_sumWkuadrat[$ayat]*$root_sumQkuadrat)), 4);
            }
          }
        }

        // dd($cosine);
        // dd($daftar_kata,$tf,$df,$idf,$W,$Qkuadrat,$Wkuadrat,$Q,$sumWkuadrat);

        //--------------------------------------------------------- END TFIDF ---------------------------------------------------

        //------------------------------------------ START COSINE SIMILIARITY ---------------------------------------------------


        //------------------------------------------ END COSINE SIMILIARITY ---------------------------------------------------
         return view('admin.haditss.result', compact('kata_yang_dicari','query_implode','daftar_kata','haditss','jumlah_hadits','tf','df','idf','W','Wkuadrat','Q','Qkuadrat','sumQkuadrat','sumWkuadrat','root_sumWkuadrat','root_sumQkuadrat','cosine'));

  }

  public function search()
  {
    return view('admin.haditss.search');
  }

  public function imambukhari()
  {  
    // dd("haha");
      return view('admin.haditss.imambukhari');
  }

  public function index()
  {  
      $haditss = Hadits::all();
      return view('admin.haditss.index')->with('haditss', $haditss);
  }

   public function create()
  {
      return view('admin.haditss.create');
  }
    public function store(Request $request)
  {
        $rules=[
         'nomor'=>'required',
         'kitab'=>'required',
         'bab'=>'required',
         'isi'=>'required',
        ];
       $messages = [
         'required' => ':attribute Masih Kosong',
       ];
       $niceNames = array(
            'nomor'=>'Nomor Hadits',
            'kitab'=>'Kitab',
            'bab'=>'Bab',
            'isi'=>'Isi',

        );
       $validator = \Validator::make($request->all(), $rules,$messages);
       $validator->setAttributeNames($niceNames); 

       if ($validator->fails()) {
          return redirect()->back()->withErrors($validator);
       }

       $hadits = Hadits::create($request->only('nomor','kitab','bab','isi')); 
       // dd($hadits->isi);

       //------------------------Preprocessing hadits-------------------------------------------//

        //lowercase
        $keyword = str_replace("-"," ", strtolower($hadits->isi)); 
        //menghilangkan simbol dan angka
        $keywordBaru = preg_replace("/[^a-z ]/i", "", $keyword);
        // dd($keywordBaru);

        //pecah keyword
        $query = explode(" ", $keywordBaru);
        // menghilangagkan simbol <br>
        foreach ($query as $key => $word) {
          if($word=="br"){
            unset($query[$key]); 
          }
        }
        // $query = array_unique($query); // menghilangkan kata yang sama
        $query = array_values($query); //after unique untuk minimalisir error karena nomor index yg dihampus
        // dd($query);
        $jml = count($query); //count query after explode
        // dd($jml);
        $queryHighlight = explode(" ", $keywordBaru);

        $stopword = array(); 

        // $filter=mysql_query('SELECT word_stoplist.word FROM word_stoplist'); ***CI***
        $filters = Word_stoplist::select('word')->get();
        // dd($filters);
        
        //***CI***
        // while ($row = @mysql_fetch_array($filter)) {
        //     $stopword[] = trim($row['word']);
        // }

        foreach ($filters as $filter) {
          array_push($stopword, $filter->word);
        }
        // dd($stopword);
        
        //menghilangkan keyword yg sama dengan stoplist
        for ($i = 0; $i < $jml; $i++) {
            if (in_array($query[$i], $stopword)) {
                unset($query[$i]); 
            }
            if (in_array($queryHighlight[$i], $stopword)) {
                unset($queryHighlight[$i]); 
            }

            if(!empty($query[$i])){
              $query[$i] = $this->hapusakhiran($this->hapusawalan2($this->hapusawalan1($this->hapuspp($this->hapuspartikel($query[$i])))));
            }
        }
        // dd($query);
        $query_implode = implode(" ", $query);
        // dd($query_implode);
      
         // $pt_hadits = new Hadits_pt();
         // $pt_hadits->hadits_id = $hadits->id;
         // $pt_hadits->isi = $query_implode;
         // $pt_hadits->save();
          
          $pt_hadits = Hadits_pt::create([
            'hadits_id' => $hadits->id,
            'isi' => $query_implode,
          ]);

          // dd($pt_hadits);
        //-----------------------END Preprocessing Hadits---------------------------------//

         //masukkan setiap kata ke table pt_daftar kata
         foreach ($query as $key => $word) {
           if($word != ""){
             $pt_daftar_kata = new Daftar_kata_pt();
             $pt_daftar_kata->hadits_id = $hadits->id;
             $pt_daftar_kata->kata = $word;
             $pt_daftar_kata->save();
           }
         }


       return redirect()->route('haditss.index')
            ->with('success',
             'Hadits successfully added');
  }
    public function show()
  {   
    
  }
    public function edit($id)
  {
      $hadits = Hadits::findOrFail($id); //Get hadits with specified id
        
      return view('admin.haditss.edit')->with('hadits', $hadits); //pass hadits data to view
  }
     public function update(Request $request, $id)
  {     
        $hadits = Hadits::findOrFail($id);
        //Validate fields
        $rules=[
         'nomor'=>'required',
         'kitab'=>'required',
         'bab'=>'required',
         'isi'=>'required',
        ];
       $messages = [
         'required' => ':attribute Masih Kosong',
       ];
       $niceNames = array(
            'nomor'=>'Nomor Hadits',
            'kitab'=>'Kitab',
            'bab'=>'Bab',
            'isi'=>'Isi',

        );
       $validator = \Validator::make($request->all(), $rules,$messages);
       $validator->setAttributeNames($niceNames); 

        $hadits->nomor  = Input::get('nomor');
        $hadits->kitab  = Input::get('kitab');
        $hadits->bab  = Input::get('bab');
        $hadits->isi  = Input::get('isi');
        $hadits->save();

        $pt_hadits = Hadits_pt::where('hadits_id','=',$id);
        $hadits->delete();
        $pt_daftar_kata = Daftar_kata_pt::where('hadits_id','=',$id);
        $hadits->delete();

        //------------------------Preprocessing hadits-------------------------------------------//

        //lowercase
        $keyword = str_replace("-"," ", strtolower($hadits->isi)); 
        //menghilangkan simbol dan angka
        $keywordBaru = preg_replace("/[^a-z ]/i", "", $keyword);
        // dd($keywordBaru);

        //pecah keyword
        $query = explode(" ", $keywordBaru);
        // menghilangagkan simbol <br>
        foreach ($query as $key => $word) {
          if($word=="br"){
            unset($query[$key]); 
          }
        }
        // $query = array_unique($query); // menghilangkan kata yang sama
        $query = array_values($query); //after unique untuk minimalisir error karena nomor index yg dihampus
        // dd($query);
        $jml = count($query); //count query after explode
        // dd($jml);
        $queryHighlight = explode(" ", $keywordBaru);

        $stopword = array(); 

        // $filter=mysql_query('SELECT word_stoplist.word FROM word_stoplist'); ***CI***
        $filters = Word_stoplist::select('word')->get();
        // dd($filters);
        
        //***CI***
        // while ($row = @mysql_fetch_array($filter)) {
        //     $stopword[] = trim($row['word']);
        // }

        foreach ($filters as $filter) {
          array_push($stopword, $filter->word);
        }
        // dd($stopword);
        
        //menghilangkan keyword yg sama dengan stoplist
        for ($i = 0; $i < $jml; $i++) {
            if (in_array($query[$i], $stopword)) {
                unset($query[$i]); 
            }
            if (in_array($queryHighlight[$i], $stopword)) {
                unset($queryHighlight[$i]); 
            }

            if(!empty($query[$i])){
              $query[$i] = $this->hapusakhiran($this->hapusawalan2($this->hapusawalan1($this->hapuspp($this->hapuspartikel($query[$i])))));
            }
        }
        // dd($query);
        $query_implode = implode(" ", $query);
        //--------------------------------------------- END PREPROCESSING ------------------------------------------------------

        $pt_hadits = Hadits_pt::create([
            'hadits_id' => $hadits->id,
            'isi' => $query_implode,
        ]);

        foreach ($query as $key => $word) {
           if($word != ""){
             $pt_daftar_kata = new Daftar_kata_pt();
             $pt_daftar_kata->hadits_id = $hadits->id;
             $pt_daftar_kata->kata = $word;
             $pt_daftar_kata->save();
           }
         }


        return redirect()->route('haditss.index')
            ->with('success',
             'Hadits successfully edited.');
  }
     public function destroy($id)
  {
        $hadits = Hadits::findOrFail($id);
        $hadits->delete();

        return redirect()->route('haditss.index')
            ->with('success',
             'Hadits successfully deleted.');
  }
public function pecahkataawal($id)
{
$pt_hadits = Hadits_pt::all();
    for($i=0;$i<=199;$i++){
        $queries = explode(" ", $pt_hadits[$i]->isi);
        DB::beginTransaction();
          try {
            foreach ($queries as $key => $word) {
             if($word != ""){
               $pt_daftar_kata = new Daftar_kata_pt();
               $pt_daftar_kata->hadits_id = $pt_hadits[$i]->hadits_id;
               $pt_daftar_kata->kata = $word;
               $pt_daftar_kata->save();
             }
            }
            DB::commit();
          } catch (\Exception $e) {
            dd($e);
            DB::rollback();
          }
      }

      return redirect()->route('haditss.index')
            ->with('success',
             'Hadits successfully dipecah indeks 0 - 199');
}

  public function stemmingawal($id)
  {
  // fungsi ini bukan buat show sebenarnya, tapi untuk stemming data awal (master)
      // dd('hahaha');
      $haditss = Hadits::all();
       //------------------------Preprocessing hadits-------------------------------------------//

      foreach ($haditss as $key => $hadits) {
        $pthadtis = Hadits_pt::where('hadits_id','=',$hadits->id)->first();
        if(!empty($pthadtis)){
          continue;
        }
        //lowercase
        $keyword = str_replace("-"," ", strtolower($hadits->isi)); 
        //menghilangkan simbol dan angka
        $keywordBaru = preg_replace("/[^a-z ]/i", "", $keyword);
        // dd($keywordBaru);

        //pecah keyword
        $query = explode(" ", $keywordBaru);
        // $query = array_unique($query); // menghilangkan kata yang sama
        $query = array_values($query); //after unique untuk minimalisir error karena nomor index yg dihampus
        // dd($query);
        $jml = count($query); //count query after explode
        // dd($jml);
        $queryHighlight = explode(" ", $keywordBaru);

        $stopword = array(); 

        // $filter=mysql_query('SELECT word_stoplist.word FROM word_stoplist'); ***CI***
        $filters = Word_stoplist::select('word')->get();
        // dd($filters);
        
        //***CI***
        // while ($row = @mysql_fetch_array($filter)) {
        //     $stopword[] = trim($row['word']);
        // }

        foreach ($filters as $filter) {
          array_push($stopword, $filter->word);
        }
        // dd($stopword);
        
        //menghilangkan keyword yg sama dengan stoplist
        for ($i = 0; $i < $jml; $i++) {
            if (in_array($query[$i], $stopword)) {
                unset($query[$i]); 
            }
            if (in_array($queryHighlight[$i], $stopword)) {
                unset($queryHighlight[$i]); 
            }

            if(!empty($query[$i])){
              $query[$i] = $this->hapusakhiran($this->hapusawalan2($this->hapusawalan1($this->hapuspp($this->hapuspartikel($query[$i])))));
            }
        }
        // dd($query);
        $query_implode = implode(" ", $query);
        // dd($query_implode);
     
         $pt_hadits = new Hadits_pt();
         $pt_hadits->hadits_id = $hadits->id;
         $pt_hadits->isi = $query_implode;
         $pt_hadits->save();

      } //endforeach
       return redirect()->route('haditss.index')
            ->with('success',
             'Hadits sudah di stemming semua.');
      //-----------------------END Preprocessing Hadits---------------------------------//
    }


    //--------------------------------------- FUNGSI TEXT PREPROCESSING ------------------------------------------//
  public function cari($kata){
      //***CI***
      // $hasil = mysql_num_rows(mysql_query("SELECT * FROM kata_dasar WHERE katadasar='$kata'"));
    $hasil = Kata_dasar::where('katadasar','=',$kata)->count();
    // dd($kata);
    return $hasil;

  }

  //langkah 1 - hapus partikel
  public function hapuspartikel($kata)
  {
    if($this->cari($kata)!=1){
      if((substr($kata, -3) == 'kah' )||( substr($kata, -3) == 'lah' )||( substr($kata, -3) == 'pun' )){
        $kata = substr($kata, 0, -3);     
      }
    }
    return $kata;
  }

  //langkah 2 - hapus possesive pronoun
  public function hapuspp($kata){
    if($this->cari($kata)!=1){
      if(strlen($kata) > 4){
        if((substr($kata, -2)== 'ku')||(substr($kata, -2)== 'mu')){
          $kata = substr($kata, 0, -2);
        }else if((substr($kata, -3)== 'nya')){
          $kata = substr($kata,0, -3);
        }
      }
    }
    return $kata;
  }

      //langkah 3 hapus first order prefiks (awalan pertama)
  function hapusawalan1($kata){
    if($this->cari($kata)!=1){

      if(substr($kata,0,4)=="meng"){
        if(substr($kata,4,1)=="e"){
          $kata = "k".substr($kata,4);
        }else{
          $kata = substr($kata,4);
        }
      }else if(substr($kata,0,4)=="meny"){
        $kata = "s".substr($kata,4);
      }else if(substr($kata,0,3)=="men"){
        if(substr($kata,3,1)=="a" || substr($kata,3,1)=="i" || substr($kata,3,1)=="e" || substr($kata,3,1)=="u" || substr($kata,3,1)=="o"){
          $kata = "t".substr($kata,3);
        }else{
          $kata = substr($kata,3);
        }
      }else if(substr($kata,0,3)=="mem"){
        if(substr($kata,3,1)=="a" || substr($kata,3,1)=="i" || substr($kata,3,1)=="e" || substr($kata,3,1)=="u" || substr($kata,3,1)=="o"){
          $kata = "p".substr($kata,3);
        }else{
          $kata = substr($kata,3);
        }
      }else if(substr($kata,0,2)=="me"){
        $kata = substr($kata,2);
      }else if(substr($kata,0,4)=="peng"){
        if(substr($kata,4,1)=="e"){
          $kata = "k".substr($kata,4);
        }else{
          $kata = substr($kata,4);
        }
      }else if(substr($kata,0,4)=="peny"){
        $kata = "s".substr($kata,4);
      }else if(substr($kata,0,3)=="pen"){
        if(substr($kata,3,1)=="a" || substr($kata,3,1)=="i" || substr($kata,3,1)=="e" || substr($kata,3,1)=="u" || substr($kata,3,1)=="o"){
          $kata = "t".substr($kata,3);
        }else{
          $kata = substr($kata,3);
        }
      }else if(substr($kata,0,3)=="pem"){
        if(substr($kata,3,1)=="a" || substr($kata,3,1)=="i" || substr($kata,3,1)=="e" || substr($kata,3,1)=="u" || substr($kata,3,1)=="o"){
         $kata = "p".substr($kata,3);
       }else{
        $kata = substr($kata,3);
      }
    }else if(substr($kata,0,2)=="di"){
      $kata = substr($kata,2);
    }else if(substr($kata,0,3)=="ter"){
      $kata = substr($kata,3);
    }else if(substr($kata,0,2)=="ke"){
      $kata = substr($kata,2);
    }
  }
  return $kata;
}

      //langkah 4 hapus second order prefiks (awalan kedua)
public function hapusawalan2($kata){
  if($this->cari($kata)!=1){

    if(substr($kata,0,3)=="ber"){
      $kata = substr($kata,3);
    }else if(substr($kata,0,3)=="bel"){
      $kata = substr($kata,3);
    }else if(substr($kata,0,2)=="be"){
      $kata = substr($kata,2);
    }else if(substr($kata,0,3)=="per" && strlen($kata) > 5){
      $kata = substr($kata,3);
    }else if(substr($kata,0,3)=="pel"  && strlen($kata) > 5){
      $kata = substr($kata,3);
    }else if(substr($kata,0,2)=="pe"  && strlen($kata) > 5){
      $kata = substr($kata,2);
    }else if(substr($kata,0,2)=="se"  && strlen($kata) > 5){
      $kata = substr($kata,2);
    }
  }
  return $kata;
}

      ////langkah 5 hapus suffiks
public function hapusakhiran($kata){
  if($this->cari($kata)!=1){

    if (substr($kata, -3)== "kan"){
      $kata = substr($kata, 0, -3);
    }
    else if(substr($kata, -1)== "i" ){
      $kata = substr($kata, 0, -1);
    }
    else if(substr($kata, -2)== "an"){
      $kata = substr($kata, 0, -2);
    }
  } 

  return $kata;
}
//-------------------------------------------- END TEXT PREPROCESSING -----------------------------------------//

}
