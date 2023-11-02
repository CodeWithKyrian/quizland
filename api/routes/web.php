<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    dd(route('passport.token'));
    return "This is the backend of QuizLand! It's still under construction, but you can check out the frontend at <a href='http://quizland.local'>http://quizland.local</a>";
});
