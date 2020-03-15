<?php
class Document {
        
        private $base;
	private $title;
        private $h1;
        private $h1_prefix;
        private $error_message;
	private $description;
	private $keywords;	
        private $canonical = false;
        private $robots = false;
	private $links = array();		
	private $styles = array();
	private $scripts = array();
        private $breadcrumbs = array();
	private $defaults = array();
        
        private $booShowMenu = true;       
       
        private $includes_url       = "includes/";
        private $javascripts_url    = "js/";
        private $stylesheet_url     = "css/";
        
        private $strTheAPIAddress   = "";
        
        private $global_stylesheet  = "/includes/css/style.css";
        
        private $bootstrap_location = "bootstrap-3.1.1-dist/";
        private $jquery_location    = "1.11.1/";

        private $layouts            = array();
        
        function __construct()
        {
            
            $this->CI =& get_instance();
            $this->setDefaults();
        }
        
        public function getShowMenu()
        {
            return $this->booShowMenu;
        }
        
        public function doHideMenu()
        {
            $this->booShowMenu = false;
        }
        
        public function doShowMenu()
        {
            $this->booShowMenu = true;
        }
        
        public function getScriptsArray() {
		return $this->scripts;
	}
        
        public function getStylesheetArray()
        {
                return $this->styles;
        }
        
        public function getImagesFolderString()
        {
            return $this->getBaseUrlString().$this->includes_url."images/";
        }
        
        public function getFaviconIncludeString()
        {
            return $this->getImagesFolderString()."favicon.ico";
        }


        public function getStylesheetIncludeUrlString()
        {
                return $this->getBaseUrlString().$this->includes_url.$this->stylesheet_url;
        }
        
        public function getGlobalStyleUrl()
        {
                return $this->global_stylesheet;
        }
        
        public function addStyleToStyleArray($style, $string = false) {
		$this->styles[md5($style)] = array( 
                        'src' => $style,
                        'string' => $string
                );
	}
        
        public function addScriptToScriptArray($script, $string = false, $external =false) {
		$this->scripts[md5($script)] = array( 
                        'src' => $script,
                        'string' => $string,
                        'external' => $external
                );
	}
        
        public function getJqueryScriptIncludeString()
        {
            return $this->getBaseUrlString().$this->includes_url."jquery/".$this->jquery_location."jquery.min.js";
        }
        
        public function getBootstrapScriptIncludeString()
        {
            return $this->getBaseUrlString().$this->includes_url."bootstrap/".$this->bootstrap_location."js/bootstrap.min.js";
        }
        
        public function getBootstrapPluginScriptIncludeString($strPlugin)
        {
            return $this->getBaseUrlString().$this->includes_url."bootstrap/".$strPlugin."/js/".$strPlugin.".min.js";
        }
        
        public function getBootstrapStyleIncludeString()
        {
            return $this->getBaseUrlString().$this->includes_url."bootstrap/".$this->bootstrap_location."css/bootstrap.min.css";
        }
        
        public function getBootstrapPluginStyleIncludeString($strPlugin)
        {
            return $this->getBaseUrlString().$this->includes_url."bootstrap/".$strPlugin."/css/".$strPlugin.".min.css";
        }
        
        public function getJavascriptIncludeUrlString()
        {
            return $this->getBaseUrlString().$this->includes_url.$this->javascripts_url;
        }
        
        public function setDefaults()
        {
            $CI =& get_instance();
            $CI->load->config('document', TRUE);
            $this->setH1Prefix($CI->config->item('prepend_to_h1','document'));
            $this->setBaseUrlString($CI->config->item('base_url'));
            $this->setTitleString("SHI - AsthmaGC");
            
            $CI->load->config('api', TRUE);
            $this->strTheAPIAddress .= $CI->config->item('api_endpoint','api');
        }
        
        public function setH1Prefix($strPrefix)
        {
            $this->h1_prefix = $strPrefix;
        }
        
        public function setBaseUrlString($base) 
        {
		$this->base = $base;
	}
	
	public function getBaseUrlString() 
        {
		return $this->base;
	}
        
        public function setTitleString($title) {
		$this->title = $title;
	}
	
	public function getTitleString() {
		return $this->title;
	}
        
        public function setH1String($title) {
            
		$this->h1 = $this->h1_prefix.$title;
	}
	
	public function getH1String() {
            
		return $this->h1;
	}
        
        public function setErrorMessage($message) {
		$this->error_message = $message;
	}
        
        public function getErrorMessage() {
		return $this->error_message;
	}
        
        
        public function getAPIUserObjectFromCookie()
        {
            $CI =& get_instance();
            return unserialize($CI->input->cookie('agc_APIUser'));
        }
        
        public function getAPIPreviousUserObjectFromCookie()
        {
            $CI =& get_instance();
            return unserialize($CI->input->cookie('agc_APIPreviousUser'));
        }
        
        public function getAPIPatientObjectFromCookie()
        {
            $CI =& get_instance();
            return unserialize($CI->input->cookie('agc_APIPatient'));
        }
        
        public function getAPIUrl()
        {
            return $this->strTheAPIAddress;
        }
}