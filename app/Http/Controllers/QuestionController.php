<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $request->merge([
            'name' => $request->input('name', $request->input('nama')),
            'question' => $request->input('question', $request->input('pertanyaan')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'question' => ['required', 'string', 'max:1000'],
        ]);

        Question::query()->create($validated);

        session(['question' => [
            'nama' => $validated['name'],
            'email' => $validated['email'],
            'pertanyaan' => $validated['question'],
        ]]);

        return redirect('/home')->with('success', 'Question berhasil disimpan.');
    }

    public function respon()
    {
        $data = session()->pull('question');

        if (!$data) {
            return redirect('/home');
        }

        return view('home-question-respon', $data);
    }
}
