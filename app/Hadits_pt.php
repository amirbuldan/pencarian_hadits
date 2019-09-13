<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Auth;

class Hadits_pt extends Model
{
    protected $guarded = [];
    protected $table = 'pt_haditss';

 public function hadits()
  {
      return $this->belongsTo('App\Hadits');
  }

 }
