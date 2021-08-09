<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Optional_priorities;
use App\Models\CourseOptional_priorities;
use App\Models\Optional_priority_subcategories;
use App\Models\Optional_priority_categories;

class OptionalPrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //start with categories. there are 2
        $opcat1 = new Optional_priority_categories;
        $opcat1->cat_id = 1;
        $opcat1->cat_name = "Ministry of Advanced Education and Skills Training";
        $opcat1->save();
        
        $opcat1 = new Optional_priority_categories;
        $opcat1->cat_id = 2;
        $opcat1->cat_name = "UBC Strategic Priorities";
        $opcat1->save();
        
        //now subcategories. 6 total, (2,4)
        $osc = new Optional_priority_subcategories;
        $osc->subcat_id = 1;
        $osc->cat_id = 1;
        $osc->subcat_name = "UBC's Mandate by the Ministry";
        $osc->subcat_desc = "UBC's mandate letter (see <a href=\"https://www2.gov.bc.ca/gov/content/education-training/post-secondary-education/institution-resources-
                            administration/mandate-letters\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> mandate letter here </a>)
                            calls for the below, as they relate to curriculum:";
        $osc->subcat_postamble = "";
        $osc->save();
        
        $osc = new Optional_priority_subcategories;
        $osc->subcat_id = 2;
        $osc->cat_id = 1;
        $osc->subcat_name = "BC's Labour Market: Top skills in Demand";
        $osc->subcat_desc = "BC's tops skills in demand,as forecasted to the year 2029 by the <a href=\"https://www.workbc.ca/getmedia/18214b5d-b338-4bbd-80bf-b04e48a11386/BC_
                            Labour_Market_Outlook_2019.pdf.aspx\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> BC Labour Market Outlook (page 46)</a>
                            , are the following:";
        $osc->subcat_postamble = "Additionally, BC expects <a href=\"https://www.workbc.ca/Labour-Market-Industry/Jobs-in-Demand/High-Demand-Occupations.aspx\""
                                . "target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> these occupations to be of \"High Opportunity\"</a> in the province. 
                                Does your course/program align with a High Opportunity Occupation in BC ?
                                <select id=\"highOpportunity\" class=\"highOpportunity\"><option value=\"1\">Yes</option> <option value=\"0\">No</option></select>";
        $osc->save();
        
        $osc = new Optional_priority_subcategories;
        $osc->subcat_id = 3;
        $osc->cat_id = 2;
        $osc->subcat_name = "<a href=\"https://strategicplan.ubc.ca/\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> Shaping UBCs next Century</a>";
        $osc->subcat_desc = "";
        $osc->subcat_postamble = "";
        $osc->save();
        
        $osc = new Optional_priority_subcategories;
        $osc->subcat_id = 4;
        $osc->cat_id = 2;
        $osc->subcat_name = "<a href=\"https://okmain.cms.ok.ubc.ca/wp-content/uploads/sites/26/2019/02/UBCO-Outlook-2040.pdf\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i>
                                            UBC Okanagan 2040 Outlook</a>";
        $osc->subcat_desc = "";
        $osc->subcat_postamble = "";
        $osc->save();
        
        $osc = new Optional_priority_subcategories;
        $osc->subcat_id = 5;
        $osc->cat_id = 2;
        $osc->subcat_name = "<a href=\"https://aboriginal-2018.sites.olt.ubc.ca/files/2020/09/UBC.ISP_C2V13.1_Spreads_Sept1.pdf\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i>
                                        UBC's Indigenous Strategic Plan (2020)</a>";
        $osc->subcat_desc = "";
        $osc->subcat_postamble = "";
        $osc->save();
        
        $osc = new Optional_priority_subcategories;
        $osc->subcat_id = 6;
        $osc->cat_id = 2;
        $osc->subcat_name = "<a href=\"https://bog3.sites.olt.ubc.ca/files/2021/01/4_2021.02_Climate-Emergency-Engagement.pdf\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> UBC's Climate Priorities</a>";
        $osc->subcat_desc = "The <a href=\"https://bog3.sites.olt.ubc.ca/files/2021/01/4_2021.02_Climate-Emergency-Engagement.pdf\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> UBC's Climate Emergency Engagement Report and Recommendations (2021)</a> set out the below curricular examples.
                                            Programs are encouraged to take these and/or other initiatives that align with the report:";
        $osc->subcat_postamble = "";
        $osc->save();
        
        
        // priorities themselves
        $opp = new Optional_priorities;
        $opp->op_id = 1;
        $opp->subcat_id = 1;
        $opp->optional_priority = "Incorporation of the Declaration on the Rights of Indigenous Peoples Act and Calls to Action of the Truth and Reconciliation Commission
                                  <a href=\"http://trc.ca/assets/pdf/Calls_to_Action_English2.pdf\" target=\"_blank\">( <i class=\"bi bi-box-arrow-up-right\"></i> More 
                                  Information can be found here)</a>";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 2;
        $opp->subcat_id = 1;
        $opp->optional_priority = "Align with CleanBC's plan to a protect our communities towards a more sustainable future
                                   <a href=\"https://cleanbc.gov.bc.ca/\" target=\"_blank\">( <i class=\"bi bi-box-arrow-up-right\"></i> 
                                   More Information can be found here)</a>";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 3;
        $opp->subcat_id = 1;
        $opp->optional_priority = "Advancing and supporting open learning resources";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 4;
        $opp->subcat_id = 1;
        $opp->optional_priority = "Offer programming aligned with high opportunity and priority occupations (such as trades, technology, early childhood educators and health)";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 5;
        $opp->subcat_id = 1;
        $opp->optional_priority = "Embed more co-op and work-integrated learning opportunities";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 6;
        $opp->subcat_id = 1;
        $opp->optional_priority = "Respond to the reskilling needs of British Columbians to support employment and career transitions";
        $opp->save();
       
        $opp = new Optional_priorities;
        $opp->op_id = 7;
        $opp->subcat_id = 1;
        $opp->optional_priority = "Supporting students' awareness of career planning resources (such as the Labour Market Outlook)"
                                . "<a href=\"https://www.workbc.ca/getmedia/18214b5d-b338-4bbd-80bf-b04e48a11386/BC_Labour_Market_Outlook_2019.pdf.aspx\" 
                                target=\"_blank\">( <i class=\"bi bi-box-arrow-up-right\"></i> More Information can be found here)</a>";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 8;
        $opp->subcat_id = 2;
        $opp->optional_priority = "Active Listening";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 9;
        $opp->subcat_id = 2;
        $opp->optional_priority = "Speaking";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 10;
        $opp->subcat_id = 2;
        $opp->optional_priority = "Reading Comprehension";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 11;
        $opp->subcat_id = 2;
        $opp->optional_priority = "Critical Thinking";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 12;
        $opp->subcat_id = 2;
        $opp->optional_priority = "Social Perceptiveness";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 13;
        $opp->subcat_id = 2;
        $opp->optional_priority = "Judgement and Decision Making";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 14;
        $opp->subcat_id = 2;
        $opp->optional_priority = "Writing";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 15;
        $opp->subcat_id = 2;
        $opp->optional_priority = "Monitoring";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 16;
        $opp->subcat_id = 2;
        $opp->optional_priority = "Complex Problem Solving";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 17;
        $opp->subcat_id = 2;
        $opp->optional_priority = "Coordination";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 18;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-1-great-people/\" target=\"_blank\">Strategy 1: </a>
                                  Great People: Attract, engage and retain a diverse global community of outstanding students, faculty and staff.";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 19;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-2-inspiring-spaces/\" target=\"_blank\">Strategy 2: </a>
                                  Inspiring Spaces: Create welcoming physical and virtual spaces to advance collaboration, innovation and community development.";
        $opp->save();
        
         $opp = new Optional_priorities;
        $opp->op_id = 20;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-3-thriving-communities/\" target=\"_blank\">Strategy 3: </a>Thriving Communities: 
                                   Support the ongoing development of sustainable, healthy and connected campuses and communities, consistent with the 20-Year Sustainability 
                                   Strategy and the developing Wellbeing Strategy.";
        $opp->save();
        
         $opp = new Optional_priorities;
        $opp->op_id = 21;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-4-inclusive-excellence/\" target=\"_blank\">Strategy 4: </a>
                                  Inclusive Excellence: Cultivate a diverse community that creates and sustains equitable and inclusive campuses.";
        $opp->save();
        
         $opp = new Optional_priorities;
        $opp->op_id = 22;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-5-systems-renewal/\" target=\"_blank\">Strategy 5: </a>Systems Renewal:
                                  Transform university-level systems and processes to facilitate collaboration, innovation and agility.";
        $opp->save();
        
         $opp = new Optional_priorities;
        $opp->op_id = 23;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-6-collaborative-clusters/\" target=\"_blank\">Strategy 6: </a>
                                                    Collaborative Clusters: Enable interdisciplinary clusters of research excellence in pursuit of societal impact.";
        $opp->save();
        
         $opp = new Optional_priorities;
        $opp->op_id = 24;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-7-research-support/\" target=\"_blank\">Strategy 7: </a>
                                  Research Support: Strengthen shared infrastructure and resources to support research excellence.";
        $opp->save();
        
         $opp = new Optional_priorities;
        $opp->op_id = 25;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-8-student-research/\" target=\"_blank\">Strategy 8: </a>
                                  Student Research: Broaden access to, and enhance, student research experiences.";
        $opp->save();
        
         $opp = new Optional_priorities;
        $opp->op_id = 26;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-9-knowledge-exchange/\" target=\"_blank\">Strategy 9: </a>
                                  Knowledge Exchange: Improve the ecosystem that supports the translation of research into action.";
        $opp->save();
        
         $opp = new Optional_priorities;
        $opp->op_id = 27;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-10-research-culture/\" target=\"_blank\">Strategy 10: </a>
                                  Research Culture: Foster a strong and diverse research culture that embraces the highest standards of integrity, collegiality and service.";
        $opp->save();
        
         $opp = new Optional_priorities;
        $opp->op_id = 28;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-11-education-renewal/\" target=\"_blank\">Strategy 11: </a>
                                  Education Renewal: Facilitate sustained program renewal and improvements in teaching effectiveness.";
        $opp->save();
        
         $opp = new Optional_priorities;
        $opp->op_id = 29;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-12-program-redesign/\" target=\"_blank\">Strategy 12: </a>
                                  Program Redesign: Reframe undergraduate academic program design in terms of learning outcomes and competencies.";
        $opp->save();
        
         $opp = new Optional_priorities;
        $opp->op_id = 30;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-13-practical-learning/\" target=\"_blank\">Strategy 13: </a>
                                  Practical Learning: Expand experiential, work-integrated and extended learning opportunities for students, faculty, staff and alumni.";
        $opp->save();
        
         $opp = new Optional_priorities;
        $opp->op_id = 31;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-14-interdisciplinary-education/\" target=\"_blank\">Strategy 14: </a>
                                  Interdisciplinary Education: Facilitate the development of integrative, problem-focused learning.";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 32;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-15-student-experience/\" target=\"_blank\">Strategy 15: </a>
                                  Student Experience: Strengthen undergraduate and graduate student communities and experience.";
        $opp->save();
        $opp = new Optional_priorities;
        $opp->op_id = 33;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-16-public-relevance/\" target=\"_blank\">Strategy 16: </a>
                                  Public Relevance: Deepen the relevance and public impact of UBC research and education.";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 34;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-17-indigenous-engagement/\" target=\"_blank\">Strategy 17: </a>
                                  Indigenous Engagement: Support the objectives and actions of the renewed Indigenous Strategic Plan.";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 35;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-18-alumni-engagement/\" target=\"_blank\">Strategy 18: </a>
                                  Alumni Engagement: Reach, inspire and engage alumni through lifelong enrichment, consistent with the alumniUBC strategic plan,
                                  <a href=\"https://www.alumni.ubc.ca/about/strategic-plan/\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> Connecting Forward.</a>";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 36;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-19-global-networks/\" target=\"_blank\">Strategy 19: </a>
                                  Global Networks: Build and sustain strategic global networks, notably around the Pacific Rim, that enhance impact.";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 37;
        $opp->subcat_id = 3;
        $opp->optional_priority = "<a href=\"https://strategicplan.ubc.ca/strategy-20-co-ordinated-engagement/\" target=\"_blank\">Strategy 20: </a>
                                  Co-ordinated Engagement: Co-create with communities the principles and effective practices of engagement, and establish supporting infrastructure.";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 38;
        $opp->subcat_id = 4;
        $opp->optional_priority = "Continuing education programs";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 39;
        $opp->subcat_id = 4;
        $opp->optional_priority = "Offer hybrid pedagogies";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 40;
        $opp->subcat_id = 4;
        $opp->optional_priority = "Professional programs in health and technology";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 41;
        $opp->subcat_id = 4;
        $opp->optional_priority = "Increase graduate student training";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 42;
        $opp->subcat_id = 4;
        $opp->optional_priority = "Leverage new academic and/or research space";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 43;
        $opp->subcat_id = 4;
        $opp->optional_priority = "Increased community engagement";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 44;
        $opp->subcat_id = 5;
        $opp->optional_priority = "Integration of Indigenous histories, experiences, worldviews and knowledge systems";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 45;
        $opp->subcat_id = 5;
        $opp->optional_priority = "Inclusion of substantive content that explores histories and identifies how Indigenous issues intersect with the field of study";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 46;
        $opp->subcat_id = 5;
        $opp->optional_priority = "Inclusion of Indigenous people for the development and offering of the curriculum";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 47;
        $opp->subcat_id = 5;
        $opp->optional_priority = "Continue to partner with Indigenous communities locally and globally";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 48;
        $opp->subcat_id = 6;
        $opp->optional_priority = "Climate justice education";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 49;
        $opp->subcat_id = 6;
        $opp->optional_priority = "Climate research";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 50;
        $opp->subcat_id = 6;
        $opp->optional_priority = "Content on Indigenous rights, content, history, and culture";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 51;
        $opp->subcat_id = 6;
        $opp->optional_priority = "Environmental and sustainability education";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 52;
        $opp->subcat_id = 6;
        $opp->optional_priority = "Content from Indigenous scholars and communities and/or equity-seeking and marginalized groups";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 53;
        $opp->subcat_id = 6;
        $opp->optional_priority = "Inclusion of de-colonial approaches to science through Indigenous and community traditional knowledge and \"authorship\"";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 54;
        $opp->subcat_id = 6;
        $opp->optional_priority = "Knowledge, awareness and skills related to the relationship between climate change and food systems";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 55;
        $opp->subcat_id = 6;
        $opp->optional_priority = "Climate-related mental health content";
        $opp->save();
        
        $opp = new Optional_priorities;
        $opp->op_id = 56;
        $opp->subcat_id = 6;
        $opp->optional_priority = "Applied learning opportunities grounded in the personal, local and regional community (e.g. flood and wildfire impacted communities in BC)";
        $opp->save();
    }
}
