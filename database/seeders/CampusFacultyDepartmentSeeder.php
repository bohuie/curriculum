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
     */
    public function run(): void
    {
        // create campuses
        $campusV = new Campus;
        $campusV->campus_id = 1;
        $campusV->campus = 'Vancouver';
        $campusV->save();

        $campusO = new Campus;
        $campusO->campus_id = 2;
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

        // Faculty and department of arts ubcV
        $facultyARTS = new Faculty;
        $facultyARTS->faculty = 'Faculty of Arts';
        $facultyARTS->campus_id = $campusV->campus_id;
        $facultyARTS->save();

        $departmentDOA = new Department;
        $departmentDOA->department = 'Anthropology';
        $departmentDOA->faculty_id = $facultyARTS->faculty_id;
        $departmentDOA->save();

        $departmentAHVA = new Department;
        $departmentAHVA->department = 'Art History, Visual Art and Theory';
        $departmentAHVA->faculty_id = $facultyARTS->faculty_id;
        $departmentAHVA->save();

        $departmentAO = new Department;
        $departmentAO->department = 'Arts One';
        $departmentAO->faculty_id = $facultyARTS->faculty_id;
        $departmentAO->save();

        $departmentDAS = new Department;
        $departmentDAS->department = 'Asian Studies';
        $departmentDAS->faculty_id = $facultyARTS->faculty_id;
        $departmentDAS->save();

        $departmentCENES = new Department;
        $departmentCENES->department = 'Central Eastern Northern European Studies';
        $departmentCENES->faculty_id = $facultyARTS->faculty_id;
        $departmentCENES->save();

        $departmentCNERS = new Department;
        $departmentCNERS->department = 'Classical, Near Eastern and Religious Studies';
        $departmentCNERS->faculty_id = $facultyARTS->faculty_id;
        $departmentCNERS->save();

        $departmentCOAP = new Department;
        $departmentCOAP->department = 'Co-ordinated Arts Program';
        $departmentCOAP->faculty_id = $facultyARTS->faculty_id;
        $departmentCOAP->save();

        $departmentCWP = new Department;
        $departmentCWP->department = 'Creative Writing Program';
        $departmentCWP->faculty_id = $facultyARTS->faculty_id;
        $departmentCWP->save();

        $departmentVSE = new Department;
        $departmentVSE->department = 'Vancouver School of Economics';
        $departmentVSE->faculty_id = $facultyARTS->faculty_id;
        $departmentVSE->save();

        $departmentELLD = new Department;
        $departmentELLD->department = 'English Language & Literatures';
        $departmentELLD->faculty_id = $facultyARTS->faculty_id;
        $departmentELLD->save();

        $departmentFHIS = new Department;
        $departmentFHIS->department = 'French, Hispanic, & Italian Studies';
        $departmentFHIS->faculty_id = $facultyARTS->faculty_id;
        $departmentFHIS->save();

        $departmentFNELP = new Department;
        $departmentFNELP->department = 'First Nations and Endangered Languages Program';
        $departmentFNELP->faculty_id = $facultyARTS->faculty_id;
        $departmentFNELP->save();

        $departmentFNISP = new Department;
        $departmentFNISP->department = 'First Nations and Indigenous Studies Program';
        $departmentFNISP->faculty_id = $facultyARTS->faculty_id;
        $departmentFNISP->save();

        $departmentGRSSJ = new Department;
        $departmentGRSSJ->department = 'Institute for Gender, Race, Sexuality and Social Justice';
        $departmentGRSSJ->faculty_id = $facultyARTS->faculty_id;
        $departmentGRSSJ->save();

        $departmentCSS = new Department;
        $departmentCSS->department = 'Critical Studies in Sexuality';
        $departmentCSS->faculty_id = $facultyARTS->faculty_id;
        $departmentCSS->save();

        $departmentGEO = new Department;
        $departmentGEO->department = 'Geography';
        $departmentGEO->faculty_id = $facultyARTS->faculty_id;
        $departmentGEO->save();

        $departmentHIST = new Department;
        $departmentHIST->department = 'History';
        $departmentHIST->faculty_id = $facultyARTS->faculty_id;
        $departmentHIST->save();

        $departmentICIS = new Department;
        $departmentICIS->department = 'Institute for Critical Indigenous Studies';
        $departmentICIS->faculty_id = $facultyARTS->faculty_id;
        $departmentICIS->save();

        $departmentJ = new Department;
        $departmentJ->department = 'School of Journalism';
        $departmentJ->faculty_id = $facultyARTS->faculty_id;
        $departmentJ->save();

        $departmentLAIS = new Department;
        $departmentLAIS->department = 'Library, Archival and Information Studies';
        $departmentLAIS->faculty_id = $facultyARTS->faculty_id;
        $departmentLAIS->save();

        $departmentLING = new Department;
        $departmentLING->department = 'Linguistics';
        $departmentLING->faculty_id = $facultyARTS->faculty_id;
        $departmentLING->save();

        $departmentMA = new Department;
        $departmentMA->department = 'Museum of Anthropology';
        $departmentMA->faculty_id = $facultyARTS->faculty_id;
        $departmentMA->save();

        $departmentM = new Department;
        $departmentM->department = 'School of Music';
        $departmentM->faculty_id = $facultyARTS->faculty_id;
        $departmentM->save();

        $departmentP = new Department;
        $departmentP->department = 'Philosophy';
        $departmentP->faculty_id = $facultyARTS->faculty_id;
        $departmentP->save();

        $departmentPOLI = new Department;
        $departmentPOLI->department = 'Political Science';
        $departmentPOLI->faculty_id = $facultyARTS->faculty_id;
        $departmentPOLI->save();

        $departmentEUR = new Department;
        $departmentEUR->department = 'Institute of European Studies';
        $departmentEUR->faculty_id = $facultyARTS->faculty_id;
        $departmentEUR->save();

        $departmentPSYCH = new Department;
        $departmentPSYCH->department = 'Psychology';
        $departmentPSYCH->faculty_id = $facultyARTS->faculty_id;
        $departmentPSYCH->save();

        $departmentPPGA = new Department;
        $departmentPPGA->department = 'School of Public Policy and Global Affairs';
        $departmentPPGA->faculty_id = $facultyARTS->faculty_id;
        $departmentPPGA->save();

        $departmentSSW = new Department;
        $departmentSSW->department = 'School of Social Work';
        $departmentSSW->faculty_id = $facultyARTS->faculty_id;
        $departmentSSW->save();

        $departmentSOC = new Department;
        $departmentSOC->department = 'Sociology';
        $departmentSOC->faculty_id = $facultyARTS->faculty_id;
        $departmentSOC->save();

        $departmentTAF = new Department;
        $departmentTAF->department = 'Theatre and Film';
        $departmentTAF->faculty_id = $facultyARTS->faculty_id;
        $departmentTAF->save();

        // Faculty of Audio Audiology and Speech Science
        $facultyASS = new Faculty;
        $facultyASS->faculty = 'Audiology and Speech Sciences';
        $facultyASS->campus_id = $campusV->campus_id;
        $facultyASS->save();

        $departmentASS = new Department;
        $departmentASS->department = 'Audiology and Speech Sciences';
        $departmentASS->faculty_id = $facultyASS->faculty_id;
        $departmentASS->save();

        // Faculty of Business
        $facultyBUS = new Faculty;
        $facultyBUS->faculty = 'Sauder School of Business';
        $facultyBUS->campus_id = $campusV->campus_id;
        $facultyBUS->save();

        $departmentAIS = new Department;
        $departmentAIS->department = 'Division of Accounting and Information Systems';
        $departmentAIS->faculty_id = $facultyBUS->faculty_id;
        $departmentAIS->save();

        $departmentBC = new Department;
        $departmentBC->department = 'Bachelor of Commerce';
        $departmentBC->faculty_id = $facultyBUS->faculty_id;
        $departmentBC->save();

        $departmentEIG = new Department;
        $departmentEIG->department = 'Entrepreneurship & Innovation Group';
        $departmentEIG->faculty_id = $facultyBUS->faculty_id;
        $departmentEIG->save();

        $departmentEEDU = new Department;
        $departmentEEDU->department = 'Executive Education';
        $departmentEEDU->faculty_id = $facultyBUS->faculty_id;
        $departmentEEDU->save();

        $departmentDF = new Department;
        $departmentDF->department = 'Division of Finance';
        $departmentDF->faculty_id = $facultyBUS->faculty_id;
        $departmentDF->save();

        $departmentLBCG = new Department;
        $departmentLBCG->department = 'Law & Business Communications Group';
        $departmentLBCG->faculty_id = $facultyBUS->faculty_id;
        $departmentLBCG->save();

        $departmentDMBS = new Department;
        $departmentDMBS->department = 'Division of Marketing & Behavioural Science';
        $departmentDMBS->faculty_id = $facultyBUS->faculty_id;
        $departmentDMBS->save();

        $departmentMBA = new Department;
        $departmentMBA->department = 'Master of Business Administration';
        $departmentMBA->faculty_id = $facultyBUS->faculty_id;
        $departmentMBA->save();

        $departmentMBA = new Department;
        $departmentMBA->department = 'Master of Business Administration';
        $departmentMBA->faculty_id = $facultyBUS->faculty_id;
        $departmentMBA->save();

        $departmentMOM = new Department;
        $departmentMOM->department = 'Master of Management';
        $departmentMOM->faculty_id = $facultyBUS->faculty_id;
        $departmentMOM->save();

        $departmentDOL = new Department;
        $departmentDOL->department = 'Division of Operations and Logistics';
        $departmentDOL->faculty_id = $facultyBUS->faculty_id;
        $departmentDOL->save();

        $departmentOBHR = new Department;
        $departmentOBHR->department = 'Division of Organizational Behaviour and Human Resources';
        $departmentOBHR->faculty_id = $facultyBUS->faculty_id;
        $departmentOBHR->save();

        $departmentPHDP = new Department;
        $departmentPHDP->department = 'PhD Program';
        $departmentPHDP->faculty_id = $facultyBUS->faculty_id;
        $departmentPHDP->save();

        $departmentRED = new Department;
        $departmentRED->department = 'Real Estate Division';
        $departmentRED->faculty_id = $facultyBUS->faculty_id;
        $departmentRED->save();

        $departmentSBE = new Department;
        $departmentSBE->department = 'Division of Strategy and Business Economics';
        $departmentSBE->faculty_id = $facultyBUS->faculty_id;
        $departmentSBE->save();

        // Community and Regional Planning, School of
        $faculty = new Faculty;
        $faculty->faculty = 'Community and Regional Planning';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Centre for Human Settlements';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Dentistry
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Dentistry';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Department of Oral Biological and Medical Sciences (OBMS)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Oral Health Sciences (OHS)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Education
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Education';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Department of Educational and Counselling Psychology, and Special Education (ECPS)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Centre for Cross-Faculty Inquiry in Education (CCFI)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Curriculum and Pedagogy (EDCP)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Educational Studies (EDST)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'School of Kinesiology (HKIN)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Language and Literacy Education (LLED)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Teacher Education';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Extended Learning
        $faculty = new Faculty;
        $faculty->faculty = 'Extended Learning';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Continuing Education for Adult Learners (In-class and Online)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'English Language Institute';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Forestry
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Forestry';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Department of Forest Resources Management';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Forest Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Wood Science';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Graduate and Postdoctoral Studies
        $faculty = new Faculty;
        $faculty->faculty = 'Graduate and Postdoctoral Studies';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Green College';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = "St. John's College";
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Journalism
        $faculty = new Faculty;
        $faculty->faculty = 'School of Journalism';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'International Reporting Program';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Journalism
        $faculty = new Faculty;
        $faculty->faculty = 'School of Kinesiology';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Kinesiology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Journalism
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Land and Food Systems';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Global Resource Systems';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Applied Biology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Food, Nutrition & Health';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Graduate Programs';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Journalism
        $faculty = new Faculty;
        $faculty->faculty = 'Peter A. Allard School of Law';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'School of Law';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Library, Archival and Information Studies, School of
        $faculty = new Faculty;
        $faculty->faculty = 'School of Library, Archival and Information Studies';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Library, Archival and Information Studies';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Medicine
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Medicine';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Department of Anesthesiology, Pharmacology & Therapeutics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'School of Audiology and Speech Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Biochemistry and Molecular Biology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Cellular and Physiological Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Dermatology and Skin Science';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Family Practice';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Midwifery';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'International Collaboration on Repair Discoveries (ICORD)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Medical Genetics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Allergy and Immunology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Cardiology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Critical Care Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Endocrinology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Experimental Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Gastroenterology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of General Internal Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Geriatric Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Hematology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Infectious Diseases';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Medical Oncology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Nephrology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Neurology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Physical Medicine and Rehabilitation';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Respiratory Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Rheumatology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Neuroscience';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Obstetrics and Gynaecology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Occupational Science & Occupational Therapy';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Ophthalmology and Visual Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Orthopaedic Surgery';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Pathology and Laboratory Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Pediatrics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Physical Therapy';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'School of Population and Public Health';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Psychiatry';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Radiology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Division of Nuclear Medicine';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Surgery';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Urologic Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Music
        $faculty = new Faculty;
        $faculty->faculty = 'School of Music';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Music';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Music
        $faculty = new Faculty;
        $faculty->faculty = 'School of Nursing';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Nursing';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // School of population and Public Health
        $faculty = new Faculty;
        $faculty->faculty = 'School of Population and Public Health';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Applied Ethics, W Maurice Young Centre for (CAE)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Human Early Learning Partnership (HELP)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // School of population and Public Health
        $faculty = new Faculty;
        $faculty->faculty = 'School of Public Policy and Global Affairs';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Institute of Asian Research';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Canadian International Resources and Development Institute';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Liu Institute for Global Issues';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Centre for the Study of Democratic Institutions';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Pharmaceutical Sciences, Faculty of
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Pharmaceutical Sciences';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Pharmaceutical Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // science
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Science';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Applied Mathematics, Institute of (IAM)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Bioinformatics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Botany';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Chemistry';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Computer Science';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Earth and Ocean Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Fisheries Centre (FC)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Fisheries Economics Research Unit';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Marine Mammal Research Unit';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Project Seahorse';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Sea Around Us Project';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Fisheries Ecosystems Restoration Research';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Quantitative Analysis and Modeling';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Mathematics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Microbiology and Immunology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Physics and Astronomy';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Institute for Resources, Environment and Sustainability (IRES)';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Eco-Risk Research Unit';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Forest Economics and Policy Analysis';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Sustainable Development Research Initiative';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Westwater Research Unit';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Resources, Management and Environmental Studies';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Statistics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Department of Zoology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // social work
        $faculty = new Faculty;
        $faculty->faculty = 'School of Social Work';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Social Work';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // UBC Vantage College
        $faculty = new Faculty;
        $faculty->faculty = 'UBC Vantage College';
        $faculty->campus_id = $campusV->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'UBC Vantage College';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Irving K. Barber Faculty of Arts and Social Sciences
        $faculty = new Faculty;
        $faculty->faculty = 'Irving K. Barber Faculty of Arts and Social Sciences';
        $faculty->campus_id = $campusO->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Community, Culture and Global Studies';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Economics, Philosophy and Political Science';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'History and Sociology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Psychology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Faculty of Creative and critical Studies
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Creative and Critical Studies';
        $faculty->campus_id = $campusO->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Creative Studies';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'English and Cultural Studies';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Languages and World Literature';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Okanagan School of Education
        $faculty = new Faculty;
        $faculty->faculty = 'Okanagan School of Education';
        $faculty->campus_id = $campusO->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Education';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // School of Engineering
        $faculty = new Faculty;
        $faculty->faculty = 'School of Engineering';
        $faculty->campus_id = $campusO->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Engineering';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Faculty of Health and Social Development
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Health and Social Development';
        $faculty->campus_id = $campusO->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Health and Exercise Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Nursing';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Social Work';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Faculty of Health and Social Development
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Management';
        $faculty->campus_id = $campusO->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Management';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Irving K. Barber Faculty of Science
        $faculty = new Faculty;
        $faculty->faculty = 'Irving K. Barber Faculty of Science';
        $faculty->campus_id = $campusO->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Biology';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Chemistry';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Computer Science, Mathematics, Physics, and Statistics';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        $department = new Department;
        $department->department = 'Earth, Environmental and Geographic Sciences';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // Faculty of Medicine Southern Medical Program
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Medicine Southern Medical Program';
        $faculty->campus_id = $campusO->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Southern Medical Program';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

        // College of Graduate Studies
        $faculty = new Faculty;
        $faculty->faculty = 'College of Graduate Studies';
        $faculty->campus_id = $campusO->campus_id;
        $faculty->save();

        $department = new Department;
        $department->department = 'Graduate Studies';
        $department->faculty_id = $faculty->faculty_id;
        $department->save();

    }
}
