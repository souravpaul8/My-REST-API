<?php

    class DbOperations{

        private $con;

        function __construct(){
            require_once dirname(__FILE__) . '/DbConnect.php';
            $db = new DbConnect;
            $this->con = $db->connect();
        }

        //Function to retrieve Development Funds in Finanacial Section when user enters financialyear, state, scheme
        public function retrieveDevFunds($financialyear, $state, $scheme) {
            //Statement to execute the SQL query to retrieve the details
            $stmt = $this->con->prepare("SELECT financialyear, state, scheme, allocation, expenditure FROM devfunds WHERE financialyear = ? AND state = ? AND scheme = ?");
            $stmt->bind_param("sss", $financialyear, $state, $scheme);
            $stmt->execute(); 
            $stmt->bind_result($financialyear, $state, $scheme, $allocation, $expenditure);
            $stmt->fetch();
            
            //Creating array to store all the response i.e. financialyear, state, scheme
            $devFund = array(); 
            $devFund['financialyear'] = $financialyear; 
            $devFund['state']=$state; 
            $devFund['scheme'] = $scheme;
            $devFund['allocation'] = $allocation;
            $devFund['expenditure'] = $expenditure;

            //Return the array in which response is stored
            return $devFund;
            
        }

        //Function to retrieve Maintainence Funds in Finanacial Section when user enters financialyear, state, scheme
        public function retreiveMainFunds($financialyear, $state, $scheme) {
            //Statement to execute the SQL query to retrieve the details
            $stmt = $this->con->prepare("SELECT financialyear, state, scheme, allocation, expenditure FROM maintainence_fund WHERE financialyear = ? AND state = ? AND scheme = ?");
            $stmt->bind_param("sss", $financialyear, $state, $scheme);
            $stmt->execute(); 
            $stmt->bind_result($financialyear, $state, $scheme, $allocation, $expenditure);
            $stmt->fetch();

            //Creating array to store all the response i.e. financialyear, state, scheme
            $mainFund = array(); 
            $mainFund['financialyear'] = $financialyear; 
            $mainFund['state']=$state; 
            $mainFund['scheme'] = $scheme;
            $mainFund['allocation'] = $allocation;
            $mainFund['expenditure'] = $expenditure;

            //Return the array in which response is stored
            return $mainFund;
        }

        
        public function retrieveNHdetails($financialyear, $state_UT, $scheme) {
            //Statement to execute the SQL query to retrieve the details
            $stmt = $this->con->prepare("SELECT financialyear, state_UT, scheme, sub_scheme, str, 2Lane, 4Lane, 6_8Lane, PR_IRQP, Bridges, Total  FROM NH_Construction WHERE financialyear = ? AND state_UT = ? AND scheme = ?");
            $stmt->bind_param("sss", $financialyear, $state_UT, $scheme);
            $stmt->execute(); 
            $stmt->bind_result($financialyear, $state, $scheme, $sub_scheme, $str, $TwoLane, $FourLane, $SixOrEightLane, $PR_IRQP, $Bridges, $Total);
            $TotalNHDetails = array();
            while($stmt->fetch()){

            $NHDetails = array();
            $NHDetails['financialyear'] = $financialyear; 
            $NHDetails['state_UT']=$state_UT; 
            $NHDetails['scheme'] = $scheme;
            $NHDetails['sub_scheme'] = $sub_scheme; 
            $NHDetails['streets']=$str; 
            $NHDetails['2_Lanes'] = $TwoLane; 
            $NHDetails['4_Lanes'] = $FourLane; 
            $NHDetails['6/8 Lanes']=$SixOrEightLane;
            $NHDetails['PR/IRQP'] = $PR_IRQP; 
            $NHDetails['Bridges'] = $Bridges; 
            $NHDetails['Total']=$Total;
            
            array_push($TotalNHDetails, $NHDetails);
            } 

            return $TotalNHDetails;
            
        }

         
        public function userLogin($email, $password){
            if($this->isEmailExist($email)){
                $hashed_password = $this->getUsersPasswordByEmail($email); 
                if(password_verify($password, $hashed_password)){
                    return USER_AUTHENTICATED;
                }else{
                    return USER_PASSWORD_DO_NOT_MATCH; 
                }
            }else{
                return USER_NOT_FOUND; 
            }
        }

        private function getUsersPasswordByEmail($email){
            $stmt = $this->con->prepare("SELECT password FROM Member_Profile WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($password);
            $stmt->fetch();
            return $password; 
        }

        public function getUserByEmail($email){
            $stmt = $this->con->prepare("SELECT Emp_ID, email, Name FROM Member_Profile WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute(); 
            $stmt->bind_result($id, $email, $name);
            $stmt->fetch(); 
            $user = array(); 
            $user['Emp_ID'] = $id; 
            $user['email']=$email;
            $user['Name'] = $name; 
           return $user; 
       }

        private function isEmailExist($email){
            $stmt = $this->con->prepare("SELECT Emp_ID FROM Member_Profile WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute(); 
            $stmt->store_result(); 
            return $stmt->num_rows > 0;  
         }

         public function getAllMembers(){

            $stmt = $this->con->prepare("SELECT Emp_ID, email, Name, Posting FROM Member_Profile");
            $stmt->execute(); 
            $stmt->bind_result($emp_id, $email, $name, $posting);
            $Members = array();
            while($stmt->fetch()){

            $Member = array(); 
            $Member['Emp_id'] = $emp_id; 
            $Member['Email']=$email; 
            $Member['Name'] = $name;
            $Member['Posting'] = $posting;
            array_push($Members,$Member);
            } 

            return $Members;
        }

        public function getMemberDetail($email){
            $stmt = $this->con->prepare("SELECT Emp_ID, Gender, Name, Branch, Batch_Year, Designation, Posting, Zone, Graduation, PG, Category,
                                                                 DOB, ESE_Year, DOIA, DORAIG, DoSA, MoRTH, Contact_Number, email FROM Member_Profile WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute(); 
            $stmt->bind_result($emp_id, $gender, $name, $branch, $batch_year, $designation, $posting, $zone, $graduation, $pg, $category,
                                                                $dob, $ese_year, $doia, $doraig, $dosa, $morth, $contact_number, $email);
            $stmt->fetch();
            $Member = array(); 
            $Member['emp_id'] = $emp_id;
            $Member['Gender'] = $gender;
            $Member['Name'] = $name;
            $Member['Branch'] = $branch;
            $Member['Batch_Year'] = $batch_year; 
            $Member['Designation'] = $designation;
            $Member['Posting'] = $posting;
            $Member['Zone'] = $zone;
            $Member['Graduation'] = $graduation;
            $Member['PG'] = $pg;
            $Member['Category'] = $category;
            $Member['DOB'] = $dob;
            $Member['ESE_Year'] = $ese_year;
            $Member['DOIA'] = $doia;
            $Member['DORAIG'] = $doraig;
            $Member['DoSA'] = $dosa;
            $Member['MoRTH'] = $morth;
            $Member['Contact_Number'] = $contact_number;
            $Member['email'] = $email;
           return $Member; 
       }


       public function updatePassword($currentpassword, $newpassword, $email){
        $hashed_password = $this->getUsersPasswordByEmail($email);
        
        if(password_verify($currentpassword, $hashed_password)){
            
            $hash_password = password_hash($newpassword, PASSWORD_DEFAULT);
            $stmt = $this->con->prepare("UPDATE Member_Profile SET password = ? WHERE email = ?");
            $stmt->bind_param("ss",$hash_password, $email);
             if($stmt->execute())
                return PASSWORD_CHANGED;
            return PASSWORD_NOT_CHANGED;
         }else{
            return PASSWORD_DO_NOT_MATCH; 
        }
    }

    }