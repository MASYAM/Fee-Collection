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
        
        $receipt_items = $db->find_by_sql("item","receipt_items ORDER BY item_order LIMIT 2","","");
        //$extra_added_items = $db->find_by_sql("DISTINCT item","extra_added_items ORDER BY item","","");
        
        if($receipt_items == 'No Result Found')
        {
            echo 'No Items Found';
        }else{
            
            
         
    //code for data show 

            $username = $db->find_by_sql("*","users ORDER BY username","","");
            $colspan = count($username) + 2;
                $r .= '<table style="float:left;border:1px solid #0099CC;" cellpadding="3">';
                   $r .= '<tr>';
                    $r .= '<td style="text-align:center;background:#0099CC;color:#fff;" colspan="'.$colspan.'"><b>Monthly Items Transaction (Total)</b></td>';
                   $r .= '</tr>';
                   $r .= '<tr>';
                    $r .= '<td style="text-align:left;width:100px;"><b>Month:</b></td><td style="text-align:left;width:120px;">'.$full_month.'</td>';
                   $r .= '</tr>';
                   $r .= '<tr>';
                    $r .= '<td style="text-align:left;width:100px;"><b>Year: </b>'.$year.'</td><td style="text-align:right;width:120px;"><b>All</b></td>';
                    foreach ($username as  $user_value) 
                    {
                        $r .= '<td style="text-align:right;width:120px;"><b>'.$user_value['username'].'</b></td>';
                    }
                   $r .= '</tr>';
                 foreach ($receipt_items as $receipt_value)
                 {
                      foreach($date as $date_value)
                      {
                            $total_paid = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","year='$year' AND date='{$date_value['date']}' AND receipt_item='{$receipt_value['item']}'","");
                            $total_paid_sum += $total_paid[0]["total_paid"];
                            $total_amount_of_all += $total_paid[0]["total_paid"];
                            foreach($username as $user_value)
                            {
                                $total_paid_by_user = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","year='$year' AND date='{$date_value['date']}' AND receipt_item='{$receipt_value['item']}' AND username='{$user_value['username']}'","");
                                $total_paid_sum_by_user[$user_value['username']] += $total_paid_by_user[0]["total_paid"];
                                $total_amount_by_user[$user_value['username']]  += $total_paid_by_user[0]["total_paid"];
                            }
                      }
                       $r .= '<tr>';
                         $r .= '<td style="text-align:left;width:100px;border:1px solid #ccc;"><b>'.$receipt_value['item'].':</b></td>';
                         $r .= '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_paid_sum.'</td>';
                        // print_r($total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                              $r .= '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_paid_sum_by_user[$user_value['username']].'</td>';
                              $total_paid_sum_by_user[$user_value['username']] = 0;
                         }
                       $r .= '</tr>';
                       $total_paid_sum = 0;
                 }/*
                 foreach ($extra_added_items as $extra_items_value)
                 {
                       foreach($date as $date_value)
                       {
                          $extra_items_total_paid = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","year='$year' AND date='{$date_value['date']}' AND receipt_item='{$extra_items_value['item']}'","");
                          $extra_items_total_paid_sum += $extra_items_total_paid[0]["total_paid"];
                          $total_amount_of_all += $extra_items_total_paid[0]["total_paid"];
                          
                            foreach($username as $user_value)
                            {
                                $extra_items_total_paid_by_user = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","year='$year' AND date='{$date_value['date']}' AND receipt_item='{$extra_items_value['item']}' AND username='{$user_value['username']}'","");
                                $extra_items_total_paid_sum_by_user[$user_value['username']] += $extra_items_total_paid_by_user[0]["total_paid"];
                                $total_amount_by_user[$user_value['username']]  += $extra_items_total_paid_by_user[0]["total_paid"];
                            }
                         
                       }
                       $r .= '<tr>';
                         $r .= '<td style="text-align:left;width:100px;border:1px solid #ccc;"><b>'.$extra_items_value['item'].':</b></td><td style="text-align:right;width:120px;border:1px solid #ccc;">'.$extra_items_total_paid_sum.'</td>';
                         //print_r($extra_items_total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                             $r .= '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$extra_items_total_paid_sum_by_user[$user_value['username']].'</td>';
                             $extra_items_total_paid_sum_by_user[$user_value['username']] = 0;
                         }
                       $r .= '</tr>';
                       $extra_items_total_paid_sum = 0;
                      
                 }*/
                 
                  $r .= '<tr>';
                         $r .= '<td style="text-align:left;width:100px;border:1px solid #ccc;"><b>Total:</b></td><td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_amount_of_all.'</td>';
                         //print_r($extra_items_total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                             $r .= '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_amount_by_user[$user_value['username']].'</td>';
                         }
                       $r .= '</tr>';

                $r .= '</table>';
   
      }
    
    
        

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        echo $r ;


}