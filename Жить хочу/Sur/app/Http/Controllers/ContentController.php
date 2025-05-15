<?php

namespace App\Http\Controllers;

use App\Models\AdminArticle;
use App\Models\UserArticle;
use App\Models\Video;

class ContentController extends Controller
{
    public function index()
    {
        $adminArticles = AdminArticle::latest()->get();
        $userArticles = UserArticle::where('is_approved', true)->latest()->get();
        $videos = Video::latest()->get();

        return view('content.index', compact('adminArticles', 'userArticles', 'videos'));
    }
}