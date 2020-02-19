<?php



function date_field()
{
   $r .= '<form action="" method="post">';
     $r .= '<table style="margin:auto;" cellpadding="10">';
       $r .= '<tr>';
       $r .= '<td><b>Date </b>(mm/dd/yyyy) <input id="datepicker1" style="border:2px solid #9933ff;width:200px;height:20px;border-radius:10px;" name="date" placeholder="" required></td>';
       $r .= '<td><input type="submit" id="submit_button" name="submit" value="Submit" onClick="doLoading()"></td>';
       $r .= '</tr>';
     $r .= '</table>';
    $r .= '</form>';
    
    return $r;
}

function year_n_month_field($student_id)
{ global $db;
     $years = $db->find_by_sql("*","years ORDER BY year DESC","","");
     $months = $db->find_by_sql("*","months ORDER BY id","","");
   $r .= '<form action="" method="post">';
     $r .= '<table style="margin:auto;" cellpadding="10">';
       $r .= '<tr>';
       $r .= '<td><select name="year"><option value="Available Years">Available Years</option>';
       foreach ($years as $year_value)
       {
           $r.='<option value="'.$year_value['year'].'">'.$year_value['year'].'</option>';
       }
       $r .='</select></td>';
        $r .= '<td><select name="month"><option value="Months">Months</option>';
       foreach ($months as $month_value)
       {
           $r.='<option value="'.$month_value['month'].'">'.$month_value['month'].'</option>';
       }
       $r .='</select></td>';
       $r .='<input type="hidden" name="student_id" value="'.$student_id.'" >';
       $r .= '<td><input type="submit" id="submit_button" name="submit" value="Submit" onClick="doLoading()"></td>';
       $r .= '</tr>';
     $r .= '</table>';
    $r .= '</form>';
    
    return $r;
}


function year_n_section_field()
{ global $db;
     //$years = $db->find_by_sql("*","years ORDER BY year DESC","","");
     $available_section   = $db->find_by_sql("section","section ORDER BY section ASC","","");
     $year = date('Y');
	 $year_prev = $year - 1;
	 $year_post = $year + 1;
     
   $r .= '<form action="" method="post">';
     $r .= '<table style="margin:auto;" cellpadding="10">';
       $r .= '<tr>';
       $r .= '<td><select name="year"><option value="Available Years">Available Years</option>';
      // foreach ($years as $year_value)
      // {
           $r.='<option value="'.$year_prev.'">'.$year_prev.'</option>';
		   $r.='<option value="'.$year.'">'.$year.'</option>';
		   $r.='<option value="'.$year_post.'">'.$year_post.'</option>';
     //  }
       $r .='</select></td>';
      
       $r .= '<td><select name="section"><option value="Section">Available Section</option>';
       foreach ($available_section as $available_section_value)
       {
           $r.='<option value="'.$available_section_value['section'].'">'.$available_section_value['section'].'</option>';
       }
       $r .='</select></td>';
       $r .= '<td><input type="submit" id="submit_button" name="submit" value="Submit" onClick="doLoading()"></td>';
       $r .= '</tr>';
     $r .= '</table>';
    $r .= '</form>';
    
    return $r;
}


function year_month_section_field()
{ global $db;
     //$years = $db->find_by_sql("*","years ORDER BY year DESC","","");
     $months = $db->find_by_sql("*","months ORDER BY id","","");
     $available_section   = $db->find_by_sql("section","section ORDER BY section ASC","","");
     $year = date('Y');
	 $year_prev = $year - 1;
	 $year_post = $year + 1;
     
   $r .= '<form action="" method="post">';
     $r .= '<table style="margin:auto;" cellpadding="10">';
	   $r .= '<tr>';
	     $r .= '<td style="color:red;" colspan="4">Select Year & Section for yearly collection Or all for monthly</td>';
	 $r .= '</tr>';
       $r .= '<tr>';
       $r .= '<td><select name="year"><option value="Available Years">Available Years</option>';
      // foreach ($years as $year_value)
      // {
           $r.='<option value="'.$year_prev.'">'.$year_prev.'</option>';
		   $r.='<option value="'.$year.'">'.$year.'</option>';
		   $r.='<option value="'.$year_post.'">'.$year_post.'</option>';
     //  }
       $r .='</select></td>';
        $r .= '<td><select name="month"><option value="Months">Months</option>';
       foreach ($months as $month_value)
       {
           $r.='<option value="'.$month_value['month'].'">'.$month_value['month'].'</option>';
       }
       $r .='</select></td>';
       $r .= '<td><select name="section"><option value="Section">Available Section</option>';
       foreach ($available_section as $available_section_value)
       {
           $r.='<option value="'.$available_section_value['section'].'">'.$available_section_value['section'].'</option>';
       }
       $r .='</select></td>';
       $r .= '<td><input type="submit" id="submit_button" name="submit" value="Submit" onClick="doLoading()"></td>';
       $r .= '</tr>';
     $r .= '</table>';
    $r .= '</form>';
    
    return $r;
}


function daily_transaction_pagination($date)
{
    global $db;   
	
	$targetpage = "journal.php?category=Journal&&subcat=Daily Transaction";
        $limit = 15;
        $total_pages = count($db->find_by_sql("*","transaction_archive","date='$date'",""));
        $pagination = pagination($total_pages,$targetpage,$limit,$section_name,$date,$year,$month,$full_month);

if($_GET['page'] > $pagination[1])
    {
       echo '<div id="message" style="font-size:16px;color:#FF9999;"><b>No Result Found</b></div>';
    }else{
        	/* Get data. */
	$sql = "SELECT * FROM transaction_archive WHERE date='$date' ORDER BY id DESC LIMIT $pagination[2],$pagination[3]";
        
    //code for data show    
        $result = mysql_query($sql);
    echo '<div style="width:95%;height:445px;margin:1% auto 1% auto;" overflow:auto;>';
    echo '<div class="dr_moz_scrll" style="width:650px; height:430px; margin:10px auto 10px auto; overflow:auto; border:1.5px solid #86b300;border-radius:10px;" >' ;
          echo '<table table style=" text-align:center; background:whitesmoke;" >' ;
            echo '<tr style="background:#86b300;color:#fff;border-color:#86b300;">' ;
               echo '<th style="padding:4px;">Date</th>' ;
               echo '<th style="padding:4px;">Time</th>' ;
               echo '<th style="padding:4px;">Student Id</th>' ;
               echo '<th style="padding:4px;">Payable</th>' ;
               echo '<th style="padding:4px;">Paid</th>' ;
               echo '<th style="padding:4px;">Due</th>' ;
               echo '<th style="padding:4px;">Exchange</th>' ;
            echo '</tr>';
          $i =0;
          while ($row = mysql_fetch_array($result))
          {              
              echo $r .= '<tr ';
              if($i%2 == 0)
              {
                 $r .= ' class="tr1_hover" ';
              }else 
                  {
                    $r .= ' class="tr2_hover" ';
                  }
              echo $r .= ' >' ;
               echo '<td style="font-size:14px;padding:4px;width:100px;color:#000;border-color:#86b300; " ><b>'.$row["date"].'</b></td>' ;
               echo '<td style="font-size:14px;padding:4px;width:100px;color:#000;border-color:#86b300; " ><b>'.$row["time"].'</b></td>' ;
               echo '<td style="font-size:14px;padding:4px;width:140px;color:#000;border-color:#86b300; " ><b>'.$row["student_id"].'</b></td>' ;
               echo '<td style="font-size:14px;padding:4px;width:110px;color:#000;border-color:#86b300; "><b>'.$row["payable"].'</b></td>' ;
               echo '<td style="font-size:14px;padding:4px;width:100px;color:#000;border-color:#86b300; " ><b>'.$row["paid"].'</b></td>' ;
               echo '<td style="font-size:14px;padding:4px;width:100px;color:#000;border-color:#86b300; " ><b>'.$row["due"].'</b></td>' ;
               echo '<td style="font-size:14px;padding:4px;width:100px;color:#000;border-color:#86b300; " ><b>'.$row["exchange"].'</b></td>' ;
            echo '</tr>';
            $i++;
          }  
          echo '</table>' ; 



    echo '</div>';
          echo $pagination[0];


      $total_payable = $db->find_by_sql("SUM(payable) AS total_payable","transaction_archive","date='$date'","");
      $total_paid = $db->find_by_sql("SUM(paid) AS total_paid","transaction_archive","date='$date'","");
      $total_due = $db->find_by_sql("SUM(due) AS total_due","transaction_archive","date='$date'","");
      $total_exchange = $db->find_by_sql("SUM(exchange) AS total_exchange","transaction_archive","date='$date'","");
      
      $username = $db->find_by_sql("*","users ORDER BY username","","");
      $colspan = count($username) + 2;
      
     echo '<table style="margin:auto;border:1px solid #86b300;margin-top:10px;border-radius:10px;" border="1" cellpadding="2">';
      echo '<tr>';
      echo '<td style="text-align:center;background:#86b300;color:#fff;" colspan="'.$colspan.'"><b>Daily Transaction Report</b></td>';
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>Date:</b></td><td style="text-align:center;width:120px;" colspan="'.$colspan.'">'.substr($date,8,2).'</td>';
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>Month:</b></td><td style="text-align:center;width:120px;" colspan="'.$colspan.'">'.month_selection(substr(date('Y-m-d'),5,2)).'</td>';
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>Year:</b></td><td style="text-align:center;width:120px;" colspan="'.$colspan.'" >'.substr($date,0,4).'</td>';
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>User:</b></td><td style="text-align:center;width:120px;" ><b>All</b></td>';
      foreach ($username as $user_value)
      {
          echo '<td style="text-align:right;width:120px;"><b>'.$user_value['username'].'</b></td>';
      }
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>Total Payable:</b></td><td style="text-align:right;width:120px;">'.$total_payable[0]["total_payable"].'</td>';
      foreach ($username as $user_value)
      {
          $total_payable_by_user = $db->find_by_sql("SUM(payable) AS total_payable","transaction_archive","date='$date' AND username='{$user_value['username']}'","");
          echo '<td style="text-align:right;width:120px;">'.$total_payable_by_user[0]["total_payable"].'</td>';
      }
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>Total Paid:</b></td><td style="text-align:right;width:120px;">'.$total_paid[0]['total_paid'].'</td>';
      foreach ($username as $user_value)
      {
          $total_paid_by_user = $db->find_by_sql("SUM(paid) AS total_paid","transaction_archive","date='$date' AND username='{$user_value['username']}'","");
          echo '<td style="text-align:right;width:120px;">'.$total_paid_by_user[0]["total_paid"].'</td>';
      }
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>Total Exchange:</b></td><td style="text-align:right;width:120px;">'.$total_exchange[0]['total_exchange'].'</td>';
      foreach ($username as $user_value)
      {
          $total_exchange_by_user = $db->find_by_sql("SUM(exchange) AS total_exchange","transaction_archive","date='$date'  AND username='{$user_value['username']}'","");
          echo '<td style="text-align:right;width:120px;">'.$total_exchange_by_user[0]["total_exchange"].'</td>';
      }
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>Total Due:</b></td><td style="text-align:right;width:120px;">'.$total_due[0]['total_due'].'</td>';
      foreach ($username as $user_value)
      {
          $total_due_by_user = $db->find_by_sql("SUM(due) AS total_due","transaction_archive","date='$date'  AND username='{$user_value['username']}'","");
          echo '<td style="text-align:right;width:120px;">'.$total_due_by_user[0]["total_due"].'</td>';
      }
      echo '</tr>';
     echo '</table>';
    
    echo '</div>';
    
    
    
  }// end of if($_GET['page'] > $lastpage)else
}


function month_selection($month)
{
    if($month == '01')
    {     
        return 'January';
    }elseif($month == '02')
    { 
        return 'February';
    }elseif($month == '03')
    {
            return 'March';
    }elseif($month == '04')
    {
            return 'April';
    }elseif($month == '05')
    {
            return 'May'; 
    }elseif($month == '06')
    {
            return 'June';
    }elseif($month == '07')
    {
            return 'July';
    }elseif($month == '08')
    {
            return 'August';
    }elseif($month == '09')
    {
            return 'September';
    }elseif($month == '10')
    {
            return 'October';
    }elseif($month == '11')
    {
            return 'November';
    }elseif($month == '12')
    {
            return 'December';   
    }
        
    
}


function monthly_transaction_pagination($year,$month,$full_month)
{
    global $db,$user115122;
    
    date_default_timezone_set('Asia/Dhaka');
    $current_year = date('Y');
    $current_month = date('M');
    
        $calculate_permission = $db->find_by_sql("calculate_permission","permission","username='$user115122'","");
        if($calculate_permission[0]['calculate_permission'] !== 'Cancel')
        {
          calculate_monthly_transaction($year, $month);
        }
        $update_calculate_permission = $db->update("permission","calculate_permission='Cancel'","username='$user115122'");

    $targetpage = "journal.php?category=Journal&&subcat=Monthly Transaction";
    $limit = 15;
    $total_pages = count($db->find_by_sql("*","monthly_transaction","year='$year' AND month='$month'",""));
    $pagination = pagination($total_pages,$targetpage,$limit,$section_name,$date,$year,$month,$full_month);
	

if($_GET['page'] > $pagination[1])
    {
       echo '<div id="message" style="font-size:16px;color:#FF9999;"><b>No Result Found</b></div>';
    }else{
        	/* Get data. */       
	$sql = "SELECT * FROM monthly_transaction WHERE year='$year' AND month='$month' ORDER BY id LIMIT $pagination[2],$pagination[3]";
        
    //code for data show    
        $result = mysql_query($sql);
    echo '<div style="width:95%;height:445px;margin:1% auto 1% auto;">';

    echo '<div class="dr_moz_scrll" style="width:850px; height:443px; margin:auto; overflow:auto; border:1.5px solid #86b300;border-radius:10px;" >' ;
          echo '<table table style="float:left; text-align:center; background:whitesmoke;">' ;
            echo '<tr style="background:#86b300;color:#fff;">' ;
               echo '<th style="padding:4px;">Date</th>' ;
               echo '<th style="padding:4px;">Payable</th>' ;
               echo '<th style="padding:4px;">Paid</th>' ;
               echo '<th style="padding:4px;">Due</th>' ;
               echo '<th style="padding:4px;">Exchange</th>' ;
            echo '</tr>';
          $i =0;
          while ($row = mysql_fetch_array($result))
          {              
              echo $r .= '<tr ';
              if($i%2 == 0)
              {
                 $r .= ' class="tr1_hover" ';
              }else
                  {
                    $r .= ' class="tr2_hover" ';
                  }
              echo $r .= ' >' ;
               echo '<td style="font-size:14px;padding:4px;width:140px;color:#000; " ><b>'.$row["date"].'</b></td>' ;
               echo '<td style="font-size:14px;padding:4px;width:110px;color:#000; "><b>'.$row["payable"].'</b></td>' ;
               echo '<td style="font-size:14px;padding:4px;width:100px;color:#000; " ><b>'.$row["paid"].'</b></td>' ;
               echo '<td style="font-size:14px;padding:4px;width:100px;color:#000; " ><b>'.$row["due"].'</b></td>' ;
               echo '<td style="font-size:14px;padding:4px;width:100px;color:#000; " ><b>'.$row["exchange"].'</b></td>' ;
            echo '</tr>';
            $i++;
          }  
          echo '</table>' ; 
    
      $total_payable = $db->find_by_sql("SUM(payable) AS total_payable","monthly_transaction","year='$year' AND month='$month'","");
      $total_paid = $db->find_by_sql("SUM(paid) AS total_paid","monthly_transaction","year='$year' AND month='$month'","");
      $total_due = $db->find_by_sql("SUM(due) AS total_due","monthly_transaction","year='$year' AND month='$month'","");
      $total_exchange = $db->find_by_sql("SUM(exchange) AS total_exchange","monthly_transaction","year='$year' AND month='$month'","");
      
      $users = $db->find_by_sql("*","users ORDER BY username","","");
      $count_users = count($users) + 1;
      $colspan = $count_users * 2;
      
     
     echo '<table style="float:right;border:1px solid #86b300;" border="1" cellpadding="5">';
      echo '<tr>';
      echo '<td style="text-align:center;background:#86b300;color:#fff;" colspan="'.$colspan.'"><b>Monthly Transaction Report</b></td>';
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:center;background:lightgray;" colspan="2"><b>All</b></td>';
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>Month:</b></td><td style="text-align:left;width:120px;">'.$full_month.'</td>'; 
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>Year:</b></td><td style="text-align:left;width:120px;">'.$year.'</td>';
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>Total Payable:</b></td><td style="text-align:left;width:120px;">'.$total_payable[0]["total_payable"].'</td>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>Total Paid:</b></td><td style="text-align:left;width:120px;">'.$total_paid[0]['total_paid'].'</td>';
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>Total Exchange:</b></td><td style="text-align:left;width:120px;">'.$total_exchange[0]['total_exchange'].'</td>';
      echo '</tr>';
      echo '<tr>';
      echo '<td style="text-align:left;width:100px;"><b>Total Due:</b></td><td style="text-align:left;width:120px;">'.$total_due[0]['total_due'].'</td>';
      echo '</tr>';
     echo '</table>';

	 
    echo '</div>';
    echo '</div>';
    
    echo $pagination[0];
    
  }// end of if($_GET['page'] > $lastpage)else
}


function calculate_monthly_transaction($year,$month)
{
    global $db;
    
    $distinct_date = $db->find_by_sql("DISTINCT date","transaction_archive","year='$year' AND month='$month'","");
   
    foreach ($distinct_date as $dist_date_value) 
    {
        
        $total_payable = $db->find_by_sql("SUM(payable) AS total_payable","transaction_archive","date='{$dist_date_value['date']}'","");
        $total_paid = $db->find_by_sql("SUM(paid) AS total_paid","transaction_archive","date='{$dist_date_value['date']}'","");
        $total_due = $db->find_by_sql("SUM(due) AS total_due","transaction_archive","date='{$dist_date_value['date']}'","");
        $total_exchange = $db->find_by_sql("SUM(exchange) AS total_exchange","transaction_archive","date='{$dist_date_value['date']}'","");
        
        if($db->find_by_sql("date","monthly_transaction","date='{$dist_date_value['date']}'","") == 'No Result Found')
        {
            $insert = $db->insert("monthly_transaction","year,month,date,payable,paid,due,exchange","'$year','$month','{$dist_date_value['date']}','{$total_payable[0]['total_payable']}','{$total_paid[0]['total_paid']}','{$total_due[0]['total_due']}','{$total_exchange[0]['total_exchange']}'","");
        }else{
            $update = $db->update("monthly_transaction","payable='{$total_payable[0]['total_payable']}',paid='{$total_paid[0]['total_paid']}',due='{$total_due[0]['total_due']}',exchange='{$total_exchange[0]['total_exchange']}'","date='{$dist_date_value['date']}'");
        }
    }
}


function monthly_items_transaction_pagination($year,$month,$full_month)
{
    global $db;
    
    date_default_timezone_set('Asia/Dhaka');

    $targetpage = "journal.php?category=Journal&&subcat=Monthly Items Collection";
    $limit = 15;
    $total_pages = count($db->find_by_sql("DISTINCT date","transaction_archive","year='$year' AND month='$month'  ORDER BY id DESC",""));
    $pagination = pagination($total_pages,$targetpage,$limit,$section_name,$date,$year,$month,$full_month);

if(htmlspecialchars($_GET['page']) > $pagination[1])
    {
       echo '<div id="message" style="font-size:16px;color:#FF9999;"><b>No Result Found</b></div>';
    }else{
        	/* Get data. */
        $date = $db->find_by_sql("DISTINCT date","transaction_archive","year='$year' AND month='$month' ORDER BY id DESC","");

        $date = array_slice($date,$pagination[2],$pagination[3]);
        
        $receipt_items = $db->find_by_sql("item","receipt_items ORDER BY item_order","","");
        $extra_added_items = $db->find_by_sql("DISTINCT item","extra_added_items ORDER BY item","","");
        
        if($receipt_items == 'No Result Found')
        {
            echo 'No Items Found';
        }else{
            
            
            
    //code for data show 

    echo '<div style="width:98%;height:445px;margin:1% auto 1% auto;overflow:auto;">';
    echo '<div class="dr_moz_scrll" style="width:98%; height:460px; float:left; margin:0 0 10px 0; overflow:auto; border:1.5px solid #86b300;border-radius:10px;" >' ;
          echo '<table style=" text-align:center;background:whitesmoke;" >' ;
           echo '<tr style="background:#86b300;color:#fff;">' ;
                echo '<th style="width:200px;padding:2px;">Date</th>' ;
            foreach ($receipt_items as $receipt_value)
            {
                echo '<th style="width:200px;padding:2px;border:1px solid #fff;">'.$receipt_value['item'].'</th>' ;
            }
            foreach ($extra_added_items as $extra_items_value)
            {
                echo '<th style="width:200px;padding:2px;border:1px solid #fff;">'.$extra_items_value['item'].'</th>' ;
            }
           echo '</tr>';
          $i =0;
          foreach ($date as $date_value) // do not use month use date 
          {              
                echo $r .= '<tr ';
                if($i%2 == 0)
                {
                   $r .= ' class="tr1_hover" ';
                }else 
                    {
                      $r .= ' class="tr2_hover" ';
                    }
                echo $r .= ' >' ;
                 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000; " ><b>'.$date_value['date'].'</b></td>' ;
                 foreach ($receipt_items as $receipt_value)
                 {
                     $receipt_items_value = $receipt_value['item'];
                     $result = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item"," date='{$date_value['date']}' AND receipt_item='{$receipt_items_value}'","");    
                     echo '<td style="font-size:14px;padding:4px;width:200px;color:#000; " ><b>'.$result[0]['total_paid'].'</b></td>' ;
                 }
                 
                 foreach ($extra_added_items as $extra_items_value)
                 {
                     $extra_added_items_value = $extra_items_value['item'];
                     $result = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","date='{$date_value['date']}' AND receipt_item='{$extra_added_items_value}'",""); 
                     echo '<td style="font-size:14px;padding:4px;width:200px;color:#000; " ><b>'.$result[0]['total_paid'].'</b></td>' ;
                 }
              echo '</tr>';
              $i++;
          }  
          echo '</table>' ;
    echo '</div>';
    echo $pagination[0];
    
      if($pagination[2] == 0)
      {
            $username = $db->find_by_sql("*","users ORDER BY username","","");
            $colspan = count($username) + 2;
                echo '<table style="float:left;border:1.5px solid #86b300;" cellpadding="3">';
                   echo '<tr>';
                    echo '<td style="text-align:center;background:#86b300;color:#fff;" colspan="'.$colspan.'"><b>Monthly Items Transaction (Total)</b></td>';
                   echo '</tr>';
                   echo '<tr>';
                    echo '<td style="text-align:left;width:100px;"><b>Month:</b></td><td style="text-align:left;width:120px;">'.$full_month.'</td>';
                   echo '</tr>';
                   echo '<tr>';
                    echo '<td style="text-align:left;width:100px;"><b>Year: </b>'.$year.'</td><td style="text-align:right;width:120px;"><b>All</b></td>';
                    foreach ($username as  $user_value) 
                    {
                        echo '<td style="text-align:right;width:120px;"><b>'.$user_value['username'].'</b></td>';
                    }
                   echo '</tr>';
                 foreach ($receipt_items as $receipt_value)
                 {
                      foreach($date as $date_value)
                      {
                            $total_paid = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item"," date='{$date_value['date']}' AND receipt_item='{$receipt_value['item']}'","");
                            $total_paid_sum += $total_paid[0]["total_paid"];
                            $total_amount_of_all += $total_paid[0]["total_paid"];
                            foreach($username as $user_value)
                            {
                                $total_paid_by_user = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","date='{$date_value['date']}' AND receipt_item='{$receipt_value['item']}' AND username='{$user_value['username']}'","");
                                $total_paid_sum_by_user[$user_value['username']] += $total_paid_by_user[0]["total_paid"];
                                $total_amount_by_user[$user_value['username']]  += $total_paid_by_user[0]["total_paid"];
                            }
                      }
                       echo '<tr>';
                         echo '<td style="text-align:left;width:100px;border:1px solid #86b300;"><b>'.$receipt_value['item'].':</b></td>';
                         echo '<td style="text-align:right;width:120px;border:1px solid #86b300;">'.$total_paid_sum.'</td>';
                        // print_r($total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                              echo '<td style="text-align:right;width:120px;border:1px solid #86b300;">'.$total_paid_sum_by_user[$user_value['username']].'</td>';
                              $total_paid_sum_by_user[$user_value['username']] = 0;
                         }
                       echo '</tr>';
                       $total_paid_sum = 0;
                 }
                 foreach ($extra_added_items as $extra_items_value)
                 {
                       foreach($date as $date_value)
                       {
                          $extra_items_total_paid = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","date='{$date_value['date']}' AND receipt_item='{$extra_items_value['item']}'","");
                          $extra_items_total_paid_sum += $extra_items_total_paid[0]["total_paid"];
                          $total_amount_of_all += $extra_items_total_paid[0]["total_paid"];
                          
                            foreach($username as $user_value)
                            {
                                $extra_items_total_paid_by_user = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","date='{$date_value['date']}' AND receipt_item='{$extra_items_value['item']}' AND username='{$user_value['username']}'","");
                                $extra_items_total_paid_sum_by_user[$user_value['username']] += $extra_items_total_paid_by_user[0]["total_paid"];
                                $total_amount_by_user[$user_value['username']]  += $extra_items_total_paid_by_user[0]["total_paid"];
                            }
                         
                       }
                       echo '<tr>';
                         echo '<td style="text-align:left;width:100px;border:1px solid #86b300;"><b>'.$extra_items_value['item'].':</b></td><td style="text-align:right;width:120px;border:1px solid #ccc;">'.$extra_items_total_paid_sum.'</td>';
                         //print_r($extra_items_total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                             echo '<td style="text-align:right;width:120px;border:1px solid #86b300;">'.$extra_items_total_paid_sum_by_user[$user_value['username']].'</td>';
                             $extra_items_total_paid_sum_by_user[$user_value['username']] = 0;
                         }
                       echo '</tr>';
                       $extra_items_total_paid_sum = 0;
                      
                 }
                 
                  echo '<tr>';
                         echo '<td style="text-align:left;width:100px;border:1px solid #86b300;"><b>Total:</b></td><td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_amount_of_all.'</td>';
                         //print_r($extra_items_total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                             echo '<td style="text-align:right;width:120px;border:1px solid #86b300;">'.$total_amount_by_user[$user_value['username']].'</td>';
                         }
                       echo '</tr>';

                echo '</table>';
      }
    
    echo '</div>';
        
        } // end of receipt_items else
    
  }// end of if($_GET['page'] > $lastpage)else
}


function monthly_items_section_trans_pagination($year,$month,$full_month,$students_id,$section)
{
    global $db;
    
    date_default_timezone_set('Asia/Dhaka');

    $targetpage = "journal.php?category=Journal&&subcat=Monthly Items Collection(Section)";
    $limit = 10;
    $total_pages = count($students_id);
    $pagination = pagination($total_pages,$targetpage,$limit,$section,$date,$year,$month,$full_month);

    if(htmlspecialchars($_GET['page']) > $pagination[1])
    {
       echo '<div id="message" style="font-size:16px;color:#FF9999;"><b>No Result Found</b></div>';
    }else{
        	/* Get data. */
        $date = $db->find_by_sql("DISTINCT date","transaction_archive","year='$year' AND month='$month' ORDER BY id DESC","");

        $students_id = array_slice($students_id,$pagination[2],$pagination[3]);
        
        $receipt_items = $db->find_by_sql("item","receipt_items ORDER BY item_order","","");
        $extra_added_items = $db->find_by_sql("DISTINCT item","extra_added_items ORDER BY item","","");
        
        if($receipt_items == 'No Result Found')
        {
            echo 'No Items Found';
        }else{
            
            
         
    //code for data show 
    
    echo '<div style="width:98%;height:445px;margin:1% auto 1% auto;overflow:auto;">';
    echo '<div class="dr_moz_scrll" style="width:98%; height:460px; float:left; margin:0 0 10px 0; overflow:auto; border:1.5px solid #86b300;border-radius:10px;" >' ;
          echo '<table style=" text-align:center;background:whitesmoke;" >' ;
           echo '<tr style="background:#86b300;color:#fff;">' ;
                echo '<th style="width:200px;padding:2px;">Stu.id</th>' ;
                 echo '<th style="width:200px;padding:2px;border:1px solid #fff;background:OrangeRed ;">Due ('.$month.')</th>' ;
            foreach ($receipt_items as $receipt_value)
            {
                echo '<th style="width:200px;padding:2px;border:1px solid #fff;">'.$receipt_value['item'].'</th>' ;
            }
            foreach ($extra_added_items as $extra_items_value)
            {
                echo '<th style="width:200px;padding:2px;border:1px solid #fff;">'.$extra_items_value['item'].'</th>' ;
            }
           echo '</tr>';
          $i =0;
          foreach ($students_id as $students_id_value)
          {              
                echo $r .= '<tr ';
                if($i%2 == 0)
                {
                   $r .= ' class="tr1_hover" ';
                }else 
                    {
                      $r .= ' class="tr2_hover" ';
                    }
                echo $r .= ' >' ;
                 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000; " ><b>'.$students_id_value['student_id'].'</b></td>' ;
                 
                 
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
                 
                 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000; " ><b>'.$due.'</b></td>' ;
                 
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
                         
                        $result = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","year='$year' AND student_id='{$students_id_value['student_id']}' AND receipt_item='{$receipt_items_value}' AND date IN (".$combineDate.")","");
                        $total_items_paid_amount = $result[0]['total_paid'];
                         
                     echo '<td style="font-size:14px;padding:4px;width:200px;color:#000; " ><b>'.$total_items_paid_amount.'</b></td>' ;
                     $total_items_paid_amount = 0;
                 }
                 
                 $total_extraItems_paid_amount = 0;
                 foreach ($extra_added_items as $extra_items_value)
                 {
                     $extra_added_items_value = $extra_items_value['item'];
                    
                     $result = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","year='$year' AND student_id='{$students_id_value['student_id']}' AND receipt_item='{$extra_added_items_value}' AND date IN (".$combineDate.")",""); 
                     $total_extraItems_paid_amount = $result[0]['total_paid'];
                     
                     echo '<td style="font-size:14px;padding:4px;width:200px;color:#000; " ><b>'.$total_extraItems_paid_amount.'</b></td>' ;
                     $total_extraItems_paid_amount = 0;
                 }
              echo '</tr>';
              $i++;
          }  
          echo '</table>' ;
    echo '</div>';
    echo $pagination[0].'</br></br>';
   
      if($pagination[2] == 0)
      {   
          /*
            $username = $db->find_by_sql("*","users ORDER BY username","","");
            $colspan = count($username) + 2;
                echo '<table style="float:left;border:1px solid #0099CC;" cellpadding="3">';
                   echo '<tr>';
                    echo '<td style="text-align:center;background:#0099CC;color:#fff;" colspan="'.$colspan.'"><b>Monthly Items Transaction (Total)</b></td>';
                   echo '</tr>';
                   echo '<tr>';
                    echo '<td style="text-align:left;width:100px;"><b>Month:</b></td><td style="text-align:left;width:120px;">'.$full_month.'</td>';
                   echo '</tr>';
                   echo '<tr>';
                    echo '<td style="text-align:left;width:100px;"><b>Year: </b>'.$year.'</td><td style="text-align:right;width:120px;"><b>All</b></td>';
                    foreach ($username as  $user_value) 
                    {
                        echo '<td style="text-align:right;width:120px;"><b>'.$user_value['username'].'</b></td>';
                    }
                   echo '</tr>';
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
                       echo '<tr>';
                         echo '<td style="text-align:left;width:100px;border:1px solid #ccc;"><b>'.$receipt_value['item'].':</b></td>';
                         echo '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_paid_sum.'</td>';
                        // print_r($total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                              echo '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_paid_sum_by_user[$user_value['username']].'</td>';
                              $total_paid_sum_by_user[$user_value['username']] = 0;
                         }
                       echo '</tr>';
                       $total_paid_sum = 0;
                 }
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
                       echo '<tr>';
                         echo '<td style="text-align:left;width:100px;border:1px solid #ccc;"><b>'.$extra_items_value['item'].':</b></td><td style="text-align:right;width:120px;border:1px solid #ccc;">'.$extra_items_total_paid_sum.'</td>';
                         //print_r($extra_items_total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                             echo '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$extra_items_total_paid_sum_by_user[$user_value['username']].'</td>';
                             $extra_items_total_paid_sum_by_user[$user_value['username']] = 0;
                         }
                       echo '</tr>';
                       $extra_items_total_paid_sum = 0;
                      
                 }
                 
                  echo '<tr>';
                         echo '<td style="text-align:left;width:100px;border:1px solid #ccc;"><b>Total:</b></td><td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_amount_of_all.'</td>';
                         //print_r($extra_items_total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                             echo '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_amount_by_user[$user_value['username']].'</td>';
                         }
                       echo '</tr>';

                echo '</table>';
           
           */
      }
    
    echo '</div>';
        
        } // end of receipt_items else
        
    
  }// end of if($_GET['page'] > $lastpage)else
}





function yearly_items_section_trans_pagination($year,$students_id,$section)
{
    
    global $db;
    
    date_default_timezone_set('Asia/Dhaka');

    $targetpage = "journal.php?category=Journal&&subcat=Yearly Items Collection(Section)";
    $limit = 15;
    $total_pages = count($students_id);
    $pagination = pagination($total_pages,$targetpage,$limit,$section,$date,$year,$month,$full_month);

    if(htmlspecialchars($_GET['page']) > $pagination[1])
    {
       echo '<div id="message" style="font-size:16px;color:#FF9999;"><b>No Result Found</b></div>';
    }else{
        	/* Get data. */

        $students_id = array_slice($students_id,$pagination[2],$pagination[3]);
        
         
    //code for data show 
	
	$months_name = array("January","February","March","April","May","June","July","August","September","October","November","December");
    
    echo '<div style="width:98%;height:700px;margin:1% auto 1% auto;overflow:auto;">';
    echo '<div  style="width:98%; height:550px; float:left; margin:0 0 10px 0; overflow:scroll; border:1.5px solid #86b300;border-radius:10px;" >' ;
          echo '<table style="width:100%;overflow:auto; text-align:center;background:whitesmoke;" >' ;
           echo '<tr style="background:#86b300;color:#fff;">' ;
                echo '<th style="width:300px;padding:2px;">Student.id</th>' ;
				echo '<th style="width:300px;padding:2px;border:1px solid whitesmoke;">Name</th>' ;
				echo '<th style="width:300px;padding:2px;border:1px solid whitesmoke;">Class Roll</th>' ;
            foreach ($months_name as $months_name_value)
            {
                echo '<th style="width:200px;padding:2px;border:1px solid whitesmoke;" colspan="3">'.$months_name_value.'</th>' ;
            }
			echo '<th style="width:300px;padding:2px;background:#0066ff;"></th>' ;
            //foreach ($extra_added_items as $extra_items_value)
            //{
            //    echo '<th style="width:200px;padding:2px;border:1px solid #fff;">'.$extra_items_value['item'].'</th>' ;
            //}
           echo '</tr>';
		    
		   echo '<tr style="background:#86b300;color:#fff;">' ;
           echo '<td style="width:200px;padding:2px;"></td>' ; 
           echo '<td style="width:200px;padding:2px;border:1px solid whitesmoke;"></td>' ;	
           echo '<td style="width:200px;padding:2px;border:1px solid whitesmoke;"></td>' ;		   
            foreach ($months_name as $months_name_value)
            {
                echo '<td style="width:200px;padding:2px;border:1px solid whitesmoke;">Due</td>' ;
				echo '<td style="width:200px;padding:2px;border:1px solid whitesmoke;">Paid</td>' ;
				echo '<td style="width:200px;padding:2px;border:1px solid whitesmoke;">Status<br>(Paid Date)</td>' ;
            }
            echo '<td style="width:200px;padding:2px;border:1px solid whitesmoke;background:#0066ff;">Total</td>' ;
           echo '</tr>';
		   
          $i =0;
          foreach ($students_id as $students_id_value)
          {              
                echo $r .= '<tr ';
                if($i%2 == 0)
                {
                   $r .= ' class="tr1_hover" ';
                }else 
                    {
                      $r .= ' class="tr2_hover" ';
                    }
                echo $r .= ' >' ;
                echo '<td style="font-size:14px;padding:4px;width:200px;color:#000; " ><b>'.$students_id_value['student_id'].'</b></td>' ;
				echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #86b300; " ><b>'.$students_id_value['student_name'].'</b></td>' ;
				echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #86b300; " ><b>'.$students_id_value['class_roll'].'</b></td>' ;
                 
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
						 
						 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #86b300; " ><b>'.$due.'</b></td>' ;
						 
						 $monthNameShort = substr($months_name_value,0,3);
						 
						 
						 $result = $db->find_by_sql("SUM(paid) AS total_paid","transaction_archive","year='$year' AND month='$monthNameShort' AND student_id='{$students_id_value['student_id']}'","");
						 
						 if(empty($result[0]['total_paid']))
						 {
							 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #86b300; " ><b></b></td>' ;
						 }else{
						    echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #86b300; " ><b>'.$result[0]['total_paid'].'</b></td>' ;
							$total_paid += $result[0]['total_paid'];
						 }
						 
						 if($payment_status == 'No Result Found')
						 {
						    echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #86b300; " ><b></b></td>';
						 }else{
							 
							 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #86b300; " ><b>'.$payment_status[0]['status'].'<br> ('.$payment_status[0]['date'].')</b></td>';
						 }
						 
						 
						 
						 /*$total_items_paid_amount = 0;
						 
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
								 
								$result = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","year='$year' AND student_id='{$students_id_value['student_id']}' AND receipt_item='{$receipt_items_value}' AND date IN (".$combineDate.")","");
								$total_items_paid_amount = $result[0]['total_paid'];
								 
							 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$total_items_paid_amount.'</b></td>' ;
							 $total_items_paid_amount = 0;
						 } */
						 
						// $total_extraItems_paid_amount = 0;
						// foreach ($extra_added_items as $extra_items_value)
						// {
						//	 $extra_added_items_value = $extra_items_value['item'];
							
						//	 $result = $db->find_by_sql("SUM(paid) AS total_paid","transaction_receipt_item","year='$year' AND student_id='{$students_id_value['student_id']}' AND receipt_item='{$extra_added_items_value}' AND date IN (".$combineDate.")",""); 
						//	 $total_extraItems_paid_amount = $result[0]['total_paid'];
							 
						//	 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$total_extraItems_paid_amount.'</b></td>' ;
						//	 $total_extraItems_paid_amount = 0;
						 //}
				 }
				 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #86b300; " ><b>'.$total_paid.'</b></td>' ;
              echo '</tr>';
              $i++;
          }  
          echo '</table>' ;
    echo '</div>';
    echo $pagination[0].'</br></br>';
   
      if($pagination[2] == 0)
      {   
          /*
            $username = $db->find_by_sql("*","users ORDER BY username","","");
            $colspan = count($username) + 2;
                echo '<table style="float:left;border:1px solid #0099CC;" cellpadding="3">';
                   echo '<tr>';
                    echo '<td style="text-align:center;background:#0099CC;color:#fff;" colspan="'.$colspan.'"><b>Monthly Items Transaction (Total)</b></td>';
                   echo '</tr>';
                   echo '<tr>';
                    echo '<td style="text-align:left;width:100px;"><b>Month:</b></td><td style="text-align:left;width:120px;">'.$full_month.'</td>';
                   echo '</tr>';
                   echo '<tr>';
                    echo '<td style="text-align:left;width:100px;"><b>Year: </b>'.$year.'</td><td style="text-align:right;width:120px;"><b>All</b></td>';
                    foreach ($username as  $user_value) 
                    {
                        echo '<td style="text-align:right;width:120px;"><b>'.$user_value['username'].'</b></td>';
                    }
                   echo '</tr>';
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
                       echo '<tr>';
                         echo '<td style="text-align:left;width:100px;border:1px solid #ccc;"><b>'.$receipt_value['item'].':</b></td>';
                         echo '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_paid_sum.'</td>';
                        // print_r($total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                              echo '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_paid_sum_by_user[$user_value['username']].'</td>';
                              $total_paid_sum_by_user[$user_value['username']] = 0;
                         }
                       echo '</tr>';
                       $total_paid_sum = 0;
                 }
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
                       echo '<tr>';
                         echo '<td style="text-align:left;width:100px;border:1px solid #ccc;"><b>'.$extra_items_value['item'].':</b></td><td style="text-align:right;width:120px;border:1px solid #ccc;">'.$extra_items_total_paid_sum.'</td>';
                         //print_r($extra_items_total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                             echo '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$extra_items_total_paid_sum_by_user[$user_value['username']].'</td>';
                             $extra_items_total_paid_sum_by_user[$user_value['username']] = 0;
                         }
                       echo '</tr>';
                       $extra_items_total_paid_sum = 0;
                      
                 }
                 
                  echo '<tr>';
                         echo '<td style="text-align:left;width:100px;border:1px solid #ccc;"><b>Total:</b></td><td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_amount_of_all.'</td>';
                         //print_r($extra_items_total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                             echo '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_amount_by_user[$user_value['username']].'</td>';
                         }
                       echo '</tr>';

                echo '</table>';
           
           */
      }
    
    echo '</div>';
        
    
        
    
  }// end of if($_GET['page'] > $lastpage)else
}




function monthly_past_transaction_pagination($year,$full_month,$students_id,$paid_years)
{
    global $db;
    
    date_default_timezone_set('Asia/Dhaka');

    $targetpage = "journal.php?category=Journal&&subcat=Collection of past year";
    $limit = 15;
    $total_pages = count($students_id);
    $pagination = pagination($total_pages,$targetpage,$limit,$section_name,$date,$year,$month,$full_month);

    
if(htmlspecialchars($_GET['page']) > $pagination[1])
    {
       echo '<div id="message" style="font-size:16px;color:#FF9999;"><b>No Result Found</b></div>';
    }else{
        	/* Get data. */
        //$date = $db->find_by_sql("DISTINCT date","transaction_archive","year='$year' AND month='$month' ORDER BY id DESC","");

        $students_id = array_slice($students_id,$pagination[2],$pagination[3]);
        
        //$receipt_items = $db->find_by_sql("item","receipt_items ORDER BY item_order","","");
       // $extra_added_items = $db->find_by_sql("DISTINCT item","extra_added_items ORDER BY item","","");
        $items = array("date","time","paid year","paid month","paid");
      
            
    //code for data show 

    echo '<div style="width:98%;height:445px;margin:1% auto 1% auto;overflow:auto;">';
    echo '<div class="dr_moz_scrll" style="width:98%; height:460px; float:left; margin:0 0 10px 0; overflow:auto; border:1px solid #0099CC;" >' ;
          echo '<table style=" text-align:center;background:whitesmoke;margin:auto;" >' ;
           echo '<tr style="background:#0099CC;color:#fff;">' ;
                echo '<th style="width:200px;padding:2px;">Stu.id</th>' ;
            foreach ($items as $items_value)
            {
                echo '<th style="width:200px;padding:2px;border:1px solid #fff;">'.$items_value.'</th>' ;
            }
            
           echo '</tr>';
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
                                echo $r .= '<tr ';
                                if($i%2 == 0)
                                {
                                   $r .= ' class="tr1_hover" ';
                                }else 
                                    {
                                      $r .= ' class="tr2_hover" ';
                                    }
                                echo $r .= ' >' ;


                                 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000; " ><b>'.$students_id_value['student_id'].'</b></td>' ;
                                 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$dateNtime[0]['date'].'</b></td>' ;
                                 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$dateNtime[0]['time'].'</b></td>' ;
                                 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$paid_years_value['paid_year'].'</b></td>' ;
                                 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$paid_months_value['paid_month'].'</b></td>' ;
                                 echo '<td style="font-size:14px;padding:4px;width:200px;color:#000;border:1px solid #fff; " ><b>'.$result[0]['total_paid'].'</b></td>' ;

                              echo '</tr>';
                              $i++;
              
                        }
              }
                        
                     
              }
          }  
          echo '</table>' ;
    echo '</div>';
    echo $pagination[0];
    
      if($pagination[2] == 0)
      {
            $username = $db->find_by_sql("*","users ORDER BY username","","");
            $colspan = count($username) + 2;
                echo '<table style="float:left;border:1px solid #0099CC;" cellpadding="3">';
                   echo '<tr>';
                    echo '<td style="text-align:center;background:#0099CC;color:#fff;" colspan="'.$colspan.'"><b>Monthly Items Transaction (Total)</b></td>';
                   echo '</tr>';
                   echo '<tr>';
                    echo '<td style="text-align:left;width:100px;"><b>Month:</b></td><td style="text-align:left;width:120px;">'.$full_month.'</td>';
                   echo '</tr>';
                   echo '<tr>';
                    echo '<td style="text-align:left;width:100px;"><b>Year: </b>'.$year.'</td><td style="text-align:right;width:120px;"><b>All</b></td>';
                    foreach ($username as  $user_value) 
                    {
                        echo '<td style="text-align:right;width:120px;"><b>'.$user_value['username'].'</b></td>';
                    }
                   echo '</tr>';
                
                            $total_paid = $db->find_by_sql("SUM(paid) AS total_paid","transaction_notcurrentyear","current_year='$year' AND current_month='$full_month' ","");
                            
                            foreach($username as $user_value)
                            {
                                $total_paid_by_user = $db->find_by_sql("SUM(paid) AS total_paid","transaction_notcurrentyear","current_year='$year' AND current_month='$full_month' AND username='{$user_value['username']}'","");
                                $total_paid_sum_by_user[$user_value['username']] += $total_paid_by_user[0]["total_paid"];
                            }
                      
                       echo '<tr>';
                        echo '<td style="text-align:left;width:100px;border:1px solid #ccc;"><b>Total</b></td>';
                         echo '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_paid[0]['total_paid'].'</td>';
                        // print_r($total_paid_sum_by_user);
                         foreach($username as $user_value)
                         {
                              echo '<td style="text-align:right;width:120px;border:1px solid #ccc;">'.$total_paid_sum_by_user[$user_value['username']].'</td>';
                              $total_paid_sum_by_user[$user_value['username']] = 0;
                         }
                       echo '</tr>';
                

                echo '</table>';
      }
    
    echo '</div>';
        
      
    
  }// end of if($_GET['page'] > $lastpage)else
}


function pagination($total_pages,$targetpage,$limit,$section_name,$date,$year,$month,$full_month)
{
         $adjacents = 3;
        
	$total_pages = $total_pages;
	
	/* Setup vars for query. */
	$targetpage = $targetpage; //your file name  (the name of this file)
	$limit = $limit; 				//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 	//first item to display on this page
	else
		$start = 0;	//if no page var is given, set start to 0
	      
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;	//if no page var is given, default to 1.
	$prev = $page - 1;		//previous page is page - 1
	$next = $page + 1;		//next page is page + 1
	$lastpage = ceil($total_pages/$limit);	//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;	//this is previous page of last page //last page minus 1
	
	/* 
            Now we apply our rules and draw the pagination object. 
            We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
                $pagination .= '<span style="padding:2px 4px;border:1.5px solid green;border-radius:10px;background:whitesmoke;">Page '.$page.'/'.$lastpage.'</span>';
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=$prev\" onclick=\"doLoading()\">&nbsp;<< </a>";
		else
			$pagination.= "<span class=\"disabled\">&nbsp;<< </span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\" >$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=$counter\" onclick=\"doLoading()\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=$counter\" onclick=\"doLoading()\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=$lpm1\" onclick=\"doLoading()\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=$lastpage\" onclick=\"doLoading()\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=1\" onclick=\"doLoading()\">1</a>";
				$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=2\" onclick=\"doLoading()\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=$counter\" onclick=\"doLoading()\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=$lpm1\" onclick=\"doLoading()\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=$lastpage\" onclick=\"doLoading()\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=1\" onclick=\"doLoading()\">1</a>";
				$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=2\" onclick=\"doLoading()\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\" >$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=$counter\" onclick=\"doLoading()\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage&&date=$date&&month=$full_month&&year=$year&&section=$section_name&&subm=$month&&page=$next\" onclick=\"doLoading()\"> >>&nbsp;</a>";
		else
			$pagination.= "<span class=\"disabled\" > >>&nbsp;</span>";
		$pagination.= "</div>\n";		
	}
        
        return array(0=>$pagination,1=>$lastpage,2=>$start,3=>$limit);
}

?>