<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::latest()->get();

        $data = [
            'username' => 'Heroku',
            'last_login' => date('Y-m-d H:i:s'),
            'list_pendidikan' => ['SD', 'SMP', 'SMA', 'S1', 'S2', 'S3'],
            'questions' => $questions,
        ];

        return view('home', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
