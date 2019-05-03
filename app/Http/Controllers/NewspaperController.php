<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Newspaper;

class NewspaperController extends Controller
{
    public function newspaper(){
    	$newspapers = Newspaper::all();
    	return view('title',['newspapers'=>$newspapers]); 
    }
}
