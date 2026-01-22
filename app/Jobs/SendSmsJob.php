<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\SmsOtpService;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $templateKey;
    protected array $data;
    protected string $phoneNumber;

    public function __construct(string $templateKey, string $phoneNumber, array $data = [])
    {
        $this->templateKey = $templateKey;
        $this->phoneNumber = $phoneNumber;
        $this->data = $data;
    }

    public function handle(SmsOtpService $SmsOtpService)
    {
        $SmsOtpService->send($this->templateKey, $this->phoneNumber, $this->data);
    }
}