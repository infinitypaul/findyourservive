<?php

namespace App\Http\Controllers;


use App\Service;


class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','is.admin']);
    }

    /**
     * Admininistrative Homepage for Services
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $user =\Auth::user();
        return view('admin_dashboard', compact('user'));
    }


    /**
     * Administrative Page For Editing Services
     *
     * @param \App\Service $service
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editService(Service $service){
        $user =\Auth::user();
        return view('editService', compact('user', 'service'));
    }

}
