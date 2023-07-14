<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Plan::create([
            'title' => 'Starter'
        ]);

        $user = User::query()->create(attributes: [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make(value: 'password')
        ]);

        event(new Registered($user));

        // factory(App\Models\Project::class, 10)->create();
        // $this->call(UsersTableSeeder::class);
    }
}
