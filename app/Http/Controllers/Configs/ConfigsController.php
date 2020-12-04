<?php

namespace App\Http\Controllers\Configs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
