<?php

namespace Database\Seeders;

use App\Models\ClassList;
use Illuminate\Database\Seeder;

class ClassListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Daftar nama kelas yang diberikan user
        $classNames = [
            'BodyCombat', 'BodyPump', 'Pilates', 'Circuit Training', 'Dynamic Strength', 
            'Fit Track', 'Power Up', 'Muay Thai', 'Boxing', 'Zumba', 'Hip Hop', 
            'Yoga', 'Pound Fit'
        ];

        // 2. Hapus duplikat jika ada
        $uniqueClassNames = array_unique($classNames);

        // 3. Prepare data for upsert
        $data = [];
        foreach ($uniqueClassNames as $className) {
            $data[] = ['name' => $className];
        }

        // 4. Use upsert to efficiently insert or update records
        ClassList::upsert($data, ['name'], ['name']);
    }
}
