<?php 

	class Pages
	{
		public function __construct($a="")
		{
			$this->ext = $a;	
			return $this;					
		}		

		public function addSufix()
		{
			$i = 0;
			foreach ($this as $key => $value) {
				if ($i>0) $this->$key .= $this->ext;	
				$i++;
	        }			
			return $this;		
		}

		private $ext;
		public  $about      = "about",
			    $home       = "index",
			    $workshop   = "workshop",
			    $workshops  = "workshops",	
				$register	= "register",
				$login		= "login",
				$contact	= "contact",
				$gallery    = "gallery",
				$news       = "news";
	}
	$PAGES = (new Pages())->addSufix();


?>