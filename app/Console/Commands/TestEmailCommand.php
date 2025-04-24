<?php

namespace App\Console\Commands;

use App\Mail\TestEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    protected $signature = 'email:test {email?}';
    protected $description = 'Send a test email to verify email configuration';

    public function handle()
    {
        $email = $this->argument('email') ?? 'test@example.com';

        $this->info("Sending test email to {$email}...");

        try {
            Mail::to($email)->send(new TestEmail());
            $this->info('Test email sent successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to send test email: {$e->getMessage()}");
            return 1;
        }
    }
}
