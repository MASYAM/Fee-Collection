<?php
require 'authenticate.php';
require_once('includes/MySqlDb.php');
ini_set('max_execution_time', 1800); //3600 seconds = 60 minutes
ini_set("memory_limit","1500M");
require 'includes/functions.php';
require 'includes/functions2.php';
?>

<?php require 'includes/header.php';   ?>
  <div id="main_content">
   <?php   
   if($category == 'Journal')        
   {
       if($_GET['subcat'] == 'Daily Transaction') 
       {
                echo date_field();

                if(isset($_POST['submit']))
                {
                     $date = $_POST['date'];
                     $explode_date = explode("/", $date);                     
                     $rearrange_date = $explode_date[2].'-'.$explode_date[0].'-'.$explode_date[1] ;
                   
                    if($db->find_by_sql("*","transaction_archive","date='".$rearrange_date."'","") == 'No Result Found')
                     {
                          echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                               <div id="message" ><b>No Result Found</b></div>';
                     }else{
                       echo daily_transaction_pagination($rearrange_date);
                     }
                }elseif($_GET['date'])
                {
                     $date = mysql_real_escape_string($_GET['date']);                     

                     if($db->find_by_sql("*","transaction_archive","date='$date'","") == 'No Result Found')
                     {
                          echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                               <div id="message" ><b>No Result Found</b></div>';
                     }else{
                       echo daily_transaction_pagination($date);
                     }
           }else{
                    date_default_timezone_set('Asia/Dhaka');
                    $date = date('Y-m-d') ;
                    
                    if($db->find_by_sql("*","transaction_archive","date='$date'","") == 'No Result Found')
                    {
                         echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                              <div id="message" ><b>No Transaction Found Today</b></div>';
                    }else{
                      echo daily_transaction_pagination($date);
                    }
           }
           
       }
       elseif($_GET['subcat'] == 'Monthly Transaction')
       {
                    echo year_n_month_field("");
                    if(isset($_POST['submit']))
                    {
                            $year = $_POST['year'];
                            $month = $_POST['month'];
                            $subsmonth = substr($month,0,3);
                            if($db->find_by_sql("*","transaction_archive","year='$year' AND month='$subsmonth'","") == 'No Result Found')
                                {
                                     echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                          <div id="message" ><b>No Transaction Found</b></div>';
                                }else{
                                  echo monthly_transaction_pagination($year,$subsmonth,$month);
                                }
                    }elseif($_GET['month'] && $_GET['year'] && $_GET['subm'])
                    {
                            $year = $_GET['year'];
                            $month = $_GET['month'];
                            $subm = $_GET['subm'];
                            if($db->find_by_sql("*","transaction_archive","year='$year' AND month='$subm'","") == 'No Result Found')
                                {
                                     echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                          <div id="message" ><b>No Transaction Found</b></div>';
                                }else{
                                  echo monthly_transaction_pagination($year,$subm,$month);
                                }
                    }
                    else{
                        $update_calculate_permission = $db->update("permission","calculate_permission='Yes'","username='$user115122'");
                    }
       }
       elseif($_GET['subcat'] == 'Monthly Items Collection')
       {            
                    echo year_n_month_field("");
                    if(isset($_POST['submit']))
                    {
                            $year = $_POST['year'];
                            $month = $_POST['month'];
                            $subsmonth = substr($month,0,3);
                            if($db->find_by_sql("*","transaction_archive","year='$year' AND month='$subsmonth'","") == 'No Result Found')
                                {
                                     echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                          <div id="message" ><b>No Transaction Found</b></div>';
                                }else{
                                  echo monthly_items_transaction_pagination($year,$subsmonth,$month);
                                  echo '<div style="margin:auto;width: 500px;"></div>
                                                        <div id="message">
                                                        <form action="excel_publish_all.php" method="post">
                                                          <input type="hidden" name="year" value="'.$year.'">
                                                          <input type="hidden" name="month" value="'.$subsmonth.'">
                                                          <input type="hidden" name="fullmonth" value="'.$month.'">
                                                          <input type="submit" id="submit_button" style="width:180px;" name="submit" value="Excel Publish">
                                                        </form>
                                                        </div>';
                                }
                    }elseif($_GET['month'] && $_GET['year'])
                    {
                            $year = $_GET['year'];
                            $month = $_GET['month'];
                            $subsmonth = substr($month,0,3);

                            if($db->find_by_sql("*","transaction_archive","year='$year' AND month='$subsmonth'","") == 'No Result Found')
                                {
                                     echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                          <div id="message" ><b>No Transaction Found</b></div>';
                                }else{
                                    echo monthly_items_transaction_pagination($year,$subsmonth,$month);
                                    echo '<div style="margin:auto;width: 500px;"></div>
                                                        <div id="message">
                                                        <form action="excel_publish_all.php" method="post">
                                                          <input type="hidden" name="year" value="'.$year.'">
                                                          <input type="hidden" name="month" value="'.$subsmonth.'">
                                                          <input type="hidden" name="fullmonth" value="'.$month.'">
                                                          <input type="submit" id="submit_button" style="width:180px;" name="submit" value="Excel Publish">
                                                        </form>
                                                        </div>';
                                }
                    }
       }
       elseif($_GET['subcat'] == 'Monthly Items Collection(Section)')
       {            
                    echo year_month_section_field();
                    
                    if(isset($_POST['submit']))
                    {
                            $year = $_POST['year'];
                            $month = $_POST['month'];
                            $section =  $_POST['section'];
                            $subsmonth = substr($month,0,3);
                            echo ' &nbsp; &nbsp;<b>Year:</b> '.$year.'  <b>Month:</b> '.$month.'  <b>Section:</b> '.$section;
                            $students_id = $db->find_by_sql("student_id","student_on_section","section='$section' ORDER BY  LENGTH(student_id), student_id","");
                            
                            $found = 0;
                            if($students_id !== 'No Result Found')
                            {
                                foreach ($students_id as $students_id_value)
                                {
                                    if($db->find_by_sql("student_id","transaction_archive","year='$year' AND month='$subsmonth' AND student_id='{$students_id_value['student_id']}'","") !== 'No Result Found')
                                    {
                                        echo monthly_items_section_trans_pagination($year,$subsmonth,$month,$students_id,$section);                                        
                                        $found = 1;
                                        break;
                                    }
                                }
                            }
                            
                            if($found == 0)
                            {
                                   echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                          <div id="message" ><b>No Transaction Found</b></div>';
                            }
                                
                    }elseif($_GET['month'] && $_GET['year'] && $_GET['section'])
                    {
                            $year = $_GET['year'];
                            $month = $_GET['month'];
                            $section =  $_GET['section'];
                            $subsmonth = substr($month,0,3);
                            echo ' &nbsp; &nbsp;<b>Year:</b> '.$year.'  <b>Month:</b> '.$month.'  <b>Section:</b> '.$section;
                            $students_id = $db->find_by_sql("student_id","student_on_section","section='$section' ORDER BY  LENGTH(student_id), student_id","");
                            
                            $found = 0;
                            if($students_id !== 'No Result Found')
                            {
                                foreach ($students_id as $students_id_value)
                                {
                                    if($db->find_by_sql("*","transaction_archive","year='$year' AND month='$subsmonth' AND student_id='{$students_id_value['student_id']}'","") !== 'No Result Found')
                                    {
                                        echo monthly_items_section_trans_pagination($year,$subsmonth,$month,$students_id,$section);                                         
                                        $found = 1;
                                        break;
                                    }
                                }
                            }
                            
                            if($found == 0)
                            {
                                   echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                          <div id="message" ><b>No Transaction Found</b></div>';
                            }
                    }
       }
	   else if($_GET['subcat'] == 'Yearly Items Collection(Section)')
	   {
		    
		             echo year_n_section_field();
                    
                    if(isset($_POST['submit']))
                    {
                            $year = $_POST['year'];
                            $month = $_POST['month'];
                            $section =  $_POST['section'];
                            $subsmonth = substr($month,0,3);
                            echo ' &nbsp; &nbsp;<b>Year:</b> '.$year.' &nbsp; <b>Section:</b> '.$section;
                            $students_id = $db->find_by_sql("student_on_section.student_id, student_info.student_name, student_info.class_roll","student_on_section LEFT JOIN student_info ON student_on_section.student_id = student_info.student_id","section='$section' ORDER BY  LENGTH(student_on_section.student_id), student_on_section.student_id","");
                            
							
                            $found = 0;
                            if($students_id !== 'No Result Found')
                            {
                                foreach ($students_id as $students_id_value)
                                {
                                    if($db->find_by_sql("student_id","transaction_archive","year='$year' AND student_id='{$students_id_value['student_id']}'","") !== 'No Result Found' || $db->find_by_sql("student_id","payment_status","year='$year' AND student_id='{$students_id_value['student_id']}'","") !== 'No Result Found') 
                                    {
                                        echo yearly_items_section_trans_pagination($year,$students_id,$section);                                        
                                        $found = 1;
                                        break;
                                    }
                                }
																
                            }
                            
                            if($found == 0)
                            {
                                   echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                          <div id="message" ><b>No Transaction Found</b></div>';
                            }
                                
                    }elseif($_GET['year'] && $_GET['section'])
                    {
                            $year = $_GET['year'];
                            $month = $_GET['month'];
                            $section =  $_GET['section'];
                            $subsmonth = substr($month,0,3);
                            echo ' &nbsp; &nbsp;<b>Year:</b> '.$year.' &nbsp; <b>Section:</b> '.$section;
                            $students_id = $db->find_by_sql("student_on_section.student_id, student_info.student_name, student_info.class_roll","student_on_section LEFT JOIN student_info ON student_on_section.student_id = student_info.student_id","section='$section' ORDER BY  LENGTH(student_on_section.student_id), student_on_section.student_id","");
                            
                            $found = 0;
                            if($students_id !== 'No Result Found')
                            {
                                foreach ($students_id as $students_id_value)
                                {
                                   if($db->find_by_sql("student_id","transaction_archive","year='$year' AND student_id='{$students_id_value['student_id']}'","") !== 'No Result Found' || $db->find_by_sql("student_id","payment_status","year='$year' AND student_id='{$students_id_value['student_id']}'","") !== 'No Result Found')
                                    {
                                        echo yearly_items_section_trans_pagination($year,$students_id,$section);                                        
                                        $found = 1;
                                        break;
                                    }
                                }
                            }
                            
                            if($found == 0)
                            {
                                   echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                          <div id="message" ><b>No Transaction Found</b></div>';
                            }
                    }
		   
	   }
       elseif($_GET['subcat'] == 'Quick Excel Publish(Section)')
	   {   
	           // echo year_month_section_field();
			  echo year_n_section_field();
					
                    if(isset($_POST['submit']))
                    {
                            $year = $_POST['year'];
                            $month = $_POST['month'];
                            $section =  $_POST['section'];
                            $subsmonth = substr($month,0,3);
							
							$month = 'Months'; //Temporary I disable monthly option
							
							if($month == "Months")
							{
								echo ' &nbsp; &nbsp;<b>Year:</b> '.$year.' <b>&nbsp; Section:</b> '.$section.' <b> &nbsp; (Yearly Collection)</b>';
							}else{
								echo ' &nbsp; &nbsp;<b>Year:</b> '.$year.'  <b>&nbsp; Month:</b> '.$month.'  <b>&nbsp; Section:</b> '.$section.'<b> &nbsp; (Monthly Collection)</b>';
							}
							
                            
                            $students_id = $db->find_by_sql("student_id","student_on_section","section='$section' ORDER BY  LENGTH(student_id), student_id","");
                            
                            $found = 0;
                            if($students_id !== 'No Result Found')
                            {
                                foreach ($students_id as $students_id_value)
                                {
									if($month == "Months")
							        { //for year
									        if($db->find_by_sql("student_id","transaction_archive","year='$year' AND student_id='{$students_id_value['student_id']}'","") !== 'No Result Found' || $db->find_by_sql("student_id","payment_status","year='$year' AND student_id='{$students_id_value['student_id']}'","") !== 'No Result Found')
											{
						 
												echo '<div style="margin:50px auto;width: 500px;"></div>
																<div id="message">
																<form action="excel_publish_section_yearly.php" method="post">
																  <input type="hidden" name="year" value="'.$year.'">
																  <input type="hidden" name="section" value="'.$section.'">
																  <input type="submit" id="submit_button" style="width:180px;" name="submit" value="Publish Student">
																</form>
																</div>';
												/*echo '<div style="margin:auto;width: 500px;"></div>
																<div id="message">
																<form action="excel_publish_section_total.php" method="post">
																  <input type="hidden" name="year" value="'.$year.'">
																  <input type="hidden" name="month" value="'.$subsmonth.'">
																  <input type="hidden" name="fullmonth" value="'.$month.'">
																  <input type="hidden" name="section" value="'.$section.'">
																  <input type="submit" id="submit_button" style="width:180px;" name="submit" value="Publish Total">
																</form>
																</div>'; */
												 
												$found = 1;
												break;
											}
									
									}else{
										    if($db->find_by_sql("student_id","transaction_archive","year='$year' AND month='$subsmonth' AND student_id='{$students_id_value['student_id']}'","") !== 'No Result Found'  ||  $db->find_by_sql("student_id","payment_status","year='$year' AND month='$month' AND student_id='{$students_id_value['student_id']}'","") !== 'No Result Found')
											{ // for a single month
						 
												echo '<div style="margin:50px auto;width: 500px;"></div>
																<div id="message">
																<form action="excel_publish_section_student.php" method="post">
																  <input type="hidden" name="year" value="'.$year.'">
																  <input type="hidden" name="month" value="'.$subsmonth.'">
																  <input type="hidden" name="fullmonth" value="'.$month.'">
																  <input type="hidden" name="section" value="'.$section.'">
																  <input type="submit" id="submit_button" style="width:180px;" name="submit" value="Publish Student">
																</form>
																</div>';
												echo '<div style="margin:auto;width: 500px;"></div>
																<div id="message">
																<form action="excel_publish_section_total.php" method="post">
																  <input type="hidden" name="year" value="'.$year.'">
																  <input type="hidden" name="month" value="'.$subsmonth.'">
																  <input type="hidden" name="fullmonth" value="'.$month.'">
																  <input type="hidden" name="section" value="'.$section.'">
																  <input type="submit" id="submit_button" style="width:180px;" name="submit" value="Publish Total">
																</form>
																</div>';
												 
												$found = 1;
												break;
											}
										
									}
                                   
                                }
                            }
                            
                            if($found == 0)
                            {
                                   echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                          <div id="message" ><b>No Transaction Found</b></div>';
                            }
                                
                    }
                            
                            
                    
	   }
	   elseif($_GET['subcat'] == 'Collection of past year')
       {            
                    echo year_n_month_field("");
                    if(isset($_POST['submit']))
                    {
                            $year = $_POST['year'];
                            $month = $_POST['month'];
                            
                            if($db->find_by_sql("*","transaction_notcurrentyear","current_year='$year' AND current_month='$month'","") == 'No Result Found')
                                {
                                     echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                          <div id="message" ><b>No Transaction Found</b></div>';
                                }else{
                                    
                                  echo ' &nbsp; &nbsp;<b>Year:</b> '.$year.'  <b>Month:</b> '.$month;
                                  $students_id = $db->find_by_sql("DISTINCT student_id","transaction_notcurrentyear","current_year='$year' AND current_month='$month' ORDER BY  LENGTH(student_id), student_id","");
                                  $paid_years = $db->find_by_sql("DISTINCT paid_year","transaction_notcurrentyear","current_year='$year' AND current_month='$month' ","");
                                  
                                  echo monthly_past_transaction_pagination($year,$month,$students_id,$paid_years);
                                  echo '<div style="margin:auto;width: 500px;"></div>
                                                        <div id="message">
                                                        <form action="excel_publish_pastYear.php" method="post">
                                                          <input type="hidden" name="year" value="'.$year.'">
                                                          <input type="hidden" name="month" value="'.$month.'">
                                                          <input type="submit" id="submit_button" style="width:180px;" name="submit" value="Excel Publish">
                                                        </form>
                                                        </div>';
                                }
                    }elseif($_GET['month'] && $_GET['year'])
                    {
                            $year = $_GET['year'];
                            $month = $_GET['month'];

                            if($db->find_by_sql("*","transaction_notcurrentyear","current_year='$year' AND current_month='$month'","") == 'No Result Found')
                            {
                                     echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                          <div id="message" ><b>No Transaction Found</b></div>';
                            }else{
                                    
                                  echo ' &nbsp; &nbsp;<b>Year:</b> '.$year.'  <b>Month:</b> '.$month;
                                  $students_id = $db->find_by_sql("DISTINCT student_id","transaction_notcurrentyear","current_year='$year' AND current_month='$month' ORDER BY  LENGTH(student_id), student_id","");
                                  $paid_years = $db->find_by_sql("DISTINCT paid_year","transaction_notcurrentyear","current_year='$year' AND current_month='$month' ","");
                                  
                                  echo monthly_past_transaction_pagination($year,$month,$students_id,$paid_years);
                                  echo '<div style="margin:auto;width: 500px;"></div>
                                                        <div id="message">
                                                        <form action="excel_publish_pastYear.php" method="post">
                                                          <input type="hidden" name="year" value="'.$year.'">
                                                          <input type="hidden" name="month" value="'.$month.'">
                                                          <input type="submit" id="submit_button" style="width:180px;" name="submit" value="Excel Publish">
                                                        </form>
                                                        </div>';
                                }
                    }
       }
   }else{
          echo 'Page not found';
        }     
   ?> 
  </div>
<?php require 'includes/footer.php';   ?>
