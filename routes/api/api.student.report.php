<?php

use Illuminate\Support\Facades\Route;


Route::group(["prefix" => "student-report", "middleware" => ["auth.api"]], function () {
    Route::get("/", [\App\Http\Controllers\Api\ApiStudentReportController::class, 'getStudentReport']);
    Route::get("/my-reports", [\App\Http\Controllers\Api\ApiStudentReportController::class, 'getMyReports']);
    Route::get("/{id}", [\App\Http\Controllers\Api\ApiStudentReportController::class, 'getStudentReportDetailById']);
    Route::post("/create", [\App\Http\Controllers\Api\ApiStudentReportController::class, 'createStudentReport']);
    Route::post("/{id}/update", [\App\Http\Controllers\Api\ApiStudentReportController::class, 'updateStudentReport']);
    Route::delete("/{id}/delete", [\App\Http\Controllers\Api\ApiStudentReportController::class, 'deleteStudentReport']);
});