<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\ProfileController;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

Route::get("/", function () {
    $messaging = app("firebase.messaging");
    $message = CloudMessage::new()
        ->withNotification(Notification::create("Draw Update", "Incoming draw in 5 4 3 2..."))
        ->withData([
            "type" => "draw-update",
            "data" => "draw date",
        ])
        ->toTopic("draw");

    $messaging->send($message);
});

Route::get("/municipality/{citymunCode}", [ProfileController::class, "showMunicipality"]);
=======

Route::get('/', function () {
    return view('welcome');
});


>>>>>>> f25abc5f00da603459bce557d47fb14f02be1d72
