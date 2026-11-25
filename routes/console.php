<?php

use App\Services\LoanFineCalculator;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    LoanFineCalculator::syncAll();
})->dailyAt('00:05')->name('sync-loan-fines');
