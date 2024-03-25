<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;


class HomeController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')->paginate(20);
        return view('home', compact('events'));
    }
    public function indexUser()
    {
        return view('my_profile.index');
    }
}
