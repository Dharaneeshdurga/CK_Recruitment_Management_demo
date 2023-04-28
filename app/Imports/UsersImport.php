<?php

namespace App\Imports;

// use App\Models\User;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel , WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // return new User
        $insert_data[] = ([
         'name'  => $row['name'],
         'email'   => $row['email'],
         'mobile_number'   => $row['mobile_number'],
         'current_location'    => $row['current_location'],
         'exp'  => $row['exp'],
         'skill_set'   => $row['skill_set'],
         'position_applying_to'   => $row['position_applying_to'],
         'years_of_experience'   => $row['years_of_experience'],
         'remarks'   => $row['remarks']

        ]);

    if(!empty($insert_data))
      {
       DB::table('external_candidate_details')->insert($insert_data);
      }

    }
}
