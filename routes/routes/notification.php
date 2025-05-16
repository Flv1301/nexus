<?php

use App\Http\Controllers\Mails\EmailNotificationController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'permission:notificacao'])->controller(EmailNotificationController::class)->group(
    function () {
        Route::get('/email/notificados', 'index')->name('email_notifications');
        Route::get('/email/user/notificacao', 'create')->name('email_notification.create');
        Route::get('/email/user/notificacao/{id}', 'edit')->name('email_notification.edit');
        Route::post('/email/user/notificacao', 'store')->name('email_notification.store');
        Route::put('/email/user/notificacao/{id}', 'update')->name('email_notification.update');
        Route::delete('/email/user/notificacao/{id}', 'destroy')->name('email_notification.destroy');
    }
);
