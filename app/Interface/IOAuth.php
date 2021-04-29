<?php

namespace App\Interface;

interface IOAuth {
    function login();
    
    function redirect();
}