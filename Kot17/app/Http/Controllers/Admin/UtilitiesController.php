<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilitiesController extends Controller
{
    public function index()
    {
        // បង្ហាញទំព័រដើមនៃផ្នែកទឹកភ្លើង
        return view('admin.utilities.index');
    }
}