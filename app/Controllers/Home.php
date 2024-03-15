<?php

namespace App\Controllers;

class Roles extends BaseController
{
    public static $NAME = 'roles';
    public function index(): string
    {
        return view('welcome_message');
    }
}
