<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/** Creates or resets an admin login. Safe on production, unlike db:seed. */
class CreateAdminCommand extends Command
{
    protected $signature = 'clinic:admin {--email= : Login email} {--name=Smile Inn Admin : Display name} {--password= : Password (prompted if omitted)}';

    protected $description = 'Create or update an admin account for the Smile Inn backend';

    public function handle(): int
    {
        $email = $this->option('email') ?: $this->ask('Email', config('clinic.email'));
        $name = $this->option('name');
        $password = $this->option('password') ?: $this->secret('Password (min 12 characters)');
        $v = Validator::make(compact('email', 'name', 'password'), ['email' => ['required', 'email'], 'name' => ['required', 'string', 'max:100'], 'password' => ['required', 'string', 'min:12']]);
        if ($v->fails()) {
            foreach ($v->errors()->all() as $e) {
                $this->error($e);
            }

            return self::FAILURE;
        }
        $user = User::updateOrCreate(['email' => $email], ['name' => $name, 'password' => $password, 'role' => 'admin']);
        $this->info(($user->wasRecentlyCreated ? 'Created' : 'Updated').' admin '.$user->email.'. Sign in at '.route('admin.login'));

        return self::SUCCESS;
    }
}
