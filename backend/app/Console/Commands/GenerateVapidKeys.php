<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

class GenerateVapidKeys extends Command
{
    protected $signature = 'vapid:generate';
    protected $description = 'Generate VAPID keys for push notifications';

    public function handle()
    {
        $keys = VAPID::createVapidKeys();
        
        $this->info('VAPID Keys Generated:');
        $this->line('');
        $this->line('Public Key:');
        $this->line($keys['publicKey']);
        $this->line('');
        $this->line('Private Key:');
        $this->line($keys['privateKey']);
        $this->line('');
        $this->info('Add these to your .env file:');
        $this->line('VAPID_PUBLIC_KEY=' . $keys['publicKey']);
        $this->line('VAPID_PRIVATE_KEY=' . $keys['privateKey']);
        
        return 0;
    }
}