<?php
require 'authenticate.php';
require_once('includes/MySqlDb.php');
require 'includes/functions.php';
require 'includes/functions2.php';
require 'includes/functions3.php';


$year = $_GET['year'];
$student_id = $_GET['student_id'];

$current_year = $year;
    
     $months = $db->find_by_sql("*","months ORDER BY id","","");   
    
     
      $r .= '<table style="margin:18px auto;font-size:14px;" cellpadding="3" border="1">';
      $r .= '<tr>';
      foreach ($months as $value)
      {
         $payment_status = $db->find_by_sql("status","payment_status","student_id='$student_id' AND month='{$value['month']}' AND year='$current_year'","");

         if($payment_status == 'No Result Found')
         { 
             $payment_status = 'Not paid'; 
             $payable = $db->find_by_sql("SUM(amount) AS total_payable","receipt_items_amount_on_student","student_id='$student_id' AND month='{$value['month']}' AND year='$current_year' ","");

             if(empty($payable[0]['total_payable']) == TRUE)
             { $payable = 'Not set'; $color = "lightgray"; }
             else{ 
                 $payable = $payable[0]['total_payable']; $color = "black"; 
             }
             $paid = 0;
             $items_due = 'No Result Found';
             
         }elseif($payment_status[0]['status'] == 'Not full paid')
         {
             $payment_status = $payment_status[0]['status']; 
             $payable = $db->find_by_sql("SUM(amount) AS total_payable","receipt_items_amount_on_student","student_id='$student_id' AND month='{$value['month']}' AND year='$current_year' ","");
             
             if(empty($payable[0]['total_payable']) == TRUE)
             { $payable = 'Not found'; $color = "lightgray"; }else{ $payable = $payable[0]['total_payable']; $color = "black"; }
             $paid = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","student_id='$student_id' AND month='{$value['month']}' AND year='$current_year' ","");
             $paid = $paid[0]['total_paid'];
             $items_due = $db->find_by_sql("receipt_item,due","due","student_id='$student_id' AND month='{$value['month']}' AND year='$current_year'","");

         }elseif($payment_status[0]['status'] == 'Full paid')
         {
             $payment_status = $payment_status[0]['status']; 
             $payable = $db->find_by_sql("SUM(amount) AS total_payable","receipt_items_amount_on_student","student_id='$student_id' AND month='{$value['month']}' AND year='$current_year' ","");
             
             if(empty($payable[0]['total_payable']) == TRUE)
             { $payable = 'Not found'; $color = "lightgray"; }else{ $payable = $payable[0]['total_payable']; $color = "black"; }
             $paid = $payable;
             $items_due = 'No Result Found';
             
         }
         
         
         
         
         $r .= '<td>';
            $r .= '<table>';
                $r .= '<tr>';
                  $r .= '<td style="width:130px;text-align:center;color:'.$color.'"><b>'.$value['month'].'</b></td>';
                $r .= '</tr>';
                $r .= '<tr style="border-top-style:solid;border-top-width:1px;border-top-color:#999;">';
                    $r .= '<td>';
                          $r .= '<table style="border:1px solid '.$color.'"  border="1" cellpadding="3">';
                                $r .= '<tr>';
                                   $r .= '<td style="width:130px;text-align:center;color:'.$color.'">Payable</td>';
                                   $r .= '<td style="width:130px;text-align:center;color:'.$color.'"><b>Paid</b></td>';
                                   $r .= '<td style="width:230px;text-align:center;color:'.$color.'">Due</td>';
                                   $r .= '<td style="width:170px;text-align:center;color:'.$color.'">Payment Status</td>';
                                $r .= '</tr>';
                                $r .= '<tr>';
                                   $r .= '<td style="width:130px;text-align:center;color:'.$color.'">'.$payable.'</td>';
                                   $r .= '<td style="width:130px;text-align:center;color:'.$color.'"><b>'.$paid.'</b></td>';
                                   $r .= '<td style="width:230px;text-align:center;color:'.$color.'">';
                                            if($items_due !== 'No Result Found')
                                            {
                                                 foreach($items_due as $value)
                                                 {
                                                     $r .= '<table cellpadding="2">';
                                                     $r .= '<tr>';
                                                     $r .= '<td style="text-align:left;"><b>'.$value['receipt_item'].'</b></td>';
                                                     $r .= '<td style="color:red;">'.$value['due'].'</td>';
                                                     $r .= '</tr>';
                                                     $r .= '</table>';
                                                 }
                                            }else{
                                                $r .= '0';
                                            }

                                          $r .='</td>';
                                   $r .= '<td style="width:170px;text-align:center;color:'.$color.'">'.$payment_status.'</td>';
                                $r .= '</tr>';
                          $r .= '</table>';
                    $r .= '<td>';     
                $r .= '</tr>';
            $r .= '</table>';
         $r .= '</td>';
      }
      $r .= '</tr>';
      $r .= '</table>';
      
     
     echo $r;
?> 
