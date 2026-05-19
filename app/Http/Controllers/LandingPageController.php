<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Gallery;
use App\Models\Consultant;
use App\Models\Video;
use App\Models\Post;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $cars = Car::where('is_available', true)->get();
        $galleries = Gallery::all();
        $consultant = Consultant::first();
        $video = Video::where('type', 'youtube')->where('is_active', true)->latest()->first();
        $popupVideo = Video::where('type', 'popup')->where('is_active', true)->latest()->first();
        $posts = Post::where('is_published', true)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->get();

        return view('welcome', compact('cars', 'galleries', 'consultant', 'video', 'popupVideo', 'posts'));
    }

    public function show(Car $car)
    {
        $consultant = Consultant::first();
        return view('car-detail', compact('car', 'consultant'));
    }

    public function pricelist()
    {
        $cars = Car::where('is_available', true)->get();
        $consultant = Consultant::first();
        return view('pricelist', compact('cars', 'consultant'));
    }

    public function postsIndex()
    {
        $posts = Post::where('is_published', true)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(9);
        
        $consultant = Consultant::first();
        return view('posts.index', compact('posts', 'consultant'));
    }

    public function postsShow(Post $post)
    {
        if (!$post->is_published || $post->published_at > now()) {
            abort(404);
        }

        $consultant = Consultant::first();
        $relatedPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(3)
            ->get();

        return view('posts.show', compact('post', 'consultant', 'relatedPosts'));
    }
}
