<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(['email' => 'wahyukurniawan101630@gmail.com'], [
            'name' => 'Super Developer',
            'password' => bcrypt('Script42hyu42'),
            'role' => 'developer',
            'plan' => 'pro',
        ]);

        User::updateOrCreate(['email' => 'sales@autoshow.id'], [
            'name' => 'Sales Demo',
            'password' => bcrypt('password'),
            'role' => 'sales',
            'plan' => 'regular',
        ]);

        $this->call([
            \Database\Seeders\AnalyticsSeeder::class,
        ]);
    }
}
