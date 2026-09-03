<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Services\SentenceValidatorService;
use Database\Seeders\LanguageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SentenceValidatorTest extends TestCase
{
    use RefreshDatabase;

    protected SentenceValidatorService $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(LanguageSeeder::class);

        $this->validator = new SentenceValidatorService;
    }

    public function test_yes_no_present_valid_sentences(): void
    {
        $validExamples = [
            'Is she a nice girl?',
            'Am I a teacher?',
            'Are you a student?',
            'Is the cat brown?',
            'Are the boys happy?',
            'Is Cartagena a big city?',
            'Is this pencil black?',
            'Are these tables clean?',
            'Are Michael and Charles doctors?',
            'Is that car fast?',
            'Are those cars fast?',
        ];

        foreach ($validExamples as $sentence) {
            $result = $this->validator->validate($sentence, 'YES_NO_PRESENT');
            $this->assertTrue($result['is_valid'], "Expected '{$sentence}' to be valid for YES_NO_PRESENT.");
            $this->assertEquals('YES_NO_PRESENT', $result['type']);
            $this->assertNotEmpty($result['components']['verb']);
            $this->assertNotEmpty($result['components']['subject']);
        }
    }

    public function test_wh_questions_valid_sentences(): void
    {
        $validExamples = [
            'Where is the cat?',
            'Who is she?',
            'Why are you happy?',
            'Where were you yesterday?',
            'What is Cartagena?',
            'How are the boys today?',
            'When was Maria sick?',
            'Which is this pencil?',
            'Why was the dog furious?',
            'Where were they in Barranquilla?',
        ];

        foreach ($validExamples as $sentence) {
            $result = $this->validator->validate($sentence, 'WH_QUESTION');
            $this->assertTrue($result['is_valid'], "Expected '{$sentence}' to be valid for WH_QUESTION.");
            $this->assertEquals('WH_QUESTION', $result['type']);
            $this->assertNotEmpty($result['components']['wh_word']);
            $this->assertNotEmpty($result['components']['verb']);
            $this->assertNotEmpty($result['components']['subject']);
        }
    }

    public function test_past_was_were_valid_sentences(): void
    {
        $validExamples = [
            'Were you a good student?',
            'Were they in Barranquilla yesterday?',
            'Was the dog furious?',
            'Was Maria sick last week?',
            'Was I late?',
            'Was this pencil black?',
            'Were the boys happy?',
            'Was that car fast?',
            'Were those tables clean?',
        ];

        foreach ($validExamples as $sentence) {
            $result = $this->validator->validate($sentence, 'PAST_WAS_WERE');
            $this->assertTrue($result['is_valid'], "Expected '{$sentence}' to be valid for PAST_WAS_WERE.");
            $this->assertEquals('PAST_WAS_WERE', $result['type']);
            $this->assertNotEmpty($result['components']['verb']);
            $this->assertNotEmpty($result['components']['subject']);
        }
    }

    public function test_invalid_sentences_give_feedback(): void
    {
        $invalidCases = [
            'Is you a student?' => 'SUBJECT_VERB_DISAGREEMENT',
            'Am I a teacher' => 'MISSING_QUESTION_MARK',
            'Where she is?' => 'WH_WORD_ORDER',
            'Was they in Barranquilla yesterday?' => 'SUBJECT_VERB_DISAGREEMENT',
            'She is my girlfriend.' => 'AFFIRMATIVE_INSTEAD_OF_QUESTION',
        ];

        foreach ($invalidCases as $sentence => $expectedError) {
            $result = $this->validator->validate($sentence);
            $this->assertFalse($result['is_valid'], "Expected '{$sentence}' to be invalid.");
            $this->assertEquals($expectedError, $result['error_type'], "Mismatch error code for '{$sentence}'");
        }
    }

    public function test_api_validation_endpoint_success(): void
    {
        $response = $this->postJson('/api/validate', [
            'message' => 'Is she a nice girl?',
            'type' => 'YES_NO_PRESENT',
            'user_name' => 'Carlos',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'validation' => [
                    'is_valid' => true,
                    'type' => 'YES_NO_PRESENT',
                ],
            ]);

        $this->assertDatabaseHas('conversation_messages', [
            'user_message' => 'Is she a nice girl?',
            'is_valid' => true,
        ]);
    }

    public function test_api_examples_endpoint(): void
    {
        $response = $this->getJson('/api/examples');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'categories' => [
                    'YES_NO_PRESENT',
                    'WH_QUESTION',
                    'PAST_WAS_WERE',
                ],
            ]);
    }

    public function test_conversation_history_returns_messages_from_database(): void
    {
        $this->postJson('/api/validate', [
            'message' => 'Is she a nice girl?',
            'type' => 'YES_NO_PRESENT',
            'user_name' => 'Carlos',
        ])->assertOk();

        $response = $this->getJson('/api/conversation/history');

        $response->assertOk()
            ->assertJsonPath('conversation.user_name', 'Carlos')
            ->assertJsonPath('conversation.messages.0.user_message', 'Is she a nice girl?')
            ->assertJsonPath('conversation.messages.0.is_valid', true)
            ->assertJsonPath('conversation.messages.0.matched_language.code', 'YES_NO_PRESENT');
    }

    public function test_new_chat_endpoint_creates_a_separate_active_conversation(): void
    {
        $this->withSession(['chat_session_id' => 'test-chat-session']);

        $this->postJson('/api/validate', [
            'message' => 'Is she a nice girl?',
            'type' => 'YES_NO_PRESENT',
            'user_name' => 'Carlos',
        ])->assertOk();

        $newChatResponse = $this->postJson('/api/conversations')
            ->assertCreated()
            ->assertJsonPath('conversation.title', 'Nuevo chat')
            ->assertJsonPath('conversation.messages', []);

        $newConversationId = $newChatResponse->json('conversation.id');

        $this->postJson('/api/validate', [
            'message' => 'Were you a good student?',
            'type' => 'PAST_WAS_WERE',
            'user_name' => 'Carlos',
            'conversation_id' => $newConversationId,
        ])->assertOk();

        $historyResponse = $this->getJson('/api/conversation/history');

        $historyResponse->assertOk()
            ->assertJsonCount(2, 'conversations')
            ->assertJsonPath('conversation.id', $newConversationId)
            ->assertJsonPath('conversation.messages.0.user_message', 'Were you a good student?');

        $this->assertDatabaseHas('conversations', [
            'session_id' => 'test-chat-session',
            'title' => 'Is she a nice girl?',
        ]);

        $this->assertDatabaseHas('conversations', [
            'session_id' => 'test-chat-session',
            'title' => 'Were you a good student?',
        ]);
    }

    public function test_conversation_title_can_be_updated_for_current_session(): void
    {
        $this->withSession(['chat_session_id' => 'test-chat-session']);

        $conversation = Conversation::query()->create([
            'session_id' => 'test-chat-session',
            'title' => 'Nuevo chat',
            'user_name' => 'Carlos',
            'last_message_at' => now(),
        ]);

        $response = $this->patchJson("/api/conversations/{$conversation->id}", [
            'title' => 'Práctica de preguntas',
        ]);

        $response->assertOk()
            ->assertJsonPath('conversation.title', 'Práctica de preguntas')
            ->assertJsonPath('conversations.0.title', 'Práctica de preguntas');

        $this->assertDatabaseHas('conversations', [
            'id' => $conversation->id,
            'title' => 'Práctica de preguntas',
        ]);
    }

    public function test_edited_empty_conversation_title_is_not_replaced_by_first_message(): void
    {
        $this->withSession(['chat_session_id' => 'test-chat-session']);

        $conversation = Conversation::query()->create([
            'session_id' => 'test-chat-session',
            'title' => 'Repaso examen',
            'user_name' => 'Carlos',
            'last_message_at' => now(),
        ]);

        $response = $this->postJson('/api/validate', [
            'message' => 'Is she a nice girl?',
            'type' => 'YES_NO_PRESENT',
            'user_name' => 'Carlos',
            'conversation_id' => $conversation->id,
        ]);

        $response->assertOk()
            ->assertJsonPath('conversation.title', 'Repaso examen');

        $this->assertDatabaseHas('conversations', [
            'id' => $conversation->id,
            'title' => 'Repaso examen',
        ]);
    }

    public function test_conversation_title_update_returns_404_for_another_session(): void
    {
        $this->withSession(['chat_session_id' => 'test-chat-session']);

        $conversation = Conversation::query()->create([
            'session_id' => 'another-chat-session',
            'title' => 'Chat privado',
            'user_name' => 'Carlos',
            'last_message_at' => now(),
        ]);

        $response = $this->patchJson("/api/conversations/{$conversation->id}", [
            'title' => 'Título invasor',
        ]);

        $response->assertNotFound();

        $this->assertDatabaseHas('conversations', [
            'id' => $conversation->id,
            'title' => 'Chat privado',
        ]);
    }

    public function test_active_conversation_can_be_deleted_and_falls_back_to_latest_chat(): void
    {
        $this->withSession([
            'chat_session_id' => 'test-chat-session',
        ]);

        $this->postJson('/api/validate', [
            'message' => 'Is she a nice girl?',
            'type' => 'YES_NO_PRESENT',
            'user_name' => 'Carlos',
        ])->assertOk();

        $newChatResponse = $this->postJson('/api/conversations')->assertCreated();
        $conversationId = $newChatResponse->json('conversation.id');

        $this->postJson('/api/validate', [
            'message' => 'Were you a good student?',
            'type' => 'PAST_WAS_WERE',
            'user_name' => 'Carlos',
            'conversation_id' => $conversationId,
        ])->assertOk();

        $response = $this->deleteJson("/api/conversations/{$conversationId}");

        $response->assertOk()
            ->assertJsonCount(1, 'conversations')
            ->assertJsonPath('conversation.title', 'Is she a nice girl?');

        $this->assertDatabaseMissing('conversations', [
            'id' => $conversationId,
        ]);
    }
}
