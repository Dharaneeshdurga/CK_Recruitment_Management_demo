<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departments;
class CreateDepartmentseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            'IT',
            'Accounts',
            'Finance',
            'HR',
            
         ];
    
         foreach ($departments as $department) {
            Departments::create(['name' => $department]);
         }
    }
}
