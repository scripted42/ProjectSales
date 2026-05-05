<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Gallery;
use App\Models\Consultant;
use App\Models\Video;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $cars = Car::where('is_available', true)->get();
        $galleries = Gallery::all();
        $consultant = Consultant::first();
        $video = Video::where('is_active', true)->latest()->first();

        return view('welcome', compact('cars', 'galleries', 'consultant', 'video'));
    }

    public function show(Car $car)
    {
        $consultant = Consultant::first();
        return view('car-detail', compact('car', 'consultant'));
    }
}
