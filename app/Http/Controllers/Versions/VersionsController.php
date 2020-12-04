<?php

namespace App\Http\Controllers\Versions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VersionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
