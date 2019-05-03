<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Accident;

use App\Statistic;

use Illuminate\Support\Facades\DB;

use Chart;

class HomeController extends Controller
{
    public function index()
    {   
        $accidents = Accident::paginate(10);
        // $accidents = DB::table('accidents')->orderBy('id','desc')->paginate(10);
      	return view('welcome',['accidents'=>$accidents]);
    }

    public function month()
    {
    	$stats = Statistic::all();
    	$accidents = DB::table('statistics')->select('month','accidentQuantity AS quantity','diedQuantity AS died','hurtQuantity AS hurt')->get();
        return view('statistic-month',['stats'=>$stats,'accidents'=>$accidents]);
    }

}
