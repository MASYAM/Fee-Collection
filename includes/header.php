<!DOCTYPE html>
<html>
  <head>
    <title>Micro Accounts</title>
    <link rel="shortcut icon" href="images/Logofinal.ico" />
    <link href="stylesheets/design.css" media="all" rel="stylesheet" type="text/css" />
    <link href="stylesheets/design2.css" media="all" rel="stylesheet" type="text/css" />

    <link href="stylesheets/css.pagination2.css" media="all" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Electrolize' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="javascripts/jquery.min.js"> </script>
    <script type="text/javascript" src="javascripts/macc_javascripts.js"> </script>
     <!-- Adding Calender into form -->
     <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
     <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
     <noscript>
        <p style="margin:auto;color:white; width:350px;height:20px; "><b>This page needs JavaScript activated to work.</b></p> 
        <style>div { display:none; }</style>
     </noscript>
     
     
     
     
  </head>
  <body>
    <div id="header_nav">
        <table style="margin:0px 0px 10px 20px;float: left;" cellpadding="10" >
            <?php  
                $set_header_nav =array('Home'   => 'home.php?category=Home&&subcat=Monthly Fee',
                                      'Journal'  => 'journal.php?category=Journal&&subcat=Daily Transaction',
                                      'Setup'  => 'set.php?category=Setup&&subcat=Setup regular due',
                                      'View'  => 'view.php?category=View&&subcat=Students In Section'
                                      );
                //echo '<td style="padding:0px;"><img src="images/leaf.png" width="30px" height="30px"></td>';
                foreach ($set_header_nav as $key => $value)
                {
                   if($_GET['category'] == $key)
                   {
                       if($_GET['category'] == 'Home')
                       { echo '<td style="padding:7px 10px 0 0;"><span class="selected_header_nav_link"><b>'.$key.'</b></span></td>'; }
                       else{ echo '<td style="padding:7px 10px 0 0;"><a class="selected_header_nav_link" href="'.$value.'"><b>'.$key.'</b></a></td>'; }
                   }else
                       {
                          echo '<td style="padding:7px 10px 0 0;"><a class="header_nav_link" href="'.$value.'">'.$key.'</a></td>';
                       }
                }
            ?>
        </table>
        
        <table style="margin:5px 10px 10px 0px;float: right;" >
            <td><a class="header_nav_link" href="logout.php" onclick="return confirm('Are you sure want to log out?')">Logout(Eng)</a></td>
        </table>
    </div>
	
	 <?php
	   echo '<div style="float:right;margin:5px 10px 0 0;"><b>Username:</b> '.$user115122.'(Eng)</div>';
      ?>
      
  <div id="main">
      
      <div id="main_content_nav">
          
         <?php
            $category = $_GET['category'];

            if($category == 'Home' || $category == 'Journal' || $category == 'Setup' || $category == 'View' )
            {
                $set_main_content_nav=array('Home'  => array( 'Monthly Fee' => 'home.php',
                                                            'Monthly Due Fee' =>'home.php',
                                                            'Other Fee' =>'home.php',
                                                            'Old Student Fee' => 'home.php',
                                                            'Print Paid Receipt' => 'home.php'
                                                           ),
                                           'Journal' => array('Daily Transaction' => 'journal.php',
                                                             'Monthly Transaction' =>'journal.php',
                                                             'Monthly Items Collection' =>'journal.php',
                                                             'Monthly Items Collection(Section)' =>'journal.php',
															 'Yearly Items Collection(Section)' =>'journal.php',
															 'Quick Excel Publish(Section)' =>'journal.php',
                                                             'Collection of past year' =>'journal.php'
                                                             ),
                                           'Setup' => array('Setup regular due' => 'set.php',
                                                            'Edit' => 'set.php'
                                                             ),
                                           'View' => array('Students In Section' => 'view.php',
                                                           'Receipt Collection' => 'view.php'
                                                             ),
                                           'Help'   => array('Insert Name and Id' => 'manage.php',
                                                             'Insert Numbers' =>'manage.php'
                                                            )
                                      ) ;


                foreach ($set_main_content_nav[$category] as $key => $value)
                {

                    if($_GET['subcat'] == $key)
                    {
                        if($_GET['category'] == 'Home')
                        {
                           echo '<div style="float:left;margin:3px 0px 0 4px;"><span class="selected_main_content_link"  >'.$key.'</span></div>';
                        }else{
                           echo '<div style="float:left;margin:3px 0px 0 4px;"><a class="selected_main_content_link"  href="'.$value.'?category='.$category.'&&subcat='.$key.'" >'.$key.'</a></div>';
                        }
                    }else 
                        {
                           echo '<div style="float:left;margin:3px 0px 0 4px;"><a class="main_content_link" href="'.$value.'?category='.$category.'&&subcat='.$key.'" >'.$key.'</a></div>';
                        }

                }
                
               
            }
          ?>

          
      </div>
	 
