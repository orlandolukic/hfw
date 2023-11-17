<?php 

//	Global functions
	function make_date($s, $d, $sep = '.')
	{
		global $lang_acr;
		switch(strtoupper($lang_acr))
		{
		case "SR": case "RU": case "EN": // Print for Serbian users
			return substr($d,8,2).$sep.substr($d,5,2).$sep.substr($d,0,4);
			break;
			/*
		case 1:	// Print for USA users
			return substr($d,5,2).$sep.substr($d,8,2).$sep.substr($d,0,4);
			break;*/
		}		
	}

	function make_date_format($t, $sep = '.')
	{
		global $lang_acr;
		switch(strtoupper($lang_acr))
		{
		case "SR": case "RU": case "EN": // Serbian users
			return date("d".$sep."m".$sep."Y", $t);
			break;/*
		case 1: // Print for USA users
			return date("m".$sep."d".$sep."Y", $t);*/
		}
	}

	function DEC($n)
	{
		$n = intval($n);
		return $n<10 ? "0".$n : $n;
	}

	function get_date_format()
	{
		global $lang_acr, $USER;
		if (isset($USER))
		{
			switch($USER->lang)
			{
			case "SR": case "PL": $t = 0; break;
			case "EN": case "RU": $t = 1; break;
			}
		}		
		return $t;
	}

	function make_time($t)
	{
		return date("H:i", $t);
	}

	function make_image($im, $prepath='', $ext='')
	{
		if ($im == NULL)
		{
			return $prepath."img/user.png";
		} else if ($ext==='')
		{
			return $prepath."img/users/".$im;
		} else
		{
			return $prepath."img/users/".$im.".".$ext;
		}
	}

	function make_image_content($im, $prepath='', $ext='', $sym = "content")
	{
		if ($ext==='')
		{
			return $prepath."img/".$sym."/".$im;
		} else
		{
			return $prepath."img/".$sym."/".$im.".".$ext;
		}
	}

/*	
	Function that returns part of specific date.
	PARAMETERS:   $c {string} - code, tells witch part of date would be returned
				  $d {string} - date, wich will be returned edited
	=============================================================================
	RETURN VALUE:    {string}
*/
	function part_date($c, $d)
	{
		switch($c)
		{
		case 'D': case 'd':
			return substr($d,8,2);
			break;
		case 'M': case 'm':
		return substr($d,5,2);
			break;
		case 'Y': case 'y':
			return substr($d,0,4);
			break;
		}
	}

	function is_leap_year($y)
	{
		return $y%4==0 && $y%100!=0 || $y%400==0;
	}

	function pop_obj($o,$a)
	{
		return $o->$a;
	}

	//	$DAYS = array(array(31,28,31,30,31,30,31,31,30,31,30,31),
	//            array(31,29,31,30,31,30,31,31,30,31,30,31)
	//             );

	function print_duration($fl)
	{
		global $lang;
		switch($fl)
		{
		case 1:
			return pop("oneMonth");
			break;
		case 12:
			return pop("oneYear");
			break;
		case 3:
			return pop("threeMonths");
			break;
		}
	}

	function get_month_days($y)
	{
		$DAYS = array(
		              array(31,28,31,30,31,30,31,31,30,31,30,31),	// Not Leap Year - 365
	                  array(31,29,31,30,31,30,31,31,30,31,30,31)	// Leap Year - 366
	                 );
		return $DAYS[is_leap_year($y)];
	}

	function print_money($mon, $curr=1)
	{
		global $lang_acr;
		switch(strtoupper($lang_acr))
		{
		case "SR": case "PL":
			return number_format($mon,0,',','.').($curr ? " <span class=\"pm-currency\">".print_curr()."</span>" : '');			
			break;
		case "EN": case "RU":
			return number_format($mon,0,'.',',').($curr ? " <span class=\"pm-currency\">".print_curr()."</span>" : '');
			break;
		}
	}

	function print_money_PLAINTXT($mon, $dec=0, $pr_curr=0)
	{
		global $curr, $USER;
		switch(isset($USER) ? $USER->currencyID : $curr)
		{
		case "RSD": case "PLN":
			return number_format($mon,$dec,',','.').($pr_curr ? " <span class=\"pm-currency\">".print_curr()."</span>" : '');			
			break;
		case "EUR": case "RUB": case "USD":
			return number_format($mon,$dec,'.',',').($pr_curr ? " <span class=\"pm-currency\">".print_curr()."</span>" : '');
			break;
		}
	}

	function print_money_out($mon, $curr=1)
	{
		global $lang_acr;
		switch(strtoupper($lang_acr))
		{
		case "SR": case "PL":
			return "<span class=\"pm-value\">".number_format($mon,0,',','.')."</span>".($curr ? " <span class=\"pm-currency\">".print_curr()."</span>" : '');			
			break;
		case "EN": case "RU":
			return "<span class=\"pm-value\">".number_format($mon,0,'.',',')."</span>".($curr ? " <span class=\"pm-currency\">".print_curr()."</span>" : '');
			break;
		}
	}

	function print_curr($st="")
	{
		global $curr;
		switch($st === "" ? $curr : $st)
		{
		case "RSD":
			return "RSD";
			break;
		case "EUR":
			return "<i class=\"fa fa-eur\"></i>";
			break;
		case "USD":
			return "<i class=\"fa fa-usd\"></i>";
			break;
		}
	}

	function return_lang($language,$prepath)
	{
		$MAKE_LANG = false;	
		include_once $prepath."lang/".$language.".php";
		switch ($language) {
			case 'en':
				return (new Language_EN());
				break;
			case 'sr':
				return (new Language_SR());
				break;
		};
	}

	function determine_rating($rating, $img = 0)
	{
		$star_o    = ( $img===0 ? 'fa-star-o' : 'Star_empty.png' );
		$star      = ( $img===0 ? 'fa-star' : 'Star_full.png' );
		$star_half = ( $img===0 ? $star.'-half-o' : 'Star_half.png' );					    
		$arr = array(0  => $star_o, 1  => $star_o, 2  => $star_o, 3  => $star_o, 4  => $star_o);
		$i = 0xF09 ^ 0xF09;					    			
		while($i<=4)
		{
			if ($i<$rating && $rating >= $i+1) $arr[$i] = $star; elseif ($rating==$i) $arr[$i] = $star_o; elseif($rating>$i) $arr[$i] = $star_half;		    
			$i++;
		};		
		return $arr;
	};

	
	function generate_string($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	};

	function checkUser()
	{
		global $USER;
		if (!isset($USER) || $USER === NULL) exit();
	}

	function _SQL_escape_exstring($sql, $len)
	{
        global $CONNECTION;
		if (count($sql)!==2) return NULL;
		$str  = generate_string($len);
		$sql1 = mysqli_query($CONNECTION, $sql[0].$str.$sql[1]);
		if ($sql1 && mysqli_num_rows_wrapper($sql1))
		{
			while(mysqli_num_rows_wrapper($sql1)>0)
			{
				$str  = generate_string($len);
				$sql1 = mysqli_query($CONNECTION, $sql[0].$str.$sql[1]);
			};
		}
		return $str;
	}

	function print_language($a)
	{
		global $lang;
		switch($a)
		{
		case "SR": return $lang->L_serbian;
		case "EN": return $lang->L_english;
		case "RU": return $lang->L_russian;
		case "SP": return $lang->L_spanish;
		}
	};

	function print_currency_name($f)
	{
		global $lang;
		switch($f)
		{
		case "RSD": return $lang->C_RSD;
		case "EUR": return $lang->C_EUR;
		case "PLN": return $lang->C_PLN;
		case "USD": return $lang->C_USD;
		case "RUB": return $lang->C_RUB;
		}
	}

	function logInUser(&$session, $res)
	{
		$session = $res->username;
		$tok = generate_string(20);
		$sql = mysqli_query($CONNECTION, "UPDATE users SET timestamp = '".time()."', access_token = '".$tok."', last_login = CURDATE() WHERE BINARY username = '".$res->username."'");
		$_SESSION['access_token'] = $tok;
	};

	function getUserIP()
	{		
	    $client  = @$_SERVER['HTTP_CLIENT_IP'];
	    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	    $remote  = $_SERVER['REMOTE_ADDR'];

	    if(filter_var($client, FILTER_VALIDATE_IP))
	    {
	        $ip = $client;
	    }
	    elseif(filter_var($forward, FILTER_VALIDATE_IP))
	    {
	        $ip = $forward;
	    }
	    else
	    {
	        $ip = $remote;
	    }
	    return $ip;
	}

    function mysqli_num_rows_wrapper($sql) {
        if (!$sql) {
            return false;
        } else {
            return mysqli_num_rows($sql);
        }
    }

    function mysqli_fetch_object_wrapper($sql) {
        if (!$sql) {
            return NULL;
        } else {
            return mysqli_fetch_object($sql);
        }
    }


?>