<?php ob_start(); ?>
<?php
require 'authenticate.php';
require ('includes/MySqlDb.php');
ini_set('max_execution_time', 1800); //3600 seconds = 60 minutes
ini_set("memory_limit","1500M");
require 'includes/functions.php';
require 'includes/functions2.php';

if(isset($_POST['year']) && isset($_POST['section']))
{   
    $section_name = $_POST['section'];
    $year    = $_POST['year'];
    $section =  $_POST['section'];
    
    
    $file="Section:($section_name)-($year).xls";
        
    $r .='<div><b>Year: ' . $year . '  Section: ' . $section_name. '</b></div>';
        
    global $db;
    
    date_default_timezone_set('Asia/Dhaka');

        	  	/* Get data. */
        $date = $db->find_by_sql("DISTINCT date","transaction_archive","year='$year' AND month='$month' ORDER BY id DESC","");
        $students_id = $db->find_by_sql("student_on_section.student_id, student_info.student_name, student_info.class_roll","student_on_section LEFT JOIN student_info ON student_on_section.student_id = student_info.student_id","section='$section' ORDER BY  LENGTH(student_on_section.student_id), student_on_section.student_id","");
        
        $receipt_items = $db->find_by_sql("item","receipt_items ORDER BY item_order LIMIT 4","","");
        //$extra_added_items = $db->find_by_sql("DISTINCT item","extra_added_items ORDER BY item","","");
		
		$months_name = array("January","February","March","April","May","June","July","August","September","October","November","December");
        
        if($receipt_items == 'No Result Found')
        {
            echo 'No Items Found';
        }else{
            
         
    //code for data show 
    
          $r .= '<table style="width:100%;overflow:auto; text-align:center;background:whitesmoke;" >' ;
           $r .= '<tr style="background:#86b300;color:#fff;">' ;
                $r .= '<th style="width:200px;padding:2px;">Student.id</th>' ;
				$r .= '<th style="width:300px;padding:2px;border:1px solid whitesmoke;">Name</th>' ;
				$r .= '<th style="width:300px;padding:2px;border:1px solid whitesmoke;">Class Roll</th>' ;
            foreach ($months_name as $months_name_value)
            {
                $r .= '<th style="width:100px;padding:2px;border:1px solid whitesmoke;" colspan="3">'.$months_name_value.'</th>' ;
            }
           $r .= '<th style="width:300px;padding:2px;background:#0066ff;"></th>' ;
           $r .= '</tr>';
		    
		   $r .= '<tr style="background:#86b300;color:#fff;">' ;
           $r .= '<td style="width:100px;padding:2px;"></td>' ;   
           $r .= '<td style="width:200px;padding:2px;border:1px solid whitesmoke;"></td>' ;	
           $r .= '<td style="width:200px;padding:2px;border:1px solid whitesmoke;"></td>' ;
		   
            foreach ($months_name as $months_name_value)
            {
                $r .= '<td style="width:100px;padding:2px;border:1px solid whitesmoke;">Due</td>' ;
				$r .= '<td style="width:100px;padding:2px;border:1px solid whitesmoke;">Paid</td>' ;
				$r .= '<td style="width:200px;padding:2px;border:1px solid whitesmoke;">Status (Paid Date)</td>' ;
            }
            
			$r .= '<td style="width:200px;padding:2px;border:1px solid whitesmoke;background:#0066ff;">Total</td>' ;
           $r .= '</tr>';
		   
          $i =0;
          foreach ($students_id as $students_id_value)
          {              
                 $r .= '<tr ';
                if($i%2 == 0)
                {
                   $r .= ' class="tr1_hover" ';
                }else 
                    {
                      $r .= ' class="tr2_hover" ';
                    }
                 $r .= ' >' ;
                $r .= '<td style="font-size:14px;padding:4px;width:200px;color:#000; " ><b>'.$students_id_value['student_id'].'</b></td>' ;
				$r .= '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #86b300; " ><b>'.$students_id_value['student_name'].'</b></td>' ;
				$r .= '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #86b300; " ><b>'.$students_id_value['class_roll'].'</b></td>' ;
                 
				 $total_paid = 0;
				 
                 foreach($months_name as $months_name_value)
				 {
					 
						 $payment_status = $db->find_by_sql("status,date","payment_status","student_id='{$students_id_value['student_id']}' AND month='$months_name_value' AND year='$year'","");
						 
						 if($payment_status == 'No Result Found')
						 {
							 $due = $db->find_by_sql("SUM(amount) AS total_payable","receipt_items_amount_on_student","student_id='{$students_id_value['student_id']}' AND month='$months_name_value' AND year='$year' ","");
								
								if(empty($due[0]['total_payable']) == TRUE)
								{ 
        							$due = '';//not set 
							    }
								else{ 
									$due = $due[0]['total_payable']; 
								}
							 
						 }elseif($payment_status[0]['status'] == 'Not full paid')
						 { 
								$due = $db->find_by_sql("due","due","student_id='{$students_id_value['student_id']}' AND month='$months_name_value' AND year='$year'","");

								if($due == 'No Result Found')
								{ 
							         $due = ''; //not found
   							     }else{ $due = $due[0]['due']; }
								
						 }elseif($payment_status[0]['status'] == 'Full paid')
						 {
							 $due = '0';
						 }
						 
						 $r .= '<td style="font-size:14px;padding:4px;width:100px;color:#000;border:1px solid #86b300; " ><b>'.$due.'</b></td>' ;
						 
						 $monthNameShort = substr($months_name_value,0,3);
						 
						 
						 $result = $db->find_by_sql("SUM(paid) AS total_paid","transaction_archive","year='$year' AND month='$monthNameShort' AND student_id='{$students_id_value['student_id']}'","");
						 
						 if(empty($result[0]['total_paid']))
						 {
							 $r .= '<td style="font-size:14px;padding:4px;width:100px;color:#000;border:1px solid #86b300; " ><b></b></td>' ;
						 }else{
						    $r .= '<td style="font-size:14px;padding:4px;width:100px;color:#000;border:1px solid #86b300; " ><b>'.$result[0]['total_paid'].'</b></td>' ;
							$total_paid += $result[0]['total_paid'];
						 }
						 
						 if($payment_status == 'No Result Found')
						 {
						    $r .= '<td style="font-size:14px;padding:4px;width:100px;color:#000;border:1px solid #86b300; " ><b></b></td>';
						 }else{
							 
							 $r .= '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #86b300; " ><b>'.$payment_status[0]['status'].'<br> ('.$payment_status[0]['date'].')</b></td>';
						 }
						 
						 
				 }
				 $r .= '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #86b300; " ><b>'.$total_paid.'</b></td>' ;
              $r .= '</tr>';
              $i++;
          }  
          $r .= '</table>' ;

     
   
      }
    
    
        

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        echo $r ;


}