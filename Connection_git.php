<?php
/**
 * SeJEJO CodeDeveloper
 *
 * Código de:
 * @author    SeJEJO <sjejo11@gmail.com;@SeJEJO;>
 * @copyright 2015 SeJEJO
 * @license   
 * @version   2015-04-01
 * @link      https://www.twitter.com/SeJEJO
 *
 *   
 *   Copyright (C) 2015  SeJEJO
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
	/**
		FUNCTIONS DATABASES
	*/
	/**
		Function String Drivers
			Var's 
				-> name of database;
				-> port of database;
	*/
	$nameDB = '';
	$user = '';
	$pass = '';
	$port = '';
	// Generate the structure of a driver depends of code that get the function
	function driversConnected($codeDriver){
		$driver = null;
		if($codeDriver=='mysql'){
			if($GLOBALS['port']!='')
				$driver = "mysql:host=localhost;port=".$GLOBALS['port'];	
			else
				$driver = "mysql:host=localhost";
		}
		else if($codeDriver=='sqlite'){
			$driver = "sqlite:".getcwd();
			chmod(getcwd(),0755);
			// /opt/lampp/htdocs/Ejercicios_clase/PHP/2_Trim/
		}
		return $driver;
	}
	// Generate the structure of a name of database for a driver depends of code that get the function
	function nameDatabase($codeDriver){
		$db = null;
		if($codeDriver=='mysql')
			$db = ';dbname='.$GLOBALS['nameDB'];
		else if($codeDriver=='sqlite')
			$db = $GLOBALS['nameDB'];
		return $db;
	}
	// Generate a object PDO for a connection
	function objectPDO ($codeDriver){
		$object = null;
		// Create the structure for a driver
		$driver = driversConnected($codeDriver);
  		// IF nameDB not is empty make the next part of driver structure
  		if($GLOBALS['nameDB']!='')
    		$driver = $driver.nameDatabase($codeDriver);
    	// Create a connection PDO with a corresponding driver
    	try{
    		if($codeDriver=='mysql')
				$object = new PDO($driver,$GLOBALS['user'],$GLOBALS['pass']);
			else if($codeDriver=='sqlite')
				$object = new PDO($driver);
			else if($codeDriver == 'odbc'){
				//$object = new PDO();
				$object = null;
			}
			$object->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			return $object;
    	}
    	catch(PDOException $ePDO_connected){
    		/** If can't connect to database and error is that the database is not exist*/
    		show_Errors($ePDO_connected);
    	}
	}

	/**
		Functions connections
	*/
	// Create the connection with some valors by default if not exist
	function connect($codeDriver='mysql',$dbname='',$user='',$pass=''){
  		if($dbname!='')
  			$GLOBALS['nameDB'] = $dbname;
  		if($user!='')
  			$GLOBALS['user'] = $user;
  		if($pass!='')
  			$GLOBALS['pass'] = $pass;
    	return objectPDO($codeDriver);
	}

	/**
		Functions inserts data
	*/
	// Launches a prepare statement get like param a objet "PDO->prepare(sql)" that 
	function executeStatement($objeto){
	  try{
	    $objeto->execute();
	    showMessage("Sentencia ejecutada");
	    return 1;
	  }catch(PDOException $ePDO_insData){
	    show_Errors($ePDO_insData);
	    return null;
	  }
	}

	/**
		Functions check/show Errors && Show Messages
	*/
	// Show a message get param
	function showMessage($message){
 		echo "<br />\n<span>$message</span>";
	}
	// Get PDOException->code() and post a personal error message
	function checkErrors($error){
		$mensaje = "";
  		if ($error == 1044)
  			// Usuario Incorrecto
  			// Error user
    		$mensaje = "Acceso denegado";
    	else if ($error == 1045)
    		// Password Incorrecto
  			// Error password
    		$mensaje = "Acceso denegado";
  		else if ($error == 1049)
    		$mensaje = "La base de datos no existe";
  		else if($error == 2002)
    		$mensaje = "El SGDB no está arrancado o accesible";
  		else if ($error == 23000)
    		$mensaje = "Entrada duplicada";
		else if ($error == 42000)
    		$mensaje = "La sintaxis SQL no es correcta";
		else if ($error == "42S01")
    		$mensaje = "Tabla ya existente";
  		else if ($error == "42S02")
    		$mensaje = "Tabla o vista no encontrada";
    	else if ($error == "42S22")
    		$mensaje = "Columna no encontrada";
  		else if ($error == "HY000")
    		$mensaje = "No se puede operar sobre el objeto";
    	else if ($error == "HY093")
    		$mensaje = "Numero de parámetros inválidos";
    	return $mensaje;
	}
	// If the code get not is check show the PDOException->code and PDOException->message else show a personal error message
	function show_Errors($ePDO){
		$mensaje = checkErrors($ePDO->getCode());
    	if($mensaje==="")
    		$mensaje = $ePDO->getCode().' - '.$ePDO->getMessage();
  		echo "<br />\n<span style='color:red;'>$mensaje</span>";
  		return null;
	}
\ conexion_git.php
?>
