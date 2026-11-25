<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('msg91:send-otp {mobile} {--otp=} {--authkey=}', function ($mobile) {
    $otp = $this->option('otp') ?: (string) random_int(100000, 999999);
    $authkey = $this->option('authkey');

    if ($authkey) {
        config(['services.msg91.auth_key' => $authkey]);
    }

    $this->info("Sending OTP [{$otp}] to [{$mobile}] via MSG91 Flow API...");
    $service = new \App\Services\Msg91Service();
    $result = $service->sendOtp($mobile, $otp);

    if ($result['success']) {
        $this->info("✅ Success: " . json_encode($result, JSON_PRETTY_PRINT));
    } else {
        $this->error("❌ Error: " . json_encode($result, JSON_PRETTY_PRINT));
    }
})->purpose('Send test OTP using MSG91 Flow API');

