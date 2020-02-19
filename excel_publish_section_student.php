<?php ob_start(); ?>
<?php
require 'authenticate.php';
require ('includes/MySqlDb.php');
ini_set('max_execution_time', 1800); //3600 seconds = 60 minutes
ini_set("memory_limit","1500M");
require 'includes/functions.php';
require 'includes/functions2.php';

if(isset($_POST['year']) && isset($_POST['section']) && isset($_POST['month']) && isset($_POST['fullmonth']))
{   
    $section_name = $_POST['section'];
    $year    = $_POST['year'];
    $month = $_POST['month'];
    $section =  $_POST['section'];
    $full_month =  $_POST['fullmonth'];
    
    
    $file="Section:($section_name)-($full_month)-($year).xls";
        
    $r .='<div><b>Year: ' . $year . '  Month: ' . $full_month . ',  Section: ' . $section_name. '</b></div>';
        
    global $db;
    
    date_default_timezone_set('Asia/Dhaka');

        	/* Get data. */
        $date = $db->find_by_sql("DISTINCT date","transaction_archive","year='$year' AND month='$month' ORDER BY id DESC","");
        $students_id = $db->find_by_sql("student_id","student_on_section","section='$section' ORDER BY  LENGTH(student_id), student_id","");
        
        $receipt_items = $db->find_by_sql("item","receipt_items ORDER BY item_order LIMIT 4","","");
        //$extra_added_items = $db->find_by_sql("DISTINCT item","extra_added_items ORDER BY item","","");
        
        if($receipt_items == 'No Result Found')
        {
            echo 'No Items Found';
        }else{
            
         
    //code for data show 
    
          $r .= '<table style=" text-align:center;background:whitesmoke;" >' ;
           $r .= '<tr style="background:#0099CC;color:#fff;">' ;
                $r .= '<th style="width:200px;padding:2px;">Stu.id</th>' ;
                 $r .= '<th style="width:200px;padding:2px;border:1px solid #fff;background:OrangeRed ;">Due ('.$month.')</th>' ;
            foreach ($receipt_items as $receipt_value)
            {
                $r .= '<th style="width:200px;padding:2px;border:1px solid #fff;">'.$receipt_value['item'].'</th>' ;
            }
			/*
            foreach ($extra_added_items as $extra_items_value)
            {
                $r .= '<th style="width:200px;padding:2px;border:1px solid #fff;">'.$extra_items_value['item'].'</th>' ;
            }*/
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
                 
                 
                 $payment_status = $db->find_by_sql("status","payment_status","student_id='{$students_id_value['student_id']}' AND month='$full_month' AND year='$year'","");
                 
                 if($payment_status == 'No Result Found')
                 {
                     $due = $db->find_by_sql("SUM(amount) AS total_payable","receipt_items_amount_on_student","student_id='{$students_id_value['student_id']}' AND month='$full_month' AND year='$year' ","");
                        if(empty($due[0]['total_payable']) == TRUE)
                        {  $due = 'Not set'; }
                        else{ 
                            $due = $due[0]['total_payable']; 
                        }
                     
                 }elseif($payment_status[0]['status'] == 'Not full paid')
                 { 
                        $due = $db->find_by_sql("due","due","student_id='{$students_id_value['student_id']}' AND month='$full_month' AND year='$year'","");

                        if($due == 'No Result Found')
                        { $due = 'Not found'; }else{ $due = $due[0]['due']; }
                        
                 }elseif($payment_status[0]['status'] == 'Full paid')
                 {
                     $due = '0';
                 }
                 
                 $r .= '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$due.'</b></td>' ;
                 
                 $total_items_paid_amount = 0;
                 
				 $dateCounter = 0;
				 
				 foreach ($date as $date_value) {
				    $dateCounter++;
				 }
				 $combineDate = "";
				 
                   foreach ($date as $date_value) {  // do not use month use date (archive and items table have different value on month)
                     $dateCounter--;
					  if($dateCounter > 0)
 					   { 
					     $combineDate .= "'".$date_value['date']."',";
					   }else{
					     $combineDate .= "'".$date_value['date']."'";
					   }
					  
                    }
					
				  $total_items_paid_amount = 0;		  
                 foreach ($receipt_items as $receipt_value)
                 {
                     $receipt_items_value = $receipt_value['item'];
                     //$test = $db->find_by_sql("paid","transaction_receipt_item","year='$year' AND student_id='{$students_id_value['student_id']}' AND receipt_item='{$receipt_items_value}' LIMIT 1","");
                    
                     //if($test !== 'No Result Found')
                      //{ 
                          
						 $result = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","year='$year' AND student_id='{$students_id_value['student_id']}' AND receipt_item='{$receipt_items_value}' AND date IN (".$combineDate.")","");
                         $total_items_paid_amount = $result[0]['total_paid'];
                       //}
                         
                     $r .= '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$total_items_paid_amount.'</b></td>' ;
                     $total_items_paid_amount = 0;
                 }
                 /*
                 $total_extraItems_paid_amount = 0;
                 foreach ($extra_added_items as $extra_items_value)
                 {
                     $extra_added_items_value = $extra_items_value['item'];
                     $test = $db->find_by_sql("paid","transaction_receipt_item","year='$year' AND student_id='{$students_id_value['student_id']}' AND receipt_item='{$extra_added_items_value}'","");
                     
                     if($test !== 'No Result Found')
                      { 
                     
                     foreach ($date as $date_value) {  // do not use month use date
                        $result = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","year='$year' AND date='{$date_value['date']}' AND student_id='{$students_id_value['student_id']}' AND receipt_item='{$extra_added_items_value}'",""); 
                        $total_extraItems_paid_amount += $result[0]['total_paid'];
                     }
                        }
                     
                     $r .='<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$total_extraItems_paid_amount.'</b></td>' ;
                     $total_extraItems_paid_amount = 0;
                 }*/
              $r .= '</tr>';
              $i++;
          }  
          $r .= '</table>' ;

     
   
      }
    
    
        

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        echo $r ;


}