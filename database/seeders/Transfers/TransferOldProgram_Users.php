<?php

namespace Database\Seeders;

use App\Models\ProgramUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferOldProgram_Users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // arr = [programId][userId] = time created at
        // example
        // arr = 3
        //        => 2 = null
        //        => 31 = null
        //     = 7
        //        => 2 = 5946728
        $oldPU = DB::table('program_users_old')->get();
        $programsUsers = [];
        foreach ($oldPU as $old) {
            $programsUsers[$old->program_id][$old->user_id] = ($old->created_at == null ? null : (time() - strtotime($old->created_at)));
        }
        // loop through $arr and store in new db
        foreach ($programsUsers as $programId => $programUsers) {
            if (count($programUsers) == 1) {
                //  Case: 1 there exists only one user for the program
                // insert owner
                ProgramUser::insert([
                    'user_id' => key($programUsers),
                    'program_id' => $programId,
                    'permission' => 1,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
            // There exists more than one user for a program
            } else {
                // if there are more than one collaborators per program
                $collaboratorsPerProgram = [];
                $hasDate = false;
                foreach ($programUsers as $userId => $programUser) {
                    $collaboratorsPerProgram[$userId] = $programUser;
                    if (is_int($programUser)) {
                        $hasDate = true;
                    }
                }
                if ($hasDate) {
                    // There is a date is present
                    if (! in_array(null, $collaboratorsPerProgram)) {
                        // Case 2, no null values in the array, there are only dates to compare
                        $ownerId = 0;
                        $time = 0;
                        foreach ($collaboratorsPerProgram as $userId => $cpp) {
                            // stores the first users time and id, used to compare to subsequent users
                            if ($time == 0) {
                                $time = $cpp;
                                $ownerId = $userId;
                            } elseif ($time < $cpp) {
                                // Store the Larger time, as the greatest size represents the original creator and therefore, the owner.
                                $time = $cpp;
                                $ownerId = $userId;
                            }
                        }
                        // insert owner
                        ProgramUser::insert([
                            'user_id' => $ownerId,
                            'program_id' => $programId,
                            'permission' => 1,
                            'created_at' => \Carbon\Carbon::now(),
                            'updated_at' => \Carbon\Carbon::now(),
                        ]);

                        // insert each non owner
                        foreach ($collaboratorsPerProgram as $userId => $cpp) {
                            if ($userId != $ownerId) {
                                ProgramUser::insert([
                                    'user_id' => $userId,
                                    'program_id' => $programId,
                                    'permission' => 2,
                                    'created_at' => \Carbon\Carbon::now(),
                                    'updated_at' => \Carbon\Carbon::now(),
                                ]);
                            }
                        }

                    } else {
                        // case 3 There is a combination of dates and null values
                        // step 1: loop through all collaborators.
                        $ownerId = 0;
                        $time = 0;
                        foreach ($collaboratorsPerProgram as $userId => $cpp) {
                            // step 2: store only the ones with a date, and then compare all of the users with a date to each other to find the owner as done above.
                            if ($cpp != null) {
                                // stores the first users time and id, used to compare to subsequent users
                                if ($time == 0) {
                                    $time = $cpp;
                                    $ownerId = $userId;
                                } elseif ($time < $cpp) {
                                    // Store the Larger time, as the greatest size represents the original creator and therefore, the owner.
                                    $time = $cpp;
                                    $ownerId = $userId;
                                }
                            }
                        }
                        // insert owner
                        ProgramUser::insert([
                            'user_id' => $ownerId,
                            'program_id' => $programId,
                            'permission' => 1,
                            'created_at' => \Carbon\Carbon::now(),
                            'updated_at' => \Carbon\Carbon::now(),
                        ]);
                        // step 3: store all other users as editors
                        // insert each non owner
                        foreach ($collaboratorsPerProgram as $userId => $cpc) {
                            if ($userId != $ownerId) {
                                ProgramUser::insert([
                                    'user_id' => $userId,
                                    'program_id' => $programId,
                                    'permission' => 2,
                                    'created_at' => \Carbon\Carbon::now(),
                                    'updated_at' => \Carbon\Carbon::now(),
                                ]);
                            }
                        }
                    }
                } else {
                    // case 4 There are no dates present in the array. NOTE: This case poses the most problems as there is no real way in determining which user owns the program
                    // Store the first user found as the owner and all subsequent users as editors
                    $isFirstUser = true;
                    foreach ($collaboratorsPerProgram as $userId => $cpp) {
                        // stores first user found as owner, then $isFirstUser becomes false, leading to all other users becoming editors.
                        if ($isFirstUser) {
                            ProgramUser::insert([
                                'user_id' => $userId,
                                'program_id' => $programId,
                                'permission' => 1,
                                'created_at' => \Carbon\Carbon::now(),
                                'updated_at' => \Carbon\Carbon::now(),
                            ]);
                            $isFirstUser = false;
                        } else {
                            ProgramUser::insert([
                                'user_id' => $userId,
                                'program_id' => $programId,
                                'permission' => 2,
                                'created_at' => \Carbon\Carbon::now(),
                                'updated_at' => \Carbon\Carbon::now(),
                            ]);
                        }
                    }
                }
            }
        }

    }
}
