
<html>
    <head>
</head>
 <body>
<?php

require_once 'dbheader.php';



$model = new Model($mysqli);
$view = new View();
$controller = new Controller($view,$model);

$QueryString=$controller->RouteCall("Route=RetrieveContact&ID=" . $_GET["ID"]);

require_once 'dbfooter.php'; 

//echo $QueryString;
 header('Location: '. './Contacts1.php?Mode=ReDo&Reason=Update...' . $QueryString);


?>
<div id="result"></div>
 </body>


   
<script type="text/javascript" language="javascript">
    
</script>
   
</html>