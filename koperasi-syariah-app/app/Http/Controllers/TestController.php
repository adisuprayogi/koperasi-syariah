<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $anggota = Anggota::paginate(5);
        return view('test.pagination', compact('anggota'));
    }
}
