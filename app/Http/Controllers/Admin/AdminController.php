<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodect;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public  function index()
    {
        return  Inertia::render('Admin/Dashboard');

    }
}
