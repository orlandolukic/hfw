<?php 
  session_start();
  $prepath = '';
  $REDIRECT = false;
	include "connect.php";
  include "global.php";

  $ACTIONS  = (object) array("redirect"    => true, 
                             "execute"     => true, 
                             "valid"       => false,	                           
                             "redirection" => (object) array("SQL_error"     => $FILE,
                                                             "GET_error"     => "",
                                                             "urlToPostData" => true,
                                                             "error_params"  => $FILE."user/",
                                                             "success_req"   => $FILE."user/",
                                                             "SQL_exsist"    => $FILE."user/" 
                                                             ));
	include "requests/actions.php";

?>