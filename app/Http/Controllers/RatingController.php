<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\Rating;

class RatingController extends Controller
{
    function list(){
        return Inertia::render('ratings/List');
           
        }

        public function rating_api()
        {
            $response =Rating::with('product')->get();
            return response()->json(['ratings' => $response]);
        }
    
}
