<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username'   => 'admin',
                'password'   => Hash::make('admin123'),
                'full_name'  => 'System Administrator',
                'email'      => null,
                'role'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username'   => 'staff1',
                'password'   => Hash::make('staff123'),
                'full_name'  => 'Barangay Staff',
                'email'      => null,
                'role'       => 'staff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $types = [
            'Physical Assault',
            'Theft / Robbery',
            'Verbal Abuse / Threat',
            'Trespassing',
            'Vandalism',
            'Domestic Violence',
            'Noise Complaint',
            'Illegal Dumping',
            'Land / Property Dispute',
            'Animal Bite / Stray Animal',
            'Drug-Related Incident',
            'Other Incident',
        ];

        foreach ($types as $type) {
            DB::table('incident_types')->insert(['type_name' => $type]);
        }
    }
}
