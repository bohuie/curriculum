<?php

namespace Database\Seeders;

use App\Models\CourseOptionalPriorities;
use App\Models\OptionalPriorities;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferOptionalPriorities extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $newOptionalPriorities = OptionalPriorities::all();
        $oldOptionalPriorities = DB::table('optional_priorities_old')->get();

        foreach ($oldOptionalPriorities as $oldOptionalPriority) {
            if ($index = array_search($oldOptionalPriority->custom_PLO, $newOptionalPriorities->pluck('old_optional_priority')->toArray())) {
                CourseOptionalPriorities::create([
                    'op_id' => $newOptionalPriorities[$index]->op_id,
                    'course_id' => $oldOptionalPriority->course_id,
                ]);
            } else {
                DB::table('optional_priorities_old')->where('id', $oldOptionalPriority->id)->update(['input_status' => 1]);
            }
        }
    }
}
