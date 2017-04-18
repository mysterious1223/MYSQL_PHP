<?php

class MYSQL_control
{
    private  $dbhost ='';        // Host
    private $dbname = '';        // DataBase Name
    private $dbuser = '';        // DataBase UserName
    private $dbpass = '';        // DataBase Password
    private $appname = '';       // Site or app name

    private $connection;

    private $myhash = ''; // Encryption Hash for user pass 
    //private  $myurlhash =  ''; // Encryption Hash for URL (helps prevent injection attacks)
    
    
    // Constructor
    function __construct()
    {
        
        $this->connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass,  $this->dbname);
        $GLOBALS['connect'] = $connection;

        if($connection->connect_error) die($connection ->connect_error);
         
    }
    // Create table
    function createTable($name, $query)
    {
        queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");

        echo "Table '$name' created or already exists.<br>";


    }
    // execute query
    // returns a $result
    function queryMysql($query)
    {
        
        $result = $this->connection->query($query);
        if(!result) die($this->$connection->error);
        return $result;

    }
    // Kill SQL session connection
    function destroySession()
    {
        $_SESSION = array();

        if(session_id() != "" || isset($_COOKIE[session_name()]))
            setcookie (session_name (), '', time()-2592000, '/');

        session_destroy();
    }
    // sanatize user input to preven injection
    function sanitizerString($var)
    {
        //global $connection;
        $var = strip_tags($var);
        $var = htmlentities($var);
        $var = stripcslashes($var);
        return $this->connection->real_escape_string($var);

    }

    // check user plaintext pass against our created hash. You must hash password then store in data base
    function verify_password_hash($strPassword, $strHash)
    {
        if (function_exists('password_verify')) {
            // php >= 5.5
            $boolReturn = password_verify($strPassword, $strHash);
        } else {
            $strHash2 = crypt($strPassword, $strHash);
            $boolReturn = $strHash == $strHash2;
        }
        return $boolReturn;
    }
    
    
    // Safely get our password hash
    function getHash()
    {
        
        return $this->myhash;
    }
    
    
    //////////////////////////////////////////////////////////////////////////////////////////////
    
    
   
 
    // Encrypt our url -> id=1 Will be converted into a hashed pass. We then decrypt it and use it
      function encrypt_url($string) 
      {
          $key = "MAL_979805"; //key to encrypt and decrypts.
          $result = '';
          $test = "";
           for($i=0; $i<strlen($string); $i++) 
           {
             $char = substr($string, $i, 1);
             $keychar = substr($key, ($i % strlen($key))-1, 1);
             $char = chr(ord($char)+ord($keychar));

             $test[$char]= ord($char)+ord($keychar);
             $result.=$char;
           }

           return urlencode(base64_encode($result));
        }
        function decrypt_url($string) 
        {
                $key = "MAL_979805"; //key to encrypt and decrypts.
                $result = '';
                $string = base64_decode(urldecode($string));
               for($i=0; $i<strlen($string); $i++) 
               {
                 $char = substr($string, $i, 1);
                 $keychar = substr($key, ($i % strlen($key))-1, 1);
                 $char = chr(ord($char)-ord($keychar));
                 $result.=$char;
               }
               return $result;
        }


}


?>

