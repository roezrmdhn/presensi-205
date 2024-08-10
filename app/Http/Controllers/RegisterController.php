<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function create()
    {
        // Ambil data organisasi dari database
        $organisasi = Organisasi::where('id_organisasi', '>', 0)->get();
        return view('register', compact('organisasi'));
    }
}
