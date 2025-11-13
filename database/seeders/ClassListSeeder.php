<?php

namespace Database\Seeders;

use App\Models\ClassList;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        // 3. Matikan foreign key check, truncate, lalu nyalakan lagi
        Schema::disableForeignKeyConstraints();
        DB::table('class_lists')->truncate();
        Schema::enableForeignKeyConstraints();

        // 4. Looping dan masukkan data ke tabel
        foreach ($uniqueClassNames as $className) {
            ClassList::create(['name' => $className]);
        }
    }
}
