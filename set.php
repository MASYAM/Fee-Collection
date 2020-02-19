<?php
require 'authenticate.php';
require_once('includes/MySqlDb.php');
ini_set('max_execution_time', 1800); //3600 seconds = 60 minutes
ini_set("memory_limit","1500M");
require 'includes/functions.php';
require 'includes/functions2.php';
require 'includes/functions3.php';

$available_section   = $db->find_by_sql("section","section ORDER BY section ASC","","");

?>

<?php require 'includes/header.php';   ?>
  <div id="main_content">
   <?php   
   if($category == 'Setup')        
   {
       if($_GET['subcat'] == 'Setup regular due') 
       { 
           require 'authenticate2.php';
           $db->select_db("pdbdorg_macc_englishmedium");
            
                if($available_section !== 'No Result Found')
                {                    
                     echo section();
                     
                     if(isset($_POST['section_name']))
                     {
                          $section = $_POST['section_name'];
                          echo year2($section);
                          
                           if(empty($_POST['year']) == TRUE)
                            {
                               $year = date("Y"); 
                            }else{ $year = $_POST['year']; }
                            
                                
                                $receipt_items = $db->find_by_sql("item","receipt_items ORDER BY item_order","","");
                                $students = $db->find_by_sql("student_id","student_on_section","section='$section'","");
                                echo '<div style="background:skyblue;width:30%;height:30px;margin:5px auto;text-align:center;padding-top:5px;"><b>Selected Section: </b>'.$section.'&nbsp;&nbsp; <b>Year:</b> '.$year.'</div>';
                                if($receipt_items == 'No Result Found')
                                {
                                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                         <div id="message" ><b>No receipt items found</b></div>';
                                }elseif($students == 'No Result Found') 
                                {
                                    echo '<div style="width:300px;height:100px;margin:10px auto;">';
                                        echo upload_student_id($section);
                                     echo '</div>';
                                     
                                    if(!isset($_POST['upload']))
                                    {
                                       echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                           <div id="message" ><b>No student found in section: '.$section.'</b></div>';
                                    }
                                }else{
                                        echo '<div style="width:950px;height:100px;margin:10px auto;">';
                                           echo set_regular_due_for_section($section,$year);
                                           echo set_regular_due_for_student($section,$year);
                                           echo upload_student_id($section);
                                        echo '</div>';
                                }
                     }
                     if(isset($_POST['proceed_by_section']))
                     {
                         $year = $_POST['year'];
                         $section = $_POST['section_name'];
                           
                           echo months_field2($section,$student_id, $selected_month, "month_proceed_by_section",$year);
                         
                     }elseif(isset($_POST['proceed_by_student']))
                     {
                         $year = $_POST['year'];
                         $student_id = $_POST['student_id'];
                         $section = $_POST['section_name'];
                         if($db->find_by_sql("*","student_on_section","student_id='$student_id' AND section='$section'","") == 'No Result Found')
                                {
                                    echo '<div style="margin:auto;width: 500px;height:20px;"></div><div id="message"><b>Student Id:'.$student_id.' does not belong to section: '.$section.'</b></div>';
                                }else{
                                       echo months_field2($section,$student_id, $selected_month, "month_proceed_by_student",$year);
                                    }
                     }
                     
                     if(isset($_POST['month_proceed_by_section']))
                     {
                             $year = $_POST['year'];
                             $section = $_POST['section_name'];
                             $selected_month   = $_POST['selected_month'];

                             echo months_field2($section,$student_id, $selected_month, "month_proceed_by_section",$year);
                               
                              if(count($selected_month) == 0)
                              {
                                  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                           <div id="message" ><b>Please select any month</b></div>';
                              }else{
                                        echo '<div  style="width:900px;height:99%;margin:auto;">';
                                           echo set_regular_due($section,$selected_month,$year);
                                        echo '</div>';
                                   }
                     }
                     
                     if(isset($_POST['month_proceed_by_student']))
                     {
                                $year = $_POST['year'];
                                $selected_month   = $_POST['selected_month'];
                                $section = $_POST['section_name'];
                                $student_id = $_POST['student_id']; 
                                
                                echo months_field2($section,$student_id, $selected_month, "month_proceed_by_student",$year);

                                if(count($selected_month) == 0)
                                {
                                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                             <div id="message" ><b>Please select any month</b></div>';
                                }else{
                                                echo '<div  style="width:900px;height:99%;margin:auto;">';
                                                   echo '<table style="margin:auto;font-size:15px;" cellpadding="2">
                                                          <tr>
                                                           <td style="width:300px;text-align:left;"><b><span style="text-decoration:underline;">EDIT REGULAR DUE FOR STUDENT ID:</span></b> <span style="color:#336600;">'.$student_id.'</span></td>
                                                           <td style="width:260px;text-align:left;"><b></td>
                                                          </tr>
                                                        </table>';
                                                   echo update_regular_due_for_student($section,$student_id,$selected_month,$year);
                                                echo '</div>';
                                    }
                                     
                     }
                     
                     if(isset($_POST['proceed']))
                     {
                                $year = $_POST['year'];
                                $selected_month   = $_POST['selected_month'];
                                $section = $_POST['section_name'];
                                $academic_year = $_POST['academic_year'];
                                $receipt_items = $db->find_by_sql("item","receipt_items ORDER BY item_order","","");
                                if(isset($_POST['student_id']))
                                {
                                    $student_id = $_POST['student_id'];
                                    $students  = array(0 => array('student_id' => $student_id)) ;
                                    $submit_value = 'month_proceed_by_student';
                                }else{
                                       $students = $db->find_by_sql("DISTINCT student_id","student_on_section","section='$section'","");
                                       $submit_value = 'month_proceed_by_section';
                                     }
                                $item   = $_POST['item'];
                                $amount = $_POST['amount'];
                                
                                $total_amount = array_sum($amount);
                          
                                foreach ($amount as $value)
                                {
                                    if(!is_numeric($value))
                                    {
                                        echo months_field2($section,$student_id, array(0=>$selected_month), "month_proceed_by_section",$year);
                                        echo '<div style="margin:auto;width: 500px;height:20px;"></div><div id="message" style="color:#CC3300;"><b>Invalid value: "'.$value.'" found (Only number allow)</b></div>';
                                        if(isset($_POST['student_id'])){ echo edited_update_regular_due_for_student($student_id,$section,$item,$amount,array(0=>$selected_month),$year); }
                                        else{ echo edited_regular_due($section,$item,$amount,array(0=>$selected_month),$year,$academic_year); }
                                        $error = 'error';
                                        break;
                                    }
                                }
                                
                                if($error !== 'error')
                                {
                                      if(!isset($_POST['student_id']))
                                      {  
                                            foreach ($item as $key => $value)
                                            {
                                                if($db->insert("receipt_items_amount","section,month,academic_year,year,item,amount","'$section','$selected_month','$academic_year','$year','{$value}','{$amount[$key]}'","section='$section' AND item='$value' AND month='$selected_month' AND year='$year'") == 'already exist')
                                                {
                                                    $update1 = $db->update("receipt_items_amount","academic_year='$academic_year', amount='{$amount[$key]}'","section='$section' AND item='$value' AND month='$selected_month' AND year='$year'");
                                                }
                                            }
                                      }
                                     
                                        foreach ($students as $stu_val)
                                        {                                            
                                                foreach ($item as $key => $value)
                                                {
                                                    
                                                        $del = $db->delete("receipt_items_amount_on_student","student_id='{$stu_val['student_id']}' AND section='$section' AND month='$selected_month' AND year='$year' AND item='$value'");
                                                    if($amount[$key] != 0)
                                                    { 
                                                        $db->insert("receipt_items_amount_on_student","student_id,section,month,academic_year,year,item,amount","'{$stu_val['student_id']}','$section','$selected_month','$academic_year','$year','{$value}','{$amount[$key]}'","");
                                                       //$update2 = $db->update("receipt_items_amount_on_student","amount='{$amount[$key]}'","student_id='{$stu_val['student_id']}' AND section='$section' AND item='$value' AND month='$selected_month' AND year='$year'");
                                                    }
                                                }
                                        }
                                        
                                        if(!isset($_POST['student_id']))
                                        {
                                              echo '<div style="margin:auto;width: 500px;height:20px;"></div><div id="message"><b>Monthly regular due ('.$total_amount.'tk) updated for section: '.$section.' and month: '.$selected_month.',  Year: '.$year.', Academic Year: '.$academic_year.'</b></div>';
                                        }else{
                                              echo '<div style="margin:auto;width: 500px;height:20px;"></div><div id="message"><b>Regular due ('.$total_amount.'tk) updated for student id: '.$student_id.', section: '.$section.' and month: '.$selected_month.'  Year: '.$year.'</b></div>';
                                        }
                                    
                                }
                     }
                     
                     
                     //file upload
                     
                     if(isset($_POST['file_proceed']))
                     {
                                $section = $_POST['section_name'];

                            echo '<div style="width:550px;height:170px;margin:50px auto 10px auto;">';    
                                echo '<div style="width:200px;height:200px;padding:10px;float:left;border:4px solid #ccc;border-radius:4px;">';
                                echo '<b><span style="text-decoration:underline;">Upload File Maintanance</span></b><br><br>
                                      Column <b>0</b> :: <b>Student Id</b><br>
                                      Column <b>1</b> :: <b>Student Name</b><br>
                                      Column <b>2</b> :: <b>Class Roll</b><br>
                                      Column <b>3</b> :: <b>Father Name</b><br>
                                      Column <b>4</b> :: <b>Mother Name</b><br>
                                      Column <b>5</b> :: <b>Religion</b><br>
                                      Column <b>6</b> :: <b>Phone</b><br>
                                      Column <b>7</b> :: <b>Email</b><br>
                                     ';
                                echo '</div>';

                                echo '<div style="width:300px;height:100px;float:left;">';
                                            echo upload_student_id_file($section);
                                echo '</div>';
                           echo '</div>';
                     }
                     
                     if(isset($_POST['upload']))
                     {
                          $section = $_POST['section_name'];
                         echo upload_file($section);
                     }
                     
                }else{
                    echo 'No section found';
                }
           
           
       }
       if($_GET['subcat'] == 'Edit') 
       {
            require 'authenticate2.php';
            $db->select_db("pdbdorg_macc_englishmedium");
               echo section();
                    if(isset($_POST['section_name']) && isset($_POST['only_4_name_id']))
                     {
                            $section = $_POST['section_name'];
                            $student = $db->find_by_sql("DISTINCT student_id","student_on_section","section='$section'  ORDER BY student_id","");
                            if($student == 'No Result Found')
                            {
                                  echo '<div style="padding: 20px;margin:auto;width: 500px;height:5px;"></div>
                                                       <div id="message" ><b>No student found in section: '.$section.'</b></div>';
                                                  
                            }else{

                                            echo '<form action="" method="post">';
                                            echo '<div style="width:95%;height:420px;margin:10px auto 0 auto;border:1.5px solid #86b300;border-radius:10px;overflow:auto;">';
                                            echo '<table style="border:1px solid #ccc;" border="1">';
                                                  echo '<tr  style="background:#86b300;color:whitesmoke;">';
                                                      echo'<td style="width:30px;"><b></b></td>';
                                                      echo'<td style="width:130px;"><b>Student Id</b></td>';
                                                      echo'<td style="width:200px;"><b>Student Name</b></td>';
                                                      echo'<td style="width:80px;"><b>Class Roll</b></td>';
                                                      echo'<td style="width:170px;"><b>Father Name</b></td>';
                                                      echo'<td style="width:170px;"><b>Mother Name</b></td>';
                                                      echo'<td style="width:100px;"><b>Religion</b></td>';
                                                      echo'<td style="width:150px;"><b>Phone</b></td>';
                                                      echo'<td style="width:130px;"><b>Email</b></td>';
                                                  echo'</tr>';
                                                 $i = 0;
                                              foreach($student as $value)
                                              {
                                                  $student_info = $db->find_by_sql("*","student_info","student_id='{$value['student_id']}'","");
                                                  echo $r .= '<tr style="font-size:14px;" ';
                                                    if($i%2 == 0)
                                                    {
                                                       $r .= ' class="tr1_hover" ';
                                                    }else 
                                                        {
                                                          $r .= ' class="tr2_hover" ';
                                                        }
                                                    echo $r .= ' >' ;
                                                      echo '<td><input type="checkbox" class="case" name="selected_student_id[]" value="'.$value['student_id'].'" ></td>';
                                                      echo'<td>'.$value['student_id'].'</td>';
                                                      echo'<td>'.$student_info[0]['student_name'].'</td>';
                                                      echo'<td>'.$student_info[0]['class_roll'].'</td>';
                                                      echo'<td>'.$student_info[0]['father_name'].'</td>';
                                                      echo'<td>'.$student_info[0]['mother_name'].'</td>';
                                                      echo'<td>'.$student_info[0]['religion'].'</td>';
                                                      echo'<td>'.$student_info[0]['phone'].'</td>';
                                                      echo'<td>'.$student_info[0]['email'].'</td>';
                                                      
                                                  echo'</tr>';
                                                  $i++;
                                              }
                                            echo '</table>';
                                            echo '</div>';
                                            
                                            echo '<table style="float:left;margin:0 0 0 25px;width:430px;" >';
                                            echo '<tr>';
                                            echo '<td style="text-align:left;padding:7px 0 0 0;"><input type="checkbox" id="selectall" /><b>SELECT ALL</b>
                                                  <input type="hidden" name="section_name" value="'.$section.'">
                                                  
                                                  &nbsp; &nbsp; <input type="image" name="selected_delete" alt="submit" value="submit" src="images/DeleteRed.png" width="20" height="20" onclick="return confirm(\'Only id will be deleted. Information of id will not be deleted. Are you sure you want to delete?\');"></td>';
                                            echo '</tr>';
                                            echo '</table>';
                                            echo '</form>';
                            }
                     }
                     
                     
                     if(isset($_POST['edit_x']))
                     {
                             $count_selected_id = count($_POST['selected_student_id']);
                             $selected_id = $_POST['selected_student_id'];
                             $section_name = $_POST['section_name'];
                             if($count_selected_id == 0)
                             {
                                 echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Record Selected</b></div>';
                             }else{
                                  echo edit_info($selected_id,$section_name);
                             }
                         
                     }
                     
                     if(isset($_POST['selected_delete_x']))
                     {
                             $count_selected_id = count($_POST['selected_student_id']);
                             if($count_selected_id == 0)
                             {
                                 echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Record Selected</b></div>';
                             }else{
                                     $selected_id = $_POST['selected_student_id'];
                                     $section_name = $_POST['section_name'];
                                     for($i=0;$i<$count_selected_id;$i++)
                                     { 
                                         $delete_subject = $db->delete("student_on_section","section='$section_name' AND student_id='$selected_id[$i]'"); 
                                     }
                                     if($delete_subject){ echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Selected student id have been deleted successfully</b></div>'; }
                                 }
                     }
                     
                     
       }
   }
   else{
          echo 'Page not found';
        }     
   ?> 
  </div>
<?php require 'includes/footer.php';   ?>