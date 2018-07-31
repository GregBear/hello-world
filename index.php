
<html>
    <head>
</head>
 <body>
<?php

require_once 'dbheader.php';
require_once 'styles.php';


$model = new Model($mysqli);
$view = new View();
$controller = new Controller($view,$model);

$HTML=$controller->RouteCall("Route=DisplayContacts");


echo $HTML;

require_once 'dbfooter.php'; 

?>
<div id="result"></div>
 </body>


   
<script type="text/javascript" language="javascript">
    function Get_(ID)
    {
        return document.getElementById(ID);
    }

    function DoDel(ID)
    {
        //  alert("ID=" + ID);
        var DeleteResult;


        $.ajax({ url: "delete.php?ID=" + ID, async: false, success: function (result)
        {
            DeleteResult = result;

            $("#deleteresult").html(result);

            window.location = "index.php";

            //Get_("deleteresult").DeleteResult = result;

        }
        });


       
       /* if (DeleteResult == "Record Successfully Deleted from the Database...")
        {

            alert("Success:" + $("#deleteresult").html());
            window.location = "index.php";

        }
        else
        {

            alert("Failure:" + $("#deleteresult").html())
        }*/



    }
    function DoAdd()
    {
        window.location = 'Contacts1.php?Mode=Add';

    }
    function DoUpd(ID)
    {
        //alert("ID=" + ID)
        window.location = 'UpdContacts1.php?Mode=Upd&ID=' + ID;


    }

    function Decode(result, name)
    {
        var nameEQ = name + "=";
        var ca = result.split('&');
        for (var i = 0; i < ca.length; i++)
        {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }





</script>
   




</html>