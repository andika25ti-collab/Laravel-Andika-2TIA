<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create';

    protected $description = 'Create an administrator account for the dashboard';

    public function handle(): int
    {
        $input = [
            'name' => $this->ask('Nama admin'),
            'email' => $this->ask('Email admin'),
            'password' => $this->secret('Password admin (minimal 8 karakter)'),
            'password_confirmation' => $this->secret('Konfirmasi password'),
        ];

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            $this->components->error($validator->errors()->first());

            return self::FAILURE;
        }

        User::query()->create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'is_admin' => true,
        ]);

        $this->components->info('Akun admin berhasil dibuat.');

        return self::SUCCESS;
    }
}
