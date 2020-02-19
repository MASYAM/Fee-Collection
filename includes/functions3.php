<?php
function year2($section)
{ global $db;
     $years = $db->find_by_sql("*","years ORDER BY year DESC","","");
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
       
       $r .='<input type="hidden" name="section_name" value="'.$section.'" >';
       $r .= '</tr>';
     $r .= '</table>';
    $r .= '</form>';
    
    return $r;
}


function months_field2($section,$student_id,$selected_month,$submit_value,$year)
{
    global $db;

    $months = $db->find_by_sql("*","months ORDER BY id","",""); 
    if(is_array($selected_month))
    { $selected_month = $db->set_checked($selected_month,'checked'); }
    else{
       $selected_month = $db->set_checked(array(0=>$selected_month),'checked'); 
    }
    
     $r .= '<div id="student_identity" style="background:Gainsboro ;padding:10px;width:860px;margin:30px  auto 0 auto;">';
      $r .= '<form action="" method="post">';
      $r .= '<table style="margin:auto;font-size:14px;" cellpadding="2">';
      $r .= '<tr>';
      foreach ($months as $value)
      {
            $r .= '<td><input type="radio" name="selected_month[]" value="'.$value["month"].'" '.$selected_month[$value['month']].'></td>' ;
            $r .= '<td style="width:100px;text-align:left;"><b>'.$value["month"].'</b></td>';
      }
      $r .= '</tr>';
      $r .= '</table>';
        $r .= '<input type="hidden" name="submit" value="">';
        $r .= '<input type="hidden" name="student_id" value="'.$student_id.'">';
        $r .= '<input type="hidden" name="section_name" value="'.$section.'">';
        $r .= '<input type="hidden" name="year" value="'.$year.'">';
        $r .= '<p style="width:70px;margin:10px auto 0 auto;"><input type="submit" style="float:right;" id="submit_button" name="'.$submit_value.'" value="Submit"></p>';
      $r .= '</form>';
     $r .= '</div>';
     
     return $r;
}

function section()
{
    global $db,$available_section;
    
    $r = '';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:5px auto 0 auto;font-weight:bold;">';
    $r .='<tr>';   
    $r .='<td>';
    $r .='<select name="section_name" title="Available Sections"  id="section" onchange="this.form.submit()">';
    $r .='<option value="Available Section">Available Sections</option>';
    $r .='<optgroup label="Sections :">';

        foreach($available_section as $sec_val){
        $r .='<option value="' . $sec_val['section'] . '" >' . $sec_val['section'] . '</option>';
        }
        $r .= '</optgroup>';
    
    $r .= '</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='</tr>';
    $r .='</table>';
    $r .= '<input type="hidden" name="only_4_name_id">';
    $r .='</form>';

    return $r;
}


function set_regular_due_for_section($section,$year)
{
    $r .= '<div id="set_regular_due" style="float:left;height:100px;">';
      $r .='<form action="" method="post">';
      $r .= '<table style="margin:auto;font-size:14px;" cellpadding="5">';
      $r .= '<tr>';
      $r .= '<td style="color:darkred;"><b>SET REGULAR DUE FOR SECTION : '.$section.'</b></td>';
      $r .= '</tr>';
      $r .= '<tr>';
      $r .='<td></td>';
      $r .= '</tr>';
      $r .= '<tr>';
      $r .='<td><input type="submit" id="submit_button" name="proceed_by_section" value="Proceed"></td>';
      $r .= '</tr>';
      $r .= '</table>';
      $r .= '<input type="hidden" name="section_name" value="'.$section.'">';
      $r .= '<input type="hidden" name="year" value="'.$year.'">';
      $r .='</form>';
     $r .= '</div>';
     
     return $r;
}


function set_regular_due_for_student($section,$year)
{
    $r .= '<div id="set_regular_due" style="float:left;margin-left:10px;height:100px;">';
      $r .='<form action="" method="post">';
      $r .= '<table style="margin:auto;font-size:14px;" cellpadding="5">';
      $r .= '<tr>';
      $r .= '<td style="color:darkred;"><b>EDIT REGULAR DUE FOR STUDENT</b></td>';
      $r .= '</tr>';
      $r .= '<tr>';
      $r .='<td><b>Student Id</b> <input type="text" style="border:1px solid #999;width:100px;height:20px;border-radius:2px;" name="student_id" required></td>';
      $r .= '</tr>';
      $r .= '<tr>';
      $r .='<td><input type="submit" id="submit_button" name="proceed_by_student" value="Proceed"></td>';
      $r .= '</tr>';
      $r .= '</table>';
      $r .= '<input type="hidden" name="section_name" value="'.$section.'">';
      $r .= '<input type="hidden" name="year" value="'.$year.'">';
      $r .='</form>';
     $r .= '</div>';
     
     return $r;
}


function set_regular_due($section,$selected_month,$year)
{
    global $db;
    
    $receipt_items =  $db->find_by_sql("item","receipt_items ORDER BY LENGTH(item_order),item_order","","");
    $academic_year = $db->find_by_sql("DISTINCT academic_year","receipt_items_amount","section='$section' AND month='{$selected_month[0]}' AND year='$year' ","");
         
         if($academic_year == 'No Result Found')
         { $academic_year = ''; }  else { $academic_year = $academic_year[0]['academic_year']; }
     
     $r .= '<form action="" method="post">';
     $r .= '<div id="receipt">';
      $r .= '<div style="width:700px;height:20px;background:#0099CC;color:#fff;text-align:center;"><b>SET ITEMS AMOUNT OF PAYMENT RECEIPT(MONTH:'.$selected_month[0].')</b></div>';
      
      $r .= '<table style="margin:auto;border:1px solid #000;font-size:14px;" border="1" cellpadding="3">';
      $i = 0;
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;"><b>S.L.</b></td>';
      $r .= '<td style="width:280px;text-align:center;"><b>Particulars</b></td>';
      $r .= '<td style="width:150px;text-align:center;"><b>Amount(Tk)</b></td>';
      $r .= '</tr>';
      
            foreach ($receipt_items as $item_value) 
            {
                $amount = $db->find_by_sql("amount","receipt_items_amount","section='$section' AND item='{$item_value['item']}' AND month='$selected_month[0]' AND year='$year'","");
                if($amount == 'No Result Found'){ $amount = 0; }else{ $amount = $amount[0]['amount']; }
                  $r .= '<tr>';
                  $r .= '<td style="text-align:left;">'.++$i.'</td>';
                  $r .= '<input type="hidden"  name="item[]" value="'.$item_value['item'].'">';
                  $r .= '<td style="text-align:left;">'.$item_value['item'].'</td>';
                  $r .= '<td style="text-align:right;"><input type="text" size="12" style="text-align:right;"  name="amount[]" value="'.$amount.'">/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                  $r .= '</tr>';
            }
      
      $r .= '</table>';
      $r .= '<input type="hidden" name="section_name" value="'.$section.'" >';
      $r .= '<input type="hidden" name="year" value="'.$year.'" >';
      $r .= '<input type="hidden" name="selected_month" value="'.$selected_month[0].'" >';
     $r .= '</div>';
     $r .='<table style="margin:auto;" cellpadding="5">';
      $r .= '<tr>';
      $r .= '<td style="width:700px;text-align:right;">'
              . '<b>Academic Year:</b> <input type="text" style="height:25px;" name="academic_year" value="'.$academic_year.'" >'
              . '&nbsp;<input type="submit" id="submit_button2" name="proceed" value="Proceed" onclick="return confirm(\'Are you sure you want to proceed?\');"></td>';
      $r .= '</tr>';
      $r .='</table>';
      $r .= '</form>';
    
    return $r;
}


function edited_regular_due($section,$item,$amount,$selected_month,$year,$academic_year)
{
    global $db;
     
     $r .= '<form action="" method="post">';
     $r .= '<div id="receipt">';
      $r .= '<div style="width:700px;height:20px;background:#0099CC;color:#fff;text-align:center;"><b>SET ITEMS AMOUNT OF PAYMENT RECEIPT(MONTH:'.$selected_month[0].')</b></div>';
      
      $r .= '<table style="margin:auto;border:1px solid #000;font-size:14px;" border="1" cellpadding="3">';
     
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;"><b>S.L.</b></td>';
      $r .= '<td style="width:280px;text-align:center;"><b>Particulars</b></td>';
      $r .= '<td style="width:150px;text-align:center;"><b>Amount(Tk)</b></td>';
      $r .= '</tr>';
         $i = 0;
         $j = 0;
            foreach ($item as $item_value)
            {
                  $r .= '<tr>';
                  $r .= '<td style="text-align:left;">'.++$i.'</td>';
                  $r .= '<td style="text-align:left;"><input type="text" size="50"  style="border:1px solid transparent;"  name="item[]" value="'.$item_value.'"></td>';
                  $r .= '<td style="text-align:right;"><input type="text" size="12" style="text-align:right;"  name="amount[]" value="'.$amount[$j].'">/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                  $r .= '</tr>';
                $j++;
            }
      
      $r .= '</table>';
      $r .= '<input type="hidden" name="section_name" value="'.$section.'" >';
      $r .= '<input type="hidden" name="year" value="'.$year.'" >';
      $r .= '<input type="hidden" name="selected_month" value="'.$selected_month[0].'" >';
      
     $r .= '</div>';
     $r .='<table style="margin:auto;" cellpadding="5">';
      $r .= '<tr>';
      $r .= '<td style="width:700px;text-align:right;">'
              . '<b>Academic Year:</b> <input type="text" style="height:25px;" name="academic_year" value="'.$academic_year.'" >'
              . '&nbsp;<input type="submit" id="submit_button2" name="proceed" value="Proceed" onclick="return confirm(\'Are you sure you want to proceed?\');"></td>';
      $r .= '</tr>';
      $r .='</table>';
      $r .= '</form>';
    
    return $r;
}


function update_regular_due_for_student($section,$student_id,$selected_month,$year)
{
    global $db;
    $receipt_items =  $db->find_by_sql("item","receipt_items ORDER BY LENGTH(item_order),item_order","","");
     
     $r .= '<form action="" method="post">';
     $r .= '<div id="receipt">';
      $r .= '<div style="width:700px;height:20px;background:#0099CC;color:#fff;text-align:center;"><b>SET ITEMS AMOUNT OF PAYMENT RECEIPT</b></div>';
      
      $r .= '<table style="margin:auto;border:1px solid #000;font-size:14px;" border="1" cellpadding="3">';
      $i = 0;
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;"><b>S.L.</b></td>';
      $r .= '<td style="width:280px;text-align:center;"><b>Particulars</b></td>';
      $r .= '<td style="width:150px;text-align:center;"><b>Amount(Tk)</b></td>';
      $r .= '</tr>';
      
            foreach ($receipt_items as $item_value) 
            {
                $amount = $db->find_by_sql("amount","receipt_items_amount_on_student","student_id='$student_id' AND section='$section' AND item='{$item_value['item']}' AND month='$selected_month[0]' AND year='$year'","");
                if($amount == 'No Result Found'){ $amount = 0; }else{ $amount = $amount[0]['amount']; }
                  $r .= '<tr>';
                  $r .= '<td style="text-align:left;">'.++$i.'</td>';
                  $r .= '<td style="text-align:left;"><input type="text" size="50"  style="border:1px solid transparent;"  name="item[]" value="'.$item_value['item'].'"></td>';
                  $r .= '<td style="text-align:right;"><input type="text" size="12" style="text-align:right;"  name="amount[]" value="'.$amount.'">/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                  $r .= '</tr>';
            }
      
      $r .= '</table>';
      $r .= '<input type="hidden" name="section_name" value="'.$section.'" >'; 
      $r .= '<input type="hidden" name="year" value="'.$year.'" >';
      $r .= '<input type="hidden" name="student_id" value="'.$student_id.'" >';
      $r .= '<input type="hidden" name="selected_month" value="'.$selected_month[0].'" >';
     $r .= '</div>';
     $r .='<table style="margin:auto;" cellpadding="5">';
      $r .= '<tr>';
      $r .= '<td style="width:700px;text-align:right;"><input type="submit" id="submit_button2" name="proceed" value="Proceed" onclick="return confirm(\'Are you sure you want to proceed?\');"></td>';
      $r .= '</tr>';
      $r .='</table>';
      $r .= '</form>';
    
    return $r;
}


function edited_update_regular_due_for_student($student_id,$section,$item,$amount,$selected_month,$year)
{
    global $db;
     
     $r .= '<form action="" method="post">';
     $r .= '<div id="receipt">';
      $r .= '<div style="width:700px;height:20px;background:#0099CC;color:#fff;text-align:center;"><b>SET ITEMS AMOUNT OF PAYMENT RECEIPT(MONTH:'.$selected_month[0].')</b></div>';
      
      $r .= '<table style="margin:auto;border:1px solid #000;font-size:14px;" border="1" cellpadding="3">';
     
      $r .= '<tr>';
      $r .= '<td style="width:50px;text-align:center;"><b>S.L.</b></td>';
      $r .= '<td style="width:280px;text-align:center;"><b>Particulars</b></td>';
      $r .= '<td style="width:150px;text-align:center;"><b>Amount(Tk)</b></td>';
      $r .= '</tr>';
         $i = 0;
         $j = 0;
            foreach ($item as $item_value)
            {
                  $r .= '<tr>';
                  $r .= '<td style="text-align:left;">'.++$i.'</td>';
                  $r .= '<td style="text-align:left;"><input type="text" size="50"  style="border:1px solid transparent;"  name="item[]" value="'.$item_value.'"></td>';
                  $r .= '<td style="text-align:right;"><input type="text" size="12" style="text-align:right;"  name="amount[]" value="'.$amount[$j].'">/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                  $r .= '</tr>';
                $j++;
            }
      
      $r .= '</table>';
      $r .= '<input type="hidden" name="section_name" value="'.$section.'" >';
      $r .= '<input type="hidden" name="year" value="'.$year.'" >';
      $r .= '<input type="hidden" name="student_id" value="'.$student_id.'" >';
      $r .= '<input type="hidden" name="selected_month" value="'.$selected_month[0].'" >';
     $r .= '</div>';
     $r .='<table style="margin:auto;" cellpadding="5">';
      $r .= '<tr>';
      $r .= '<td style="width:700px;text-align:right;"><input type="submit" id="submit_button2" name="proceed" value="Proceed" onclick="return confirm(\'Are you sure you want to proceed?\');"></td>';
      $r .= '</tr>';
      $r .='</table>';
      $r .= '</form>';
    
    return $r;
}


function upload_student_id($section)
{
    $r .= '<div id="set_regular_due" style="float:left;margin-left:10px;height:100px;">';
      $r .='<form action="" method="post" enctype="multi-part/form-data">';
      $r .= '<table style="margin:auto;font-size:14px;" cellpadding="5">';
      $r .= '<tr>';
      $r .= '<td style="color:darkred;"><b>UPLOAD STUDENT ID (CSV)</b></td>';
      $r .= '</tr>';
      $r .= '<tr>';
      $r .='<td></td>';
      $r .= '</tr>';
      $r .= '<tr>';
      $r .='<td><input type="submit" id="submit_button" name="file_proceed" value="Proceed"></td>';
      $r .= '</tr>';
      $r .= '</table>';
      $r .= '<input type="hidden" name="section_name" value="'.$section.'">';
      $r .='</form>';
     $r .= '</div>';
     
     return $r;
}


function upload_student_id_file($section)
{
    $r .= '<div id="set_regular_due" style="float:left;margin-left:10px;height:150px;">';
      $r .='<form action="" method="post" enctype="multipart/form-data">';
      $r .= '<table style="margin:auto;font-size:14px;" cellpadding="10">';
      $r .= '<tr>';
      $r .= '<td style="color:darkred;"><b>CHOOSE A CSV FILE</b></td>';
      $r .= '</tr>';
      $r .= '<tr>';
      $r .='<td><input type="file" style="width:200px;border-radius:2px;" name="userfile" required></td>';
      $r .= '</tr>';
      $r .= '<tr>';
      $r .='<td><input type="submit" id="submit_button" name="upload" value="Upload"></td>';
      $r .= '</tr>';
      $r .= '</table>';
      $r .= '<input type="hidden" name="section_name" value="'.$section.'">';
      $r .= '<input type="hidden" name="file_proceed" value="">';
      $r .='</form>';
     $r .= '</div>';
     
     return $r;
}


function upload_file($section)
{
    global $db;

    $name = $_FILES['userfile']['name'];
    $csv_file = $_FILES['userfile']['tmp_name'];
    $type = $_FILES['userfile']['type'];
    $size = ($_FILES['userfile']['size'] / 1024) / 1024; //bytes convert to MB
    $student_info_column = array(0=>'class_roll',1=>'father_name',2=>'mother_name',3=>'religion',4=>'phone',5=>'email');
    //$file_parts = pathinfo($test);
    // echo $file_parts['extension'];

    if (!is_file($csv_file)) {
        echo '<div style="padding: 10px;margin:auto;width: 500px;height:10px;"></div><div id="message" style="color:#CC3300;"><b>File Not Found</b></div>';
    } elseif ($size > 100) {
        echo '<div style="padding: 10px;margin:auto;width: 500px;height:10px;"></div><div id="message" style="color:#CC3300;"><b>File size: ' . $size . 'MB (Maximum file size 100MB will be accepted)</b></div>';
    } else {
        $mime_type = mime_content_type($csv_file);
        $extention = pathinfo($name, PATHINFO_EXTENSION);
        if ( $mime_type == 'text/plain' && $extention == 'csv') 
        {
            if (($handle = fopen($csv_file, "r")) !== FALSE) 
            {
               $s = upload_file_validation($handle,$section);
                if (strlen($s) == 0) 
                {
                    if (($handle = fopen($csv_file, "r")) !== FALSE) 
                    {

                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
                         {
                                $data0_id = $db->escape_value($data[0]);
                                $data1_name = $db->escape_value($data[1]);
                                $p = 2;
                                if ($db->find_by_sql("*", "student_on_section", "section='$section' AND student_id='$data0_id'","") !== 'No Result Found') 
                                {
                                        //update
                                             if(strlen($data1_name) > 0)
                                             {
                                                $update = $db->update("student_info", "student_name='$data1_name'", "student_id='$data0_id'");
                                             }  
                                            foreach ($student_info_column as $value) 
                                            {
                                                    if(strlen($data[$p]) > 0)
                                                    {
                                                        $update = $db->update("student_info", "$value='$data[$p]'", "student_id='$data0_id'");
                                                    }
                                                    $p++;
                                            }
                                             
                                }else{
                                            $insert = $db->insert("student_on_section", "section,student_id","'$section','$data0_id'","");
                                    
                                            $insert2 = $db->insert("student_info", "student_id","'$data0_id'","student_id='$data0_id'");
                                                   
                                            if(strlen($data1_name) > 0)
                                            {
                                               $update = $db->update("student_info", "student_name='$data1_name'", "student_id='$data0_id'");
                                            } 
                                            foreach ($student_info_column as $value) 
                                            {
                                                    if(strlen($data[$p]) > 0)
                                                    {
                                                       $update = $db->update("student_info", "$value='$data[$p]'", "student_id='$data0_id'");
                                                    }
                                                    $p++;
                                            }
                                            
                                
                                              //if want to set regular due
//                                                    $receipt_items =  $db->find_by_sql("item","receipt_items ORDER BY LENGTH(item_order),item_order","","");
//                                                    if($receipt_items !== 'No Result Found')
//                                                    {
//                                                            foreach ($receipt_items as $value)
//                                                            {
//                                                                $amount = $db->find_by_sql("amount","receipt_items_amount","section='$section' AND item='{$value['item']}'","");
//                                                                if($amount == 'No Result Found'){ $amount = 0; }else{ $amount = $amount[0]['amount']; }
//
//                                                                if($db->insert("receipt_items_amount_on_student","student_id,section,item,amount","'$data0_id','$section','{$value['item']}','{$amount}'","student_id='$data0_id' AND section='$section' AND item='{$value['item']}'") == 'already exist')
//                                                                {
//                                                                    $update2 = $db->update("receipt_items_amount_on_student","amount='{$amount}'","student_id='$data0_id' AND section='$section' AND item='{$value['item']}'");
//                                                                }
//                                                            }
//                                                    }
                                              
                                            
                                    }
                        }//end of while loop
                         if($update == 'updated succesfully' || $update == 'not been updated' || $insert == 'created succesfully' || $insert == 'not been created')
                         {
                           echo '<div style="padding: 10px;margin:10px auto;width: 500px;height:5px;"></div><div id="message" style="color:#336600;"><b>File has been uploaded successfully</b></div>';
                         }
                        
                    }
                    fclose($handle);
                }else{
                       echo '<div style="padding: 5px;margin:auto;width: 500px;height:5px;"></div><div id="message"  style="color:#CC3300;overflow:auto;"><b>'.$s.'</b></div>';
                     }
            }
            
        } else {
                 echo '<div style="padding: 10px;margin:auto;width: 500px;height:5px;"></div><div id="message" style="color:#CC3300;"><b>File Type Not Supported</b></div>';
              }
    }
}


function upload_file_validation($handle,$section)
{ global $db,$available_section;

        foreach ($available_section as $available_section_val)
        { 
            $sec[] = $available_section_val['section'];
        }
        $count_section = count($sec);    

       while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
       {   
            $data0_id = $db->escape_value($data[0]);
            $data1_name = $db->escape_value($data[1]);
            $p = 2;
                if (strlen($data[0]) == 0 )
                {
                  return 'Id can not be empty<br>' ;							
                }
                if(preg_match("/([',\"])/", $data[1]))
                {
                   return 'Invalid Data Found for <br>Student Id : '.$data[0].', Name : '.$data[1].'<br>(Invalid! student name)<br>' ;						
                }
                
                for($j=0;$j<$count_section;$j++)
                {
                    $escape_student_id = $data0_id;
                    $escape_student_name = $data1_name;
                    if($sec[$j] !== $section)
                    {  $check = $db->query("SELECT * FROM student_on_section WHERE section='$sec[$j]' AND student_id='$escape_student_id'");
                       if ($db->num_rows($check)) 
                       {
                           $exist_student_id = $data0_id;
                           $exist_sec_name   =  $sec[$j];
                           return  'Student id: "' . $exist_student_id . '" already exist in Section: "' . $exist_sec_name . '"<br>Student Id must be unique';
                       }
                    }
                }

               
        }
}




function edit_info($selected_student_id,$section_name)
{   
    global $db;

    $r.='<div style="margin:auto;width: 95%;height:420px;overflow:auto;">';
    $r .='<div id="ed_term_header" style="width: 94%;font-size:14px;"><b>Edit information of section: '.$section_name.' </b></div>';
   $i = 0;
   
       $r .= '<table style="border:1px solid #ccc;" border="1">
                  <tr  style="background:#669999;color:black;">
                  <td style="width:130px;"><b>Student Id</b></td>
                  <td style="width:200px;"><b>Student Name</b></td>
                  <td style="width:80px;"><b>Class Roll</b></td>
                  <td style="width:170px;"><b>Father Name</b></td>
                  <td style="width:170px;"><b>Mother Name</b></td>
                  <td style="width:100px;"><b>Religion</b></td>
                  <td style="width:150px;"><b>Phone</b></td>
                  <td style="width:130px;"><b>Email</b></td>
              </tr>';
     foreach($selected_student_id as $value)
      {
            $student_info = $db->find_by_sql("*","student_info","student_id='{$value}'","");
                        
             $r .= '<tr style="font-size:14px;"';
              if($i%2 == 0)
              {
                 $r .= ' class="tr1_hover" ';
              }else 
                  {
                    $r .= ' style="background:#ccc;" ';
                  }
              $r .= ' >' ;
               $r .='<td>'.$value.'</td>';
                $r .='<td>'.$student_info[0]['student_name'].'</td>';
                $r .='<td>'.$student_info[0]['class_roll'].'</td>';
                $r .='<td>'.$student_info[0]['father_name'].'</td>';
                $r .='<td>'.$student_info[0]['mother_name'].'</td>';
                $r .='<td>'.$student_info[0]['religion'].'</td>';
                $r .='<td>'.$student_info[0]['phone'].'</td>';
                $r .='<td>'.$student_info[0]['email'].'</td>';

            $r .='</tr>';
            $i++;
      }
    $r .='</table>';       
    $r .='<table style="float:right;margin:0 40px 0 0;width:430px;" >';
    $r .='<tr>';
    $r .='<td style="text-align:right;padding:10px 0 0 0;"><input type="submit" id="submit_button" name="finally_edit" value="Edit"  onclick="return confirm(\' Are you sure you want to edit ?\');">
          </td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'" ">';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}


?>
