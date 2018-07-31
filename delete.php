<?php
    
require_once 'dbheader.php';
require_once 'styles.php';


$model = new Model($mysqli);
$view = new View();
$controller = new Controller($view,$model);

$ID=$_GET["ID"];

$result=$controller->RouteCall("Route=DeleteContact&ID=" . $ID);

echo $result;

require_once 'dbfooter.php';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
        
    </body>
</html>
