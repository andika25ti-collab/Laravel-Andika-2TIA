<?php

namespace Tests\Feature;

use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_saves_question(): void
    {
        $response = $this->post('/question/store', [
            'name' => 'Andika',
            'email' => 'andika@example.com',
            'question' => 'Apakah Laravel dapat menyimpan data?',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Question berhasil disimpan.');
        $this->assertDatabaseHas('questions', [
            'name' => 'Andika',
            'email' => 'andika@example.com',
            'question' => 'Apakah Laravel dapat menyimpan data?',
        ]);
        $this->assertTrue(Question::where('question', 'Apakah Laravel dapat menyimpan data?')->exists());
    }

    public function test_store_rejects_empty_question(): void
    {
        $response = $this->post('/question/store', [
            'name' => '',
            'email' => 'not-an-email',
            'question' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'question']);
        $this->assertDatabaseCount('questions', 0);
    }

    public function test_store_accepts_the_original_indonesian_form_fields(): void
    {
        $this->post('/question/store', [
            'nama' => 'Andika',
            'email' => 'andika@example.com',
            'pertanyaan' => 'Bagaimana kabarnya?',
        ])
            ->assertRedirect('/home')
            ->assertSessionHas('success', 'Question berhasil disimpan.')
            ->assertSessionHas('question', [
                'nama' => 'Andika',
                'email' => 'andika@example.com',
                'pertanyaan' => 'Bagaimana kabarnya?',
            ]);

        $this->assertDatabaseHas('questions', [
            'name' => 'Andika',
            'email' => 'andika@example.com',
            'question' => 'Bagaimana kabarnya?',
        ]);
    }
}
