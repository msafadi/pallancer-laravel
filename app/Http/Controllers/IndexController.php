<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class IndexController extends Controller
{
    public function index($first = '', $last = '')
    {

        return 'First: ' . $first . ' - Last: ' . $last;
    }
}
