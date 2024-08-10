<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class PostController extends Controller
{
    public function downloadPdf()
    {
        $posts = Posts::all();
        $data = [
            'tittle'  => 'All Posts Data',
            'data' => date('d/m/Y'),
            'posts' => $posts,
        ];

        $pdf = PDF::loadView('postspdf', $data);
        return $pdf->download('posts.pdf');
    }
}
