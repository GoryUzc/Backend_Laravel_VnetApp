<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
      public function create2()
    {
        return view('tasks.index');
    }
}
