<?php

namespace Tests\Feature;

use Database\Seeders\LanguageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatbotPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_the_chatbot_workspace(): void
    {
        $this->seed(LanguageSeeder::class);

        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Chatbot de expresiones regulares')
            ->assertSee('Nuevo chat')
            ->assertSee('Historial')
            ->assertSee('Categorías')
            ->assertSee('Detección Automática')
            ->assertSee('Escribe tu nombre para comenzar', false)
            ->assertSee('Especificación de Expresiones Regulares')
            ->assertSee('data-chat-action="toggle-sidebar"', false)
            ->assertSee('id="conversationList"', false)
            ->assertDontSee('data-chat-category="WH_QUESTION"', false)
            ->assertDontSee('data-chat-category="PAST_WAS_WERE"', false)
            ->assertDontSee('fa-'.'robot', false);
    }
}
