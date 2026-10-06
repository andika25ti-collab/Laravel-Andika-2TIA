<?php

namespace Tests\Feature;

use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionListTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_displays_saved_questions(): void
    {
        Question::create([
            'name' => 'Andika',
            'email' => 'andika@example.com',
            'question' => 'Pertanyaan pertama',
        ]);
        Question::create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'question' => 'Pertanyaan kedua',
        ]);

        $response = $this->get('/home');

        $response->assertOk();
        $response->assertSee('Andika');
        $response->assertSee('andika@example.com');
        $response->assertSee('Pertanyaan pertama');
        $response->assertSee('Pertanyaan kedua');
    }
}
