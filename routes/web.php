<?php

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use App\Jobs\TestJob;

Route::get('/test-queue', function () {
    TestJob::dispatch();
    return "Job dispatched!";
});
