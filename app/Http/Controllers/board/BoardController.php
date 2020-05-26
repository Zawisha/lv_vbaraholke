<?php

namespace App\Http\Controllers\board;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Regions;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function select_region()
    {
        return $regions = Regions::where('id', '>', '0')->get();
    }

    public function select_category()
    {
        return $categories = Categories::where('id', '>', '0')->get();
    }
}
