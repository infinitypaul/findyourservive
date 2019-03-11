<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Resources\ServiceResources;
use App\Service;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(){
        return view('search');
    }

    /**
     *
     */
    public function result(SearchRequest $request){
        if($request->distance !== 'Anywhere'){
            $services = Service
                ::distance($request->latitude, $request->longitude, $request->distance)
                ->where('title', 'LIKE', "%{$request->keyword}%")->get();
        } else {
            $services = Service::where('title', 'LIKE', "%{$request->keyword}%") ->get();
        }
        return ServiceResources::collection($services);
    }
}
