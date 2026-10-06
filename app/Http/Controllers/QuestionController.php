<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class QuestionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'question' => ['required', 'string', 'max:1000'],
        ]);

        Question::create($validated);

        return redirect()->back()->with('success', 'Question berhasil disimpan.');
    }
}
