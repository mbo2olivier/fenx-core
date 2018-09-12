<?php
use App\Controller\SecurityController;

$routes
    ->route("/login",[SecurityController::class,"login"])
    ->name('login')
;

$routes
    ->route("/logout",[SecurityController::class,"logout"])
    ->name('logout')
;
