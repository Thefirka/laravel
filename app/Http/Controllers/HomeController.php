<?php


namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;

class HomeController
{
    public function view()
    {
        $articles = DB::table('articles')->get();
        return view('home', ['articles' => $articles]);
    }
}
