    <?php
    class RemoteOs{
        private $_platform;
        private $_userAgent = NULL;
     
        public function __construct()
        {
            //set the useragent property
            $this->_userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
     
        public function getBrowserOs()
        {
            $win = preg_match("/win/i", $this->_userAgent);
            $linux = preg_match("/linux/i", $this->_userAgent);
            $mac = preg_match("/mac/i", $this->_userAgent);
            $os2 = preg_match("/OS\/2/i", $this->_userAgent);
            $beos = preg_match("/BeOS/i", $this->_userAgent);
           
            //now do the check as to which matches and return it
            if ($win)
            {
                $this->_platform = "Windows";
            }
            elseif ($linux)
            {
                $this->_platform = "Linux";
            }
            elseif ($mac)
            {
                $this->_platform = "Macintosh";
            }
            elseif ($os2)
            {
                $this->_platform = "OS/2";
            }
            elseif ($beos)
            {
                $this->_platform = "BeOS";
            }
            return $this->_platform;
        }
    }
    //instantiate the class
    $obj = new RemoteOs();
    $os= $obj->getBrowserOs();
	return $os;
	?>