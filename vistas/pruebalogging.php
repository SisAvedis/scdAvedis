<?php
require_once("MYSQLUndo.php");

/**
* setup example 1: lets enable logging for table "producto"
*/
$class1 = new MYSQLUndo('localhost', 'dbsistema', 'root', '');
$result = $class1->enableLogging('producto');
if (!$result){ echo $class1->getLastErrorMessage(); }



?>