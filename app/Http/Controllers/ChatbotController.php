<?php

namespace App\Http\Controllers;

use App\Services\SentenceValidatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function __construct(
        protected SentenceValidatorService $validator
    ) {}

    /**
     * Render the main Chatbot view
     */
    public function index()
    {
        return view('inicio', [
            'patterns' => SentenceValidatorService::getPatterns(),
            'examples' => $this->sampleQuestions(),
        ]);
    }

    /**
     * Validate an input question and generate chatbot response
     */
    public function validateSentence(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'type' => 'nullable|string|in:YES_NO_PRESENT,WH_QUESTION,PAST_WAS_WERE',
            'user_name' => 'nullable|string|max:100',
        ]);

        $input = $request->input('message');
        $type = $request->input('type');
        $userName = $request->input('user_name', 'Estudiante');

        $result = $this->validator->validate($input, $type);

        if ($result['is_valid']) {
            $botText = "¡Excelente trabajo, {$userName}! Tu oración es **GRAMATICALMENTE VÁLIDA** según el patrón de Expresión Regular.";
        } else {
            $botText = "Hola {$userName}, la oración ingresada es **INVÁLIDA** según las reglas gramaticales.";
        }

        return response()->json([
            'success' => true,
            'bot_message' => $botText,
            'validation' => $result,
            'quick_replies' => [
                ['label' => 'Probar otra frase', 'action' => 'continue'],
                ['label' => 'Cambiar de categoría', 'action' => 'change_type'],
                ['label' => 'Ver ejemplos válidos', 'action' => 'show_examples'],
            ],
        ]);
    }

    /**
     * Return predefined questions grouped by category
     */
    public function getExamples(): JsonResponse
    {
        return response()->json([
            'categories' => $this->sampleQuestions(),
        ]);
    }

    /**
     * Sample questions for the 3 categories
     */
    private function sampleQuestions(): array
    {
        return [
            'YES_NO_PRESENT' => [
                'title' => 'Yes/No Questions (Presente)',
                'formula' => 'Verbo To Be (Am / Is / Are) + Sujeto + Complemento + ?',
                'description' => 'Preguntas cerradas en presente simple.',
                'valid' => [
                    'Is she a nice girl?',
                    'Am I a teacher?',
                    'Are you a student?',
                    'Is the cat brown?',
                    'Are the boys happy?',
                    'Is Cartagena a big city?',
                    'Is this pencil black?',
                ],
                'invalid' => [
                    'Is you a student?' => 'Concordancia incorrecta: "you" requiere "Are".',
                    'Am I a teacher' => 'Falta el signo de interrogación final (?).',
                    'She is a teacher?' => 'Estructura afirmativa, debe ser "Is she a teacher?".',
                ],
            ],
            'WH_QUESTION' => [
                'title' => 'Wh- Questions (Información)',
                'formula' => 'Palabra Wh- + Verbo To Be + Sujeto + Complemento + ?',
                'description' => 'Preguntas abiertas con palabras interrogativas (What, Where, When, Who, Why, How, Which).',
                'valid' => [
                    'Where is the cat?',
                    'Who is she?',
                    'Why are you happy?',
                    'Where were you yesterday?',
                    'What is Cartagena?',
                    'How are the boys today?',
                    'When was Maria sick?',
                ],
                'invalid' => [
                    'Where she is?' => 'Orden incorrecto: el verbo "is" debe ir antes de "she".',
                    'Why you are sad?' => 'Orden incorrecto: debe ser "Why are you sad?".',
                    'Who is you?' => 'Concordancia: "you" requiere "are" o "were".',
                ],
            ],
            'PAST_WAS_WERE' => [
                'title' => 'Questions Pasado (Was / Were)',
                'formula' => 'Verbo To Be (Was / Were) + Sujeto + Complemento + ?',
                'description' => 'Preguntas cerradas en tiempo pasado simple.',
                'valid' => [
                    'Were you a good student?',
                    'Were they in Barranquilla yesterday?',
                    'Was the dog furious?',
                    'Was Maria sick last week?',
                    'Was I late?',
                    'Was this pencil black?',
                    'Were the boys happy?',
                ],
                'invalid' => [
                    'Was you a good student?' => 'Concordancia incorrecta: "you" requiere "Were".',
                    'Was they in Barranquilla?' => 'Concordancia incorrecta: "they" requiere "Were".',
                    'Were she sick last week?' => 'Concordancia incorrecta: "she" requiere "Was".',
                ],
            ],
        ];
    }
}
