<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Resources\ServiceResources;
use App\Service;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Index Page - Search For Services
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('search');
    }


    /**
     * Return Search Result in Json
     *
     * @param \App\Http\Requests\SearchRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
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
