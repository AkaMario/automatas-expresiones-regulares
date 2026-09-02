<?php

namespace Tests\Feature;

use App\Services\SentenceValidatorService;
use Tests\TestCase;

class SentenceValidatorTest extends TestCase
{
    protected SentenceValidatorService $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new SentenceValidatorService();
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
}
