<?php

namespace App\Http\Controllers;

use App\Jobs\TestJob;
use App\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        //factory(User::class, 5)->create();
        $users=User::all();
        print_r($users->toJson());
        //TestJob::dispatch($users->toArray()); //pass to the job handle
        TestJob::dispatch('hello '); 
    }

    
}
