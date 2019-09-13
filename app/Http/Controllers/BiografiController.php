<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Input;

//Enables us to output flash messaging
use Session;

class BiografiController extends Controller
{
  public function __construct()
  {
      // $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
  }

  public function index()
  {  
      return view('admin.haditss.imambukhari');
  }


}
