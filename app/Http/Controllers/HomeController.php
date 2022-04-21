<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with('learning_designs.import_metadata.deploy_type',
                                    'gamification_designs.gamification_engines',
                                    'gamification_designs.creator',
                                    'gamification_designs.gamification_deploys.deploy_types')
                    ->where('id',Auth::user()->id)->first();

        return view('home.layout', ['user' => $user]);
    }
}
