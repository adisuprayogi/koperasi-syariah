<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create default admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@koperasi.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'first_login' => false,
        ]);

        // Create sample pengurus users
        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ketua@koperasi.com',
            'password' => Hash::make('password'),
            'role' => 'pengurus',
            'first_login' => true,
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'sekretaris@koperasi.com',
            'password' => Hash::make('password'),
            'role' => 'pengurus',
            'first_login' => true,
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'bendahara@koperasi.com',
            'password' => Hash::make('password'),
            'role' => 'pengurus',
            'first_login' => true,
        ]);

        // Create sample anggota user
        User::create([
            'name' => 'Rina Wijaya',
            'email' => 'rina@koperasi.com',
            'password' => Hash::make('password'),
            'role' => 'anggota',
            'first_login' => true,
        ]);

        $this->command->info('Default users created successfully!');
        $this->command->info('Admin: admin@koperasi.com / password');
        $this->command->info('Ketua: ketua@koperasi.com / password');
        $this->command->info('Sekretaris: sekretaris@koperasi.com / password');
        $this->command->info('Bendahara: bendahara@koperasi.com / password');
        $this->command->info('Sample Anggota: rina@koperasi.com / password');
    }
}
