<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminAccount;
use Illuminate\Support\Facades\Hash;

class SetAdmin extends Command
{
    protected $signature = 'admin:set {username} {password}';
    protected $description = 'Create or replace single admin';

    public function handle(): int
    {
        AdminAccount::query()->delete();

        $admin = AdminAccount::create([
            'username' => $this->argument('username'),
            'password' => Hash::make($this->argument('password')),
        ]);

        $this->info("Admin set: {$admin->username}");

        return self::SUCCESS;
    }
}
