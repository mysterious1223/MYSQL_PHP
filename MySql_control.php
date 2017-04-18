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
    private  $myurlhash =  ''; // Encryption Hash for URL (helps prevent injection attacks)
    
    
    // Constructor
    function __construct()
    {
        
        $this->connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass,  $this->dbname);
        $GLOBALS['connect'] = $connection;

        if($connection->connect_error) die($connection ->connect_error);
         
    }

    function createTable($name, $query)
    {
        queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");

        echo "Table '$name' created or already exists.<br>";


    }

    function queryMysql($query)
    {
        
        $result = $this->connection->query($query);
        if(!result) die($this->$connection->error);
        return $result;

    }
    function destroySession()
    {
        $_SESSION = array();

        if(session_id() != "" || isset($_COOKIE[session_name()]))
            setcookie (session_name (), '', time()-2592000, '/');

        session_destroy();
    }

    function sanitizerString($var)
    {
        //global $connection;
        $var = strip_tags($var);
        $var = htmlentities($var);
        $var = stripcslashes($var);
        return $this->connection->real_escape_string($var);

    }


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
    function getHash()
    {
        
        return $this->myhash;
    }
    
    
    //////////////////////////////////////////////////////////////////////////////////////////////
    
     function verify_url_hash($strPassword, $strHash)
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
      function geturlHash()
    {
        
        return $this->myurlhash;
    }
 
    
  function encrypt_url($string) {
  $key = "MAL_979805"; //key to encrypt and decrypts.
  $result = '';
  $test = "";
   for($i=0; $i<strlen($string); $i++) {
     $char = substr($string, $i, 1);
     $keychar = substr($key, ($i % strlen($key))-1, 1);
     $char = chr(ord($char)+ord($keychar));

     $test[$char]= ord($char)+ord($keychar);
     $result.=$char;
   }

   return urlencode(base64_encode($result));
}
function decrypt_url($string) {
    $key = "MAL_979805"; //key to encrypt and decrypts.
    $result = '';
    $string = base64_decode(urldecode($string));
   for($i=0; $i<strlen($string); $i++) {
     $char = substr($string, $i, 1);
     $keychar = substr($key, ($i % strlen($key))-1, 1);
     $char = chr(ord($char)-ord($keychar));
     $result.=$char;
   }
   return $result;
}

function getClientName($ClientID)
{
    
    /// check if client ID exist if not return 0
    
    
    $result = $this->queryMysql("SELECT * FROM Client where ClientID=$ClientID");
    
    if($result->num_rows == 1)
    {
        $row = $result->fetch_assoc();
        
        $temp = $row['Name'];
        return $temp;
        
        
    }
    else
    {
        return 0;
    }
    
    
    
}

}


?>

