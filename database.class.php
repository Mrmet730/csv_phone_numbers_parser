<?php

class Database{

private $host = DB_HOST;
private $user = DB_USER;
private $pass = DB_PASS;
private $dbname = DB_NAME;

    
private $dbh;
    private $error;
 
    public function __construct(){
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname.';charset=utf8';
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
        // Create a new PDO instance
        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
        // Catch any errors
        catch(PDOException $e){
            $this->error = $e->getMessage();
        }
    }
      
    
public function query($query){ 
    $this->stmt = $this->dbh->prepare($query);
}


public function bind($param, $value, $type = null){
    if (is_null($type)) {
        switch (true) {
            case is_int($value):
                $type = PDO::PARAM_INT;
                break;
            case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;
            case is_null($value):
                $type = PDO::PARAM_NULL;
                break;
            default:
                $type = PDO::PARAM_STR;
        }
    }
    $this->stmt->bindValue($param, $value, $type);
}



public function execute(){
	
	try{
    	return $this->stmt->execute();
	}
	
	
	/* Catturiamo eventuali errori */
	catch(PDOException $e){
		 
		$this->error = $e->getMessage();
	
		// [!] DEBUG : disattiva quando il sito andra' in produzione per non far visualizzare lo schema database !
		echo "<br /><b>[Mysql] : si &egrave verificato un errore mysql -> <p style=\"color: red;\">'$this->error' </p></b> ! <br /><br />";
		echo "<b>Query Sql pre-statement</b> :";
		echo "<p style=\"color: #0000FF;\">";
		var_dump( $this->stmt );
		echo "</p><br />";
	
	
		/** SCRITTURA DEL LOG ERRORE MYSQL O EVENTUALE ATTACCO **/
		$fileLog = "log/attacchi_sql.txt";
		 
		$erroreSql = $e->getMessage();
		 
		setlocale(LC_ALL, 'it_IT');
		 
		/* usiamo la codifica utf-8 per le accentate */
		$dataLog = utf8_encode( strftime("%A - %e %B %Y - %H:%M:%S") );
		 
		$ipMittente 		= $_SERVER['REMOTE_ADDR'];
		$userAgentMittente 	= $_SERVER['HTTP_USER_AGENT'];
		 
		$nomePagina			= $_SERVER['PHP_SELF'];
		 
		$richiestaGet		= $_SERVER["QUERY_STRING"];
		$richiestaGetDecode	= urldecode( $richiestaGet );
		$richiestaGetDecode	= html_entity_decode( $richiestaGetDecode );
		 
		$richiestaPost		= file_get_contents("php://input");
		 
		$messaggioErrore  	 = 	"Data : $dataLog\r\n";
		$messaggioErrore 	.= 	"IP : $ipMittente\r\n";
		$messaggioErrore 	.=	"User Agent : $userAgentMittente \r\n";
		$messaggioErrore 	.= 	"Richiesta GET \"$nomePagina.$richiestaGetDecode\"\r\n";
		$messaggioErrore 	.= 	"Richiesta POST \"$richiestaPost\"\r\n";
		$messaggioErrore 	.= 	"\r\n";
		$messaggioErrore 	.= 	"Errore sql : $erroreSql \r\n";
		$messaggioErrore 	.= 	"\r\n\r\n\r\n";
		 
		/* se esiste il file di log scrivo */
		if ( file_exists( $fileLog ) ){
			file_put_contents($fileLog, $messaggioErrore, FILE_APPEND | LOCK_EX);
		}
		 
		/** FINE LOG **/

	}
	
}

public function resultset(){
	
	try{
		
    	$this->execute();
    	return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    	
	}
	
/* Capturing errors */
	catch(PDOException $e){
		 
		$this->error = $e->getMessage();
	
		$fileLog = "log/attacchi_sql.txt";
		 
		$erroreSql = $e->getMessage();
		 
		setlocale(LC_ALL, 'it_IT');
		 
		/* Using UTF-8 encoding */
		$dataLog = utf8_encode( strftime("%A - %e %B %Y - %H:%M:%S") );
		 
		$ipMittente 		= $_SERVER['REMOTE_ADDR'];
		$userAgentMittente 	= $_SERVER['HTTP_USER_AGENT'];
		 
		$nomePagina			= $_SERVER['PHP_SELF'];
		 
		$richiestaGet		= $_SERVER["QUERY_STRING"];
		$richiestaGetDecode	= urldecode( $richiestaGet );
		$richiestaGetDecode	= html_entity_decode( $richiestaGetDecode );
		 
		$richiestaPost		= file_get_contents("php://input");
		 
		$messaggioErrore  	 = 	"Data : $dataLog\r\n";
		$messaggioErrore 	.= 	"IP : $ipMittente\r\n";
		$messaggioErrore 	.=	"User Agent : $userAgentMittente \r\n";
		$messaggioErrore 	.= 	"Richiesta GET \"$nomePagina.$richiestaGetDecode\"\r\n";
		$messaggioErrore 	.= 	"Richiesta POST \"$richiestaPost\"\r\n";
		$messaggioErrore 	.= 	"\r\n";
		$messaggioErrore 	.= 	"Errore sql : $erroreSql \r\n";
		$messaggioErrore 	.= 	"\r\n\r\n\r\n";
		 
		/* WRITE INTO LOG'S FILE */
		if ( file_exists( $fileLog ) ){
			file_put_contents($fileLog, $messaggioErrore, FILE_APPEND | LOCK_EX);
		}
		 
		/** END LOG **/

	}
	
	
}

public function single(){
	
	try{
		
    	$this->execute();
    	return $this->stmt->fetch(PDO::FETCH_ASSOC);
    
	}
	
	/* Capturing errors */
	catch(PDOException $e){
			
		$this->error = $e->getMessage();

		$fileLog = "log/attacchi_sql.txt";
			
		$erroreSql = $e->getMessage();
			
		setlocale(LC_ALL, 'it_IT');
			
		$dataLog = utf8_encode( strftime("%A - %e %B %Y - %H:%M:%S") );
			
		$ipMittente 		= $_SERVER['REMOTE_ADDR'];
		$userAgentMittente 	= $_SERVER['HTTP_USER_AGENT'];
			
		$nomePagina			= $_SERVER['PHP_SELF'];
			
		$richiestaGet		= $_SERVER["QUERY_STRING"];
		$richiestaGetDecode	= urldecode( $richiestaGet );
		$richiestaGetDecode	= html_entity_decode( $richiestaGetDecode );
			
		$richiestaPost		= file_get_contents("php://input");
			
		$messaggioErrore  	 = 	"Data : $dataLog\r\n";
		$messaggioErrore 	.= 	"IP : $ipMittente\r\n";
		$messaggioErrore 	.=	"User Agent : $userAgentMittente \r\n";
		$messaggioErrore 	.= 	"Richiesta GET \"$nomePagina.$richiestaGetDecode\"\r\n";
		$messaggioErrore 	.= 	"Richiesta POST \"$richiestaPost\"\r\n";
		$messaggioErrore 	.= 	"\r\n";
		$messaggioErrore 	.= 	"Errore sql : $erroreSql \r\n";
		$messaggioErrore 	.= 	"\r\n\r\n\r\n";
			
		if ( file_exists( $fileLog ) ){
			file_put_contents($fileLog, $messaggioErrore, FILE_APPEND | LOCK_EX);
		}
			
	
	}
	
	
	
}


public function rowCount(){
    return $this->stmt->rowCount();
}


public function lastInsertId(){
    return $this->dbh->lastInsertId();
}

public function beginTransaction(){
    return $this->dbh->beginTransaction();
}


public function endTransaction(){
    return $this->dbh->commit();
}

public function cancelTransaction(){
    return $this->dbh->rollBack();
}


public function debugDumpParams(){
    return $this->stmt->debugDumpParams();
}
    

} 






?>