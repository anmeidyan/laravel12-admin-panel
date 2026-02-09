<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SlideshowController extends Controller
{
    public function index()
    {
        if (\Gate::denies('admin.slideshow.index')) {
            return redirect()->route('admin.dashboard')->with('danger', 'Unauthorized access attempt.');
        }

        dd('masuk slideshow');

        $data = [];

        return view('admin.slideshow.index', $data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
