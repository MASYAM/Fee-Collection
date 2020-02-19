<?php ob_start(); ?>
<?php
require 'authenticate.php';
require ('includes/MySqlDb.php');
ini_set('max_execution_time', 1800); //3600 seconds = 60 minutes
ini_set("memory_limit","1500M");
require 'includes/functions.php';
require 'includes/functions2.php';

if(isset($_POST['year']) && isset($_POST['month']) )
{   
    $year    = $_POST['year'];
    $full_month = $_POST['month'];
    
    $students_id = $db->find_by_sql("DISTINCT student_id","transaction_notcurrentyear","current_year='$year' AND current_month='$full_month' ORDER BY  LENGTH(student_id), student_id","");
    $paid_years = $db->find_by_sql("DISTINCT paid_year","transaction_notcurrentyear","current_year='$year' AND current_month='$full_month' ","");

    
    
    $file="Year:($year)-($full_month).xls";
        
    $r .='<div><b>Year: ' . $year . '  Month: ' . $full_month . '</b></div>';
         
    global $db;
    
    date_default_timezone_set('Asia/Dhaka');


         $items = array("date","time","paid year","paid month","paid");
            
            
    //code for data show 

          $r .= '<table style=" text-align:center;background:whitesmoke;" >' ;
           $r .= '<tr style="background:#0099CC;color:#fff;">' ;
                $r .= '<th style="width:200px;padding:2px;">Stu.id</th>' ;
            foreach ($items as $items_value)
            {
                $r .= '<th style="width:200px;padding:2px;border:1px solid #fff;">'.$items_value.'</th>' ;
            }
            
           $r .= '</tr>';
          $i =0;
          foreach ($students_id as $students_id_value) 
          {              
              foreach ($paid_years as $paid_years_value)
                 {
                     $paid_months =  $db->find_by_sql("DISTINCT paid_month","transaction_notcurrentyear","current_year='$year' AND current_month='$full_month' AND paid_year='{$paid_years_value['paid_year']}' ","");
                     
                     foreach ($paid_months as $paid_months_value) 
                     {
              
                        $result = $db->find_by_sql("SUM(paid) AS total_paid","transaction_notcurrentyear","current_year='$year' AND current_month='$full_month' AND paid_year='{$paid_years_value['paid_year']}' AND paid_month='{$paid_months_value['paid_month']}' AND student_id='{$students_id_value['student_id']}'",""); 
                        $dateNtime = $db->find_by_sql("DISTINCT date,time","transaction_notcurrentyear","current_year='$year' AND current_month='$full_month' AND paid_year='{$paid_years_value['paid_year']}' AND paid_month='{$paid_months_value['paid_month']}' AND student_id='{$students_id_value['student_id']}'",""); 
                        
                        if(empty($result[0]['total_paid']) == FALSE)
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
                                 $r .= '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$dateNtime[0]['date'].'</b></td>' ;
                                 $r .= '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$dateNtime[0]['time'].'</b></td>' ;
                                 $r .= '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$paid_years_value['paid_year'].'</b></td>' ;
                                 $r .= '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$paid_months_value['paid_month'].'</b></td>' ;
                                 $r .= '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$result[0]['total_paid'].'</b></td>' ;

                              $r .= '</tr>';
                              $i++;
              
                        }
              }
                        
                     
              }
          }   
          $r .= '</table>' ;



            $username = $db->find_by_sql("*","users ORDER BY username","","");
            $colspan = count($username) + 2;
                $r .= '<table style="float:right;border:1px solid #0099CC;" cellpadding="3">';
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
                   echo '</tr>';
                
                            $total_paid = $db->find_by_sql("SUM(paid) AS total_paid","transaction_notcurrentyear","current_year='$year' AND current_month='$full_month' ","");
                            
                            foreach($username as $user_value)
                            {
                                $total_paid_by_user = $db->find_by_sql("SUM(paid) AS total_paid","transaction_notcurrentyear","current_year='$year' AND current_month='$full_month' AND username='{$user_value['username']}'","");
                                $total_paid_sum_by_user[$user_value['username']] += $total_paid_by_user[0]["total_paid"];
                            }
                      
                       $r .= '<tr>';
                        $r .= '<td style="text-align:left;width:100px;border:1px solid #ccc;"><b>Total</b></td>';
                         $r .= '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_paid[0]['total_paid'].'</td>';
                        // print_r($total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                              $r .= '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_paid_sum_by_user[$user_value['username']].'</td>';
                              $total_paid_sum_by_user[$user_value['username']] = 0;
                         }
                       $r .= '</tr>';
                

                $r .= '</table>';

                
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        echo $r ;


}