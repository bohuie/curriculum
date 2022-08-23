<?php

use App\Models\syllabus\VancouverSyllabusResource;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveVancouverSyllabiResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        VancouverSyllabusResource::where('id_name', 'harass')->first()->delete();
        VancouverSyllabusResource::where('id_name', 'academic')->first()->delete();
        VancouverSyllabusResource::where('id_name', 'religious')->first()->delete();
        VancouverSyllabusResource::where('id_name', 'concession')->first()->delete();
        VancouverSyllabusResource::where('id_name', 'honesty')->first()->delete();
        VancouverSyllabusResource::where('id_name', 'disability')->first()->delete();
        VancouverSyllabusResource::where('id_name', 'support')->first()->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        VancouverSyllabusResource::create([
            "id_name"=>"harass",
            "title"=>"Freedom from Harassment and Discrimination"
        ]);
        VancouverSyllabusResource::create([
            "id_name"=>"academic",
            "title"=>"Academic Integrity Statement"
        ]);
        VancouverSyllabusResource::create([
            "id_name"=>"religious",
            "title"=>"Religious, Spiritual and Cultural Accommodation"
        ]);
        VancouverSyllabusResource::create([
            "id_name"=>"concession",
            "title"=>"Academic Concession"
        ]);
        VancouverSyllabusResource::create([
            "id_name"=>"honesty",
            "title"=>"Student Conduct and Discipline"
        ]);
        VancouverSyllabusResource::create([
            "id_name"=>"disability",
            "title"=>"Accommodations for Students with Disabilities"
        ]);
        VancouverSyllabusResource::create([
            "id_name"=>"support",
            "title"=>"Student Health Resources"
        ]);
    }
}
