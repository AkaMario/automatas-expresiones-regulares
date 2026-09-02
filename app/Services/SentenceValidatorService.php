<?php

namespace App\Services;

class SentenceValidatorService
{
    /**
     * Get regex definitions for all question types
     */
    public static function getPatterns(): array
    {
        $singularSubject = '(?:he|she|it|this\s+[a-z]+|that\s+[a-z]+|the\s+[a-z]+(?<!s)|(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+)';
        $pluralSubject = '(?:you|we|they|these\s+[a-z]+|those\s+[a-z]+|the\s+[a-z]+s|(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+(?:\s+and\s+(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+)+)';
        $wasSubject = '(?:I|he|she|it|this\s+[a-z]+|that\s+[a-z]+|the\s+[a-z]+(?<!s)|(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+)';
        $wereSubject = '(?:you|we|they|these\s+[a-z]+|those\s+[a-z]+|the\s+[a-z]+s|(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+(?:\s+and\s+(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+)+)';

        return [
            'YES_NO_PRESENT' => [
                'name' => 'Yes/No Questions (Presente)',
                'formula' => 'Verbo To Be (Am / Is / Are) + Sujeto + Complemento + ?',
                'pattern' => '/^(?:(?P<verb_am>Am)\s+(?P<subject_i>I)|(?P<verb_is>Is)\s+(?P<subject_singular>' . $singularSubject . ')|(?P<verb_are>Are)\s+(?P<subject_plural>' . $pluralSubject . '))\s+(?P<complement>[a-zA-Z0-9\s,\'.-]+)\?$/i',
            ],
            'WH_QUESTION' => [
                'name' => 'Wh- Questions (Información)',
                'formula' => 'Palabra Wh- + Verbo To Be + Sujeto + Complemento + ?',
                'pattern' => '/^(?P<wh_word>What|Where|When|Who|Why|How|Which)\s+(?:(?P<verb_am>am)\s+(?P<subject_i>I)|(?P<verb_is>is)\s+(?P<subject_singular>' . $singularSubject . ')|(?P<verb_are>are)\s+(?P<subject_plural>' . $pluralSubject . ')|(?P<verb_was>was)\s+(?P<subject_was>' . $wasSubject . ')|(?P<verb_were>were)\s+(?P<subject_were>' . $wereSubject . '))(?:\s+(?P<complement>[a-zA-Z0-9\s,\'.-]+))?\?$/i',
            ],
            'PAST_WAS_WERE' => [
                'name' => 'Questions Pasado (Was / Were)',
                'formula' => 'Verbo To Be (Was / Were) + Sujeto + Complemento + ?',
                'pattern' => '/^(?:(?P<verb_was>Was)\s+(?P<subject_was>' . $wasSubject . ')|(?P<verb_were>Were)\s+(?P<subject_were>' . $wereSubject . '))\s+(?P<complement>[a-zA-Z0-9\s,\'.-]+)\?$/i',
            ],
        ];
    }

    /**
     * Validate an input sentence against rules
     */
    public function validate(string $rawInput, ?string $selectedType = null): array
    {
        $patterns = self::getPatterns();
        $sentence = trim($rawInput);

        if (empty($sentence)) {
            return [
                'is_valid' => false,
                'type' => null,
                'feedback' => 'Por favor, ingresa una oración para validar.',
                'error_type' => 'EMPTY_INPUT',
                'details' => null,
            ];
        }

        // Clean extra spaces inside sentence
        $normalizedSentence = preg_replace('/\s+/', ' ', $sentence);

        // Check each category
        $matchedCategory = null;
        $matchData = [];

        if ($selectedType && isset($patterns[$selectedType])) {
            $patternInfo = $patterns[$selectedType];
            if (preg_match($patternInfo['pattern'], $normalizedSentence, $matches)) {
                $matchedCategory = $selectedType;
                $matchData = $matches;
            }
        } else {
            foreach ($patterns as $key => $patternInfo) {
                if (preg_match($patternInfo['pattern'], $normalizedSentence, $matches)) {
                    $matchedCategory = $key;
                    $matchData = $matches;
                    break;
                }
            }
        }

        if ($matchedCategory) {
            $parsed = $this->extractComponents($matchedCategory, $matchData, $normalizedSentence);
            $typeName = $patterns[$matchedCategory]['name'];
            return [
                'is_valid' => true,
                'type' => $matchedCategory,
                'type_name' => $typeName,
                'formula' => $patterns[$matchedCategory]['formula'],
                'pattern_used' => $patterns[$matchedCategory]['pattern'],
                'feedback' => "¡Excelente! La oración es gramaticalmente válida como '{$typeName}'.",
                'components' => $parsed,
                'error' => null,
            ];
        }

        // If not matched, diagnose detailed error
        $diagnosis = $this->diagnoseError($normalizedSentence, $selectedType);

        return [
            'is_valid' => false,
            'type' => $selectedType,
            'feedback' => $diagnosis['feedback'],
            'error_type' => $diagnosis['error_code'],
            'suggestion' => $diagnosis['suggestion'] ?? null,
            'details' => $diagnosis['details'] ?? null,
            'pattern_used' => $selectedType && isset($patterns[$selectedType]) ? $patterns[$selectedType]['pattern'] : null,
        ];
    }

    /**
     * Extract syntactic components from regex matches
     */
    private function extractComponents(string $type, array $matches, string $sentence): array
    {
        $whWord = !empty($matches['wh_word']) ? $matches['wh_word'] : null;

        $verb = null;
        foreach (['verb_am', 'verb_is', 'verb_are', 'verb_was', 'verb_were'] as $vKey) {
            if (!empty($matches[$vKey])) {
                $verb = $matches[$vKey];
                break;
            }
        }

        $subject = null;
        foreach (['subject_i', 'subject_singular', 'subject_plural', 'subject_was', 'subject_were'] as $sKey) {
            if (!empty($matches[$sKey])) {
                $subject = $matches[$sKey];
                break;
            }
        }

        $complement = !empty($matches['complement']) ? $matches['complement'] : '(Opcional / Implícito)';

        $subjectType = $this->classifySubject($subject);

        return [
            'wh_word' => $whWord ?: null,
            'verb' => $verb,
            'tense' => in_array(strtolower($verb ?? ''), ['was', 'were']) ? 'Pasado' : 'Presente',
            'subject' => $subject,
            'subject_type' => $subjectType,
            'complement' => trim((string)$complement) !== '' ? trim((string)$complement) : '(Ninguno)',
            'ends_with_question_mark' => str_ends_with($sentence, '?'),
        ];
    }

    /**
     * Classify subject type based on project specifications
     */
    public function classifySubject(?string $subject): string
    {
        if (!$subject) return 'Desconocido';

        $subLower = strtolower(trim($subject));

        if (in_array($subLower, ['i', 'you', 'he', 'she', 'it', 'we', 'they'])) {
            return "Pronombre Personal ('{$subject}')";
        }

        if (preg_match('/^(this|that|these|those)\s+/i', $subject)) {
            return "Pronombre Demostrativo ('{$subject}')";
        }

        if (preg_match('/^the\s+/i', $subject)) {
            return "Sustantivo Común con artículo The ('{$subject}')";
        }

        if (preg_match('/^[A-Z][a-z]+(?:\s+and\s+[A-Z][a-z]+)*$/', $subject)) {
            return "Nombre Propio ('{$subject}')";
        }

        return "Sustantivo / Frase Nominal ('{$subject}')";
    }

    /**
     * Intelligent diagnosis of common regex / grammar mistakes
     */
    private function diagnoseError(string $sentence, ?string $selectedType): array
    {
        $patterns = self::getPatterns();
        $hasQuestionMark = str_ends_with($sentence, '?');
        $cleanSentence = rtrim($sentence, '?');
        $words = preg_split('/\s+/', trim($cleanSentence));
        $firstWord = $words[0] ?? '';
        $firstLower = strtolower($firstWord);

        // 1. Missing Question Mark
        if (!$hasQuestionMark) {
            $testWithMark = $sentence . '?';
            $testResult = $this->validate($testWithMark, $selectedType);
            if ($testResult['is_valid']) {
                return [
                    'error_code' => 'MISSING_QUESTION_MARK',
                    'feedback' => "La estructura es correcta, pero le falta el signo de interrogación final (?).",
                    'suggestion' => $testWithMark,
                    'details' => "Recuerda que todas las preguntas en inglés terminan con '?'.",
                ];
            }
        }

        // 2. Affirmative structure typed instead of question
        if (in_array($firstLower, ['i', 'you', 'he', 'she', 'it', 'we', 'they', 'the', 'this', 'that', 'these', 'those']) || preg_match('/^[A-Z][a-z]+$/', $firstWord)) {
            if (isset($words[1]) && in_array(strtolower($words[1]), ['am', 'is', 'are', 'was', 'were'])) {
                $verb = $words[1];
                $subject = $words[0];
                $rest = implode(' ', array_slice($words, 2));
                $suggested = ucfirst($verb) . " " . lcfirst($subject) . ($rest ? " " . $rest : "") . "?";

                return [
                    'error_code' => 'AFFIRMATIVE_INSTEAD_OF_QUESTION',
                    'feedback' => "Has ingresado una oración afirmativa en lugar de una pregunta.",
                    'suggestion' => $suggested,
                    'details' => "Para formar una pregunta con el verbo TO BE, debes invertir el orden: coloca el verbo al inicio ('{$verb} {$subject} ...?').",
                ];
            }
        }

        // 3. Subject-Verb Agreement Errors
        if (in_array($firstLower, ['am', 'is', 'are', 'was', 'were'])) {
            $verb = $firstWord;
            $subject = $words[1] ?? '';
            $subLower = strtolower($subject);

            if (in_array($subLower, ['you', 'we', 'they']) && in_array($firstLower, ['is', 'was'])) {
                $correctVerb = ($firstLower === 'is') ? 'Are' : 'Were';
                return [
                    'error_code' => 'SUBJECT_VERB_DISAGREEMENT',
                    'feedback' => "Error de concordancia: el sujeto '{$subject}' no concuerda con el verbo '{$verb}'.",
                    'suggestion' => preg_replace('/^' . preg_quote($verb, '/') . '/i', $correctVerb, $sentence),
                    'details' => "Con '{$subject}' debes usar '{$correctVerb}' en lugar de '{$verb}'.",
                ];
            }

            if (in_array($subLower, ['he', 'she', 'it']) && in_array($firstLower, ['are', 'were', 'am'])) {
                $correctVerb = ($firstLower === 'were') ? 'Was' : 'Is';
                return [
                    'error_code' => 'SUBJECT_VERB_DISAGREEMENT',
                    'feedback' => "Error de concordancia: el sujeto '{$subject}' es 3ra persona singular y no concuerda con '{$verb}'.",
                    'suggestion' => preg_replace('/^' . preg_quote($verb, '/') . '/i', $correctVerb, $sentence),
                    'details' => "Con '{$subject}' debes usar '{$correctVerb}' en lugar de '{$verb}'.",
                ];
            }

            if ($subLower === 'i' && in_array($firstLower, ['is', 'are', 'were'])) {
                $correctVerb = ($firstLower === 'were') ? 'Was' : 'Am';
                return [
                    'error_code' => 'SUBJECT_VERB_DISAGREEMENT',
                    'feedback' => "Error de concordancia: el pronombre 'I' requiere '{$correctVerb}', no '{$verb}'.",
                    'suggestion' => preg_replace('/^' . preg_quote($verb, '/') . '/i', $correctVerb, $sentence),
                    'details' => "Con 'I' debes usar '{$correctVerb}'.",
                ];
            }
        }

        // 4. Wh- Questions error: inverted subject and verb (e.g., "Where she is?")
        $whWords = ['what', 'where', 'when', 'who', 'why', 'how', 'which'];
        if (in_array($firstLower, $whWords)) {
            $wh = $firstWord;
            if (isset($words[1]) && !in_array(strtolower($words[1]), ['am', 'is', 'are', 'was', 'were'])) {
                if (isset($words[2]) && in_array(strtolower($words[2]), ['am', 'is', 'are', 'was', 'were'])) {
                    $subject = $words[1];
                    $verb = $words[2];
                    $rest = implode(' ', array_slice($words, 3));
                    $suggested = "{$wh} {$verb} {$subject}" . ($rest ? " " . $rest : "") . "?";

                    return [
                        'error_code' => 'WH_WORD_ORDER',
                        'feedback' => "Error de orden sintáctico en la pregunta Wh-.",
                        'suggestion' => $suggested,
                        'details' => "En preguntas de información (Wh-), la estructura correcta es: Palabra Wh- + Verbo To Be + Sujeto + Complemento + ?.",
                    ];
                }

                return [
                    'error_code' => 'WH_MISSING_TO_BE',
                    'feedback' => "Después de la palabra '{$wh}' debe ir una forma del verbo TO BE (am, is, are, was, were).",
                    'suggestion' => null,
                    'details' => "Fórmula: {$wh} + [am/is/are/was/were] + [Sujeto] + [Complemento]?",
                ];
            }
        }

        // 5. Selected type mismatch
        if ($selectedType) {
            foreach ($patterns as $otherKey => $otherPattern) {
                if ($otherKey !== $selectedType && preg_match($otherPattern['pattern'], $sentence)) {
                    $targetName = $patterns[$selectedType]['name'];
                    $otherName = $otherPattern['name'];
                    return [
                        'error_code' => 'WRONG_TYPE_SELECTED',
                        'feedback' => "La oración es válida, pero pertenece a la categoría '{$otherName}', no a '{$targetName}'.",
                        'suggestion' => null,
                        'details' => "Cambia la categoría seleccionada a '{$otherName}' para validarla en esa sección.",
                    ];
                }
            }
        }

        // Default invalid response
        return [
            'error_code' => 'SYNTAX_OR_VOCABULARY_ERROR',
            'feedback' => 'La oración no cumple con las reglas gramaticales y patrones de expresión regular del verbo TO BE.',
            'suggestion' => null,
            'details' => 'Verifica que contenga: Verbo To Be adecuado, un sujeto válido (pronombre, nombre propio, sustantivo con the/this/that), complemento y signo de interrogación final (?).',
        ];
    }
}
