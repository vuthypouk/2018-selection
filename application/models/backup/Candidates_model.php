<?php
/**
 * This model contains the business logic and manages the persistence of users and roles
 * @copyright  Copyright (c) 2018 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/skeleton
 * @since      1.0.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This model contains the business logic and manages the persistence of users and roles
 * It is also used by the session controller for the authentication.
 */
class Candidates_model extends CI_Model {

    /**
     * Default constructor
     */
    public function __construct() {

    }

    // function get all candidates 
    public function showAllCandidates()
    {
        $id = $this->uri->segment(3);
    	$this->db->select("skeleton_tbl_candidates.can_id, concat(skeleton_tbl_candidates.can_firstname,' ',skeleton_tbl_candidates.can_lastname) AS can_name,can_gender,can_global_grade,skeleton_tbl_provinces.pro_name AS province");
    	$this->db->from('skeleton_tbl_candidates');   
    	$this->db->join('skeleton_tbl_provinces', 'skeleton_tbl_provinces.pro_id = skeleton_tbl_candidates.pro_id','INNER');
    	$query = $this->db->get(); 
    	if($query->num_rows() > 0){
    		return $query->result();
    		
    	}else{
    		return false;
    	}
    }

    // function to delete candidates
    function deleteCandidate()
    {
        $id = $this->input->get('can_id');
        $this->db->where('can_id', $id);
        $this->db->delete('skeleton_tbl_candidates');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    // function to count all candidates
    public function countCandidates()
    {
        $this->db->select("count(can_id) AS total");
        $this->db->from("skeleton_tbl_candidates");
        $query = $this->db->get();
        // var_dump($query);die("Hello"); 
        if($query->num_rows() > 0){
            return $query->result();   
        }else{
            return false;
        }
    }
    // function to count all selected candidates
    public function countSelectedCandidates()
    {
        $this->db->select("count(can_id) AS totalSelected");
        $this->db->from("skeleton_tbl_candidates");
        $this->db->where("can_global_grade !='Failed' ");
        $query = $this->db->get(); 
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }
    // function to count male selected candidates
    public function countMale()
    {
        $this->db->select("COUNT(skeleton_tbl_candidates.can_gender) AS countMale");
        $this->db->from('skeleton_tbl_candidates');
        $this->db->where('skeleton_tbl_candidates.can_gender', 'Male');
        $this->db->where_not_in('skeleton_tbl_candidates.can_global_grade','Failed');
        $count = $this->db->get(); 
        return $count->result();
    }
    // function to count female candidates
    public function countFemale()
    {
        $this->db->select("COUNT(skeleton_tbl_candidates.can_gender) AS countFemale");
        $this->db->from('skeleton_tbl_candidates');
        $this->db->where('skeleton_tbl_candidates.can_gender', 'Female');
        $this->db->where_not_in('skeleton_tbl_candidates.can_global_grade','Failed');
        $count = $this->db->get(); 
        return $count->result();
    }
    // function to count grade A plus selected candidates
    public function getGradeAPlus()
    {
        $this->db->select("COUNT(skeleton_tbl_candidates.can_global_grade) AS GradeAPlus");
        $count =  $this->db->get_where('skeleton_tbl_candidates',array('skeleton_tbl_candidates.can_global_grade'=>'A+'));
        return $count->result();
    }
    // function to count grade A candidates
    public function getGradeA()
    {
        $this->db->select("COUNT(skeleton_tbl_candidates.can_global_grade) AS GradeA");
        $count =  $this->db->get_where('skeleton_tbl_candidates',array('skeleton_tbl_candidates.can_global_grade'=>'A'));
        return $count->result();
    }
    // function to count grade A minus candidates
    public function getGradeAMinus()
    {
        $this->db->select("COUNT(skeleton_tbl_candidates.can_global_grade) AS GradeAMinus");
        $count =  $this->db->get_where('skeleton_tbl_candidates',array('skeleton_tbl_candidates.can_global_grade'=>'A-'));
        return $count->result();
    }
    // function to count grade B plus candidates
    public function getGradeBPlus()
    {
        $this->db->select("COUNT(skeleton_tbl_candidates.can_global_grade) AS GradeBPlus");
        $count =  $this->db->get_where('skeleton_tbl_candidates',array('skeleton_tbl_candidates.can_global_grade'=>'B+'));
        return $count->result();
    }
    // function to count grade B candidates
    public function getGradeB()
    {
        $this->db->select("COUNT(skeleton_tbl_candidates.can_global_grade) AS GradeB");
        $count =  $this->db->get_where('skeleton_tbl_candidates',array('skeleton_tbl_candidates.can_global_grade'=>'B'));
        return $count->result();
    }
    // function to count grade failed candidates
    public function getGradeFailed()
    {
        $this->db->select("COUNT(skeleton_tbl_candidates.can_global_grade) AS GradeFailed");
        $count =  $this->db->get_where('skeleton_tbl_candidates',array('skeleton_tbl_candidates.can_global_grade'=>'Failed'));
        return $count->result();
    }
    // function to count selected candidates from NGO
    public function fromNGO()
    {
        $this->db->select("COUNT(skeleton_tbl_candidates.ngo_id) AS FromNGO");
        $this->db->from("skeleton_tbl_candidates");
        $this->db->join('skeleton_tbl_ngo', 'skeleton_tbl_ngo.ngo_id = skeleton_tbl_candidates.ngo_id','INNER');
        $this->db->where_not_in('skeleton_tbl_ngo.ngo_name','Other');
        $this->db->where_not_in('skeleton_tbl_candidates.can_global_grade','Failed');
        $count = $this->db->get(); 
        return $count->result();
    }
    // function to count selected candidates not from NGO
    public function notFromNGO()
    {
        $this->db->select("COUNT(skeleton_tbl_candidates.ngo_id) AS NotFromNGO");
        $this->db->from("skeleton_tbl_candidates");
        $this->db->join('skeleton_tbl_ngo', 'skeleton_tbl_ngo.ngo_id = skeleton_tbl_candidates.ngo_id','INNER');
        $this->db->where('skeleton_tbl_ngo.ngo_name','Other');
        $this->db->where_not_in('skeleton_tbl_candidates.can_global_grade','Failed');
        $count = $this->db->get(); 
        return $count->result();
    }
    // function to get selected candidates
    public function showSelected()
    {
        $this->db->select("skeleton_tbl_candidates.can_id, concat(skeleton_tbl_candidates.can_firstname,' ',skeleton_tbl_candidates.can_lastname) AS can_name,can_gender,can_global_grade,skeleton_tbl_provinces.pro_name AS province");   
        $this->db->from('skeleton_tbl_candidates');   
        $this->db->join('skeleton_tbl_provinces', 'skeleton_tbl_provinces.pro_id = skeleton_tbl_candidates.pro_id','INNER');
        $this->db->where('skeleton_tbl_candidates.can_global_grade !=',"Failed");
        $query = $this->db->get(); 
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }
    // funciton to count all provinces
    public function countProvinces()
    {
        $this->db->select("count(pro_id) AS totalProvinces");
        $this->db->from("skeleton_tbl_provinces");
        $query = $this->db->get(); 
        if($query->num_rows() > 0){
            return $query->result();  
        }else{
            return false;
        }
    }

    function addStudent($fname,$lname,$gender,$class,$year,$stuid,$province,$tutor)
    {
        $data = array(
            'stu_firstname' => $fname,
            'stu_lastname' => $lname,
            'gender' => $gender,
            'class' => $class,
            'year' => $year,
            'idnumber' => $stuid,
            'province' => $province,
            'userid' => $tutor
        );
        $insert = $this->db->insert('tblstudents',$data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    //function to get all province
    public function getAllProvince()
    {
       $this->db->select("*");
       $this->db->from("skeleton_tbl_provinces");
       $query = $this->db->get(); 
       if($query->num_rows() > 0){
           return $query->result();  
       }else{
           return false;
       }
    }
    //End  
    //function to get NGO
    public function getAllngo()
    {
        $this->db->select("*");
        $this->db->from("skeleton_tbl_ngo");
        $query = $this->db->get(); 
        if($query->num_rows() > 0){
            return $query->result();  
        }else{
            return false;
        }
    }
    //End function 
    public function addCandidate($fname,$lname,$gender,$age,$province,$ngo,$health,$rankClass,$achivement,$pncChoice,$responsibility,$motivate,$communication,$scholarship,$otherChoiceRank,$stuCommite,$parCommite,$globalGrade,$ngoComment,$healthComment)
    {
            $data = array(
                'can_firstname' => $fname,
                'can_lastname' => $lname,
                'can_gender' => $gender,
                'can_age' => $age,
                'can_rank_inclass' => $rankClass,
                'can_healthy' => $health,
                'can_pn_choic_rank' => $pncChoice,
                'can_activity_achivement' => $achivement,
                'can_resposibility' => $responsibility,
                'can_communicate' => $communication,
                'can_other_choice_rank' => $otherChoiceRank,
                'can_pnc_motivation' => $motivate, 
                'can_other_scholaship' => $scholarship,
                'can_student_commit' => $stuCommite,
                'can_parents_commit' => $parCommite,
                'can_global_grade' => $globalGrade,
                'can_ngo_comment' => $ngoComment,
                'can_health_comment' => $healthComment,
                'pro_id' =>$province,
                'ngo_id' =>$ngo
            );
           // the two function below is to prevent the constrain error from foriegn key
            // and the query code is stay between of those function
            $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
            $insert = $this->db->insert('skeleton_tbl_candidates',$data);
            if ($this->db->affected_rows() > 0) {
                $this->db->select("MAX(can_id)");
                $this->db->from("skeleton_tbl_candidates");
                $query = $this->db->get(); 
                if($query->num_rows() > 0){
                    return $query->result();  
                }else{
                    return false;
                }
            } else {
                return false;
            }
            $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
        }
        // function to get the candidate
        public function getCandidate()
        {
            $this->db->select("skeleton_tbl_candidates.can_id, concat(skeleton_tbl_candidates.can_firstname,' ',skeleton_tbl_candidates.can_lastname) AS can_name,can_gender,can_global_grade,skeleton_tbl_provinces.pro_name AS province");   
            $this->db->from('skeleton_tbl_candidates');   
            $this->db->join('skeleton_tbl_provinces', 'skeleton_tbl_provinces.pro_id = skeleton_tbl_candidates.pro_id','INNER');
            $this->db->where('skeleton_tbl_candidates.can_global_grade !=',"$canId");
            $query = $this->db->get(); 
            if($query->num_rows() > 0){
                return $query->result();
            }else{
                return false;
            }
        }
        // get the max id of the candidate
        public function getLastId()
        {
            $this->db->select("MAX(sc.can_id)");
            $this->db->from("skeleton_tbl_candidates");
            $query = $this->db->get(); 
            if($query->num_rows() > 0){
                return $query->result();  
            }else{
                return false;
            }

        }
        // end function get max candidate
    //function call globale grad
    function globle_grade($id){
        $this->db->select('*');
        $this->db->from('skeleton_tbl_candidates');
        $this->db->where('skeleton_tbl_candidates.can_id',$id);
        $query=$this->db->get();
        return $query->result();
    } 
    // function call view candidate detail 
    function view_can($id)
    {
        $this->db->select('*'); 
        $this->db->from('skeleton_tbl_candidates'); 
        $this->db->join('skeleton_tbl_provinces', 'skeleton_tbl_provinces.pro_id = skeleton_tbl_candidates.pro_id');
        $this->db->join('skeleton_tbl_ngo','skeleton_tbl_ngo.ngo_id=
            skeleton_tbl_candidates.ngo_id');
        // $this->db->get_where("skeleton_tbl_candidates",array('can_id'=>$id));
        $this->db->where('skeleton_tbl_candidates.can_id',$id);
        $query=$this->db->get();
        return $query->result();
    } 
    // function call family information
    function view_can_family($id)
    {
        $this->db->select('*'); 
        $this->db->from('skeleton_tbl_family'); 
        $this->db->join('skeleton_tbl_profile', 'skeleton_tbl_profile.p_id=
            skeleton_tbl_family.tbl_profile_p_id','INNER');
        $this->db->where('skeleton_tbl_family.family_id',$id);
        $query=$this->db->get();
        return $query->result();
    }
    // function call family income
    function view_income($id)
    {
        $this->db->select('*');
        $this->db->from('skeleton_tbl_family');
        $this->db->join('skeleton_tbl_income','skeleton_tbl_income.in_id = skeleton_tbl_family.tbl_income_in_id','INNER');
        $this->db->where('skeleton_tbl_family.family_id',$id);
        $query=$this->db->get();
        return $query->result();
    } 
     // function call family exspend
    function view_exspense($id)
    {
        $this->db->select('*');
        $this->db->from('skeleton_tbl_family');
        $this->db->join('skeleton_tbl_expense','skeleton_tbl_expense.ex_id = 
            skeleton_tbl_family.tbl_income_in_id','INNER');
        $this->db->where('skeleton_tbl_family.family_id',$id);
        $query=$this->db->get();
        return $query->result();
    }
    // function call view_loan and dets
    function view_loan($id)
    {
        $this->db->select('*');
        $this->db->from('skeleton_tbl_family');
        $this->db->join('skeleton_tbl_loan_debt','skeleton_tbl_loan_debt.ld_id = 
            skeleton_tbl_family.tbl_loan_debt_ld_id','INNER');
        $this->db->where('skeleton_tbl_family.family_id',$id);
        $query=$this->db->get();
        return $query->result();
    }
    // function call view skeleton_tbl_residence
    function view_residence($id)
    {
        $this->db->select('*');
        $this->db->from('skeleton_tbl_family');
        $this->db->join('skeleton_tbl_residence','skeleton_tbl_residence.re_id = 
            skeleton_tbl_family.tbl_residence_re_id','INNER');
        $this->db->where('skeleton_tbl_family.family_id',$id);
        $query=$this->db->get();
        return $query->result();
    } 
    // function call view home assets
    function view_home_assets($id)
    {
        $this->db->select('*');
        $this->db->from('skeleton_tbl_family');
        $this->db->join('skeleton_tbl_home_asset','skeleton_tbl_home_asset.h_id = 
            skeleton_tbl_family.tbl_home_asset_h_id','INNER');
        $this->db->where('skeleton_tbl_family.family_id',$id);
        $query=$this->db->get();
        return $query->result();
    }  

    // function to add data to family profile
    public function addFamilyProfile($fAge,$fOccupation,$fSpecify,$fHealth,$fHealthSpec,$fEdu,$mAge,$mOccu,$mSpecify,$mhealthStatus,$mHealthSpec,$mEdu,$numSiblings,$marriedStatus,$separated,$numFamily,$studentRank)
    {
        $data = array(
            'f_age' => $fAge,
            'f_occupation' => $fOccupation,
            'f_health' => $fHealth,
            'f_edu' => $fEdu,
            'm_age' => $mAge,
            'm_occupation' => $mOccu,
            'm_health' => $mhealthStatus,
            'm_edu' => $mEdu,
            'number_sibbling' => $numSiblings,
            'number_maried' => $marriedStatus,
            'number_separated' => $separated,
            'number_family_living' => $numFamily,
            'stu_rank' => $studentRank,
            'f_occupation_comment' => $fSpecify,
            'm_occupation_comment' => $mSpecify,
            'f_health_comment' => $fHealthSpec,
            'm_health_comment' => $mHealthSpec
        );

        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
        $insert = $this->db->insert('skeleton_tbl_profile',$data);
        if ($this->db->affected_rows() > 0) {
           return true;
        } else {
            return false;
        }
        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");

        
    }
    // end function that add on family profile

    // model for add family income resource
    public function addFamilyIncome($paMonthIncome,$paDailyIncome,$paSesIncome,$paYearIncome,$paTotalIncome,$chMonthIncome,$chDailyIncome,$chSeasonIncome,$chYearIncome,$chTotalIncome,$totalIncome,$totalIncomeId)
    {
        $data = array(
            'f_monthly'=>$paMonthIncome,
            'f_daily'=>$paDailyIncome,
            'f_seasonal'=>$paSesIncome,
            'f_yearly'=>$paYearIncome,
            'f_total'=>$paTotalIncome,
            'c_monthly'=>$chMonthIncome,
            'c_daily'=>$chDailyIncome,
            'c_seasonal'=>$chSeasonIncome,
            'c_yearly'=>$chYearIncome,
            'c_total'=>$chTotalIncome,
            'g_monthly'=>$totalIncome,
            'g_monthly_individual'=>$totalIncomeId
        );

        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
        $insert = $this->db->insert('skeleton_tbl_income',$data);
        if ($this->db->affected_rows() > 0) {
            $this->db->select("MAX(can_id)");
            $this->db->from("skeleton_tbl_candidates");
            $query = $this->db->get(); 
            if($query->num_rows() > 0){
                return $query->result();  
            }else{
                return false;
            }
        } else {
            return false;
        }
        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
    }
    // end family income resource
    // function add family loan and debts
    public function addLoan($amount,$institution,$interest,$reason,$monthly,$trimester,$semester,$capital)
    {
        $data = array(
            'ld_initial_amount' => $amount,
            'ld_instritution' => $institution,
            'ld_interest_tates' => $interest,
            'ld_reason' => $reason,
            'ld_monthly' => $monthly,
            'ld_trimester' => $trimester,
            'ld_semester' => $semester,
            'ld_capital' => $capital
        );
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
        $insert = $this->db->insert('skeleton_tbl_loan_debt',$data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
    }
    // end function add loan and debts
    // function add family expense
    public function addExpense($rice,$food,$firewood,$loan,$study,$medical,$electric,$agriculture,$wedding,$other,$totalExpense)
    {
        $data = array(
            'ex_rice' => $rice,
            'ex_food' => $food,
            'ex_firewood' => $firewood,
            'ex_loan' => $loan,
            'ex_study' => $study,
            'ex_medical' => $medical,
            'ex_electricities_water' => $electric,
            'ex_agriculture' => $agriculture,
            'ex_weding' => $wedding,
            'ex_other_utilities' => $other,
            'ex_total' => $totalExpense
        );
    
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
        $insert = $this->db->insert('skeleton_tbl_expense',$data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
    }

    public function addResidence($status,$age,$rating)
    {
        $data = array(
            're_status' => $status, 
            're_age' => $age, 
            're_rating_scale' => $rating
        );
        
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
        $insert = $this->db->insert('skeleton_tbl_residence',$data);
        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
        
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // end function add family expense

    // function add family home asset
    public function addAssets($refrigerator,$radio,$airCon,$riceCooker,$lcdTV,$colorTV,$chComputer,$exComputer,$fCabinet,$dvd,$smartPhone,$phone,$cheapCam,$expenCam,$cheapSofa,$exSofa,$gasCooker,$fruitBlender,$elecCooker,$motoBike,$farmMachine,$car,$vihicleComment,$cow,$buffalo,$pig,$animalCmt,$farmSize,$farmCmt,$sumQuantity5,$sumQuantity3,$globalAsset,$certificate,$specifyLevel)
    {
        $data = array(
            'h_refrigerator' => $refrigerator,
            'h_air_condictioner' => $airCon,
            'h_lcd_tv' => $lcdTV,
            'h_computer_big_100' => $exComputer,
            'h_furnished_big_300' => $fCabinet,
            'h_smartphone_big_100' => $smartPhone,
            'h_camera_big_100' => $expenCam,
            'h_sofa_big_300' => $exSofa,
            'h_motobike_big_500' => $motoBike,
            'h_farming_machine' => $farmMachine,
            'h_car_truck' => $car,
            'h_cow' => $cow,
            'h_buffaloe' => $buffalo,
            'h_pig' => $pig,
            'h_farm_size' => $farmSize,
            'h_radio' => $radio,
            'h_rice_cooker' => $riceCooker,
            'h_color_tv' => $colorTV,
            'h_computer_smal_100' => $chComputer,
            'h_dvd_player' => $dvd,
            'h_phone_smal_100' => $phone,
            'h_camera_small_100' => $cheapCam,
            'h_sofa_smal_300' => $cheapSofa,
            'h_gascooker' => $gasCooker,
            'h_fruit_blender' => $fruitBlender,
            'h_electrical_cooker' => $elecCooker,
            'h_vehicles_comment' => $vihicleComment,
            'h_animals_comment' => $animalCmt,
            'h_farm_comment' => $farmCmt,
            'h_total_x5' => $sumQuantity5,
            'h_total_x3' => $sumQuantity3,
            'h_glbal_total' => $globalAsset,
            'h_poverty_certificate' => $specifyLevel,
        );
       $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
       $insert = $this->db->insert('skeleton_tbl_home_asset',$data);
       if ($this->db->affected_rows() > 0) {
           return true;
       } else {
           return false;
       }
       $this->db->query("SET FOREIGN_KEY_CHECKS = 1"); 
    }
    // end function add home asset
 }   