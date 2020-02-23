<?php
require 'authenticate.php';
require_once('includes/MySqlDb.php');
require 'includes/functions.php';
require 'includes/functions2.php';
require 'includes/functions3.php';
?>

<?php require 'includes/header.php';   ?>
  <div id="main_content">
       
          <?php 
                
if($_GET['subcat'] == 'Monthly Fee')
{
    
                  echo '<div style="width:400px;height:35px;padding-top:5px;margin:auto;">';
                      echo student_id();
                  echo'</div>';
                                    
                  if(isset($_POST['submit']) || isset($_POST['year']))
                  {
                       $student_id = $_POST['student_id'];
                         
                        if(empty($_POST['year']) == TRUE)
                        {
                           $year = date("Y"); 
                        }else{ $year = $_POST['year']; }
                        
                        $student_info = $db->find_by_sql("*","student_info","student_id='$student_id'","");
                        $selected_month = $_POST['selected_month'];
                        if($student_info == 'No Result Found')
                        {
                             echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                   <div id="message" ><b>No Result Found</b></div>';
                        }else{
                            echo student_info($student_id, $student_info);
                            echo year($student_id);
//                            echo '<div  style="text-align:center;border:1.5px solid #C0C0C0;height:23px;padding:0px 10px;width:90.3%;left:49px;position:relative;">';
//                             echo '<b>Payment Status (Year:</b>'.$current_year.')';
//                                echo'</div>';
//                                 echo '<div id="livesearch"  style="border:3px solid #C0C0C0;padding:10px 10px 10px 10px;height:140px;width:90%;overflow:auto;margin:auto;">';
//                                 echo'</div>';
//                            echo '<form>
//                                    <input type="button" value="Click to view"  onClick="showResult(\''.$year.'\', \''.$student_id.'\')" >                
//                                  </form>';
                            echo monthly_payment_status($student_id, $year);
                            echo months_field($student_id,$selected_month,$year);
                        }
                  }
                  if(isset($_POST['month_proceed']))
                  {
                      $update_reload_permission = $db->update("permission","reload_permission='Yes'","username='$user115122'");
                      
                      $year =  $_POST['year'];
                      $selected_month = $_POST['selected_month'];
                      $student_id = $_POST['student_id'];
                      date_default_timezone_set('Asia/Dhaka');

                         if(count($selected_month) == 0)
                          {
                                    echo '<div style="padding: 20px;margin:50px 0 0 0;width: 500px;height:5px;"></div>
                                             <div id="message" ><b>Please choose any month</b></div>';
                                      
                          }else{
                                        foreach ($selected_month as $value)
                                        {
                                                $check_if_paid = $db->find_by_sql("status","payment_status","student_id='$student_id' AND month='$value' AND year='$year'","");
                                                if($check_if_paid !== 'No Result Found')
                                                {
                                                      if(($check_if_paid[0]['status'] ==  'Not full paid') || ($check_if_paid[0]['status'] == 'Full paid'))
                                                      {
                                                          echo '<div style="padding: 20px;margin:50px 0 0 0;width: 500px;height:5px;"></div>
                                                               <div id="message" ><b>Already paid for Month: '.$value.'</b></div>';
                                                          $error = 'error';
                                                          break;
                                                      }
                                                }
                                                    $payable = $db->find_by_sql("SUM(amount) AS total_payable","receipt_items_amount_on_student","student_id='$student_id' AND month='$value' AND year='$year' ","");

                                                    if($payable[0]['total_payable'] == '')
                                                    { $payable = 'Not set';  
                                                           echo '<div style="padding: 10px;margin:50px 0 0 0;width: 500px;height:5px;"></div>
                                                                <div id="message" ><b>Payable amount not set for Month: '.$value.'</b></div>';
                                                           $error = 'error';
                                                           break;  
                                                    }else{  }
                                        }

                                        if($error !== 'error'){echo identity_n_receipt($student_id,$selected_month,$year);}
                               }
                  }
                  if(isset($_POST['calculate']))
                  {
                          $amount = $_POST['amount'];
                          $comment = $_POST['comment'];
                          if(empty($_POST['posponed_item'])== FALSE)
                          { $posponed_item = $_POST['posponed_item'];}else{ $posponed_item = array(0=>'0'); }
                          $student_id = $_POST['student_id'];
                          $student_info = $db->find_by_sql("*","student_info","student_id='$student_id'","");
                          $selected_month = $_POST['selected_month'];
                          $year =  $_POST['year'];
                          
                          //echo student_info($student_id, $student_info);
                          //echo months_field($student_id,$selected_month,$year);
                          echo identity_n_receipt_when_calculate($student_id,$amount,$comment,$posponed_item,$selected_month,$year);
                  }
                  if(isset($_POST['save&print']))
                  {
                          date_default_timezone_set('Asia/Dhaka');
                          $date = date('Y-m-d') ;
                          $time = date('h:i:s A') ;
                          
                          $paid_year =  $_POST['year'];
                          $current_year = date("Y");
                          $current_month = date("M");
                          $current_month_full = date("F");
                          
                          if(empty($_POST['posponed_item'])== FALSE)
                          { 
                              $posponed_item = $_POST['posponed_item'];
                              $payment_status = 'Not full paid';
                          }else{ 
                              $posponed_item = array(0=>'0');
                              $payment_status = 'Full paid';
                          }
     
                         
                          $amount = $_POST['amount'];
                          //
                          foreach ($amount as $key => $value)
                          {
                             
                                 if(!in_array($key, $posponed_item))
                                 {
                                     $rearrange_amount[$key] = $value ; 
                                 }
                             
                          }

                          $item   = $_POST['item'];
                          
                          $selected_month = $_POST['selected_month'];
                          $comment = $_POST['comment'];
                          $student_id = $_POST['student_id'];
                          $student_info = $db->find_by_sql("*","student_info","student_id='$student_id'","");
                          $total_payable = array_sum($rearrange_amount) ;
                          $total_paid = trim($_POST['paid']);
                          $exchange = $total_paid - $total_payable;
                          
                          
                           foreach ($amount as $key => $value)
                           {
                                if(!is_numeric($value))
                                {
                                    $selected_month = $_POST['selected_month'];
                                    echo '<div style="padding: 20px;margin:110px 0 0 0;width: 500px;height:5px;"></div>
                                                        <div id="message" style="color:darkred;"><b>Invalid value ('.$value.') passed for particular: '.$key.'</b></div>';

                                    echo identity_n_receipt_when_calculate($student_id,$amount,$comment,$posponed_item,$selected_month,$year);
                                    $error = 'error';
                                    break;
                                }
                           }
                          
                         if($error !== 'error') 
                         {
                                    if(!is_numeric($total_paid) || ($total_paid < 0))
                                    {
                                        $selected_month = $_POST['selected_month'];
                                        echo '<div style="padding: 20px;margin:110px 0 0 0;width: 500px;height:5px;"></div>
                                                                <div id="message" style="color:darkred;"><b>Invalid value ('.$total_paid.') passed for paid amount</b></div>';

                                        echo identity_n_receipt_when_calculate($student_id,$amount,$comment,$posponed_item,$selected_month,$year);
                                    }elseif($total_paid < $total_payable)
                                    { 
                                        $selected_month = $_POST['selected_month'];
                                        echo '<div style="padding: 20px;margin:110px 0 0 0;width: 500px;height:5px;"></div>
                                                                <div id="message" style="color:darkred;"><b>Paid amount ('.$total_paid.') is not enough</b></div>';
                                        echo identity_n_receipt_when_calculate($student_id,$amount,$comment,$posponed_item,$selected_month,$year);
                                    }else{
                                                if($exchange < 0)
                                                {
                                                   $final_exchange = 0;
                                                   $final_due = abs($exchange);
                                                }else{
                                                   $final_exchange = $exchange;
                                                   $final_due = 0;
                                                }

                                               $reload_permission = $db->find_by_sql("reload_permission","permission","username='$user115122'","");

                                               if($reload_permission[0]['reload_permission'] !== 'Cancel')
                                               {
                                                     $final_payment_status = 'Full paid';
                                                     if($payment_status == 'Not full paid')
                                                     {
                                                            foreach ($selected_month as $month_value)
                                                            {   
                                                                        foreach ($rearrange_amount as $key => $value) 
                                                                        { 
                                                                            if($value != 0)
                                                                            { 
                                                                                    if(count($selected_month) == '1')
                                                                                    {
                                                                                              $insert_transaction_receipt_item = $db->insert("transaction_receipt_item","year,month,date,time,receipt_item,student_id,paid,username","'$paid_year','$month_value','$date','$time','$key','$student_id','$value','$user115122'","");
                                                                                              if($paid_year !== $current_year)
                                                                                              {
                                                                                                    $insert_transaction_receipt_item_notCurrentYear = $db->insert("transaction_notcurrentyear","paid_year,paid_month,current_year,current_month,date,time,student_id,receipt_item,paid,username","'$paid_year','$month_value','$current_year','$current_month_full','$date','$time','$student_id','$key','$value','$user115122'","");
                                                                                              }
                                                                                    }
                                                                                    else{
                                                                                             $item_amount = $db->find_by_sql("amount","receipt_items_amount_on_student","student_id='$student_id' AND month='$month_value' AND item='$key' AND year='$paid_year'","");
                                                                                             if($item_amount == 'No Result Found')
                                                                                             { $item_amount = 0; }else{ $item_amount = $item_amount[0]['amount']; }
                                                                                             $insert_transaction_receipt_item = $db->insert("transaction_receipt_item","year,month,date,time,receipt_item,student_id,paid,username","'$paid_year','$month_value','$date','$time','$key','$student_id','$item_amount','$user115122'","");
                                                                                              
                                                                                              if($paid_year !== $current_year)
                                                                                              {
                                                                                                    $insert_transaction_receipt_item_notCurrentYear = $db->insert("transaction_notcurrentyear","paid_year,paid_month,current_year,current_month,date,time,student_id,receipt_item,paid,username","'$paid_year','$month_value','$current_year','$current_month_full','$date','$time','$student_id','$key','$item_amount','$user115122'","");
                                                                                              }
                                                                                       }
                                                                            }
                                                                        }

                                                                        foreach($posponed_item as $posponed_item_value)
                                                                        { 
                                                                                $item_amount = $db->find_by_sql("amount","receipt_items_amount_on_student","student_id='$student_id' AND month='$month_value' AND item='$posponed_item_value' AND year='$paid_year'","");

                                                                                if($item_amount !== 'No Result Found')
                                                                                {
                                                                                       if($item_amount[0]['amount'] > 0)
                                                                                       { 
                                                                                            $insert_item_due = $db->insert("due","year,month,date,time,student_id,receipt_item,due","'$paid_year','$month_value','$date','$time','$student_id','$posponed_item_value','{$item_amount[0]['amount']}'",""); 
                                                                                            $final_payment_status = 'Not full paid';
                                                                                       }
                                                                                }
                                                                        }
                                                                    
                                                                    $insert_payment_status = $db->insert("payment_status","year,month,date,time,student_id,status","'$paid_year','$month_value','$date','$time','$student_id','$final_payment_status'",""); 
                                                                    $final_payment_status = 'Full paid';
                                                            }
                                                     }else{
                                                               foreach ($selected_month as $month_value)
                                                                {
                                                                        foreach ($amount as $key => $value) 
                                                                        {
                                                                            if($value != 0)
                                                                            { 
                                                                                if(count($selected_month) == '1')
                                                                                {
                                                                                      $insert_transaction_receipt_item = $db->insert("transaction_receipt_item","year,month,date,time,receipt_item,student_id,paid,username","'$paid_year','$month_value','$date','$time','$key','$student_id','$value','$user115122'","");
                                                                                              if($paid_year !== $current_year)
                                                                                              {
                                                                                                    $insert_transaction_receipt_item_notCurrentYear = $db->insert("transaction_notcurrentyear","paid_year,paid_month,current_year,current_month,date,time,student_id,receipt_item,paid,username","'$paid_year','$month_value','$current_year','$current_month_full','$date','$time','$student_id','$key','$value','$user115122'","");
                                                                                              }
                                                                                }else{
                                                                                      $item_amount = $db->find_by_sql("amount","receipt_items_amount_on_student","student_id='$student_id' AND month='$month_value' AND item='$key' AND year='$paid_year'","");
                                                                                      if($item_amount == 'No Result Found')
                                                                                      { $item_amount = 0; }else{ $item_amount = $item_amount[0]['amount']; }
                                                                                      $insert_transaction_receipt_item = $db->insert("transaction_receipt_item","year,month,date,time,receipt_item,student_id,paid,username","'$paid_year','$month_value','$date','$time','$key','$student_id','$item_amount','$user115122'","");
                                                                                        if($paid_year !== $current_year)
                                                                                          {
                                                                                              $insert_transaction_receipt_item_notCurrentYear = $db->insert("transaction_notcurrentyear","paid_year,paid_month,current_year,current_month,date,time,student_id,receipt_item,paid,username","'$paid_year','$month_value','$current_year','$current_month_full','$date','$time','$student_id','$key','$item_amount','$user115122'","");
                                                                                          }
                                                                                }
                                                                            }
                                                                        }

                                                                        $insert_payment_status = $db->insert("payment_status","year,month,date,time,student_id,status","'$paid_year','$month_value','$date','$time','$student_id','Full paid'",""); 
                                                                }
                                                     }
                                                     
                                                     
                                                     $insert_transaction = $db->insert("transaction_archive","year,month,date,time,student_id,payable,paid,due,exchange,payment_type,username","'$current_year','$current_month','$date','$time','$student_id','$total_payable','$total_paid','$final_due','$final_exchange','Monthly Fee','$user115122'","");
                                                     
                                                     $fullmonth = month_selection(substr(date('Y-m-d'),5,2));
                                                     $select_last_receipt = $db->find_by_sql("receipt_id","receipt_id WHERE year='$current_year' AND month='$fullmonth' ORDER BY id DESC","","1");

                                                     if($select_last_receipt !== 'No Result Found')
                                                     {  $final_receipt_id = substr($select_last_receipt[0]['receipt_id'],7,100000); $final_receipt_id = $current_year.''.date('m').'-'.++$final_receipt_id; }
                                                     else{ $final_receipt_id = $current_year.''.date('m').'-'.'1'; }
                                                     $insert_receipt_id = $db->insert("receipt_id","year,month,date,time,student_id,receipt_id,username","'$current_year','$fullmonth','$date','$time','$student_id','$final_receipt_id','$user115122'","");
                                               }

                                            $update_reload_permission = $db->update("permission","reload_permission='Cancel'","username='$user115122'");

                                            $section = $db->find_by_sql("DISTINCT section","student_on_section","student_id='$student_id'","");
                                            if($section == 'No Result Found'){ $section = ''; }else{ $section = $section[0]['section']; }
                                            
                                            $fullmonth = month_selection(substr(date('Y-m-d'),5,2));
                                            $receipt_id = $db->find_by_sql("receipt_id","receipt_id  WHERE  username='$user115122' ORDER BY id DESC","","1");

                                            echo '<div id="report" style="display:none;">';
                                                       
                                                        echo '<div style="width:550px;height:90%;margin:20px auto 0 auto;font-family:calibri;">';
                                                           echo '<div style="width:450px;height:110px;margin:auto;font-size:20px;text-align:center;" ><img src="images/demo_logo.png" style="float:left;"  width="60px" height="60px" ><b>DEMO INTERNATIONAL SCHOOL<br><span style="font-size:12px;">Demo Address</span></b></div>';
                                                           echo '<div style="width:545px;height:70px;font-size:14px;margin:auto;text-align:left;" ><b>Student Id: </b>'.$student_id.' &nbsp; &nbsp; <b>Class Roll: </b>'.$student_info[0]['class_roll'].'<br><br><b>Name: </b>'.$student_info[0]['student_name'].' &nbsp; &nbsp; <b>Section: </b>'.$section.'</div>';
                                                           echo '<table  style="font-family:calibri;font-size:14px;margin:10px auto;border:1px solid black;border-collapse: collapse;border-radius:3px;" border="1" cellpadding="1">';
                                                              echo '<tr>';
                                                                     echo '<td style="width:50px;text-align:center;"><b>S.L</b></td>';
                                                                     echo '<td style="width:330px;"><b>Particulars</b></td>';
                                                                     echo '<td style="width:170px;text-align:right;"><b>Amount(Tk)</b></td>';
                                                              echo '</tr>';
                                                              $i = 1;
                                                              foreach ($amount as $key => $value)
                                                              {
                                                                  echo '<tr>';
                                                                    if(!in_array($key, $posponed_item))
                                                                    {
                                                                        echo '<td style="text-align:center;">'.$i.'</td>';
                                                                        echo '<td>'.$key.'</td>';
                                                                        echo '<td style="text-align:right;">'.$value.'</td>';
                                                                    }
                                                                  echo '</tr>';
                                                                  $i++;
                                                              }
                                                              echo '<tr>';
                                                                     echo '<td style="text-align:right;" colspan="2"><b>Payable</b></td>';
                                                                     echo '<td style="text-align:right;"><b>'.$total_payable.'</b></td>';
                                                              echo '</tr>';
                                                              echo '<tr>';
                                                                     echo '<td style="text-align:right;" colspan="2"><b>Paid</b></td>';
                                                                     echo '<td style="text-align:right;"><b>'.$total_paid.'</b></td>';
                                                              echo '</tr>';
                                                              echo '<tr>';
                                                                     echo '<td style="text-align:right;" colspan="2"><b>Exchange</b></td>';
                                                                     echo '<td style="text-align:right;"><b>'.$final_exchange.'</b></td>';
                                                              echo '</tr>';
                                                              
                                                           echo '</table>';
                                                           echo '<div style="width:545px;height:20px;font-size:14px;margin: 20px auto 0 auto;text-align:left;" ><b>Paid Month: '.  join(", ", $selected_month).'</b></div>';
                                                           echo '<div style="width:545px;height:50px;font-size:14px;margin: 10px auto;text-align:left;" ><b>In Word: </b>'.convert_number_to_words($total_payable).' taka only</div>';
                                                           echo '<div style="width:180px;font-size:14px;border:1px solid transparent;border-top-color:black;float:left;text-align:center;"><b>Paid by</b></div>';
                                                           echo '<div style="width:180px;font-size:14px;border:1px solid transparent;border-top-color:black;float:right;text-align:center;"><b>Received by<br>( '.$user115122.' )</b></div>';
                                                           echo '<div style="width:545px;height:50px;margin: 10px auto;text-align:left;float:left;font-size:12px;" ><b>Date: </b>'.$date.'<br><b>Receipt No: </b>'.$receipt_id[0]['receipt_id'].'</div>';
                                                        echo '</div>';
                                                
                                            echo '</div>';
                                            echo '<script type="text/javascript">PrintElem(report)</script>' ;
                                            echo '<div style="padding:50px;margin:100px auto;width:500px;">Transaction has been saved. If not printed automatically, click <input type="button" id="submit_button" class="submit_button" value="Print"   onClick="PrintElem(report)" ></div>'  ;

                                    }
                         }
                  }
}
elseif($_GET['subcat'] == 'Monthly Due Fee')
{
                  echo '<div style="width:400px;height:35px;padding-top:5px;margin:auto;">';
                      echo student_id();
                  echo'</div>';
                  
                  if(isset($_POST['submit']) || isset($_POST['year']))
                  {                         
                        if(empty($_POST['year']) == TRUE)
                        {
                           $year = date("Y"); 
                        }else{ $year = $_POST['year']; }
                        
                        //$update_reload_permission = $db->update("permission","reload_permission='Yes'","username='$user115122'");

                        $student_id = $_POST['student_id'];
                        $student_info = $db->find_by_sql("*","student_info","student_id='$student_id'","");
                        $selected_month = $_POST['selected_month'];

                        if($student_info == 'No Result Found')
                        {
                             echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                   <div id="message" ><b>No Result Found</b></div>';
                        }else{
                            echo student_info($student_id, $student_info);
                            echo year($student_id);
                            echo monthly_payment_status($student_id,$year);
                            echo months_field2($section,$student_id,$selected_month,"month_proceed",$year);
                        }
                  }
                  if(isset($_POST['month_proceed']))
                  {
                      $year = $_POST['year'];
                      $update_reload_permission = $db->update("permission","reload_permission='Yes'","username='$user115122'");
                      $selected_month = $_POST['selected_month'];
                      $student_id = $_POST['student_id'];

                          if(count($selected_month) == 0)
                          {
                                  echo '<div style="padding: 20px;margin:110px 0 0 0;width: 500px;height:5px;"></div>
                                          <div id="message" style="color:darkred;"><b>Please choose any month</b></div>';
                                      
                          }else{
                                        $check_if_paid = $db->find_by_sql("status","payment_status","student_id='$student_id' AND month='$selected_month[0]' AND year='$year'","");
                                        if($check_if_paid !== 'No Result Found')
                                        {
                                              if(($check_if_paid[0]['status'] == 'Full paid'))
                                              {
                                                  echo '<div style="padding: 20px;margin:0 0 0 0;width: 500px;height:5px;"></div>
                                                       <div id="message" ><b>No due found for Month: '.$selected_month[0].'</b></div>';
                                                  $error = 'error';
                                              }
                                        }else{
                                                  echo '<div style="padding: 20px;margin:0 0 0 0;width: 500px;height:5px;"></div>
                                                       <div id="message" ><b>No payment status found for Month: '.$selected_month[0].'</b></div>';
                                                  $error = 'error';
                                            }
                                    

                                    if($error !== 'error'){echo due_receipt($student_id,$selected_month,$year);}
                               }
                  }
                  
                   if(isset($_POST['save&print']))
                  {
                          date_default_timezone_set('Asia/Dhaka');
                          $date = date('Y-m-d') ;
                          $time = date('h:i:s A') ;
                          
                          $current_year = date("Y");
                          $paid_year = $_POST['year'];
                          $current_month = date("M");
                          $current_month_full = date("F");
                         
                          $amount = $_POST['amount'];                         
                          
                          $selected_month = $_POST['selected_month'];
                          $comment = $_POST['comment'];
                          $student_id = $_POST['student_id'];
                          $student_info = $db->find_by_sql("*","student_info","student_id='$student_id'","");
                          $total_payable = array_sum($amount) ;
                          $total_paid = trim($_POST['paid']);
                          $exchange = $total_paid - $total_payable;
                          

                                    if(!is_numeric($total_paid) || ($total_paid < 0))
                                    {
                                        $selected_month = $_POST['selected_month'];

                                        echo '<div style="padding: 20px;margin:0 0 0 0;width: 500px;height:5px;"></div>
                                             <div id="message" style="color:darkred;" ><b>Invalid value ('.$total_paid.') passed for paid amount</b></div>';
                                        echo due_receipt($student_id,array(0=>$selected_month),$year);
                                    }elseif($total_paid < $total_payable)
                                    { 
                                        $selected_month = $_POST['selected_month'];
                                        echo '<div style="padding: 20px;margin:0 0 0 0;width: 500px;height:5px;"></div>
                                             <div id="message" style="color:darkred;" ><b>Paid amount ('.$total_paid.') is not enough</b></div>';
                                        
                                        echo due_receipt($student_id,array(0=>$selected_month),$year);
                                    }else{
                                                if($exchange < 0)
                                                {
                                                   $final_exchange = 0;
                                                   $final_due = abs($exchange);
                                                }else{
                                                   $final_exchange = $exchange;
                                                   $final_due = 0;
                                                }

                                               $reload_permission = $db->find_by_sql("reload_permission","permission","username='$user115122'","");
                                               
                                               if($reload_permission[0]['reload_permission'] !== 'Cancel')
                                               {
                                                     
                                                        foreach ($amount as $key => $value) 
                                                        {
                                                             $insert_transaction_receipt_item = $db->insert("transaction_receipt_item","year,month,date,time,receipt_item,student_id,paid,username","'$paid_year','$selected_month','$date','$time','$key','$student_id','$value','$user115122'","");
                                                                   if($paid_year !== $current_year)
                                                                     {
                                                                        $insert_transaction_receipt_item_notCurrentYear = $db->insert("transaction_notcurrentyear","paid_year,paid_month,current_year,current_month,date,time,student_id,receipt_item,paid,username","'$paid_year','$selected_month','$current_year','$current_month_full','$date','$time','$student_id','$key','$value','$user115122'","");
                                                                     }
                                                             $delete_due = $db->delete("due","year='$paid_year' AND month='$selected_month' AND student_id='$student_id' AND receipt_item='$key' AND due='$value'");
                                                        }

                                                        $insert_payment_status = $db->update("payment_status","date='$date',time='$time',status='Full paid'","year='$paid_year' AND month='$selected_month' AND student_id='$student_id'"); 
                                                        $insert_transaction = $db->insert("transaction_archive","year,month,date,time,student_id,payable,paid,due,exchange,payment_type,username","'$current_year','$current_month','$date','$time','$student_id','$total_payable','$total_paid','$final_due','$final_exchange','Monthly Due Fee','$user115122'","");

                                                        $fullmonth = month_selection(substr(date('Y-m-d'),5,2));
                                                     $select_last_receipt = $db->find_by_sql("receipt_id","receipt_id WHERE year='$current_year' AND month='$fullmonth' ORDER BY id DESC","","1");

                                                     if($select_last_receipt !== 'No Result Found')
                                                     {  $final_receipt_id = substr($select_last_receipt[0]['receipt_id'],7,100000); $final_receipt_id = $current_year.''.date('m').'-'.++$final_receipt_id; }
                                                     else{ $final_receipt_id = $current_year.''.date('m').'-'.'1'; }
                                                     $insert_receipt_id = $db->insert("receipt_id","year,month,date,time,student_id,receipt_id,username","'$current_year','$fullmonth','$date','$time','$student_id','$final_receipt_id','$user115122'","");
                                               }

                                            $update_reload_permission = $db->update("permission","reload_permission='Cancel'","username='$user115122'");
                                            
                                            $receipt_id = $db->find_by_sql("receipt_id","receipt_id  WHERE  username='$user115122' ORDER BY id DESC","","1");                                            
                                            

                                            $section = $db->find_by_sql("DISTINCT section","student_on_section","student_id='$student_id'","");
                                            if($section == 'No Result Found'){ $section = ''; }else{ $section = $section[0]['section']; }
                                            echo '<div id="report" style="display:none;">';
                                                       
                                                        echo '<div style="width:550px;height:90%;margin:20px auto 0 auto;font-family:calibri;">';
                                                           echo '<div style="width:450px;height:110px;margin:auto;font-size:20px;text-align:center;" ><img src="images/demo_logo.png" style="float:left;"  width="60px" height="60px" ><b>DEMO INTERNATIONAL SCHOOL<br><span style="font-size:12px;">Demo Address</span></b></div>';
                                                           echo '<div style="width:545px;height:70px;font-size:14px;margin:auto;text-align:left;" ><b>Student Id: </b>'.$student_id.' &nbsp; &nbsp; <b>Class Roll: </b>'.$student_info[0]['class_roll'].'<br><br><b>Name: </b>'.$student_info[0]['student_name'].' &nbsp; &nbsp; <b>Section: </b>'.$section.'</div>';
                                                           echo '<table  style="font-family:calibri;font-size:14px;margin:10px auto;border:1px solid black;border-collapse: collapse;border-radius:3px;" border="1" cellpadding="2">';
                                                              echo '<tr>';
                                                                     echo '<td style="width:50px;text-align:center;"><b>S.L</b></td>';
                                                                     echo '<td style="width:330px;"><b>Particulars</b></td>';
                                                                     echo '<td style="width:170px;text-align:right;"><b>Amount(Tk)</b></td>';
                                                              echo '</tr>';
                                                              $i = 1;
                                                              foreach ($amount as $key => $value)
                                                              {
                                                                  echo '<tr>';
                                                                        echo '<td style="text-align:center;">'.$i.'</td>';
                                                                        echo '<td>'.$key.'</td>';
                                                                        echo '<td style="text-align:right;">'.$value.'</td>';
                                                                  echo '</tr>';
                                                                  $i++;
                                                              }
                                                              echo '<tr>';
                                                                     echo '<td style="text-align:right;" colspan="2"><b>Payable</b></td>';
                                                                     echo '<td style="text-align:right;"><b>'.$total_payable.'</b></td>';
                                                              echo '</tr>';
                                                              echo '<tr>';
                                                                     echo '<td style="text-align:right;" colspan="2"><b>Paid</b></td>';
                                                                     echo '<td style="text-align:right;"><b>'.$total_paid.'</b></td>';
                                                              echo '</tr>';
                                                              echo '<tr>';
                                                                     echo '<td style="text-align:right;" colspan="2"><b>Exchange</b></td>';
                                                                     echo '<td style="text-align:right;"><b>'.$final_exchange.'</b></td>';
                                                              echo '</tr>';
                                                              
                                                           echo '</table>';
                                                           echo '<div style="width:545px;height:20px;font-size:14px;margin: 20px auto 0 auto;text-align:left;" ><b>Paid Month: '.$selected_month.'</b></div>';
                                                           echo '<div style="width:545px;height:50px;font-size:14px;margin: 10px auto;text-align:left;" ><b>In Word: </b>'.convert_number_to_words($total_payable).' taka only</div>';
                                                           echo '<div style="width:180px;font-size:14px;border:1px solid transparent;border-top-color:black;float:left;text-align:center;"><b>Paid by</b></div>';
                                                           echo '<div style="width:180px;font-size:14px;border:1px solid transparent;border-top-color:black;float:right;text-align:center;"><b>Received by<br>( '.$user115122.' )</b></div>';
                                                           echo '<div style="width:545px;height:50px;margin: 10px auto;text-align:left;float:left;font-size:12px;" ><b>Date: </b>'.$date.'<br><b>Receipt No: </b>'.$receipt_id[0]['receipt_id'].'</div>';
                                                        echo '</div>';
                                                
                                            echo '</div>';
                                            echo '<script type="text/javascript">PrintElem(report)</script>' ;
                                            echo '<div style="margin:30px auto;width:500px;">Transaction has been saved. If not printed automatically, click <input type="button" id="submit_button" class="submit_button" value="Print"   onClick="PrintElem(report)" ></div>'  ;
                                    }
                         
                  }
}
elseif($_GET['subcat'] == 'Other Fee')
{
                  echo '<div style="width:400px;height:35px;padding-top:5px;margin:auto;">';
                      echo student_id();
                  echo'</div>';
                  
                  if(isset($_POST['submit']) || isset($_POST['year']))
                  {                         
                        if(empty($_POST['year']) == TRUE)
                        {
                           $year = date("Y"); 
                        }else{ $year = $_POST['year']; }
                      
                        $student_id = $_POST['student_id'];
                        $student_info = $db->find_by_sql("*","student_info","student_id='$student_id'","");
                        $selected_month = $_POST['selected_month'];
                        if($student_info == 'No Result Found')
                        {
                             echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                   <div id="message" ><b>No Result Found</b></div>';
                        }else{
                            echo student_info($student_id, $student_info);                            
                            //echo year($student_id);
                            echo '<p style="margin:10px auto 0 auto;width:150px;"><b>Current Year:</b> '.$year.'</p>';
                            echo months_field2($section,$student_id,$selected_month,"month_proceed",$year);
                        }
                  }
                  if(isset($_POST['month_proceed']))
                  {
                      $year = $_POST['year'];
                      $update_reload_permission = $db->update("permission","reload_permission='Yes'","username='$user115122'");
                      $selected_month = $_POST['selected_month'];
                      $student_id = $_POST['student_id'];

                          if(count($selected_month) == 0)
                          {
                                  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                                       <div id="message" ><b>Please choose any month</b></div>';
                                      
                          }else{
                                  echo other_receipt($student_id,$selected_month,$year);
                               }
                  }
                  if(isset($_POST['calculate']))
                  {
                          $year = $_POST['year'];
                          $amount = $_POST['amount'];
                          $any_item = $_POST['any_item'];
                          $any_item_value = $_POST['any_item_value'];
                          $comment = $_POST['comment'];
                          $student_id = $_POST['student_id'];
                          $student_info = $db->find_by_sql("*","student_info","student_id='$student_id'","");
                          $selected_month = $_POST['selected_month'];
                          
                          echo other_receipt_when_calculate($student_id,$selected_month,$amount,$any_item,$any_item_value,$year);
                  }
                  if(isset($_POST['save&print']))
                  {
                          date_default_timezone_set('Asia/Dhaka');
                          $date = date('Y-m-d') ;
                          $time = date('h:i:s A') ;
                          
                          $year = $_POST['year'];
                          
                          $current_year = date("Y");
                          $current_month = date("M");     
                         
                          $amount = $_POST['amount'];
                          $any_item = $_POST['any_item'];
                          $any_item_value = $_POST['any_item_value'];

                          if($any_item == 'Write any item' || $any_item_value == 0)
                          {
                             $any_item_value = 0;
                          }
                          $selected_month = $_POST['selected_month'];
                          $comment = $_POST['comment'];
                          $student_id = $_POST['student_id'];
                          $student_info = $db->find_by_sql("*","student_info","student_id='$student_id'","");
                          $total_payable = array_sum($amount)+ $any_item_value ;
                          $total_paid = trim($_POST['paid']);
                          $exchange = $total_paid - $total_payable;
                          
                          
                           foreach ($amount as $key => $value)
                           {
                                if(!is_numeric($value))
                                {
                                    $selected_month = $_POST['selected_month'];
                                    echo '<div id="message" style="color:darkred;margin-top:20px;"><b>Invalid value ('.$value.') passed for particular: '.$key.'</b></div>';
                                    echo other_receipt_when_calculate($student_id,$selected_month,$amount,$any_item,$any_item_value,$year);
                                    $error = 'error';
                                    break;
                                }
                           }
                           if($error !== 'error')
                           {
                                if(!is_numeric($any_item_value))
                                {
                                         $selected_month = $_POST['selected_month'];
                                         echo '<div id="message" style="color:darkred;margin-top:20px;"><b>Invalid value ('.$any_item_value.') passed for particular: '.$any_item.'</b></div>';
                                         echo other_receipt_when_calculate($student_id,$selected_month,$amount,$any_item,$any_item_value);
                                         $error = 'error';
                                }
                           }
                          
                         if($error !== 'error') 
                         {
                                    if(!is_numeric($total_paid) || ($total_paid < 0))
                                    {
                                        $selected_month = $_POST['selected_month'];
                                        echo '<div id="message" style="color:darkred;margin-top:20px;"><b>Invalid value ('.$total_paid.') passed for paid amount</b></div>';
                                        echo other_receipt_when_calculate($student_id,$selected_month,$amount,$any_item,$any_item_value,$year);
                                    }elseif($total_paid < $total_payable)
                                    { 
                                        $selected_month = $_POST['selected_month'];
                                        echo '<div id="message" style="color:darkred;margin-top:20px;"><b>Paid amount ('.$total_paid.') is not enough</b></div>';
                                        echo other_receipt_when_calculate($student_id,$selected_month,$amount,$any_item,$any_item_value,$year);
                                    }else{
                                                if($exchange < 0)
                                                {
                                                   $final_exchange = 0;
                                                   $final_due = abs($exchange);
                                                }else{
                                                   $final_exchange = $exchange;
                                                   $final_due = 0;
                                                }

                                               $reload_permission = $db->find_by_sql("reload_permission","permission","username='$user115122'","");

                                               if($reload_permission[0]['reload_permission'] !== 'Cancel')
                                               {
                                                        foreach ($selected_month as $month_value)
                                                        {
                                                                foreach ($amount as $key => $value) 
                                                                {
                                                                    if($value != 0)
                                                                    {
                                                                        $insert_transaction_receipt_item = $db->insert("transaction_receipt_item","year,month,date,time,receipt_item,student_id,paid,username","'$current_year','$month_value','$date','$time','$key','$student_id','$value','$user115122'","");
                                                                    }
                                                                }
                                                            if(!($any_item == 'Write any item' || $any_item_value == 0))
                                                            {
                                                                $insert_transaction_receipt_item = $db->insert("transaction_receipt_item","year,month,date,time,receipt_item,student_id,paid,username","'$current_year','$month_value','$date','$time','$any_item','$student_id','$any_item_value','$user115122'","");
                                                                 
                                                                 if($db->find_by_sql("*","extra_added_items","item='$any_item' ","") == 'No Result Found')
                                                                 {
                                                                      $insert_extra_item = $db->insert("extra_added_items","item","'$any_item'","");
                                                                 }
                                                                
                                                            }
                                                        }        
                                                     $insert_transaction = $db->insert("transaction_archive","year,month,date,time,student_id,payable,paid,due,exchange,payment_type,username","'$current_year','$current_month','$date','$time','$student_id','$total_payable','$total_paid','$final_due','$final_exchange','Other Fee','$user115122'","");
                                               
                                                     $fullmonth = month_selection(substr(date('Y-m-d'),5,2));
                                                     $select_last_receipt = $db->find_by_sql("receipt_id","receipt_id WHERE year='$current_year' AND month='$fullmonth' ORDER BY id DESC","","1");

                                                     if($select_last_receipt !== 'No Result Found')
                                                     {  $final_receipt_id = substr($select_last_receipt[0]['receipt_id'],7,100000); $final_receipt_id = $current_year.''.date('m').'-'.++$final_receipt_id; }
                                                     else{ $final_receipt_id = $current_year.''.date('m').'-'.'1'; }
                                                     $insert_receipt_id = $db->insert("receipt_id","year,month,date,time,student_id,receipt_id,username","'$current_year','$fullmonth','$date','$time','$student_id','$final_receipt_id','$user115122'","");
                                               }

                                            $update_reload_permission = $db->update("permission","reload_permission='Cancel'","username='$user115122'");
                                            
                                            $receipt_id = $db->find_by_sql("receipt_id","receipt_id  WHERE  username='$user115122' ORDER BY id DESC","","1");
                                            $section = $db->find_by_sql("DISTINCT section","student_on_section","student_id='$student_id'","");
                                            
                                            if($section == 'No Result Found'){ $section = ''; }else{ $section = $section[0]['section']; }
                                            
                        
                                            echo '<div id="report" style="display:none;">';
                                                       
                                                        echo '<div style="width:550px;height:90%;margin:20px auto 0 auto;font-family:calibri;">';
                                                           echo '<div style="width:450px;height:110px;margin:auto;font-size:20px;text-align:center;" ><img src="images/demo_logo.png" style="float:left;"  width="60px" height="60px" ><b>DEMO INTERNATIONAL SCHOOL<br><span style="font-size:12px;">Demo Address</span></b></div>';
                                                           echo '<div style="width:545px;height:70px;font-size:14px;margin:auto;text-align:left;" ><b>Student Id: </b>'.$student_id.' &nbsp; &nbsp; <b>Class Roll: </b>'.$student_info[0]['class_roll'].'<br><br><b>Name: </b>'.$student_info[0]['student_name'].' &nbsp; &nbsp; <b>Section: </b>'.$section.'</div>';
                                                           echo '<table  style="font-family:calibri;font-size:14px;margin:10px auto;border:1px solid black;border-collapse: collapse;border-radius:3px;" border="1" cellpadding="2">';
                                                              echo '<tr>';
                                                                     echo '<td style="width:50px;text-align:center;"><b>S.L</b></td>';
                                                                     echo '<td style="width:330px;"><b>Particulars</b></td>';
                                                                     echo '<td style="width:170px;text-align:right;"><b>Amount(Tk)</b></td>';
                                                              echo '</tr>';
                                                              $i = 1;
                                                              foreach ($amount as $key => $value)
                                                              {
                                                                  echo '<tr>';
                                                                        echo '<td style="text-align:center;">'.$i.'</td>';
                                                                        echo '<td>'.$key.'</td>';
                                                                        echo '<td style="text-align:right;">'.$value.'</td>';
                                                                  echo '</tr>';
                                                                  $i++;
                                                              }
                                                               if(!($any_item == 'Write any item' || $any_item_value == 0))
                                                                {
                                                                        echo '<tr>';
                                                                            echo '<td style="text-align:center;">'.$i.'</td>';
                                                                            echo '<td>'.$any_item.'</td>';
                                                                            echo '<td style="text-align:right;">'.$any_item_value.'</td>';
                                                                        echo '</tr>';
                                                                }
                                                              echo '<tr>';
                                                                     echo '<td style="text-align:right;" colspan="2"><b>Payable</b></td>';
                                                                     echo '<td style="text-align:right;"><b>'.$total_payable.'</b></td>';
                                                              echo '</tr>';
                                                              echo '<tr>';
                                                                     echo '<td style="text-align:right;" colspan="2"><b>Paid</b></td>';
                                                                     echo '<td style="text-align:right;"><b>'.$total_paid.'</b></td>';
                                                              echo '</tr>';
                                                              echo '<tr>';
                                                                     echo '<td style="text-align:right;" colspan="2"><b>Exchange</b></td>';
                                                                     echo '<td style="text-align:right;"><b>'.$final_exchange.'</b></td>';
                                                              echo '</tr>';
                                                              
                                                           echo '</table>';
                                                           echo '<div style="width:545px;height:20px;font-size:14px;margin: 20px auto 0 auto;text-align:left;" ><b>Paid Month: '.$selected_month[0].'</b></div>';
                                                           echo '<div style="width:545px;height:50px;font-size:14px;margin: 10px auto;text-align:left;" ><b>In Word: </b>'.convert_number_to_words($total_payable).' taka only</div>';
                                                           echo '<div style="width:180px;font-size:14px;border:1px solid transparent;border-top-color:black;float:left;text-align:center;"><b>Paid by</b></div>';
                                                           echo '<div style="width:180px;font-size:14px;border:1px solid transparent;border-top-color:black;float:right;text-align:center;"><b>Received by<br>( '.$user115122.' )</b></div>';
                                                           echo '<div style="width:545px;height:50px;margin: 10px auto;text-align:left;float:left;font-size:12px;" ><b>Date: </b>'.$date.'<br><b>Receipt No: </b>'.$receipt_id[0]['receipt_id'].'</div>';
                                                        echo '</div>';
                                                
                                            echo '</div>';
                                            echo '<script type="text/javascript">PrintElem(report)</script>' ;
                                            echo '<div style="margin:100px auto;width:500px;">Transaction has been saved. If not printed automatically, click <input type="button" id="submit_button" class="submit_button" value="Print"   onClick="PrintElem(report)" ></div>'  ;

                                    }
                         }
                  }
}
elseif($_GET['subcat'] == 'Old Student Fee')
{
      if(isset($_POST['submit']))
      {
            $update_reload_permission = $db->update("permission","reload_permission='Yes'","username='$user115122'");
                $student_id = $_POST['student_id'];
                $student_name = $_POST['student_name'];
                $class_roll = $_POST['class_roll'];
                $section = $_POST['section'];
                $selected_item = $_POST['selected_item'];
                echo '<div style="width:300px;margin:auto;height:40px;"></div>';
                echo old_student_form("Student Identity",$student_name,$student_id,$class_roll,$section,$selected_item); 
                if(empty($selected_item) == True)
                {
                     echo '<div id="message"><b>Please select any particular item</b></div>';
                }else{
                   echo old_student_receipt($student_name,$student_id,$class_roll,$section,$selected_item);
                }
      }elseif($_POST['calculate'])
      {
                $student_id = $_POST['student_id'];
                $student_name = $_POST['student_name'];
                $class_roll = $_POST['class_roll'];
                $section = $_POST['section'];
                $amount = $_POST['amount'];
                $selected_item = $_POST['selected_item'];
                echo '<div style="width:300px;margin:auto;height:40px;"></div>';
                echo old_student_form("Student Identity",$student_name,$student_id,$class_roll,$section,$selected_item); 
                echo old_student_receipt_when_calculate($amount,$student_name,$student_id,$class_roll,$section,$selected_item);
      }elseif($_POST['save&print'])
      {
                        date_default_timezone_set('Asia/Dhaka');
                          $date = date('Y-m-d') ;
                          $time = date('h:i:s A') ;

                          $current_year = date("Y");
                          $current_month = date("M");

                        $student_id = $_POST['student_id'];
                        $student_name = $_POST['student_name'];
                        $class_roll = $_POST['class_roll'];
                        $section = $_POST['section'];
                        $amount = $_POST['amount'];
                        $selected_item = $_POST['selected_item'];
                        $total_payable = array_sum($amount)+ $any_item_value ;
                          $total_paid = trim($_POST['paid']);
                          $exchange = $total_paid - $total_payable;
                          
                          
                           foreach ($amount as $key => $value)
                           {
                                if(!is_numeric($value))
                                {
                                    echo '<div style="width:300px;margin:auto;height:40px;"></div>';
                                    echo old_student_form("Student Identity",$student_name,$student_id,$class_roll,$section,$selected_item);
                                    echo '<div id="message" style="color:#CC3300;"><b>Invalid value ('.$value.') passed for particular: '.$key.'</b></div>';
                                    echo old_student_receipt_when_calculate($amount,$student_name,$student_id,$class_roll,$section,$selected_item);                                   
                                    
                                    $error = 'error';
                                    break;
                                }
                           }
                           
                           if($error !== 'error') 
                            {
                                       if(!is_numeric($total_paid) || ($total_paid < 0))
                                       {
                                           echo '<div style="width:300px;margin:auto;height:40px;"></div>';
                                           echo old_student_form("Student Identity",$student_name,$student_id,$class_roll,$section,$selected_item);
                                           echo '<div id="message" style="color:#CC3300;"><b>Invalid value ('.$total_paid.') passed for paid amount</b></div>';
                                           echo old_student_receipt_when_calculate($amount,$student_name,$student_id,$class_roll,$section,$selected_item); 
                                           
                                       }elseif($total_paid < $total_payable)
                                       { 
                                           echo '<div style="width:300px;margin:auto;height:40px;"></div>';
                                           echo old_student_form("Student Identity",$student_name,$student_id,$class_roll,$section,$selected_item);
                                           echo '<div id="message" style="color:#CC3300;"><b>Paid amount ('.$total_paid.') is not enough</b></div>';
                                           echo old_student_receipt_when_calculate($amount,$student_name,$student_id,$class_roll,$section,$selected_item);
                                       }else{
                                                   if($exchange < 0)
                                                   {
                                                      $final_exchange = 0;
                                                      $final_due = abs($exchange);
                                                   }else{
                                                      $final_exchange = $exchange;
                                                      $final_due = 0;
                                                   }

                                                  $reload_permission = $db->find_by_sql("reload_permission","permission","username='$user115122'","");
                                                   $fullmonth = month_selection(substr(date('Y-m-d'),5,2));
                                                  if($reload_permission[0]['reload_permission'] !== 'Cancel')
                                                  {
                                                               foreach ($amount as $key => $value) 
                                                                {
                                                                   $insert_transaction_receipt_item = $db->insert("transaction_receipt_item","year,month,date,time,receipt_item,student_id,paid,username","'$current_year','$fullmonth','$date','$time','$key','$student_id','$value','$user115122'","");
                                                                }
                                                                 
                                                        $insert_transaction = $db->insert("transaction_archive","year,month,date,time,student_id,payable,paid,due,exchange,payment_type,username","'$current_year','$current_month','$date','$time','$student_id','$total_payable','$total_paid','$final_due','$final_exchange','Old Student Fee','$user115122'","");

                                                        $select_last_receipt = $db->find_by_sql("receipt_id","receipt_id WHERE year='$current_year' AND month='$fullmonth' ORDER BY id DESC","","1");

                                                        if($select_last_receipt !== 'No Result Found')
                                                        {  $final_receipt_id = substr($select_last_receipt[0]['receipt_id'],7,100000); $final_receipt_id = $current_year.''.date('m').'-'.++$final_receipt_id; }
                                                        else{ $final_receipt_id = $current_year.''.date('m').'-'.'1'; }
                                                        $insert_receipt_id = $db->insert("receipt_id","year,month,date,time,student_id,receipt_id,username","'$current_year','$fullmonth','$date','$time','$student_id','$final_receipt_id','$user115122'","");
                                                  }

                                               $update_reload_permission = $db->update("permission","reload_permission='Cancel'","username='$user115122'");

                                               $receipt_id = $db->find_by_sql("receipt_id","receipt_id  WHERE  username='$user115122' ORDER BY id DESC","","1");


                                               echo '<div style="width:300px;margin:auto;height:40px;"></div>';
                                               echo old_student_form("Student Identity",$student_name,$student_id,$class_roll,$section,$selected_item);

                                               echo '<div id="report" style="display:none;">';

                                                           echo '<div style="width:550px;height:90%;margin:20px auto 0 auto;font-family:calibri;">';
                                                              echo '<div style="width:450px;height:110px;margin:auto;font-size:20px;text-align:center;" ><img src="images/demo_logo.png" style="float:left;"  width="60px" height="60px" ><b>DEMO INTERNATIONAL SCHOOL<br><span style="font-size:12px;">Demo Address</span></b></div>';
                                                              echo '<div style="width:545px;height:70px;font-size:14px;margin:auto;text-align:left;" ><b>Student Id: </b>'.$student_id.' &nbsp; &nbsp; <b>Class Roll: </b>'.$class_roll.'<br><br><b>Name: </b>'.$student_name.' &nbsp; &nbsp; <b>Section: </b>'.$section.'</div>';
                                                              echo '<table  style="font-family:calibri;font-size:14px;margin:10px auto;border:1px solid black;border-collapse: collapse;border-radius:3px;" border="1" cellpadding="2">';
                                                                 echo '<tr>';
                                                                        echo '<td style="width:50px;text-align:center;"><b>S.L</b></td>';
                                                                        echo '<td style="width:330px;"><b>Particulars</b></td>';
                                                                        echo '<td style="width:170px;text-align:right;"><b>Amount(Tk)</b></td>';
                                                                 echo '</tr>';
                                                                 $i = 1;
                                                                 foreach ($amount as $key => $value)
                                                                 {
                                                                     echo '<tr>';
                                                                           echo '<td style="text-align:center;">'.$i.'</td>';
                                                                           echo '<td>'.$key.'</td>';
                                                                           echo '<td style="text-align:right;">'.$value.'</td>';
                                                                     echo '</tr>';
                                                                     $i++;
                                                                 }
                                                                  if(!($any_item == 'Write any item' || $any_item_value == 0))
                                                                   {
                                                                           echo '<tr>';
                                                                               echo '<td style="text-align:center;">'.$i.'</td>';
                                                                               echo '<td>'.$any_item.'</td>';
                                                                               echo '<td style="text-align:right;">'.$any_item_value.'</td>';
                                                                           echo '</tr>';
                                                                   }
                                                                 echo '<tr>';
                                                                        echo '<td style="text-align:right;" colspan="2"><b>Payable</b></td>';
                                                                        echo '<td style="text-align:right;"><b>'.$total_payable.'</b></td>';
                                                                 echo '</tr>';
                                                                 echo '<tr>';
                                                                        echo '<td style="text-align:right;" colspan="2"><b>Paid</b></td>';
                                                                        echo '<td style="text-align:right;"><b>'.$total_paid.'</b></td>';
                                                                 echo '</tr>';
                                                                 echo '<tr>';
                                                                        echo '<td style="text-align:right;" colspan="2"><b>Exchange</b></td>';
                                                                        echo '<td style="text-align:right;"><b>'.$final_exchange.'</b></td>';
                                                                 echo '</tr>';

                                                              echo '</table>';
                                                              //echo '<div style="width:545px;height:20px;font-size:14px;margin: 20px auto 0 auto;text-align:left;" ><b>Paid Month: '.$selected_month.'</b></div>';
                                                              echo '<div style="width:545px;height:50px;font-size:14px;margin: 10px auto;text-align:left;" ><b>In Word: </b>'.convert_number_to_words($total_payable).' taka only</div>';
                                                              echo '<div style="width:180px;font-size:14px;border:1px solid transparent;border-top-color:black;float:left;text-align:center;"><b>Paid by</b></div>';
                                                              echo '<div style="width:180px;font-size:14px;border:1px solid transparent;border-top-color:black;float:right;text-align:center;"><b>Received by<br>( '.$user115122.' )</b></div>';
                                                              echo '<div style="width:545px;height:50px;margin: 10px auto;text-align:left;float:left;font-size:12px;" ><b>Date: </b>'.$date.'<br><b>Receipt No: </b>'.$receipt_id[0]['receipt_id'].'</div>';
                                                           echo '</div>';

                                               echo '</div>';
                                               echo '<script type="text/javascript">PrintElem(report)</script>' ;
                                               echo '<div style="margin:100px auto;width:500px;">Transaction has been saved. If not printed automatically, click <input type="button" id="submit_button" class="submit_button" value="Print"   onClick="PrintElem(report)" ></div>'  ;

                                       }
                            }
      }else{
          echo '<div style="width:300px;margin:auto;height:40px;"></div>';
          echo old_student_form("Fill up this form",$student_name,$student_id,$class_roll,$section,$selected_item); 
      }
}
elseif($_GET['subcat'] == 'Print Paid Receipt')
{
    
       echo '<div style="width:400px;height:35px;padding-top:5px;margin:auto;">';
                      echo student_id();
       echo'</div>';     
      if(isset($_POST['submit']))
      {
            $student_id = $_POST['student_id'];
            $student_info = $db->find_by_sql("*","student_info","student_id='$student_id'","");
            $selected_month = $_POST['selected_month'];
            if($student_info == 'No Result Found')
            {
                 echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                       <div id="message" ><b>No Result Found</b></div>';
            }else{
                        echo '<div style="width:300px;margin:20px auto;"><b>Selected Student Id: </b>'.$student_id.'</div>';
                        echo year_n_month_field($student_id);                       
                 }
          
      }
      
      if(isset($_POST['year']) || isset($_POST['month']))
      {
                $student_id = $_POST['student_id'];
                $student_info = $db->find_by_sql("*","student_info","student_id='$student_id'","");
                $section = $db->find_by_sql("DISTINCT section","student_on_section","student_id='$student_id'","");
                if($section == 'No Result Found'){ $section = ''; }else{ $section = $section[0]['section']; }
                $year  = $_POST['year'];
                $month = $_POST['month'];
                $current_date = date('Y-m-d') ;
                $subsmonth = substr($month,0,3);
                if($year == 'Available Years' || $month == 'Months')
                {
                    echo '<div id="message" style="margin:100px;color:#CC3300;"><b>Please select any available year and month</b></div>';
                }else{
                          $date = $db->find_by_sql("DISTINCT date,time,payment_type","transaction_archive","year='$year' AND month='$subsmonth' AND student_id='$student_id'","");

                          if($date !== 'No Result Found')
                          {
                                 echo '<table style="margin:30px auto 0 auto;border:1.5px solid #86b300;" border="1" cellpadding="3">';
                                 echo '<tr style="background:#86b300;color:#fff;">';
                                        echo '<td><b>Date</b></td>';
                                        echo '<td><b>Time</b></td>';
                                        echo '<td><b>Paid as</b></td>';
                                        echo '<td><b>Print</b></td>';
                                 echo '</tr>';
                                 $x = 1;
                                foreach ($date as $date_value)
                                {
                                        $ppde = $db->find_by_sql("payable,paid,due,exchange","transaction_archive","year='$year' AND date='{$date_value['date']}' AND time='{$date_value['time']}' AND student_id='$student_id'","");
                                        $months_of_paid = $db->find_by_sql("DISTINCT month","transaction_receipt_item","year='$year' AND date='{$date_value['date']}' AND time='{$date_value['time']}' AND student_id='$student_id'","");
                                           
                                        $transaction_receipt_item = $db->find_by_sql("DISTINCT receipt_item","transaction_receipt_item","year='$year' AND date='{$date_value['date']}' AND time='{$date_value['time']}' AND student_id='$student_id'","");
                                        echo '<tr>';
                                            echo '<td style="width:200px;"><b>'.$date_value['date'].'</b></td>';
                                            echo '<td style="width:200px;"><b>'.$date_value['time'].'</b></td>';
                                            echo '<td style="width:200px;"><b>'.$date_value['payment_type'].'</b></td>';
                                            echo '<td style="width:200px;">';
                                            
                                            echo '<div id="report'.$x.'"  style="display:none;">';
                                                        echo '<div style="width:550px;height:90%;margin:20px auto 0 auto;font-family:calibri;">';
                                                           echo '<div style="width:450px;height:110px;margin:auto;font-size:20px;text-align:center;" ><img src="images/demo_logo.png" style="float:left;"  width="60px" height="60px" ><b>DEMO INTERNATIONAL SCHOOL<br><span style="font-size:12px;">Demo Address</span></b></div>';
                                                           echo '<div style="width:545px;height:70px;font-size:14px;margin:auto;text-align:left;" ><b>Student Id: </b>'.$student_id.' &nbsp; &nbsp; <b>Class Roll: </b>'.$student_info[0]['class_roll'].'<br><br><b>Name: </b>'.$student_info[0]['student_name'].' &nbsp; &nbsp; <b>Section: </b>'.$section.'</div>';
                                                           echo '<table  style="font-family:calibri;font-size:14px;margin:10px auto;border:1px solid black;border-collapse: collapse;border-radius:3px;" border="1" cellpadding="2">';
                                                              echo '<tr>';
                                                                     echo '<td style="width:50px;text-align:center;"><b>S.L</b></td>';
                                                                     echo '<td style="width:330px;"><b>Particulars</b></td>';
                                                                     echo '<td style="width:170px;text-align:right;"><b>Amount(Tk)</b></td>';
                                                              echo '</tr>';
                                                              $i = 1;
                                                              foreach ($transaction_receipt_item as $item_value)
                                                              {
                                                                  $total_paid = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","year='$year' AND date='{$date_value['date']}' AND time='{$date_value['time']}' AND receipt_item='{$item_value['receipt_item']}' AND student_id='$student_id'","");
                                                                  echo '<tr>';
                                                                      echo '<td style="text-align:center;">'.$i.'</td>';
                                                                        echo '<td>'.$item_value['receipt_item'].'</td>';
                                                                       echo '<td style="text-align:right;">'.$total_paid[0]['total_paid'].'</td>';
                                                                    
                                                                  echo '</tr>';
                                                                  $i++;
                                                              }
                                                              echo '<tr>';
                                                                     echo '<td style="text-align:right;" colspan="2"><b>Payable</b></td>';
                                                                     echo '<td style="text-align:right;"><b>'.$ppde[0]['payable'].'</b></td>';
                                                              echo '</tr>';
                                                              echo '<tr>';
                                                                     echo '<td style="text-align:right;" colspan="2"><b>Paid</b></td>';
                                                                     echo '<td style="text-align:right;"><b>'.$ppde[0]['paid'].'</b></td>';
                                                              echo '</tr>';
                                                              echo '<tr>';
                                                                     echo '<td style="text-align:right;" colspan="2"><b>Exchange</b></td>';
                                                                     echo '<td style="text-align:right;"><b>'.$ppde[0]['exchange'].'</b></td>';
                                                              echo '</tr>';
                                                              
                                                           echo '</table>';
                                                           echo '<div style="width:545px;height:20px;font-size:14px;margin: 20px auto 0 auto;text-align:left;" ><b>Paid Month: ';
                                                           $z = 1;
                                                           foreach ($months_of_paid as $months_val)
                                                           {
                                                               if(count($months_of_paid) == $z )
                                                               { echo $months_val['month']; }
                                                               else{ echo $months_val['month'].', '; }
                                                               $z++;
                                                           }
                                                           echo'</b></div>';
                                                           
                                                           $received_by = $db->find_by_sql("username","receipt_id","year='$year' AND date='{$date_value['date']}' AND time='{$date_value['time']}' AND student_id='$student_id'","");
                                                           
                                                           echo '<div style="width:545px;height:50px;font-size:14px;margin: 10px auto;text-align:left;" ><b>In Word: </b>'.convert_number_to_words($ppde[0]['payable']).' taka only</div>';
                                                           echo '<div style="width:180px;font-size:14px;border:1px solid transparent;border-top-color:black;float:left;text-align:center;"><b>Paid by</b></div>';
                                                           echo '<div style="width:180px;font-size:14px;border:1px solid transparent;border-top-color:black;float:right;text-align:center;"><b>Received by<br>( '.$received_by[0]['username'].' )</b></div>';
                                                           
                                                           $receipt_id = $db->find_by_sql("receipt_id","receipt_id","year='$year' AND date='{$date_value['date']}' AND time='{$date_value['time']}' AND student_id='$student_id'","");
                                                           echo '<div style="width:545px;height:50px;margin: 10px auto;text-align:left;float:left;font-size:12px;" ><b>Date: </b>'.$date_value['date'].'(Previous publish), '.$current_date.'<br><b>Receipt No: </b>'.$receipt_id[0]['receipt_id'].' (Duplicate Copy)</div>';
                                                        echo '</div>';
                                                
                                            echo '</div>';
                                            echo '<div style="margin:10px auto;width:100px;"><input type="button" id="submit_button" class="submit_button" value="Print"  onClick="PrintElem(report'.$x.')" ></div>'  ;
                                            echo '</td>';
                                        echo '</tr>';                      
                                        $x++;
                                }
                                echo '</table>';
                          }else{
                                echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                 <div id="message" ><b>No Result Found</b></div>';
                          }
                }
      }
      
      
}
else{
    echo 'No Page Found';
}
          ?> 
          
  </div>

    
<!--    <script type="text/javascript">
         function showResult(str,str1)
            {

            if (window.XMLHttpRequest)
              {// code for IE7+, Firefox, Chrome, Opera, Safari
              xmlhttp=new XMLHttpRequest();
              }
            else
              {// code for IE6, IE5
              xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
              }
            xmlhttp.onreadystatechange=function()
              {
              if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                 document.getElementById("livesearch").innerHTML=xmlhttp.responseText;
                }
              }

            xmlhttp.open("GET","payment_status.php?year="+str+"&student_id="+str1,true);
            xmlhttp.send();

            }
     </script>-->
    

 <script type="text/javascript" src="javascripts/macc_javascripts.js"> </script>
<?php require 'includes/footer.php';   ?>
