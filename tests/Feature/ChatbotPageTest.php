<?php

namespace Tests\Feature;

use Tests\TestCase;

class ChatbotPageTest extends TestCase
{
    public function test_home_page_renders_the_chatbot_workspace(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('RegexBot')
            ->assertSee('Categorías')
            ->assertSee('Detección Automática')
            ->assertSee('Escribe una pregunta en inglés', false)
            ->assertSee('Especificación de Expresiones Regulares')
            ->assertSee('data-chat-action="toggle-sidebar"', false)
            ->assertDontSee('fa-'.'robot', false);
    }
}
