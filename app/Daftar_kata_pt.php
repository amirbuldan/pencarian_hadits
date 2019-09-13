<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Daftar_kata_pt extends Model
{
    protected $guarded = [];
    protected $table = 'pt_daftar_kata';


public function hadits()
  {
      return $this->belongsTo('App\Hadits');
  }

public function pt_daftar_kata()
  {
    return $this->hasMany('App\Daftar_kata_pt');
  }
public function pt_hadits()
  {
    return $this->hasMany('App\Hadits_pt');
  }

}
