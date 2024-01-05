<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            MappingScaleCategoriesSeeder::class,
            MappingScaleSeeder::class,
            OptionalPrioritiesSeeder::class,
            StandardCategorySeeder::class,
            StandardSeeder::class,
            StandardScaleCategorySeeder::class,
            StandardScaleSeeder::class,
            OkanaganSyllabusResourceSeeder::class,
            VancouverSyllabusResourceSeeder::class,
            CampusFacultyDepartmentSeeder::class,
        ]);
    }
}
