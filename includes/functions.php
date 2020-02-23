<?php

function student_id()
{
    $r  = '';
    $r .= '<form action="" method="post">';
    $r .='<table cellpadding="5">';
    $r .='<tr>';
    $r .='<td><b>Student Id</b> &nbsp;<input type="text" style="border:2px solid #9933ff;width:200px;height:20px;border-radius:10px;" name="student_id" required></td>';
    $r .='<td><input type="submit" id="submit_button" name="submit" value="Submit"></td>';
    $r .='</tr>';
    $r .='</table>';
    $r .= '</form>';
    
    return $r;
}


function year($student_id)
{ global $db;
     $years = $db->find_by_sql("*","years ORDER BY year DESC","","");
	 $r = '';
   $r .= '<form action="" method="post">';
     $r .= '<table style="margin:auto;" cellpadding="10">';
       $r .= '<tr>';
       $r .= '<td><select name="year"  title="Available Years"  id="section" onchange="this.form.submit()">'
               . '<option value="Available Years">Available Years</option>';
       foreach ($years as $year_value)
       {
           $r.='<option value="'.$year_value['year'].'">'.$year_value['year'].'</option>';
       }
       $r .='</select></td>';
       
       $r .='<input type="hidden" name="student_id" value="'.$student_id.'" >';
       $r .= '</tr>';
     $r .= '</table>';
    $r .= '</form>';
    
    return $r;
}


function months_field($student_id,$selected_month,$year)
{
    global $db;

    $months = $db->find_by_sql("*","months ORDER BY id","",""); 
    if(empty($selected_month) == FALSE)
    { $selected_month = $db->set_checked($selected_month,'checked'); }
    
	 $r = '';
     $r .= '<div id="student_identity" style="background:Gainsboro ;padding:10px;width:850px;margin:30px auto;">';
      $r .= '<form action="" method="post">';
      $r .= '<table style="margin:auto;font-size:14px;" cellpadding="2">';
      $r .= '<tr>';
      foreach ($months as $value)
      {
            $r .= '<td><input type="checkbox" class="case" name="selected_month[]" value="'.$value["month"].'" '.$selected_month[$value['month']].'></td>' ;
            $r .= '<td style="width:100px;text-align:left;"><b>'.$value["month"].'</b></td>';
      }
      $r .= '</tr>';
      $r .= '</table>';
        $r .= '<input type="hidden" name="submit" value="">';
        $r .= '<input type="hidden" name="student_id" value="'.$student_id.'">';
        $r .= '<input type="hidden" name="year" value="'.$year.'">';
        $r .= '<p style="width:70px;margin:10px auto 0 auto;"><input type="submit" style="float:right;" id="submit_button" name="month_proceed" value="Submit"></p>';
      $r .= '</form>';
     $r .= '</div>';
     
     return $r;
}


function student_info($student_id,$student_info)
{
    global $db;
     $section = $db->find_by_sql("DISTINCT section","student_on_section","student_id='$student_id'","");
     if($section == 'No Result Found'){ $section = ''; }else{ $section = $section[0]['section']; }   
    $r = '';
     $r .= '<div id="student_identity">';
      $r .= '<table style="margin:auto 20px;font-size:14px;" cellpadding="2">';
      $r .= '<tr>';
      $r .= '<td style="color:red;padding:5px 0;" colspan="3"><b>STUDENT IDENTITY</b></td>';
      $r .= '</tr>';
      $r .= '<tr>';
      $r .= '<td style="width:300px;text-align:left;"><b>Student Id:</b> '.$student_info[0]['student_id'].'</td>';
      $r .= '<td style="width:260px;text-align:right;"><b>Class Roll:</b> '.$student_info[0]['class_roll'].'</td>';
      $r .= '</tr>';
      $r .= '<tr>';
      $r .= '<td style="width:300px;text-align:left;"><b>Student Name:</b> '.$student_info[0]['student_name'].'</td>';
      $r .= '<td style="width:260px;text-align:right;"><b>Section:</b> '.$section.'</td>';
      $r .= '</tr>';
      $r .= '</table>';
     $r .= '</div>';
     
     return $r;
     
}


function monthly_payment_status($student_id,$year)
{
    global $db;
    date_default_timezone_set('Asia/Dhaka');
    
    $current_year = $year;
    
     $months = $db->find_by_sql("*","months ORDER BY id","","");   
    
	 $r = '';
     $r .= '<div style="text-align:center;border:1.5px solid transparent;height:23px;padding:10px 0 0 0;width:300px;margin:auto;">';
     $r .= '<b>Payment Status (Year:</b>'.$current_year.')';
     $r .= '</div>';
     $r .= '<div style="border:1.5px solid #000;padding:0px 10px 10px 10px;height:150px;width:90%;margin:15px auto;overflow:auto;border-radius:20px;">';
	 
      $r .= '<table style="margin:18px auto;font-size:14px;border:1px solid gray;" cellpadding="3" border="1">';
      $r .= '<tr>';
      foreach ($months as $value)
      {
         $payment_status = $db->find_by_sql("status","payment_status","student_id='$student_id' AND month='{$value['month']}' AND year='$current_year'","");
         $academic_year = $db->find_by_sql("DISTINCT academic_year","receipt_items_amount_on_student","student_id='$student_id' AND month='{$value['month']}' AND year='$current_year' ","");
         
         if($academic_year == 'No Result Found')
         { $academic_year = 'Not set'; }elseif(empty ($academic_year[0]['academic_year']) == TRUE){$academic_year = 'Not set';} else { $academic_year = $academic_year[0]['academic_year']; }
         
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
				if($color == "black")
				{  
					$r .= '<tr style="background:#86b300;">';
				}else{
					$r .= '<tr style="background:whitesmoke;">';
				}
                $r .= '<td style="width:130px;text-align:center;color:'.$color.'"><b>'.$value['month'].' ( '.$academic_year.' )</b></td>';
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
										  if($payment_status == "Full paid" && $color != "lightgray")
										  {
											  $r .= '<td style="width:170px;text-align:center;color:green;">'.$payment_status.'</td>';
										  }else if($payment_status == "Not paid" && $color != "lightgray"){
											  $r .= '<td style="width:170px;text-align:center;color:red;">'.$payment_status.'</td>';
										  }else{
											  $r .= '<td style="width:170px;text-align:center;color:'.$color.';">'.$payment_status.'</td>';
										  }
                                $r .= '</tr>';
                          $r .= '</table>';
                    $r .= '<td>';     
                $r .= '</tr>';
            $r .= '</table>';
         $r .= '</td>';
      }
      $r .= '</tr>';
      $r .= '</table>';
     $r .= '</div>';
 /*    
     $years = $db->find_by_sql("*","years ORDER BY year DESC","","");
          
     $r .= '<div id="student_identity"  style="border:3px solid #C0C0C0;padding:10px 10px 10px 10px;height:80px;width:20%;margin:30px 3% 0 0;float:right;position:relative;">';
     
     $r .= '<table style="border:1px solid gray;font-size:14px;color:darkred;" border="1" cellpadding="5">';
     $r .= '<tr>';
          $r .= '<td colspan="3"><b>Due of All Years</b></td>';
     $r .= '</tr>';
     $r .= '<tr>';
          $r .= '<td><b>Year</b></td>';
          $r .= '<td style="width:60px;"><b>Month</b></td>';
          $r .= '<td><b>Amount has to pay</b></td>';
     $r .= '</tr>';
        $count_due = 0;
     foreach ($years as $year_value)
     {
            foreach ($months as $value)
             {
                   $payment_status = $db->find_by_sql("status","payment_status","student_id='$student_id' AND month='{$value['month']}' AND year='{$year_value['year']}'","");

                   if($payment_status == 'No Result Found')
                   { 
                       //Not paid
                       $payable = $db->find_by_sql("SUM(amount) AS total_payable","receipt_items_amount_on_student","student_id='$student_id' AND month='{$value['month']}' AND year='{$year_value['year']}' ","");

                       if(empty($payable[0]['total_payable']) == FALSE)
                       {   $count_due++;
                           $payable = $payable[0]['total_payable']; ; 
                           $r .= '<tr>';
                                $r .= '<td>'.$year_value['year'].'</td>';
                                $r .= '<td>'.$value['month'].'</td>';
                                $r .= '<td>'.$payable.' (Not paid)</td>';
                           $r .= '</tr>';
                       }

                   }elseif($payment_status[0]['status'] == 'Not full paid')
                   {
                         //Not full paid
                        $count_due++;
                       $items_due = $db->find_by_sql("SUM(due) as total_due","due","student_id='$student_id' AND month='{$value['month']}' AND year='{$year_value['year']}'","");
                       $r .= '<tr>';
                                $r .= '<td>'.$year_value['year'].'</td>';
                                $r .= '<td>'.$value['month'].'</td>';
                                $r .= '<td>'.$items_due[0]['total_due'].' (Monthly due)</td>';
                           $r .= '</tr>';
                   }
             }
     }
     if($count_due == 0)
     {
         $r .= '<tr>';
          $r .= '<td colspan="3"><b>No due found</b></td>';
         $r .= '</tr>';
     }
     $r .= '</table>';
     $r .= '</div>';
    */ 
     return $r;
     
}


//I set receipt item will fetch first fifteen  LIMIT 0,16 sothat from 17, items will show on others payment section
function identity_n_receipt($student_id,$selected_month,$year)
{
    global $db;
    date_default_timezone_set('Asia/Dhaka');
    $date = date('Y-m-d') ;
    $time = date('h:i:s A') ;
    $current_year = date("Y");
    $current_month = date("M");
     
      //I set receipt item will fetch first fifteen  LIMIT 0,16
      $receipt_items =  $db->find_by_sql("item,instant_change_permission,appear_permission_when_due_clear","receipt_items ORDER BY LENGTH(item_order),item_order LIMIT 0,16","","");
      
     $r .= '<div id="receipt" style="margin:50px auto;border-radius:10px;">';
      $r .= '<div style="width:700px;height:20px;background:#0099CC;color:#fff;text-align:center;"><b>PAYMENT RECEIPT</b></div>';
      $r .= '<form action="" method="post">';
      $r .= '<table style="margin:auto;border:1px solid #000;font-size:14px;" border="1" cellpadding="1">';
      $i = 0;
      $r .= '<tr>';
      $r .= '<td><b>Click to postponed</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><b>S.L.</b></td>';
      $r .= '<td style="width:280px;text-align:center;"><b>Particulars</b></td>';
      $r .= '<td style="width:150px;text-align:center;"><b>Amount(Tk)</b></td>';
      $r .= '</tr>';

            foreach ($receipt_items as $item_value) 
            {
                foreach ($selected_month as $value)
                {
                    $amount = $db->find_by_sql("amount","receipt_items_amount_on_student","student_id='$student_id' AND item='{$item_value['item']}' AND month='$value' AND year='$year'","");
                    if($amount == 'No Result Found'){ $amount = 0; }else{ $amount = $amount[0]['amount']; }
                    $amount_addition += $amount;
                }
                
                  $r .= '<tr>';
                  $r .= '<td><input type="checkbox" class="case" name="posponed_item[]" value="'.$item_value['item'].'" ></td>' ;
                  $r .= '<td style="text-align:left;">'.++$i.'</td>';
                  $r .= '<td style="text-align:left;">'.$item_value['item'].'</td>';
                  if($item_value['instant_change_permission'] == 'No')
                  {$r .= '<td style="text-align:right;"><input type="text" size="12" style="border:1px solid transparent;text-align:right;"  name="amount['.$item_value['item'].']" value="'.$amount_addition.'" readonly>/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';}
                  elseif(count($selected_month) == '1'){$r .= '<td style="text-align:right;"><input type="text" size="12" style="text-align:right;"  name="amount['.$item_value['item'].']" value="'.$amount_addition.'">/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';}
                  else {  $r .= '<td style="text-align:right;"><input type="text" size="12" style="border:1px solid transparent;text-align:right;"  name="amount['.$item_value['item'].']" value="'.$amount_addition.'" readonly>/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';  }
                  $r .= '</tr>';
                  
                  $r .= '<input type="hidden" name="item[]" value="'.$item_value['item'].'">';
               $total += $amount_addition;
               $amount = 0;
               $amount_addition = 0;
            }
            
            foreach ($selected_month as $value)
            {
                $r .= '<input type="hidden" name="selected_month[]" value="'.$value.'">';
            }

      $r .= '</table>';
     $r .= '</div>';
     $r .= '<div id="sumit_area"  style="margin:auto;">';
      $r .= '<table style="float:right;border:3px solid #0099CC;font-size:14px;" border="1" cellpadding="3">';
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Total</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Payable</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Paid</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="text-align:right;" size="8" name="paid"  onKeyDown="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)" onKeyUp="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)"  ></td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Exchange</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="border:1px solid transparent;" size="8" name="exchange" readonly></td>';
      $r .= '</tr>';
      $r .= '</table>';
      
      $r .='<input type="hidden" name="student_id" value="'.$student_id.'">';
      $r .= '<input type="hidden" name="year" value="'.$year.'">';
      
      $r .='<table style="float:right;margin-top:10px;" cellpadding="5">';
//      $r .= '<tr>';
//      $r .= '<td style="text-align:left;" colspan="3">*Comment <input type="text" style="float:right;border:2px solid #00c4f2;border-radius:2px;" size="98px" name="comment" value="'.$comment.'"> </td>';
//      $r .= '</tr>';
      $r .= '<tr>';
      $r .= '<td style="text-align:left;width:400px;font-size:11px;">*If you do further any change on particulars amount or postponed any particular item instantly, Press refresh/caluculate to see exact total amount </td>';
      $r .= '<td style="width:180px;text-align:center;"><input type="submit" id="submit_button2" name="calculate" value="Refresh/Calculate"></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="submit" id="submit_button2" name="save&print" value="Save & Print"></td>';
      $r .= '</tr>';
      $r .='</table>';
      $r .= '</form>';
     $r .='</div>';
     
     
     return $r;
}


//I set receipt item will fetch first fifteen  LIMIT 0,16 sothat from 17, items will show on others payment section
function identity_n_receipt_when_calculate($student_id,$amount,$comment,$posponed_item,$selected_month,$year)
{
    global $db;
//I set receipt item will fetch first fifteen  LIMIT 0,16
      $receipt_items =  $db->find_by_sql("item,instant_change_permission,appear_permission_when_due_clear","receipt_items ORDER BY LENGTH(item_order),item_order LIMIT 0,16","","");
            
     $r .= '<div id="receipt" style="margin:50px auto;">';
      $r .= '<div style="width:700px;height:20px;background:#0099CC;color:#fff;text-align:center;"><b>PAYMENT RECEIPT</b></div>';
      $r .= '<form action="" method="post">';
      $r .= '<table style="margin:auto;border:1px solid black;font-size:14px;" border="1" cellpadding="1">';
      $i = 0;
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;"><b>S.L.</b></td>';
      $r .= '<td style="width:280px;text-align:center;"><b>Particulars</b></td>';
      $r .= '<td style="width:150px;text-align:center;"><b>Amount(Tk)</b></td>';
      $r .= '</tr>';

                foreach ($receipt_items as $item_value) 
                {
                    
                        if($item_value['instant_change_permission'] == 'No')
                        {
                                if(!in_array($item_value['item'],$posponed_item))
                                {
                                    $r .= '<tr>';
                                     $r .= '<td style="text-align:left;">'.++$i.'</td>';
                                     $r .= '<td style="text-align:left;">'.$item_value['item'].'</td>';
                                     $r .= '<td style="text-align:right;"><input type="text" size="12" style="border:1px solid transparent;text-align:right;" name="amount['.$item_value['item'].']" value="'.$amount[$item_value['item']].'" readonly>/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                                    $r .= '</tr>';

                                    $r .= '<input type="hidden" name="item[]" value="'.$item_value['item'].'">';
                                     $total += $amount[$item_value['item']] ;
                                }

                        }elseif(count($selected_month) == '1')
                        {
                                    if(!in_array($item_value['item'],$posponed_item))
                                    { 
                                            $r .= '<tr>';
                                            $r .= '<td style="text-align:left;">'.++$i.'</td>';
                                            $r .= '<td style="text-align:left;">'.$item_value['item'].'</td>';
                                            $r .= '<td style="text-align:right;"><input type="text" size="12" style="text-align:right;" name="amount['.$item_value['item'].']" value="'.$amount[$item_value['item']].'">/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                                            $r .= '</tr>';

                                            $total += $amount[$item_value['item']] ;
                                            $r .= '<input type="hidden" name="item[]" value="'.$item_value['item'].'">';
                                    }
                         }else{
                                    if(!in_array($item_value['item'],$posponed_item))
                                    { 
                                            $r .= '<tr>';
                                            $r .= '<td style="text-align:left;">'.++$i.'</td>';
                                            $r .= '<td style="text-align:left;">'.$item_value['item'].'</td>';
                                            $r .= '<td style="text-align:right;"><input type="text" size="12" style="border:1px solid transparent;text-align:right;" name="amount['.$item_value['item'].']" value="'.$amount[$item_value['item']].'" readonly>/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                                            $r .= '</tr>';

                                            $total += $amount[$item_value['item']] ;
                                            $r .= '<input type="hidden" name="item[]" value="'.$item_value['item'].'">';
                                    }
                             }
                    
                }
                
                
                //catch posponed item 
                foreach ($posponed_item as $value)
                {
                    $r .= '<input type="hidden" name="posponed_item[]" value="'.$value.'">';
                }
                //catch seleted month
                foreach ($selected_month as $value)
                {
                    $r .= '<input type="hidden" name="selected_month[]" value="'.$value.'">';
                }

      $r .= '</table>';
     $r .= '</div>';
     $r .= '<div id="sumit_area" style="margin:auto;">';
      $r .= '<table style="float:right;border:3px solid #0099CC;font-size:14px;" border="1" cellpadding="3">';
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Total</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Payable</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Paid</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="text-align:right;" size="8" name="paid"  onKeyDown="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)" onKeyUp="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)" ></td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Exchange</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="border:1px solid transparent;" size="8" name="exchange" readonly></td>';
      $r .= '</tr>';
      $r .= '</table>';
      
      $r .='<input type="hidden" name="student_id" value="'.$student_id.'">';
      $r .= '<input type="hidden" name="year" value="'.$year.'">';
      
      $r .='<table style="float:right;margin-top:10px;" cellpadding="5">';
//      $r .= '<tr>';
//      $r .= '<td style="text-align:left;" colspan="3">*Comment <input type="text" style="float:right;border:2px solid #00c4f2;border-radius:2px;" size="98px" name="comment" value="'.$comment.'"> </td>';
//      $r .= '</tr>';
      $r .= '<tr>';
      $r .= '<td style="text-align:left;width:400px;font-size:11px;">*If you do further any change on particulars amount instantly, Press refresh/caluculate to see exact total amount </td>';
      $r .= '<td style="width:180px;text-align:center;"><input type="submit" id="submit_button2" name="calculate" value="Refresh/Calculate"></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="submit" id="submit_button2"  name="save&print" value="Save & Print"></td>';
      $r .= '</tr>';
      $r .='</table>';
      $r .= '</form>';
     $r .='</div>';
     
     return $r;
}


function due_receipt($student_id,$selected_month,$year)
{
    global $db;
    date_default_timezone_set('Asia/Dhaka');
    $date = date('Y-m-d') ;
    $time = date('h:i:s A') ;

      
     $r .= '<div id="receipt" style="float:left;margin:50px 0 10px 16%;"">';
      $r .= '<div style="width:700px;height:20px;background:#0099CC;color:#fff;text-align:center;"><b>PAYMENT RECEIPT (DUE) (MONTH:'.$selected_month[0].')</b></div>';
      $r .= '<form action="" method="post">';
      $r .= '<table style="margin:auto;border:1px solid #000;font-size:14px;" border="1" cellpadding="3">';
      $i = 0;
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;"><b>S.L.</b></td>';
      $r .= '<td style="width:280px;text-align:center;"><b>Particulars</b></td>';
      $r .= '<td style="width:150px;text-align:center;"><b>Amount(Tk)</b></td>';
      $r .= '</tr>';

          $due =  $db->find_by_sql("receipt_item,due","due","student_id='$student_id' AND month='$selected_month[0]' AND year='$year'","");
            foreach ($due as $value) 
            {                
                  $r .= '<tr>';
                  $r .= '<td style="text-align:left;">'.++$i.'</td>';
                  $r .= '<td style="text-align:left;">'.$value['receipt_item'].'</td>';
                  $r .= '<td style="text-align:right;"><input type="text" size="12" style="border:1px solid transparent;text-align:right;"  name="amount['.$value['receipt_item'].']" value="'.$value['due'].'" readonly>/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                  $r .= '</tr>';
                  
               $total += $value['due'];
            }

           
      $r .= '</table>';
     $r .= '</div>';
     $r .= '<div id="sumit_area" style="float:left;margin:0 0 0 16%;">';
      $r .= '<table style="float:right;border:3px solid #0099CC;font-size:14px;" border="1" cellpadding="3">';
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Total</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Payable</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Paid</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="text-align:right;" size="8" name="paid"  onKeyDown="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)" onKeyUp="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)"  ></td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Exchange</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="border:1px solid transparent;" size="8" name="exchange" readonly></td>';
      $r .= '</tr>';
      $r .= '</table>';
      
      $r .='<input type="hidden" name="student_id" value="'.$student_id.'">';
      $r .='<input type="hidden" name="year" value="'.$year.'">';
      $r .='<input type="hidden" name="selected_month" value="'.$selected_month[0].'">';
      
      $r .='<table style="float:right;margin-top:10px;" cellpadding="5">';
//      $r .= '<tr>';
//      $r .= '<td style="text-align:left;" colspan="3">*Comment <input type="text" style="float:right;border:2px solid #00c4f2;border-radius:2px;" size="98px" name="comment" value="'.$comment.'"> </td>';
//      $r .= '</tr>';
      $r .= '<tr>';
      $r .= '<td style="text-align:left;width:400px;font-size:11px;"></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="submit" id="submit_button2" name="save&print" value="Save & Print"></td>';
      $r .= '</tr>';
      $r .='</table>';
      $r .= '</form>';
     $r .='</div>';
     
     
     return $r;
}


function other_receipt($student_id,$selected_month,$year)
{
    global $db;
    date_default_timezone_set('Asia/Dhaka');
    $date = date('Y-m-d') ;
    $time = date('h:i:s A') ;
    $current_year = date("Y");
    $current_month = date("M");
     

      $receipt_items =  $db->find_by_sql("item","receipt_items","appear_permission_when_due_clear='Yes'  ORDER BY LENGTH(item_order),item_order","");
      
     $r .= '<div id="receipt" style="border-radius:10px;">';
      $r .= '<div style="width:700px;height:20px;background:#0099CC;color:#fff;text-align:center;"><b>PAYMENT RECEIPT</b></div>';
      $r .= '<form action="" method="post">';
      $r .= '<table style="margin:auto;border:1px solid #000;font-size:14px;" border="1" cellpadding="3">';
      $i = 0;
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;"><b>S.L.</b></td>';
      $r .= '<td style="width:280px;text-align:center;"><b>Particulars</b></td>';
      $r .= '<td style="width:150px;text-align:center;"><b>Amount(Tk)</b></td>';
      $r .= '</tr>';
        if($receipt_items !== 'No Result Found')
        {   foreach ($receipt_items as $item_value) 
            { 
                  $r .= '<tr>';
                  $r .= '<td style="text-align:left;">'.++$i.'</td>';
                  $r .= '<td style="text-align:left;">'.$item_value['item'].'</td>';
                  $r .= '<td style="text-align:right;"><input type="text" size="12" style="text-align:right;"  name="amount['.$item_value['item'].']" value="0">/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                  $r .= '</tr>';
                  
               $total += $final_amount;
            }
        }  
		 //Extra added item
          //$r .= '<tr>';
          //$r .= '<td style="text-align:left;">'.++$i.'</td>';
          //$r .= '<td style="text-align:left;"><input type="text" size="52" style="text-align:left;"  name="any_item" value="Write any item"></td>';
          //$r .= '<td style="text-align:right;"><input type="text" size="12" style="text-align:right;"  name="any_item_value" value="0">/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
         // $r .= '</tr>';
        
            foreach ($selected_month as $value)
            {
                $r .= '<input type="hidden" name="selected_month[]" value="'.$value.'">';
            }

      $r .= '</table>';
     $r .= '</div>';
     $r .= '<div id="sumit_area">';
      $r .= '<table style="float:right;border:3px solid #0099CC;font-size:14px;" border="1" cellpadding="3">';
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Total</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Payable</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Paid</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="text-align:right;" size="8" name="paid"  onKeyDown="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)" onKeyUp="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)"  ></td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Exchange</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="border:1px solid transparent;" size="8" name="exchange" readonly></td>';
      $r .= '</tr>';
      $r .= '</table>';
      
      $r .='<input type="hidden" name="student_id" value="'.$student_id.'">';
      $r .='<input type="hidden" name="year" value="'.$year.'">';
      
      $r .='<table style="float:right;margin-top:10px;" cellpadding="5">';
//      $r .= '<tr>';
//      $r .= '<td style="text-align:left;" colspan="3">*Comment <input type="text" style="float:right;border:2px solid #00c4f2;border-radius:2px;" size="98px" name="comment" value="'.$comment.'"> </td>';
//      $r .= '</tr>';
      $r .= '<tr>';
      $r .= '<td style="text-align:left;width:400px;font-size:11px;">*Press refresh/caluculate to see exact total amount </td>';
      $r .= '<td style="width:180px;text-align:center;"><input type="submit" id="submit_button2" name="calculate" value="Refresh/Calculate"></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="submit" id="submit_button2" name="save&print" value="Save & Print"></td>';
      $r .= '</tr>';
      $r .='</table>';
      $r .= '</form>';
     $r .='</div>';
     
     
     return $r;
}



function other_receipt_when_calculate($student_id,$selected_month,$amount,$any_item,$any_item_value,$year)
{
    global $db;
    date_default_timezone_set('Asia/Dhaka');
    $date = date('Y-m-d') ;
    $time = date('h:i:s A') ;
    $current_year = date("Y");
    $current_month = date("M");
     

      $receipt_items =  $db->find_by_sql("item","receipt_items","appear_permission_when_due_clear='Yes'  ORDER BY LENGTH(item_order),item_order","");
      
     $r .= '<div id="receipt" style="border-radius:10px;">';
      $r .= '<div style="width:700px;height:20px;background:#0099CC;color:#fff;text-align:center;"><b>PAYMENT RECEIPT</b></div>';
      $r .= '<form action="" method="post">';
      $r .= '<table style="margin:auto;border:1px solid #000;font-size:14px;" border="1" cellpadding="3">';
      $i = 0;
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;"><b>S.L.</b></td>';
      $r .= '<td style="width:280px;text-align:center;"><b>Particulars</b></td>';
      $r .= '<td style="width:150px;text-align:center;"><b>Amount(Tk)</b></td>';
      $r .= '</tr>';
        if($receipt_items !== 'No Result Found')
        {   foreach ($receipt_items as $item_value) 
            { 
                  if(empty($amount[$item_value['item']]) == TRUE)
                  {
                      $final_amount = 0;
                  }else{  $final_amount = $amount[$item_value['item']];  }
                  $r .= '<tr>';
                  $r .= '<td style="text-align:left;">'.++$i.'</td>';
                  $r .= '<td style="text-align:left;">'.$item_value['item'].'</td>';
                  $r .= '<td style="text-align:right;"><input type="text" size="12" style="text-align:right;"  name="amount['.$item_value['item'].']" value="'.$final_amount.'">/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                  $r .= '</tr>';
                  
               $total += $final_amount;
            }
        }  
         // $r .= '<tr>';
         // $r .= '<td style="text-align:left;">'.++$i.'</td>';
         // $r .= '<td style="text-align:left;"><input type="text" size="52" style="text-align:left;"  name="any_item" value="'.$any_item.'"></td>';
         // $r .= '<td style="text-align:right;"><input type="text" size="12" style="text-align:right;"  name="any_item_value" value="'.$any_item_value.'">/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
         // $r .= '</tr>';
        //if(!($any_item == 'Write any item' || $any_item_value == 0))
        // {
        //    $total += $any_item_value;
        // }
            foreach ($selected_month as $value)
            {
                $r .= '<input type="hidden" name="selected_month[]" value="'.$value.'">';
            }

      $r .= '</table>';
     $r .= '</div>';
     $r .= '<div id="sumit_area">';
      $r .= '<table style="float:right;border:3px solid #0099CC;font-size:14px;" border="1" cellpadding="3">';
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Total</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Payable</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Paid</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="text-align:right;" size="8" name="paid"  onKeyDown="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)" onKeyUp="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)"  ></td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Exchange</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="border:1px solid transparent;" size="8" name="exchange" readonly></td>';
      $r .= '</tr>';
      $r .= '</table>';
      
      $r .='<input type="hidden" name="student_id" value="'.$student_id.'">';
      $r .='<input type="hidden" name="year" value="'.$year.'">';
      
      $r .='<table style="float:right;margin-top:10px;" cellpadding="5">';
//      $r .= '<tr>';
//      $r .= '<td style="text-align:left;" colspan="3">*Comment <input type="text" style="float:right;border:2px solid #00c4f2;border-radius:2px;" size="98px" name="comment" value="'.$comment.'"> </td>';
//      $r .= '</tr>';
      $r .= '<tr>';
      $r .= '<td style="text-align:left;width:400px;font-size:11px;">*Press refresh/caluculate to see exact total amount </td>';
      $r .= '<td style="width:180px;text-align:center;"><input type="submit" id="submit_button2" name="calculate" value="Refresh/Calculate"></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="submit" id="submit_button2" name="save&print" value="Save & Print"></td>';
      $r .= '</tr>';
      $r .='</table>';
      $r .= '</form>';
     $r .='</div>';
     
     
     return $r;
}



function old_student_form($title,$student_name,$student_id,$class_roll,$section,$selected_item)
{ 
    global $db;
    
    $receipt_items =  $db->find_by_sql("*","old_student_receipt_items ORDER BY LENGTH(items_order),items_order","","");
    if(empty($selected_item) == FALSE)
    { $selected_item = $db->set_checked($selected_item,'checked'); }
    
     $r .= '<div style="width:700px;margin:15px auto; border:2px solid #990099; border-radius:15px;height:230px;">';
      $r .= '<form  action=""  method="post">';
      $r .= '<table style="margin:auto;font-size:14px;" cellpadding="6">';
      $r .= '<tr>';
      $r .= '<td style="color:#ff6600;" colspan="3"><b>'.$title.'</b></td>';
      $r .= '</tr>';
      $r .= '<tr>';
      $r .= '<td style="width:380px;text-align:left;"><b>Student Id:&nbsp;</b> <input type="text" style="border:1px solid green;width:200px;height:20px;border-radius:10px;"  name="student_id" value="'.$student_id.'" required></td>';
      $r .= '<td style="width:260px;text-align:left;"><b>Class Roll:&nbsp;</b> <input type="text" style="border:1px solid green;width:150px;height:20px;border-radius:10px;"  name="class_roll" value="'.$class_roll.'"></td>';
      $r .= '</tr>';
      $r .= '<tr>';
      $r .= '<td style="width:380px;text-align:left;"><b>Student Name:</b> <input type="text" <input type="text" style="border:1px solid green;width:200px;height:20px;border-radius:10px;"  name="student_name" value="'.$student_name.'" required></td>';
      $r .= '<td style="width:260px;text-align:left;"><b>Section: &nbsp;</b> <input type="text" <input type="text" style="border:1px solid green;width:150px;height:20px;border-radius:10px;"  name="section" value="'.$section.'"></td>';
      $r .= '</tr>'; 
      $r .= '<tr>';
      $r .= '<td style="color:#ff6600;" colspan="3"><b>Select particular item</b></td>';
      $r .= '</tr>';
      $r .= '</table>';
      $r .= '<table style="margin:10px auto 0 auto;font-size:14px;" cellpadding="2">';
      $r .= '<tr>';
      foreach ($receipt_items as $value)
      {
            $r .= '<td><input type="checkbox" class="case" name="selected_item[]" value="'.$value["item"].'" '.$selected_item[$value['item']].'></td>' ;
            $r .= '<td style="width:200px;text-align:left;"><b>'.$value["item"].'</b></td>';
      }
      $r .= '</tr>';
      $r .= '</table>';
      $r .= '<table style="margin:auto;font-size:14px;" cellpadding="6">';
      $r .= '<tr>';
      $r .='<td colspan="2"><input type="submit" id="submit_button" name="submit" value="Submit"></td>';
      $r .= '</tr>';
      $r .= '</table>';
      $r.= '</form>';
     $r .= '</div>';
     
     return $r;
     
}


function old_student_receipt($student_name,$student_id,$class_roll,$section,$selected_item)
{
    global $db;

     $r .= '<div id="receipt" style="border-radius:10px;">';
      $r .= '<div style="width:700px;height:20px;background:#0099CC;color:#fff;text-align:center;"><b>PAYMENT RECEIPT</b></div>';
      $r .= '<form action="" method="post">';
      $r .= '<table style="margin:auto;border:1px solid #000;font-size:14px;" border="1" cellpadding="3">';
      $i = 0;
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;"><b>S.L.</b></td>';
      $r .= '<td style="width:280px;text-align:center;"><b>Particulars</b></td>';
      $r .= '<td style="width:150px;text-align:center;"><b>Amount(Tk)</b></td>';
      $r .= '</tr>';

            foreach ($selected_item as $item_value) 
            {  
                $receipt_items_amount =  $db->find_by_sql("amount,instant_change_permission","old_student_receipt_items","item='$item_value'","");

                  $r .= '<tr>';
                  $r .= '<td style="text-align:left;">'.++$i.'</td>';
                  $r .= '<td style="text-align:left;">'.$item_value.'</td>';
                  if($receipt_items_amount[0]['instant_change_permission'] == 'No')
                  {$r .= '<td style="text-align:right;"><input type="text" size="12" style="border:1px solid transparent;text-align:right;"  name="amount['.$item_value.']" value="'.$receipt_items_amount[0]['amount'].'" readonly>/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';}
                  else{$r .= '<td style="text-align:right;"><input type="text" size="12" style="text-align:right;"  name="amount['.$item_value.']" value="'.$receipt_items_amount[0]['amount'].'">/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';}
                  $r .= '</tr>';
                  
               $total += $receipt_items_amount[0]['amount'];
               $r .= '<input type="hidden" name="selected_item[]"  value="'.$item_value.'" >';
            }
            
            
        $r .= '<input type="hidden" name="student_id"  value="'.$student_id.'" >';
        $r .= '<input type="hidden" name="student_name"  value="'.$student_name.'" >';
        $r .= '<input type="hidden" name="class_roll"  value="'.$class_roll.'" >';
        $r .= '<input type="hidden" name="section"  value="'.$section.'" >';
      $r .= '</table>';
     $r .= '</div>';
     $r .= '<div id="sumit_area">';
      $r .= '<table style="float:right;border:3px solid #0099CC;font-size:14px;" border="1" cellpadding="3">';
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Total</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Payable</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Paid</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="text-align:right;" size="8" name="paid"  onKeyDown="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)" onKeyUp="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)"  ></td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Exchange</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="border:1px solid transparent;" size="8" name="exchange" readonly></td>';
      $r .= '</tr>';
      $r .= '</table>';
            
      $r .='<table style="float:right;margin-top:10px;" cellpadding="5">';
//      $r .= '<tr>';
//      $r .= '<td style="text-align:left;" colspan="3">*Comment <input type="text" style="float:right;border:2px solid #00c4f2;border-radius:2px;" size="98px" name="comment" value="'.$comment.'"> </td>';
//      $r .= '</tr>';
      $r .= '<tr>';
      $r .= '<td style="text-align:left;width:400px;font-size:11px;">*If you do further any change on any particular item instantly, Press refresh/caluculate to see exact total amount </td>';
      $r .= '<td style="width:180px;text-align:center;"><input type="submit" id="submit_button2" name="calculate" value="Refresh/Calculate"></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="submit" id="submit_button2" name="save&print" value="Save & Print"></td>';
      $r .= '</tr>';
      $r .='</table>';
      $r .= '</form>';
     $r .='</div>';
     
     
     return $r;
}


function old_student_receipt_when_calculate($amount,$student_name,$student_id,$class_roll,$section,$selected_item)
{
    global $db;
      
     $r .= '<div id="receipt" style="border-radius:10px;">';
      $r .= '<div style="width:700px;height:20px;background:#0099CC;color:#fff;text-align:center;"><b>PAYMENT RECEIPT</b></div>';
      $r .= '<form action="" method="post">';
      $r .= '<table style="margin:auto;border:1px solid #000;font-size:14px;" border="1" cellpadding="3">';
      $i = 0;
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;"><b>S.L.</b></td>';
      $r .= '<td style="width:280px;text-align:center;"><b>Particulars</b></td>';
      $r .= '<td style="width:150px;text-align:center;"><b>Amount(Tk)</b></td>';
      $r .= '</tr>';

           foreach ($amount as $key => $item_value)
            {                   
                  $receipt_items_perm =  $db->find_by_sql("amount,instant_change_permission","old_student_receipt_items","item='$key'","");

                  $r .= '<tr>';
                  $r .= '<td style="text-align:left;">'.++$i.'</td>';
                  $r .= '<td style="text-align:left;">'.$key.'</td>';
                  if($receipt_items_perm[0]['instant_change_permission'] == 'No')
                  {$r .= '<td style="text-align:right;"><input type="text" size="12" style="border:1px solid transparent;text-align:right;"  name="amount['.$key.']" value="'.$item_value.'" readonly>/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';}
                  else{$r .= '<td style="text-align:right;"><input type="text" size="12" style="text-align:right;"  name="amount['.$key.']" value="'.$item_value.'">/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';}
                  $r .= '</tr>';

                  
               $total += $item_value;
                $r .= '<input type="hidden" name="selected_item[]"  value="'.$key.'" >';
            }
         
          
        $r .= '<input type="hidden" name="student_id"  value="'.$student_id.'" >';
        $r .= '<input type="hidden" name="student_name"  value="'.$student_name.'" >';
        $r .= '<input type="hidden" name="class_roll"  value="'.$class_roll.'" >';
        $r .= '<input type="hidden" name="section"  value="'.$section.'" >';
      $r .= '</table>';
     //echo '<pre>'; print_r($amount); echo '</pre>';
     $r .= '</div>';
     $r .= '<div id="sumit_area">';
      $r .= '<table style="float:right;border:3px solid #0099CC;font-size:14px;" border="1" cellpadding="3">';
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Total</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Payable</b></td>';
      $r .= '<td style="width:50px;text-align:center;">'.$total.'</td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Paid</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="text-align:right;" size="8" name="paid"  onKeyDown="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)" onKeyUp="countInput2(this.form.paid,this.form.exchange,('.$total.')),amount(this)"  ></td>';
      $r .= '<td style="width:50px;text-align:center;background:#0099CC;color:#fff;"><b>Exchange</b></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="text" style="border:1px solid transparent;" size="8" name="exchange" readonly></td>';
      $r .= '</tr>';
      $r .= '</table>';
            
      $r .='<table style="float:right;margin-top:10px;" cellpadding="5">';
//      $r .= '<tr>';
//      $r .= '<td style="text-align:left;" colspan="3">*Comment <input type="text" style="float:right;border:2px solid #00c4f2;border-radius:2px;" size="98px" name="comment" value="'.$comment.'"> </td>';
//      $r .= '</tr>';
      $r .= '<tr>';
      $r .= '<td style="text-align:left;width:400px;font-size:11px;">*Press refresh/caluculate to see exact total amount </td>';
      $r .= '<td style="width:180px;text-align:center;"><input type="submit" id="submit_button2" name="calculate" value="Refresh/Calculate"></td>';
      $r .= '<td style="width:50px;text-align:center;"><input type="submit" id="submit_button2" name="save&print" value="Save & Print"></td>';
      $r .= '</tr>';
      $r .='</table>';
      $r .= '</form>';
     $r .='</div>';
     
     
     return $r;
}


function convert_number_to_words($number) 
{
   
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'forty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
   
    if (!is_numeric($number)) {
        return false;
    }
   
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert number to words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
   
    $string = $fraction = null;
   
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
   
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
   
    if (null !== $fraction && is_numeric($fraction)) 
    {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) 
        {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
   
    return $string;
}


?>

