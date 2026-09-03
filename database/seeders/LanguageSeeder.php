<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->languages() as $language) {
            Language::query()->updateOrCreate(
                ['code' => $language['code']],
                $language,
            );
        }
    }

    /**
     * @return array<int, array{
     *     code: string,
     *     name: string,
     *     formula: string,
     *     regex_pattern: string,
     *     description: string,
     *     valid_examples: array<int, string>,
     *     invalid_examples: array<string, string>,
     *     is_active: bool,
     *     sort_order: int
     * }>
     */
    private function languages(): array
    {
        $singularSubject = '(?:he|she|it|this\s+[a-z]+|that\s+[a-z]+|the\s+[a-z]+(?<!s)|(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+)';
        $pluralSubject = '(?:you|we|they|these\s+[a-z]+|those\s+[a-z]+|the\s+[a-z]+s|(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+(?:\s+and\s+(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+)+)';
        $wasSubject = '(?:I|he|she|it|this\s+[a-z]+|that\s+[a-z]+|the\s+[a-z]+(?<!s)|(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+)';
        $wereSubject = '(?:you|we|they|these\s+[a-z]+|those\s+[a-z]+|the\s+[a-z]+s|(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+(?:\s+and\s+(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+)+)';

        return [
            [
                'code' => 'YES_NO_PRESENT',
                'name' => 'Yes/No Questions (Presente)',
                'formula' => 'Verbo To Be (Am / Is / Are) + Sujeto + Complemento + ?',
                'regex_pattern' => '/^(?:(?P<verb_am>Am)\s+(?P<subject_i>I)|(?P<verb_is>Is)\s+(?P<subject_singular>'.$singularSubject.')|(?P<verb_are>Are)\s+(?P<subject_plural>'.$pluralSubject.'))\s+(?P<complement>[a-zA-Z0-9\s,\'.-]+)\?$/i',
                'description' => 'Preguntas cerradas en presente simple.',
                'valid_examples' => [
                    'Is she a nice girl?',
                    'Am I a teacher?',
                    'Are you a student?',
                    'Is the cat brown?',
                    'Are the boys happy?',
                    'Is Cartagena a big city?',
                    'Is this pencil black?',
                ],
                'invalid_examples' => [
                    'Is you a student?' => 'Concordancia incorrecta: "you" requiere "Are".',
                    'Am I a teacher' => 'Falta el signo de interrogación final (?).',
                    'She is a teacher?' => 'Estructura afirmativa, debe ser "Is she a teacher?".',
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'WH_QUESTION',
                'name' => 'Wh- Questions (Información)',
                'formula' => 'Palabra Wh- + Verbo To Be + Sujeto + Complemento + ?',
                'regex_pattern' => '/^(?P<wh_word>What|Where|When|Who|Why|How|Which)\s+(?:(?P<verb_am>am)\s+(?P<subject_i>I)|(?P<verb_is>is)\s+(?P<subject_singular>'.$singularSubject.')|(?P<verb_are>are)\s+(?P<subject_plural>'.$pluralSubject.')|(?P<verb_was>was)\s+(?P<subject_was>'.$wasSubject.')|(?P<verb_were>were)\s+(?P<subject_were>'.$wereSubject.'))(?:\s+(?P<complement>[a-zA-Z0-9\s,\'.-]+))?\?$/i',
                'description' => 'Preguntas abiertas con palabras interrogativas (What, Where, When, Who, Why, How, Which).',
                'valid_examples' => [
                    'Where is the cat?',
                    'Who is she?',
                    'Why are you happy?',
                    'Where were you yesterday?',
                    'What is Cartagena?',
                    'How are the boys today?',
                    'When was Maria sick?',
                ],
                'invalid_examples' => [
                    'Where she is?' => 'Orden incorrecto: el verbo "is" debe ir antes de "she".',
                    'Why you are sad?' => 'Orden incorrecto: debe ser "Why are you sad?".',
                    'Who is you?' => 'Concordancia: "you" requiere "are" o "were".',
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'code' => 'PAST_WAS_WERE',
                'name' => 'Questions Pasado (Was / Were)',
                'formula' => 'Verbo To Be (Was / Were) + Sujeto + Complemento + ?',
                'regex_pattern' => '/^(?:(?P<verb_was>Was)\s+(?P<subject_was>'.$wasSubject.')|(?P<verb_were>Were)\s+(?P<subject_were>'.$wereSubject.'))\s+(?P<complement>[a-zA-Z0-9\s,\'.-]+)\?$/i',
                'description' => 'Preguntas cerradas en tiempo pasado simple.',
                'valid_examples' => [
                    'Were you a good student?',
                    'Were they in Barranquilla yesterday?',
                    'Was the dog furious?',
                    'Was Maria sick last week?',
                    'Was I late?',
                    'Was this pencil black?',
                    'Were the boys happy?',
                ],
                'invalid_examples' => [
                    'Was you a good student?' => 'Concordancia incorrecta: "you" requiere "Were".',
                    'Was they in Barranquilla?' => 'Concordancia incorrecta: "they" requiere "Were".',
                    'Were she sick last week?' => 'Concordancia incorrecta: "she" requiere "Was".',
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];
    }
}
