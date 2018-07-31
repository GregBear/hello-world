<?php
require_once 'dbheader.php'; 



$Mode =$_GET["Mode"];
//if (isset($Mode))
//{
    
  echo "Mode=" . $Mode;

if ($Mode=='Submit')
{
 $ContactID=$_POST["ContactID"];
 $FirstName=$_POST["FirstName"];
 $LastName=$_POST["LastName"];
 $Mobile=$_POST["Mobile"];
 $Home=$_POST["Home"];
 $Office=$_POST["Office"];
 $Other=$_POST["Other"];


 $QueryStringExt='&ID=' . $ContactID. '&FirstName='. $FirstName . '&LastName='. $LastName . '&Mobile='. $Mobile . '&Home='. $Home . '&Office='. $Office. '&Other='. $Other;

 $passed=TRUE;
 
 //Server Side validation


 if(strlen($FirstName)==0 || strlen($LastName)==0 || (strlen($Mobile)==0 && strlen($Home)==0 && strlen($Office)==0 && strlen($Other)==0))
 {
     
     header('Location: '. './Contacts1.php?Mode=ReDo&Reason=One the Fields is Required Fields is Zero Length...' . $QueryStringExt);
 $passed=FALSE;
 }


  if(is_numeric($FirstName) || is_numeric($LastName))
 {
     
     header('Location: '. './Contacts1.php?Mode=ReDo&Reason=Names cannot be Numeric...' . $QueryStringExt);
$passed=FALSE;
 }

 if((strlen($Mobile)!=0 && !is_numeric($Mobile)) || (strlen($Home)!=0 && !is_numeric($Home)) || (strlen($Office)!=0 && !is_numeric($Office)) || (strlen($Other)!=0 && !is_numeric($Other)))
 {
     
  header('Location: '. './Contacts1.php?Mode=ReDo&Reason=Numbers must be Numeric...' . $QueryStringExt);
  $passed=FALSE;
 }else{
     
 }



if ($passed==TRUE)
{
$model = new Model($mysqli);
$view = new View();
$controller = new Controller($view,$model);

if ($_POST["ContactID"]> 0)
{$result=$controller->RouteCall("Route=UpdateContact" . $QueryStringExt);}
else
{
$result=$controller->RouteCall("Route=InsertContact" . $QueryStringExt);
}
header('Location: '. './?Message='.$result);
}
 





//echo $result;


 }

 if ($Mode =='ReDo')
    {

 //echo "Mode=" . $Mode;
 ?>   
<script type="text/javascript" language="javascript">
var UpdateFormFlag=true;
</script>
<?PHP    
    }


require_once 'dbfooter.php'; 
require_once 'styles.php';

?>

<!DOCTYPE html>
<html lang="en">
    <head>

         

        <meta charset="utf-8" />
        <title>Contact</title>
    <script type="text/javascript" language="javascript">




        $(document).ready(function ()
        {
           // alert("Ready");

           /*
            * If the Server Side validation fails or if we are updating the form
            * we insert the values into the HTML input elements
            */

            if (UpdateFormFlag == true)
            {
                var qs = getQueryStrings();

                var Reason = qs["Reason"];

                var ContactID=qs["ID"];

                var FN = qs["FirstName"];
                var LN = qs["LastName"];
                var Mobile = qs["Mobile"];
                var Home = qs["Home"];
                var Office = qs["Office"];
                var Other = qs["Other"];

                $("#FirstName")[0].value = FN;
                $("#LastName")[0].value = LN;
                $("#Mobile")[0].value = Mobile;
                $("#Home")[0].value = Home;
                $("#Office")[0].value = Office;
                $("#Other")[0].value = Other;

                if (Reason == "Update...")
                {
                   // alert("ContactID: " + ContactID);
                    $("#ContactID")[0].value=ContactID;
                } else
                {
                    alert(Reason);
                }
                 

            }

        });

        function Get_(ID)
        {
            return document.getElementById(ID);
        }

        /*
         * Gets the query string from the URL
         */ 


        function getQueryStrings()
        {
            var assoc = {};
            var decode = function (s) { return decodeURIComponent(s.replace(/\+/g, " ")); };
            var queryString = location.search.substring(1);
            var keyValues = queryString.split('&');

            for (var i in keyValues)
            {
                var key = keyValues[i].split('=');
                if (key.length > 1)
                {
                    assoc[decode(key[0])] = decode(key[1]);
                }
            }

            return assoc;
        }

        function ValidateFields()
        {
            // alert("Validating");
            // return true; // Uncomment to let Server Validation have a go


            var fn = $("#FirstName")[0];

            if (fn.value.length == 0)
            {
                alert("Failed: FirstName is Empty...");
                return false;

            }

            var ln = $("#LastName")[0];

            if (ln.value.length == 0)
            {
                alert("Failed: LastName is Empty...");
                return false;

            }



            var Mobile = $("#Mobile")[0];
            var Home = $("#Home")[0];
            var Office = $("#Office")[0];
            var Other = $("#Other")[0];


            if (Mobile.value.length == 0 && Home.value.length == 0 && Office.value.length == 0 && Other.value.length == 0)
            {
                alert("Failed: You need at leat one number....");
                return false;
            }


            alert("Form Passed...");
            return true;

        }

    </script>

    </head>
    <body>

    <div id="main">
        <div id="content">

            <h2> Update Contact </h2>

            <form action="./Contacts1.php?Mode=Submit" method="post" id="aligned" onsubmit="return ValidateFields()">
             
                <input type="hidden" name="ContactID" id="ContactID" />
                <table>
                
                <tr>
                    <td><label for="FirstName">First Name:</label></td>
                    <td><input type="text" name="FirstName" id="FirstName" autofocus></td>
                 </tr>
                 <tr>
                <td><label for="LastName">Last Name:</label></td>
                <td><input type="text" name="LastName" id="LastName"></td>
                </tr>
                <tr>
                <td><label for="Mobile">Mobile:</label></td>
                <td><input type="text" name="Mobile" id="Mobile"></td>
                 
                 <td><label for="Home">Home:</label></td>
                <td><input type="text" name="Home" id="Home"></td>

                    <td><label for="Office">Office:</label></td>
                <td><input type="text" name="Office" id="Office"></td>

                     <td><label for="Other">Other:</label></td>
                <td><input type="text" name="Other" id="Other"></td>

                </tr>

                </table>
                <p>
        
                <input type="submit" class="btn_OK" value="Update Contact" >
            </form>
        </div>
    </div>
        
    </body>
</html>
