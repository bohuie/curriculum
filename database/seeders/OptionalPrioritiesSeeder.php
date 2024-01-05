<?php

namespace Database\Seeders;

use App\Models\OptionalPriorities;
use App\Models\OptionalPrioritiesSubdescription;
use App\Models\OptionalPriorityCategories;
use App\Models\OptionalPrioritySubcategories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionalPrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('optional_priorities')->truncate();
        DB::table('optional_priority_subcategories')->truncate();
        DB::table('optional_priority_categories')->truncate();
        //start with categories. there are 2
        $opcat1 = new OptionalPriorityCategories;
        $opcat1->cat_id = 1;
        $opcat1->cat_name = 'Ministry of Advanced Education and Skills Training';
        $opcat1->save();

        $opcat1 = new OptionalPriorityCategories;
        $opcat1->cat_id = 2;
        $opcat1->cat_name = 'UBC Strategic Priorities';
        $opcat1->save();

        //now subcategories. 6 total, (2,4)
        $osc = new OptionalPrioritySubcategories;
        $osc->subcat_id = 1;
        $osc->cat_id = 1;
        $osc->subcat_name = "<a href=\"https://www2.gov.bc.ca/gov/content/education-training/post-secondary-education/institution-resources-administration/mandate-letters\" target=\"_blank\">UBC's Mandate by the Ministry</a>";
        $osc->subcat_desc = "UBC's mandate letter outlines the below priorities, as they relate to curriculum:";
        $osc->subcat_postamble = '';
        $osc->save();

        $osc = new OptionalPrioritySubcategories;
        $osc->subcat_id = 2;
        $osc->cat_id = 1;
        $osc->subcat_name = "<a href=\"https://www.workbc.ca/research-labour-market/bcs-labour-market-outlook-2022-edition\" target=\"_blank\">BC's Labour Market: Top skills in Demand</a>";
        $osc->subcat_desc = "BC's tops skills in demand,as forecasted to the year 2032 by the <a href=\"https://www.workbc.ca/research-labour-market/bcs-labour-market-outlook-2022-edition\"></i> BC Labour Market Outlook (page 28)</a>
                                , are the following:";
        $osc->subcat_postamble = 'Additionally, BC expects <a href="https://www.workbc.ca/Labour-Market-Industry/Jobs-in-Demand/High-Demand-Occupations.aspx"'
                                .'target="_blank"><i class="bi bi-box-arrow-up-right"></i> these occupations to be of "High Opportunity"</a> in the province. 
                                Does your course/program align with a High Opportunity Occupation in BC ?
                                <select id="highOpportunity" class="highOpportunity"><option value="1">Yes</option> <option value="0">No</option></select>';
        $osc->save();

        $osc = new OptionalPrioritySubcategories;
        $osc->subcat_id = 3;
        $osc->cat_id = 2;
        $osc->subcat_name = '<a href="https://strategicplan.ubc.ca/" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Shaping UBCs Next Century</a>';
        $osc->subcat_desc = '';
        $osc->subcat_postamble = '';
        $osc->save();

        $osc = new OptionalPrioritySubcategories;
        $osc->subcat_id = 4;
        $osc->cat_id = 2;
        $osc->subcat_name = '<a href="https://okmain.cms.ok.ubc.ca/wp-content/uploads/sites/26/2019/02/UBCO-Outlook-2040.pdf" target="_blank"><i class="bi bi-box-arrow-up-right"></i>
                                        UBC Okanagan 2040 Outlook</a>';
        $osc->subcat_desc = '';
        $osc->subcat_postamble = '';
        $osc->save();

        $osc = new OptionalPrioritySubcategories;
        $osc->subcat_id = 5;
        $osc->cat_id = 2;
        $osc->subcat_name = "<a href=\"https://isp.ubc.ca/implementation/the-action-plan/\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i>
                                        UBC's Indigenous Strategic Plan (2020)</a>";
        $osc->subcat_desc = '';
        $osc->subcat_postamble = '';
        $osc->save();

        $osc = new OptionalPrioritySubcategories;
        $osc->subcat_id = 6;
        $osc->cat_id = 2;
        $osc->subcat_name = "<a href=\"https://bog3.sites.olt.ubc.ca/files/2021/01/4_2021.02_Climate-Emergency-Engagement.pdf\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> UBC's Climate Priorities</a>";
        $osc->subcat_desc = "The <a href=\"https://bog3.sites.olt.ubc.ca/files/2021/01/4_2021.02_Climate-Emergency-Engagement.pdf\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> UBC's Climate Emergency Engagement Report and Recommendations (2021)</a> set out the below curricular examples.
                                Programs are encouraged to take these and/or other initiatives that align with the report:";
        $osc->subcat_postamble = '';
        $osc->save();

        $osc = new OptionalPrioritySubcategories;
        $osc->subcat_id = 7;
        $osc->cat_id = 2;
        $osc->subcat_name = '<a href="https://health.ubc.ca/sites/health.ubc.ca/files/documents/UBCHealth_StrategicPlan_2021_2026_Printable.pdf" target="_blank">UBC Health Strategic Plan 2021-2026 <i class="bi bi-box-arrow-up-right"></i></a>"';
        $osc->subcat_desc = '';
        $osc->subcat_postamble = '';
        $osc->save();

        $osc = new OptionalPrioritySubcategories;
        $osc->subcat_id = 10;
        $osc->cat_id = 2;
        $osc->subcat_name = '<a href="https://equity.ubc.ca/about/inclusion-action-plan" target="_blank"><i class="bi bi-box-arrow-up-right"></i>
                                        UBC’s Inclusion Action Plan</a>';
        $osc->subcat_desc = 'The strategic plan defines inclusion as “a commitment to access, success, and representation of historically underserved, marginalized, or excluded populations”. To operationalize the inclusion theme of the strategic plan, UBC has developed an Inclusion Action Plan (IAP).';
        $osc->subcat_postamble = '';
        $osc->save();

        $osc = new OptionalPrioritySubcategories;
        $osc->subcat_id = 11;
        $osc->cat_id = 2;
        $osc->subcat_name = '<a href="https://wellbeing.ubc.ca/framework" target="_blank"><i class="bi bi-box-arrow-up-right"></i>
                                        Wellbeing Strategic Framework</a>';
        $osc->subcat_desc = '';
        $osc->subcat_postamble = '';
        $osc->save();

        // priorities themselves

        // UBC Mandate 2020
        $opp = new OptionalPriorities;
        $opp->op_id = 1;
        $opp->subcat_id = 1;
        $opp->optional_priority = 'Incorporation of the Declaration on the Rights of Indigenous Peoples Act and Calls to Action of the Truth and Reconciliation Commission
                                <a href="http://trc.ca/assets/pdf/Calls_to_Action_English2.pdf" target="_blank">( <i class="bi bi-box-arrow-up-right"></i> More 
                                Information can be found here)</a>';
        $opp->year = 2020;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 2;
        $opp->subcat_id = 1;
        $opp->optional_priority = "Align with CleanBC's plan to a protect our communities towards a more sustainable future
                                <a href=\"https://cleanbc.gov.bc.ca/\" target=\"_blank\">( <i class=\"bi bi-box-arrow-up-right\"></i> 
                                More Information can be found here)</a>";
        $opp->year = 2020;

        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 3;
        $opp->subcat_id = 1;
        $opp->optional_priority = 'Advancing and supporting open learning resources';
        $opp->year = 2020;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 4;
        $opp->subcat_id = 1;
        $opp->optional_priority = 'Offer programming aligned with high opportunity and priority occupations (such as trades, technology, early childhood educators and health)';
        $opp->year = 2020;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 5;
        $opp->subcat_id = 1;
        $opp->optional_priority = 'Embed more co-op and work-integrated learning opportunities';
        $opp->year = 2020;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 6;
        $opp->subcat_id = 1;
        $opp->optional_priority = 'Respond to the reskilling needs of British Columbians to support employment and career transitions';
        $opp->year = 2020;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 7;
        $opp->subcat_id = 1;
        $opp->optional_priority = "Supporting students' awareness of career planning resources (such as the Labour Market Outlook)"
                                .'<a href="https://www.workbc.ca/getmedia/18214b5d-b338-4bbd-80bf-b04e48a11386/BC_Labour_Market_Outlook_2019.pdf.aspx" 
                                target="_blank">( <i class="bi bi-box-arrow-up-right"></i> More Information can be found here)</a>';
        $opp->year = 2020;
        $opp->save();

        // End UBC Mandate 2020

        $opp = new OptionalPriorities;
        $opp->op_id = 8;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Active Listening';
        $opp->year = 2021;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 9;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Speaking';
        $opp->year = 2021;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 10;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Critical Thinking';
        $opp->year = 2021;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 11;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Reading Comprehension';
        $opp->year = 2021;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 15;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Social Perceptiveness';
        $opp->year = 2021;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 13;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Judgement and Decision Making';
        $opp->year = 2021;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 17;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Writing';
        $opp->year = 2021;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 12;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Monitoring';
        $opp->year = 2021;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 14;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Complex Problem Solving';
        $opp->year = 2021;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 16;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Coordination';
        $opp->year = 2021;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 19;
        $opp->subcat_id = 3;
        $opp->optional_priority = '<a href="https://strategicplan.ubc.ca/strategy-2-inspiring-spaces/" target="_blank">Strategy 2: </a>
                                        Inspiring Spaces: Create welcoming physical and virtual spaces to advance collaboration, innovation and community development.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 28;
        $opp->subcat_id = 3;
        $opp->optional_priority = '<a href="https://strategicplan.ubc.ca/strategy-11-education-renewal/" target="_blank">Strategy 11: </a>
                                        Education Renewal: Facilitate sustained program renewal and improvements in teaching effectiveness.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 29;
        $opp->subcat_id = 3;
        $opp->optional_priority = '<a href="https://strategicplan.ubc.ca/strategy-12-program-redesign/" target="_blank">Strategy 12: </a>
                                        Program Redesign: Reframe undergraduate academic program design in terms of learning outcomes and competencies.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 30;
        $opp->subcat_id = 3;
        $opp->optional_priority = '<a href="https://strategicplan.ubc.ca/strategy-13-practical-learning/" target="_blank">Strategy 13: </a>
                                        Practical Learning: Expand experiential, work-integrated and extended learning opportunities for students, faculty, staff and alumni.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 31;
        $opp->subcat_id = 3;
        $opp->optional_priority = '<a href="https://strategicplan.ubc.ca/strategy-14-interdisciplinary-education/" target="_blank">Strategy 14: </a>
                                        Interdisciplinary Education: Facilitate the development of integrative, problem-focused learning.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 32;
        $opp->subcat_id = 3;
        $opp->optional_priority = '<a href="https://strategicplan.ubc.ca/strategy-15-student-experience/" target="_blank">Strategy 15: </a>
                                        Student Experience: Strengthen undergraduate and graduate student communities and experience.';
        $opp->save();
        $opp = new OptionalPriorities;
        $opp->op_id = 33;
        $opp->subcat_id = 3;
        $opp->optional_priority = '<a href="https://strategicplan.ubc.ca/strategy-16-public-relevance/" target="_blank">Strategy 16: </a>
                                        Public Relevance: Deepen the relevance and public impact of UBC research and education.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 34;
        $opp->subcat_id = 3;
        $opp->optional_priority = '<a href="https://strategicplan.ubc.ca/strategy-17-indigenous-engagement/" target="_blank">Strategy 17: </a>
                                        Indigenous Engagement: Support the objectives and actions of the renewed Indigenous Strategic Plan.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 35;
        $opp->subcat_id = 3;
        $opp->optional_priority = '<a href="https://strategicplan.ubc.ca/strategy-18-alumni-engagement/" target="_blank">Strategy 18: </a>
                                        Alumni Engagement: Reach, inspire and engage alumni through lifelong enrichment, consistent with the alumniUBC strategic plan,
                                        <a href="https://www.alumni.ubc.ca/about/strategic-plan/" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Connecting Forward.</a>';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 37;
        $opp->subcat_id = 3;
        $opp->optional_priority = '<a href="https://strategicplan.ubc.ca/strategy-20-co-ordinated-engagement/" target="_blank">Strategy 20: </a>
                                        Co-ordinated Engagement: Co-create with communities the principles and effective practices of engagement, and establish supporting infrastructure.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 38;
        $opp->subcat_id = 4;
        $opp->optional_priority = 'Continuing education programs';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 39;
        $opp->subcat_id = 4;
        $opp->optional_priority = 'Offer hybrid pedagogies';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 40;
        $opp->subcat_id = 4;
        $opp->optional_priority = 'Professional programs in health and technology';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 41;
        $opp->subcat_id = 4;
        $opp->optional_priority = 'Increase graduate student training';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 42;
        $opp->subcat_id = 4;
        $opp->optional_priority = 'Leverage new academic and/or research space';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 43;
        $opp->subcat_id = 4;
        $opp->optional_priority = 'Increased community engagement';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 44;
        $opp->subcat_id = 5;
        $opp->optional_priority = 'Integration of Indigenous histories, experiences, worldviews and knowledge systems';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 45;
        $opp->subcat_id = 5;
        $opp->optional_priority = 'Inclusion of substantive content that explores histories and identifies how Indigenous issues intersect with the field of study';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 46;
        $opp->subcat_id = 5;
        $opp->optional_priority = 'Inclusion of Indigenous people for the development and offering of the curriculum';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 47;
        $opp->subcat_id = 5;
        $opp->optional_priority = 'Continue to partner with Indigenous communities locally and globally';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 48;
        $opp->subcat_id = 6;
        $opp->optional_priority = 'Climate justice education';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 49;
        $opp->subcat_id = 6;
        $opp->optional_priority = 'Climate research';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 50;
        $opp->subcat_id = 6;
        $opp->optional_priority = 'Content on Indigenous rights, content, history, and culture';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 51;
        $opp->subcat_id = 6;
        $opp->optional_priority = 'Environmental and sustainability education';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 52;
        $opp->subcat_id = 6;
        $opp->optional_priority = 'Content from Indigenous scholars and communities and/or equity-seeking and marginalized groups';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 53;
        $opp->subcat_id = 6;
        $opp->optional_priority = 'Inclusion of de-colonial approaches to science through Indigenous and community traditional knowledge and "authorship"';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 54;
        $opp->subcat_id = 6;
        $opp->optional_priority = 'Knowledge, awareness and skills related to the relationship between climate change and food systems';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 55;
        $opp->subcat_id = 6;
        $opp->optional_priority = 'Climate-related mental health content';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 56;
        $opp->subcat_id = 6;
        $opp->optional_priority = 'Applied learning opportunities grounded in the personal, local and regional community (e.g. flood and wildfire impacted communities in BC)';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 57;
        $opp->subcat_id = 1;
        $opp->optional_priority = 'Incorporation of the Declaration on the Rights of Indigenous Peoples Act and Calls to Action of the Truth and Reconciliation Commission <a href="http://trc.ca/assets/pdf/Calls_to_Action_English2.pdf" target="_blank">( <i class="bi bi-box-arrow-up-right"></i> More Information can be found here)</a>';
        $opp->year = '2021';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 58;
        $opp->subcat_id = 1;
        $opp->optional_priority = "Align with CleanBC's plan to a protect our communities towards a more sustainable future<a href='https://cleanbc.gov.bc.ca/' target='_blank'>( <i class='bi bi-box-arrow-up-right'></i> More Information can be found here)</a>";
        $opp->year = '2021';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 59;
        $opp->subcat_id = 1;
        $opp->optional_priority = 'Work with communities, employers and industry to implement skills training towards career opportunities in BC <a href="https://students.ok.ubc.ca/career-experience/faculty-workshops/" target="_blank">( <i class="bi bi-box-arrow-up-right"></i> More Information can be found here)</a>';
        $opp->year = '2021';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 60;
        $opp->subcat_id = 1;
        $opp->optional_priority = 'Offer programming aligned with <a href="https://students.ok.ubc.ca/career-experience/faculty-workshops/" target="_blank"><i class="bi bi-box-arrow-up-right"></i> high opportunity and priority occupations</a> (such as trades, technology, early childhood educators and health)';
        $opp->year = '2021';
        $opp->save();

        $op_subDesc = new OptionalPrioritiesSubdescription;
        $op_subDesc->description = '<a href="https://health.ubc.ca/collaborative-health-education" target="_blank">Collaborative Health Education <i class="bi bi-box-arrow-up-right"></i></a> integrating interprofessional and collaborative health education to shape the workforce of tomorrow';
        $op_subDesc->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 61;
        $opp->subcat_id = 7;
        $opp->optional_priority = 'Objective 1: Education innovation<br>Renew classroom-based and develop practice-based curricula, reinforced by scholarship in health education.';
        $opp->op_subdesc = $op_subDesc->op_subdesc;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 62;
        $opp->subcat_id = 7;
        $opp->optional_priority = 'Objective 2: Expanded placements Help UBC’s health and health-related programs develop models that build placement capacity, as well as promote collaborative placements across British Columbia.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 63;
        $opp->subcat_id = 7;
        $opp->optional_priority = 'Objective 3: Education ecosystem in team-based care Support the establishment of a team-based clinic on campus and connect existing campus and other clinics as learner-informed models for team-based education.';
        $opp->save();

        $op_subDesc = new OptionalPrioritiesSubdescription;
        $op_subDesc->description = '<a href="https://health.ubc.ca/interdisciplinary-health-research" target="_blank">Interdisciplinary Health Research <i class="bi bi-box-arrow-up-right"></i></a> catalyzing interdisciplinary collaborations in health research';
        $op_subDesc->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 64;
        $opp->subcat_id = 7;
        $opp->optional_priority = 'Objective 1: Community of practice Foster an active community of health research practice and mentorship, supporting and connecting health scholars and students.';
        $opp->op_subdesc = $op_subDesc->op_subdesc;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 65;
        $opp->subcat_id = 7;
        $opp->optional_priority = 'Objective 2: New research collaborations Stimulate and support health research collaborations.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 66;
        $opp->subcat_id = 7;
        $opp->optional_priority = 'Objective 3: Data infrastructure Contribute to provincial efforts to develop an integrated health research data infrastructure.';
        $opp->save();

        $op_subDesc = new OptionalPrioritiesSubdescription;
        $op_subDesc->description = '<a href="https://health.ubc.ca/health-systems" target="_blank">Health Systems <i class="bi bi-box-arrow-up-right"></i></a> advocating for and helping to develop capacity for positive change in health';
        $op_subDesc->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 67;
        $opp->subcat_id = 7;
        $opp->optional_priority = 'Objective 1: Public and patient engagement Expand engagement to advance diverse community priorities, enhance learner experiences, and align research activities.';
        $opp->op_subdesc = $op_subDesc->op_subdesc;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 68;
        $opp->subcat_id = 7;
        $opp->optional_priority = 'Objective 2: Coordinated responses Coalesce experts and efforts to further collaborative health education and interdisciplinary research in response to pressing health challenges.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 69;
        $opp->subcat_id = 7;
        $opp->optional_priority = 'Objective 3: Evidence for new approaches Help align university and sector activities to establish evidence for new approaches to health.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 70;
        $opp->subcat_id = 1;
        $opp->optional_priority = 'Incorporation of the Declaration on the Rights of Indigenous Peoples Act and Calls to Action of the Truth and Reconciliation Commission <a href="http://trc.ca/assets/pdf/Calls_to_Action_English2.pdf" target="_blank">( <i class="bi bi-box-arrow-up-right"></i> More Information can be found here)</a>';
        $opp->year = '2022';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 71;
        $opp->subcat_id = 1;
        $opp->optional_priority = "Align with CleanBC's plan to a protect our communities towards a more sustainable future<a href='https://cleanbc.gov.bc.ca/' target='_blank'>( <i class='bi bi-box-arrow-up-right'></i> More Information can be found here)</a>";
        $opp->year = '2022';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 72;
        $opp->subcat_id = 1;
        $opp->optional_priority = 'Work with communities, employers and industry to implement skills training towards career opportunities in BC <a href="https://students.ok.ubc.ca/career-experience/faculty-workshops/" target="_blank">( <i class="bi bi-box-arrow-up-right"></i> More Information can be found here)</a>';
        $opp->year = '2022';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 73;
        $opp->subcat_id = 1;
        $opp->optional_priority = 'Offer programming aligned with <a href="https://students.ok.ubc.ca/career-experience/faculty-workshops/" target="_blank"><i class="bi bi-box-arrow-up-right"></i> high opportunity and priority occupations</a> (such as trades, technology, early childhood educators and health)';
        $opp->year = '2022';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 74;
        $opp->subcat_id = 10;
        $opp->optional_priority = 'Degree Requirements: Incorporate equity, diversity, and inclusion skills and competencies info degree requirements.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 75;
        $opp->subcat_id = 10;
        $opp->optional_priority = 'Dialogue for Engagement: Facilitate and provide opportunities for dialogue and conversation around sensitive topics at UBC and beyond. Build conflict engagement skills and practices among all members of UBC’s community to equip people for working across differences.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 76;
        $opp->subcat_id = 10;
        $opp->optional_priority = 'EDI Curriculum and Program Requirements: Embed equity and inclusion education into curriculum and program requirements for all students that incorporates intercultural understanding, empathy and mutual respect.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 77;
        $opp->subcat_id = 10;
        $opp->optional_priority = 'Student Learning: Review and improve mechanisms to ensure that student perspectives on the inclusiveness of their learning experiences are integrated into the improvement of teaching.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 78;
        $opp->subcat_id = 11;
        $opp->optional_priority = 'Mental Health & Resilience: Enhance mental health literacy to create a supportive campus culture and equipping our community with the skills needed to live well and improve their health, by creating environments that support them.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 79;
        $opp->subcat_id = 11;
        $opp->optional_priority = 'Social Connection: Encourage everyone to work, learn, collaborate, and build connections in ways that are meaningful to them. Strong and lasting social and friendship connections are critical to our wellbeing.';
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 80;
        $opp->subcat_id = 11;
        $opp->optional_priority = 'Physical Activity: Produce accessible programs, policies, and facilities that support physical activity for all ages and abilities.';
        $opp->save();

        $op_subDesc = new OptionalPrioritiesSubdescription;
        $op_subDesc->description = "Top skills of the future (a skill is the ability to perform tasks based on one's knowledge, learning or practice):";
        $op_subDesc->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 81;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Active Listening: Giving full attention to what other
        people are saying, taking time to understand the points
        being made, asking questions as appropriate and not
        interrupting at inappropriate times.';
        $opp->year = 2022;
        $opp->op_subdesc = $op_subDesc->op_subdesc;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 82;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Speaking: Talking to others to share
        information effectively.';
        $opp->year = 2022;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 83;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Reading Comprehension: Understanding written
        sentences and paragraphs in work-related documents.';
        $opp->year = 2022;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 84;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Critical Thinking: Using logic and reasoning to identify
        the strengths and weaknesses of alternative solutions,
        conclusions or approaches to problems.';
        $opp->year = 2022;
        $opp->save();

        $op_subDesc = new OptionalPrioritiesSubdescription;
        $op_subDesc->description = 'Top competencies of the future (competency is the ability to apply skills and knowledge to perform complex tasks well and to adapt easily to the activities of a variety of jobs):';
        $op_subDesc->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 85;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Social Perceptiveness: Being aware of others’
        reactions and understanding why they react as they do.';
        $opp->year = 2022;
        $opp->op_subdesc = $op_subDesc->op_subdesc;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 86;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Judgement and Decision Making: Considering
        the relative costs and benefits of potential actions to
        choose the most appropriate one.';
        $opp->year = 2022;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 87;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Writing: Communicating effectively through composing
        text as appropriate for the needs of the audience.';
        $opp->year = 2022;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 88;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Monitoring: Keeping track of and assessing the
        performance of oneself, other individuals or organizations
        to make improvements or take corrective action.';
        $opp->year = 2022;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 89;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Complex Problem Solving: Being able to solve novel,
        ill-defined problems in complex, real-world settings.';
        $opp->year = 2022;
        $opp->save();

        $opp = new OptionalPriorities;
        $opp->op_id = 90;
        $opp->subcat_id = 2;
        $opp->optional_priority = 'Co-ordination: Organizing the different elements of
        a complex initiative or activity so as to enable items,
        tasks and people to work together effectively.';
        $opp->year = 2022;
        $opp->save();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
