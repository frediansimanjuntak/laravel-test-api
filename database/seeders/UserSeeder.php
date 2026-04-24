<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{    
    private const ADMIN_PASSWORD = 'Admin1234!';
    private const MANAGER_PASSWORD = 'Manager1234!';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(2)
            ->administrator()
            ->create(['password' => Hash::make(self::ADMIN_PASSWORD)])
            ->each(fn(User $user) => Order::factory(rand(1, 10))->for($user)->create());

        User::factory(2)
            ->manager()
            ->create(['password' => Hash::make(self::MANAGER_PASSWORD)])
            ->each(fn(User $user) => Order::factory(rand(1, 10))->for($user)->create());

        User::factory(15)
            ->create()
            ->each(fn(User $user) => Order::factory(rand(1, 15))->for($user)->create());
    }
}
