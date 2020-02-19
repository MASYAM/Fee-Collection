<?php
require 'authenticate.php';
require_once('includes/MySqlDb.php');
require 'includes/functions.php';
require 'includes/functions2.php';
require 'includes/functions3.php';

$available_section   = $db->find_by_sql("section","section ORDER BY section ASC","","");

?>

<?php require 'includes/header.php';   ?>
  <div id="main_content">
   <?php   
   if($category == 'View')        
   {
       if($_GET['subcat'] == 'Students In Section')
       {
               echo section();
                     
                     if(isset($_POST['section_name']))
                     {
                            $section = $_POST['section_name'];
                            $student = $db->find_by_sql("DISTINCT student_id","student_on_section","section='$section'","");
                            if($student == 'No Result Found')
                            {
                                  echo '<div style="padding: 20px;margin:auto;width: 500px;height:5px;"></div>
                                                       <div id="message" ><b>No student found in section: '.$section.'</b></div>';
                                                  
                            }else{
                                    echo '<div style="width:1000px;height:450px;margin:10px auto 0 auto;border:1.5px solid #86b300;border-radius:10px;overflow:auto;">';
                                    echo '<table style="border:1px solid #ccc;" border="1">';
                                          echo '<tr  style="background:#86b300;color:#fff;">';
                                              echo'<td style="width:130px;"><b>Student Id</b></td>';
                                              echo'<td style="width:200px;"><b>Student Name</b></td>';
                                              echo'<td style="width:200px;"><b>Class Roll</b></td>';
                                              echo'<td style="width:130px;"><b>Father Name</b></td>';
                                              echo'<td style="width:130px;"><b>Mother Name</b></td>';
                                              echo'<td style="width:100px;"><b>Religion</b></td>';
                                              echo'<td style="width:80px;"><b>Phone</b></td>';
                                              echo'<td style="width:100px;"><b>Email</b></td>';
                                          echo'</tr>';
                                         $i = 0;
                                      foreach($student as $value)
                                      {
                                          $student_info = $db->find_by_sql("*","student_info","student_id='{$value['student_id']}'","");
                                          echo $r .= '<tr ';
                                            if($i%2 == 0)
                                            {
                                               $r .= ' class="tr1_hover" ';
                                            }else 
                                                {
                                                  $r .= ' class="tr2_hover" ';
                                                }
                                            echo $r .= ' >' ;
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
                            }
                     }
       }
       elseif($_GET['subcat'] == 'Receipt Collection')
       {
              echo year_n_month_field("");
                 if(isset($_POST['submit']))
                 {
                            $year = $_POST['year'];
                            $month = $_POST['month'];
                            $result = $db->find_by_sql("*","receipt_id","year='$year' AND month='$month' ORDER BY id DESC","");
                            if( $result == 'No Result Found')
                                {
                                     echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                          <div id="message" ><b>No Receipt Collection Found</b></div>';
                                }else{
                                             echo '<div style="width:925px;height:450px;margin:10px auto 0 auto;border:1.5px solid #86b300;border-radius:10px;overflow:auto;">';
                                                echo '<table style="border:1px solid #ccc;" border="1">';
                                                      echo '<tr  style="background:#86b300;color:#fff;">';
                                                          echo'<td style="width:80px;"><b>Year</b></td>';
                                                          echo'<td style="width:100px;"><b>Month</b></td>';
                                                          echo'<td style="width:110px;"><b>Date</b></td>';
                                                          echo'<td style="width:130px;"><b>Time</b></td>';
                                                          echo'<td style="width:210px;"><b>Receipt Id</b></td>';
                                                          echo'<td style="width:130px;"><b>Student Id</b></td>';
                                                          echo'<td style="width:150px;"><b>Username</b></td>';
                                                      echo'</tr>';
                                                     $i = 0;
                                                  foreach($result as $value)
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
                                                          echo'<td>'.$value['year'].'</td>';
                                                          echo'<td>'.$value['month'].'</td>';
                                                          echo'<td>'.$value['date'].'</td>';
                                                          echo'<td>'.$value['time'].'</td>';
                                                          echo'<td>'.$value['receipt_id'].'</td>';
                                                          echo'<td>'.$value['student_id'].'</td>';
                                                          echo'<td>'.$value['username'].'</td>';
                                                      echo'</tr>';
                                                      $i++;
                                                  }
                                                echo '</table>';
                                                echo '</div>';
                                     }
                 }
       }
       else{
           echo 'Page not found';
       }
   }else{
          echo 'Page not found';
        }     
   ?> 
  </div>
<?php require 'includes/footer.php';   ?>