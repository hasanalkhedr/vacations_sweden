<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department_names = [
            'Agence Compatable',
            'Audio',
            'Bekaa',
            'Bureau du Livre',
            'Campus France',
            'Centre de Langes',
            'Communication',
            "Culturel",
            'Deir El Qamar',
            'Direction',
            'Jounieh',
            'Linguistique',
            'Secrétariat Général',
            'Sud',
            'Tripoli',
        ];
        foreach ($department_names as $name) {
            Department::create([
                'name' => $name
            ]);
        }
    }
}
