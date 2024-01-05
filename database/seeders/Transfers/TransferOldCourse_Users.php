<?php

namespace Database\Seeders;

use App\Models\CourseUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferOldCourse_Users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // arr = [courseId][userId] = time created at
        // example
        // arr = 3
        //        => 2 = null
        //        => 31 = null
        //     = 7
        //        => 2 = 5946728
        $oldCU = DB::table('course_users_old')->get();
        $coursesUsers = [];
        foreach ($oldCU as $old) {
            $coursesUsers[$old->course_id][$old->user_id] = ($old->created_at == null ? null : (time() - strtotime($old->created_at)));
        }
        // loop through $arr and store in new db
        foreach ($coursesUsers as $courseId => $courseUsers) {
            if (count($courseUsers) == 1) {
                //  Case: 1 there exists only one user for the course
                // insert owner
                CourseUser::insert([
                    'course_id' => $courseId,
                    'user_id' => key($courseUsers),
                    'permission' => 1,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
            // There exists more than one user for a course
            } else {
                // if there are more than one collaborators per course
                $collaboratorsPerCourse = [];
                $hasDate = false;
                foreach ($courseUsers as $userId => $courseUser) {
                    $collaboratorsPerCourse[$userId] = $courseUser;
                    if (is_int($courseUser)) {
                        $hasDate = true;
                    }
                }
                if ($hasDate) {
                    // There is a date is present
                    if (! in_array(null, $collaboratorsPerCourse)) {
                        // Case 2, no null values in the array, there are only dates to compare
                        $ownerId = 0;
                        $time = 0;
                        foreach ($collaboratorsPerCourse as $userId => $cpc) {
                            // stores the first users time and id, used to compare to subsequent users
                            if ($time == 0) {
                                $time = $cpc;
                                $ownerId = $userId;
                            } elseif ($time < $cpc) {
                                // Store the Larger time, as the greatest size represents the original creator and therefore, the owner.
                                $time = $cpc;
                                $ownerId = $userId;
                            }
                        }
                        // insert owner
                        CourseUser::insert([
                            'course_id' => $courseId,
                            'user_id' => $ownerId,
                            'permission' => 1,
                            'created_at' => \Carbon\Carbon::now(),
                            'updated_at' => \Carbon\Carbon::now(),
                        ]);

                        // insert each non owner
                        foreach ($collaboratorsPerCourse as $userId => $cpc) {
                            if ($userId != $ownerId) {
                                CourseUser::insert([
                                    'course_id' => $courseId,
                                    'user_id' => $userId,
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
                        foreach ($collaboratorsPerCourse as $userId => $cpc) {
                            // step 2: store only the ones with a date, and then compare all of the users with a date to each other to find the owner as done above.
                            if ($cpc != null) {
                                // stores the first users time and id, used to compare to subsequent users
                                if ($time == 0) {
                                    $time = $cpc;
                                    $ownerId = $userId;
                                } elseif ($time < $cpc) {
                                    // Store the Larger time, as the greatest size represents the original creator and therefore, the owner.
                                    $time = $cpc;
                                    $ownerId = $userId;
                                }
                            }
                        }
                        // insert owner
                        CourseUser::insert([
                            'course_id' => $courseId,
                            'user_id' => $ownerId,
                            'permission' => 1,
                            'created_at' => \Carbon\Carbon::now(),
                            'updated_at' => \Carbon\Carbon::now(),
                        ]);
                        // step 3: store all other users as editors
                        // insert each non owner
                        foreach ($collaboratorsPerCourse as $userId => $cpc) {
                            if ($userId != $ownerId) {
                                CourseUser::insert([
                                    'course_id' => $courseId,
                                    'user_id' => $userId,
                                    'permission' => 2,
                                    'created_at' => \Carbon\Carbon::now(),
                                    'updated_at' => \Carbon\Carbon::now(),
                                ]);
                            }
                        }
                    }
                } else {
                    // case 4 There are no dates present in the array. NOTE: This case poses the most problems as there is no real way in determining which user owns the course
                    // Store the first user found as the owner and all subsequent users as editors
                    $isFirstUser = true;
                    foreach ($collaboratorsPerCourse as $userId => $cpc) {
                        // stores first user found as owner, then $isFirstUser becomes false, leading to all other users becoming editors.
                        if ($isFirstUser) {
                            CourseUser::insert([
                                'course_id' => $courseId,
                                'user_id' => $userId,
                                'permission' => 1,
                                'created_at' => \Carbon\Carbon::now(),
                                'updated_at' => \Carbon\Carbon::now(),
                            ]);
                            $isFirstUser = false;
                        } else {
                            CourseUser::insert([
                                'course_id' => $courseId,
                                'user_id' => $userId,
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
