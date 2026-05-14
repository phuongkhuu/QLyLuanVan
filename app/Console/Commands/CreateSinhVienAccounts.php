<?php

namespace App\Console\Commands;

use App\Models\SinhVien;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateSinhVienAccounts extends Command
{
    protected $signature = 'sinhvien:create-accounts';
    protected $description = 'Create user accounts for all SinhVien without an account';

    public function handle()
    {
        $sinhViens = SinhVien::whereNull('user_id')->get();

        if ($sinhViens->count() === 0) {
            $this->info('All SinhVien already have accounts.');
            return;
        }

        foreach ($sinhViens as $sv) {
            $email = $sv->email ?? strtolower(Str::slug($sv->Ho_va_Ten)).'@student.example.com';

            $password = $email; 

            $user = User::create([
                'name' => $sv->Ho_va_Ten,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'SinhVien', 
            ]);

            $sv->update(['user_id' => $user->id]);

            $this->info("Created: {$sv->Ho_va_Ten} | {$email} | password: {$password}");
        }

        $this->info("Done. Accounts created.");
    }
}