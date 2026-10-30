<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateCmsAdmin extends Command
{
    protected $signature = 'cms:user:create';
    protected $description = 'Create the first GECO CMS administrator';

    public function handle(): int
    {
        if (User::query()->exists()) {
            $this->components->error('A CMS account already exists. Add other users from the CMS Users screen.');
            return self::FAILURE;
        }

        $name = trim((string) $this->ask('Administrator name'));
        $email = mb_strtolower(trim((string) $this->ask('Administrator email')));
        $password = (string) $this->secret('Password (at least 12 characters)');
        $confirmation = (string) $this->secret('Repeat password');

        $validator = Validator::make(
            ['name' => $name, 'email' => $email, 'password' => $password],
            ['name' => ['required', 'string', 'max:120'], 'email' => ['required', 'email', 'max:255', 'unique:users,email'], 'password' => ['required', 'string', 'min:12']],
        );

        if ($validator->fails()) {
            $this->components->error($validator->errors()->first());
            return self::FAILURE;
        }
        if (!hash_equals($password, $confirmation)) {
            $this->components->error('The passwords do not match.');
            return self::FAILURE;
        }

        User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_active' => true,
        ]);

        $this->components->info('Administrator created. Sign in at /admin/login.');
        return self::SUCCESS;
    }
}