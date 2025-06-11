<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $restaurants = Restaurant::withAvg('ratings', 'score')
            ->withCount('ratings')
            ->orderBy('ratings_avg_score', 'desc')
            ->get();

        return view('welcome', compact('categories', 'restaurants'));
    }
}
