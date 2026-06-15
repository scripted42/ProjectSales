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
        $devEmail = env('DEVELOPER_EMAIL', 'wahyukurniawan101630@gmail.com');
        $devPassword = env('DEVELOPER_PASSWORD', 'Script42hyu42');
        $devName = env('DEVELOPER_NAME', 'Super Developer');

        User::updateOrCreate(['email' => $devEmail], [
            'name' => $devName,
            'password' => bcrypt($devPassword),
            'role' => 'developer',
            'plan' => 'pro',
            'email_verified_at' => now(),
        ]);

        if (env('SEED_MOCK_DATA', false) || app()->environment('local')) {
            User::updateOrCreate(['email' => 'sales@autoshow.id'], [
                'name' => 'Sales Demo',
                'password' => bcrypt('password'),
                'role' => 'sales',
                'plan' => 'regular',
                'email_verified_at' => now(),
            ]);

            $this->call([
                \Database\Seeders\ProjectSeeder::class,
                \Database\Seeders\PostSeeder::class,
                \Database\Seeders\AnalyticsSeeder::class,
                \Database\Seeders\TestimonialSeeder::class,
            ]);
        }
    }
}
