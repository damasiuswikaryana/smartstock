<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Outlet;
use App\Models\Gerobak;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $date = date('Y-m-d');

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        Outlet::create([
            'nama'   => 'Outlet Gadung',
            'alamat' => 'Jl. Gadung No.8, Dangin Puri Kangin, Kec. Denpasar Tim., Kota Denpasar, Bali 80236'
        ],[
            'nama'   => 'Outlet Sesetan',
            'alamat' => 'Jl. Tegal Wangi No.105, Sesetan, Denpasar Selatan, Kota Denpasar, Bali 80225'
        ]);

        $user = User::create([
            'loc_id'         => 1,
            'emp_id'         => 'SK-000',
            'firstname'      => 'Admin',
            'lastname'       => 'Demo',
            'username'       => 'admin-demo',
            'password'       => Hash::make('password'),
            // 'email'          => 'admin@smartvisit.id',
            'remember_token' => Str::random(10),
        ]);

        $user2 = User::create([
            'loc_id'         => 1,
            'emp_id'         => 'SK-001',
            'firstname'      => 'Employee',
            'lastname'       => 'Demo',
            'username'       => 'SK-001',
            'password'       => Hash::make('password'),
            // 'email'          => 'admin@smartvisit.id',
            'remember_token' => Str::random(10),
        ]);

        Gerobak::create([
            'loc_id'    =>1,
            'user_id'   =>2,
            'kode'      => 'DK 1234 GS',
            'nama'      => 'GS-01',
        ]);
        // Pengaturan::create([
        //     "user_id" => $user->id,
        //     "photo" => "",
        //     "active_at" => date('Y-m-d'),
        //     "expired_at" => date('Y-m-d', strtotime($date . ' + 60 days')),
        //     "nonactive_at" => date('Y-m-d', strtotime($date . ' + 74 days')),
        // ]);

        $user->assignRole('admin');
        $user2->assignRole('user');
    }
}
