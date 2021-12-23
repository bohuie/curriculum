<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Seeder;

class CampusFacultyDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create campuses
        $campusV = new Campus;
        $campusV->campus = 'Vancouver';
        $campusV->save();

        $campusO = new Campus;
        $campusO->campus = 'Okanagan';
        $campusO->save();



        // create faculties for the vancouver campus
        $facultyAS = new Faculty;
        $facultyAS->faculty = 'Faculty of Applied Science';
        $facultyAS->campus_id = $campusV->campus_id;
        $facultyAS->save();

        $facultyALA = new Faculty;
        $facultyALA->faculty = 'Faculty of Architecture and Landscape Architecture';
        $facultyALA->campus_id = $campusV->campus_id;
        $facultyALA->save();



        // Departments for the applied sciences faculty
        $departmentALA = new Department;
        $departmentALA->department = 'School of Architecture and Landscape Architecture';
        $departmentALA->faculty_id = $facultyAS->faculty_id;
        $departmentALA->save();

        $departmentCRP = new Department;
        $departmentCRP->department = 'School of (SCARP) Community and Regional Planning';
        $departmentCRP->faculty_id = $facultyAS->faculty_id;
        $departmentCRP->save();

        $departmentBE = new Department;
        $departmentBE->department = 'School of Biomedical Engineering';
        $departmentBE->faculty_id = $facultyAS->faculty_id;
        $departmentBE->save();

        $departmentCBE = new Department;
        $departmentCBE->department = 'Department of Chemical and Biological Engineering';
        $departmentCBE->faculty_id = $facultyAS->faculty_id;
        $departmentCBE->save();

        $departmentCE = new Department;
        $departmentCE->department = 'Department of Civil Engineering';
        $departmentCE->faculty_id = $facultyAS->faculty_id;
        $departmentCE->save();

        $departmentECE = new Department;
        $departmentECE->department = 'Department of Electrical and Computer Engineering';
        $departmentECE->faculty_id = $facultyAS->faculty_id;
        $departmentECE->save();

        $departmentEP = new Department;
        $departmentEP->department = 'Engineering Physics';
        $departmentEP->faculty_id = $facultyAS->faculty_id;
        $departmentEP->save();

        $departmentEE = new Department;
        $departmentEE->department = 'Environmental Engineering';
        $departmentEE->faculty_id = $facultyAS->faculty_id;
        $departmentEE->save();

        $departmentEEJ = new Department;
        $departmentEEJ->department = 'Environmental Engineering (Joint UBC/UNBC program)';
        $departmentEEJ->faculty_id = $facultyAS->faculty_id;
        $departmentEEJ->save();

        $departmentGE = new Department;
        $departmentGE->department = 'Geological Engineering';
        $departmentGE->faculty_id = $facultyAS->faculty_id;
        $departmentGE->save();

        $departmentIE = new Department;
        $departmentIE->department = 'Integrated Engineering';
        $departmentIE->faculty_id = $facultyAS->faculty_id;
        $departmentIE->save();

        $departmentME = new Department;
        $departmentME->department = 'Manufacturing Engineering';
        $departmentME->faculty_id = $facultyAS->faculty_id;
        $departmentME->save();

        $departmentMAE = new Department;
        $departmentMAE->department = 'Department of Materials Engineering';
        $departmentMAE->faculty_id = $facultyAS->faculty_id;
        $departmentMAE->save();

        $departmentMECE = new Department;
        $departmentMECE->department = 'Department of Mechanical Engineering';
        $departmentMECE->faculty_id = $facultyAS->faculty_id;
        $departmentMECE->save();

        $departmentMINE = new Department;
        $departmentMINE->department = 'Norman B. Keevil Institute of Mining Engineering';
        $departmentMINE->faculty_id = $facultyAS->faculty_id;
        $departmentMINE->save();

        $departmentMEL = new Department;
        $departmentMEL->department = 'Master of Engineering Leadership';
        $departmentMEL->faculty_id = $facultyAS->faculty_id;
        $departmentMEL->save();

        $departmentMHLP = new Department;
        $departmentMHLP->department = 'Master of Health Leadership and Policy';
        $departmentMHLP->faculty_id = $facultyAS->faculty_id;
        $departmentMHLP->save();

        $departmentN = new Department;
        $departmentN->department = 'School of Nursing';
        $departmentN->faculty_id = $facultyAS->faculty_id;
        $departmentN->save();

        // Department of Architecture and Landscape Architecture
        $departmentSALA = new Department;
        $departmentSALA->department = 'School of Architecture and Landscape Architecture';
        $departmentSALA->faculty_id = $facultyALA->faculty_id;
        $departmentSALA->save();
    }
}
