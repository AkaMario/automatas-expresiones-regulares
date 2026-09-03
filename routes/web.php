<?php

use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatbotController::class, 'index'])->name('home');
Route::post('/api/validate', [ChatbotController::class, 'validateSentence'])->name('chat.validate');
Route::get('/api/examples', [ChatbotController::class, 'getExamples'])->name('chat.examples');
Route::get('/api/conversation/history', [ChatbotController::class, 'history'])->name('chat.history');
Route::post('/api/conversations', [ChatbotController::class, 'storeConversation'])->name('chat.conversations.store');
Route::patch('/api/conversations/{conversation}', [ChatbotController::class, 'updateConversation'])->name('chat.conversations.update');
Route::delete('/api/conversations/{conversation}', [ChatbotController::class, 'destroyConversation'])->name('chat.conversations.destroy');
