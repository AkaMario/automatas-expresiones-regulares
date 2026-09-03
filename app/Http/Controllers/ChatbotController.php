<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Language;
use App\Services\SentenceValidatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ChatbotController extends Controller
{
    public function __construct(
        protected SentenceValidatorService $validator
    ) {}

    /**
     * Render the main Chatbot view
     */
    public function index(): View
    {
        return view('inicio', [
            'patterns' => SentenceValidatorService::getPatterns(),
            'examples' => SentenceValidatorService::getExamples(),
        ]);
    }

    /**
     * Validate an input question and generate chatbot response
     */
    public function validateSentence(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
            'type' => [
                'nullable',
                'string',
                Rule::exists('languages', 'code')->where('is_active', true),
            ],
            'user_name' => ['nullable', 'string', 'max:100'],
            'conversation_id' => ['nullable', 'integer'],
        ]);

        $input = $validated['message'];
        $type = $validated['type'] ?? null;
        $userName = $validated['user_name'] ?? 'Estudiante';
        $conversation = $this->activeConversation($request, $validated['conversation_id'] ?? null, $userName);

        $result = $this->validator->validate($input, $type);

        if ($result['is_valid']) {
            $botText = "¡Excelente trabajo, {$userName}! Tu oración es **GRAMATICALMENTE VÁLIDA** según el patrón de Expresión Regular.";
        } else {
            $botText = "Hola {$userName}, la oración ingresada es **INVÁLIDA** según las reglas gramaticales.";
        }

        $conversation = $this->recordConversation($conversation, $userName, $input, $botText, $type, $result);

        return response()->json([
            'success' => true,
            'bot_message' => $botText,
            'validation' => $result,
            'conversation' => [
                'id' => $conversation->id,
                'title' => $conversation->title,
                'messages_count' => $conversation->messages()->count(),
            ],
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
            'categories' => SentenceValidatorService::getExamples(),
        ]);
    }

    /**
     * Return the current session conversation history.
     */
    public function history(Request $request): JsonResponse
    {
        $chatSessionId = $this->chatSessionId($request);
        $requestedConversationId = $request->integer('conversation_id') ?: $request->session()->get('active_conversation_id');
        $conversations = $this->conversationList($chatSessionId);
        $conversation = Conversation::query()
            ->where('session_id', $chatSessionId)
            ->when($requestedConversationId, fn ($query) => $query->where('id', $requestedConversationId))
            ->with([
                'messages' => fn ($query) => $query
                    ->with(['selectedLanguage:id,code,name', 'matchedLanguage:id,code,name'])
                    ->oldest(),
            ])
            ->when(! $requestedConversationId, fn ($query) => $query->latest('last_message_at')->latest('id'))
            ->first();

        if ($requestedConversationId && ! $conversation) {
            abort(404);
        }

        if ($conversation) {
            $request->session()->put('active_conversation_id', $conversation->id);
        }

        return response()->json([
            'conversation' => $conversation ? $this->serializeConversation($conversation) : null,
            'conversations' => $conversations,
        ]);
    }

    public function storeConversation(Request $request): JsonResponse
    {
        $conversation = Conversation::query()->create([
            'session_id' => $this->chatSessionId($request),
            'title' => 'Nuevo chat',
            'user_name' => 'Estudiante',
            'last_message_at' => now(),
        ]);

        $request->session()->put('active_conversation_id', $conversation->id);

        return response()->json([
            'conversation' => $this->serializeConversation($conversation->load('messages')),
            'conversations' => $this->conversationList($this->chatSessionId($request)),
        ], 201);
    }

    /**
     * @param  array<string, mixed>  $validation
     */
    private function recordConversation(Conversation $conversation, string $userName, string $input, string $botText, ?string $selectedType, array $validation): Conversation
    {
        $conversation->forceFill([
            'title' => $conversation->messages()->exists() ? $conversation->title : Str::limit($input, 48, ''),
            'user_name' => $userName,
            'last_message_at' => now(),
        ])->save();

        $selectedLanguageId = $selectedType
            ? Language::query()->where('code', $selectedType)->value('id')
            : null;

        $matchedLanguageId = $validation['is_valid']
            ? ($validation['language_id'] ?? null)
            : null;

        $conversation->messages()->create([
            'selected_language_id' => $selectedLanguageId,
            'matched_language_id' => $matchedLanguageId,
            'user_message' => $input,
            'bot_message' => $botText,
            'is_valid' => $validation['is_valid'],
            'error_type' => $validation['error_type'] ?? null,
            'suggestion' => $validation['suggestion'] ?? null,
            'validation_payload' => $validation,
        ]);

        return $conversation;
    }

    private function activeConversation(Request $request, ?int $conversationId, string $userName): Conversation
    {
        $chatSessionId = $this->chatSessionId($request);

        if ($conversationId) {
            $conversation = Conversation::query()
                ->where('session_id', $chatSessionId)
                ->where('id', $conversationId)
                ->firstOrFail();

            $request->session()->put('active_conversation_id', $conversation->id);

            return $conversation;
        }

        $activeConversationId = $request->session()->get('active_conversation_id');

        if ($activeConversationId) {
            $conversation = Conversation::query()
                ->where('session_id', $chatSessionId)
                ->where('id', $activeConversationId)
                ->first();

            if ($conversation) {
                return $conversation;
            }
        }

        $conversation = Conversation::query()->create([
            'session_id' => $chatSessionId,
            'title' => 'Nuevo chat',
            'user_name' => $userName,
            'last_message_at' => now(),
        ]);

        $request->session()->put('active_conversation_id', $conversation->id);

        return $conversation;
    }

    /**
     * @return array<int, array{id: int, title: string, user_name: string, messages_count: int, last_message_at: ?string}>
     */
    private function conversationList(string $chatSessionId): array
    {
        return Conversation::query()
            ->where('session_id', $chatSessionId)
            ->withCount('messages')
            ->latest('last_message_at')
            ->latest('id')
            ->get(['id', 'title', 'user_name', 'last_message_at'])
            ->map(fn (Conversation $conversation): array => [
                'id' => $conversation->id,
                'title' => $conversation->title,
                'user_name' => $conversation->user_name,
                'messages_count' => $conversation->messages_count,
                'last_message_at' => $conversation->last_message_at?->toISOString(),
            ])
            ->all();
    }

    /**
     * @return array{id: int, title: string, user_name: string, messages: array<int, array<string, mixed>>}
     */
    private function serializeConversation(Conversation $conversation): array
    {
        return [
            'id' => $conversation->id,
            'title' => $conversation->title,
            'user_name' => $conversation->user_name,
            'messages' => $conversation->messages->map(fn ($message): array => [
                'id' => $message->id,
                'user_message' => $message->user_message,
                'bot_message' => $message->bot_message,
                'is_valid' => $message->is_valid,
                'error_type' => $message->error_type,
                'suggestion' => $message->suggestion,
                'selected_language' => $message->selectedLanguage?->only(['code', 'name']),
                'matched_language' => $message->matchedLanguage?->only(['code', 'name']),
                'validation' => $message->validation_payload,
                'created_at' => $message->created_at?->toISOString(),
            ])->all(),
        ];
    }

    private function chatSessionId(Request $request): string
    {
        if (! $request->session()->has('chat_session_id')) {
            $request->session()->put('chat_session_id', (string) Str::uuid());
        }

        return $request->session()->get('chat_session_id');
    }
}
