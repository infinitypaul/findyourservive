<?php

namespace App\Http\Controllers;


use App\Service;
use App\User;
use Illuminate\Http\Request;
use Mail;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','is.admin']);
    }

    public function index(){
        $user =\Auth::user();
        return view('admin_dashboard', compact('user'));
    }

    public function editService(Service $service){
        //dd($service);
        $user =\Auth::user();
        return view('editService', compact('user', 'service'));
    }

}
