<?php

use App\Http\Controllers\CommandController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/commands', function () {
    return view('command');
});

Route::get('/banners', [CommandController::class, 'banner'])->name('banner');
Route::get('/enterprises', [CommandController::class, 'enterprise'])->name('enterprise');
Route::get('/contact', [CommandController::class, 'contact'])->name('contact');
Route::get('/popups', [CommandController::class, 'popup'])->name('popup');
Route::get('/guides', [CommandController::class, 'guide'])->name('guide');
Route::get('/admins', [CommandController::class, 'admin'])->name('admin');
Route::get('/users', [CommandController::class, 'user'])->name('user');
Route::get('/notifications-app', [CommandController::class, 'notificationsApp'])->name('notifications_app');
Route::get('/flashcard_topics', [CommandController::class, 'flashcardTopic'])->name('flashcard_topic');
Route::get('/flashcards', [CommandController::class, 'flashcard'])->name('flashcard');
Route::get('/flashcard-category', [CommandController::class, 'flashcardCategory'])->name('flashcard_category');
Route::get('/answers', [CommandController::class, 'answer'])->name('answer');
Route::get('/lesson', [CommandController::class, 'lesson'])->name('lesson');
Route::get('/exercise', [CommandController::class, 'exercise'])->name('exercise');
Route::get('/ebook', [CommandController::class, 'ebook'])->name('ebook');
Route::get('/feedback', [CommandController::class, 'feedback'])->name('feedback');
Route::get('/examination', [CommandController::class, 'examination'])->name('examination');
Route::get('/result-exam', [CommandController::class, 'resultExam'])->name('result_exam');
Route::get('/remove-question-answer', [CommandController::class, 'removeQuestionAnswer'])->name('remove_question_answer');
Route::get('/question-exam', [CommandController::class, 'questionExam'])->name('question_exam');
Route::get('/answer-exam', [CommandController::class, 'answerExam'])->name('answer_exam');
Route::get('/group-question-pivot', [CommandController::class, 'groupQuestionPivot'])->name('group_question_pivot');
Route::get('/group-question-pivot-exercise', [CommandController::class, 'groupQuestionPivotExercise'])->name('group_question_pivot_exercise');
Route::get('/activate', [CommandController::class, 'activate'])->name('activate');
Route::get('/truncate-all', [CommandController::class, 'truncateAll'])->name('truncate_all');
