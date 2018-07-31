<?php


$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "phone";


$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname); // 



if (!$mysqli) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
} else {
    // echo 'Success... ' . $mysqli->host_info . "\n";   
}



class Model
{
    private $JSONstring;
    private $QueryString;
    public $sql;
    public $res;
    public $ErrorList;
    
    //public Contact $Contacts[];
    private $mysqliconn;
    
    
    
    public function __construct($mysqlo)
    {
        
        
        $this->mysqliconn = $mysqlo; // 
        
        $this->JSONstring = "";
        
        
    }
    public function UpdateContact($CallString)
    {
        $CallerArray = $this->DecodeCallString($CallString);
        $ID          = $CallerArray["ID"];
        $FirstName   = $CallerArray["FirstName"];
        $LastName    = $CallerArray["LastName"];
        $Mobile      = $CallerArray["Mobile"];
        $Home        = $CallerArray["Home"];
        $Office      = $CallerArray["Office"];
        $Other       = $CallerArray["Other"];
        
        $ReturnString = "";
        
        /*
         * Use Prepared Statements to prevent SQL Injection on Update
         */
        
        $sql = "UPDATE contactnames SET FirstName=? , LastName=?  WHERE ID=?";
        // $result = $this->mysqliconn->query($sql);
        if ($stmt = $this->mysqliconn->prepare($sql)) {
            
            /* bind parameters for markers */
            $stmt->bind_param("ssi", $FirstName, $LastName, $ID);
            
            /* execute query */
            $stmt->execute();
            
            
            $stmt->close();
        }
        
        
        $sql = "SELECT * FROM contactnumbers WHERE ID_Names=" . $ID;
        
        $result1 = $this->mysqliconn->query($sql);
        
        if (!$result1) {
            
            printf('Database call failed: %s,%s', $this->mysqliconn->error, $sql);
            exit();
        }
        
        
        while ($row = $result1->fetch_row()) {
            switch ($row[3]) {
                
                case "MOBILE":
                    $Num_ID = $row[0];
                    
                    
                    $sql = "UPDATE contactnumbers SET Number=? WHERE ID=?";
                    
                    
                    if ($stmt = $this->mysqliconn->prepare($sql)) {
                        
                        
                        $stmt->bind_param("si", $Mobile, $Num_ID);
                        
                        /* execute query */
                        $stmt->execute();
                        
                        
                        $stmt->close();
                    }
                    
                    
                    
                    
                    
                    
                    break;
                
                case "HOME":
                    
                    $Num_ID = $row[0];
                    
                    
                    $sql = "UPDATE contactnumbers SET Number=? WHERE ID=?";
                    
                    
                    if ($stmt = $this->mysqliconn->prepare($sql)) {
                        
                        
                        $stmt->bind_param("si", $Home, $Num_ID);
                        
                        /* execute query */
                        $stmt->execute();
                        
                        
                        $stmt->close();
                    }
                    
                    break;
                
                case "OFFICE":
                    
                    $Num_ID = $row[0];
                    
                    
                    $sql = "UPDATE contactnumbers SET Number=? WHERE ID=?";
                    
                    
                    if ($stmt = $this->mysqliconn->prepare($sql)) {
                        
                        $stmt->bind_param("si", $Office, $Num_ID);
                        
                        /* execute query */
                        $stmt->execute();
                        
                        
                        $stmt->close();
                    }
                    
                    break;
                
                case "OTHER":
                    
                    
                    $Num_ID = $row[0];
                    
                    
                    $sql = "UPDATE contactnumbers SET Number=? WHERE ID=?";
                    
                    
                    if ($stmt = $this->mysqliconn->prepare($sql)) {
                        
                        $stmt->bind_param("si", $Other, $Num_ID);
                        
                        /* execute query */
                        $stmt->execute();
                        
                        
                        $stmt->close();
                    }
                    
                    
                    break;
                
                default:
                    
                    break;
                    
                    
                    
            }
            
            
        }
        
        
        return $ReturnString;
        
    }
    
    public function RetrieveContact($CallString)
    {
        
        $CallerArray = $this->DecodeCallString($CallString);
        
        $ID = $CallerArray["ID"];
        
        $sql = "SELECT * FROM contactnames WHERE ID=" . $ID;
        
        $result = $this->mysqliconn->query($sql);
        
        if (!$result) {
            
            printf('Database call failed: %s,%s', $this->mysqliconn->error, $sql);
            exit();
        }
        
        while ($row = $result->fetch_row()) {
            
            $this->QueryString = "&ID=" . $row[0] . "&FirstName=" . $row[1] . "&LastName=" . $row[2];
            
        }
        
        $sql = "SELECT * FROM contactnumbers WHERE ID_Names=" . $ID;
        
        $result1 = $this->mysqliconn->query($sql);
        
        if (!$result1) {
            
            printf('Database call failed: %s,%s', $this->mysqliconn->error, $sql);
            exit();
        }
        
        
        while ($row = $result1->fetch_row()) {
            switch ($row[3]) {
                
                case "MOBILE":
                    $this->QueryString .= "&Mobile=" . $row[2];
                    break;
                
                case "HOME":
                    $this->QueryString .= "&Home=" . $row[2];
                    break;
                
                case "OFFICE":
                    $this->QueryString .= "&Office=" . $row[2];
                    break;
                
                case "MOBILE":
                    $this->QueryString .= "&Other=" . $row[2];
                    break;
                
                default:
                    $this->QueryString .= "&Other=" . $row[2];
                    break;
                    
                    
                    
            }
            
            
        }
        
        
        return $this->QueryString;
        
        
    }
    
    public function DeleteContact($CallString)
    {
        $CallerArray = $this->DecodeCallString($CallString);
        
        $ID = $CallerArray["ID"];
        
        $sql = "DELETE FROM contactnames WHERE ID=" . $ID;
        
        $result = $this->mysqliconn->query($sql);
        
        $ReturnString = "";
        if ($result == true) {
            
            // Cascade Delete;
            $ReturnString .= "Name Successfully Deleted from the Database...";
            
            $sql = "DELETE FROM contactnumbers WHERE ID_Names=" . $ID;
            
            $result = $this->mysqliconn->query($sql);
            
            $ReturnString = "";
            if ($result == true) {
                
                
                $ReturnString .= "Related Numbers Successfully Deleted from the Database...";
            }
            
        } else {
            
            echo "SQL was:" . $sql;
            
            print_r($this->mysqliconn->error);
            return "Error(s) occured while deleting contact: " . $this->mysqliconn->error;
        }
        
        return $ReturnString;
        
    }
    
    
    public function InsertContact($CallString)
    {
        $CallerArray = $this->DecodeCallString($CallString);
        $FirstName   = $CallerArray["FirstName"];
        $LastName    = $CallerArray["LastName"];
        $Mobile      = $CallerArray["Mobile"];
        $Home        = $CallerArray["Home"];
        $Office      = $CallerArray["Office"];
        $Other       = $CallerArray["Other"];
        
        $sql = "INSERT INTO contactnames (FirstName, LastName) VALUES(?,?)";
        
        
        
        
        
        if ($stmt = $this->mysqliconn->prepare($sql)) {
            
            
            $stmt->bind_param("ss", $FirstName, $LastName);
            
            /* execute query */
            $result = $stmt->execute();
            if ($result == TRUE) {
                
            } else {
                return "Error(s) occured while inserting numbers: " . $this->mysqliconn->error . "...SQL was: " . $sql;
            }
            
            $last_id = $this->mysqliconn->insert_id;
            
            $stmt->close();
        }
        
        
        
        $sql = "INSERT INTO contactnumbers (ID_names, Number, Type) VALUES(?,?,'" . "MOBILE" . "')";
        
        if ($stmt = $this->mysqliconn->prepare($sql)) {
            
            $stmt->bind_param("is", $last_id, $Mobile);
            
            /* execute query */
            $result = $stmt->execute();
            if ($result == TRUE) {
                
            } else {
                return "Error(s) occured while inserting numbers: " . $this->mysqliconn->error . "...SQL was: " . $sql;
            }
            
            $stmt->close();
        }
        
        
        $sql = "INSERT INTO contactnumbers (ID_names, Number, Type) VALUES(?,?,'" . "HOME" . "')";
        
        if ($stmt = $this->mysqliconn->prepare($sql)) {
            
            $stmt->bind_param("is", $last_id, $Home);
            
            /* execute query */
            $result = $stmt->execute();
            if ($result == TRUE) {
                
            } else {
                return "Error(s) occured while inserting numbers: " . $this->mysqliconn->error . "...SQL was: " . $sql;
            }
            
            $stmt->close();
        }
        
        $sql = "INSERT INTO contactnumbers (ID_names, Number, Type) VALUES(?,?,'" . "OFFICE" . "')";
        
        if ($stmt = $this->mysqliconn->prepare($sql)) {
            
            $stmt->bind_param("is", $last_id, $Office);
            
            /* execute query */
            $result = $stmt->execute();
            if ($result == TRUE) {
                
            } else {
                return "Error(s) occured while inserting numbers: " . $this->mysqliconn->error . "...SQL was: " . $sql;
            }
            
            $stmt->close();
        }
        
        $sql = "INSERT INTO contactnumbers (ID_names, Number, Type) VALUES(?,?,'" . "OTHER" . "')";
        
        if ($stmt = $this->mysqliconn->prepare($sql)) {
            
            $stmt->bind_param("is", $last_id, $Other);
            
            /* execute query */
            $result = $stmt->execute();
            
            if ($result == TRUE) {
                
            } else {
                return "Error(s) occured while inserting numbers: " . $this->mysqliconn->error . "...SQL was: " . $sql;
            }
            
            $stmt->close();
        }
        
        
    }
    
    public function GetContacts()
    {
        $sql = "SELECT * FROM contactnames ORDER BY LastName";
        
        
        
        
        $result = $this->mysqliconn->query($sql);
        
        
        
        
        if (!$result) {
            
            printf('Database call failed: %s,%s', $this->mysqliconn->error, $sql);
            exit();
        }
        
        
        
        echo 'Number of Rows:=> ';
        $totalRows = $result->num_rows;
        
        echo $totalRows . "...";
        
        /*
         * Custom JSON Generator
         */
        
        $this->JSONstring .= "[";
        while ($row = $result->fetch_row()) {
            
            $this->JSONstring .= '{"ID": "' . $row[0] . '", "FirstName" : "' . $row[1] . '", "LastName" : "' . $row[2] . '"';
            
            $this->JSONstring .= ',"Numbers" : ';
            $sql = "SELECT * FROM contactnumbers WHERE ID_Names=" . $row[0];
            
            $result1 = $this->mysqliconn->query($sql);
            if (!$result1) {
                
                printf('Database call failed: %s,%s', $this->mysqliconn->error, $sql);
                exit();
            }
            
            $this->JSONstring .= "[";
            while ($row = $result1->fetch_row()) {
                
                $this->JSONstring .= '{"ID" : "' . $row[0] . '", "ID_Names" : "' . $row[1] . '", "Number" : "' . $row[2] . '", "Type" : "' . $row[3] . '" },';
                
            }
            $this->JSONstring = rtrim($this->JSONstring, ",");
            
            $this->JSONstring .= "]";
            $this->JSONstring .= '},';
        }
        
        $this->JSONstring = rtrim($this->JSONstring, ",");
        
        $this->JSONstring .= "]";
        
        
        /* free result set */
        $result->close();
        return $this->JSONstring;
        
    }
    
    /*
     * This function enables an infinite number of parameters to be sent
     * through one variable
     */

    function DecodeCallString($String)
    {
        $assoc = array();
        
        $keyValues = explode('&', $String);
        
        for ($i = 0; $i < count($keyValues, COUNT_NORMAL); $i++) {
            $key = explode('=', $keyValues[$i]);
            if (count($key, COUNT_NORMAL) >= 1) {
                $assoc[$key[0]] = $key[1];
                
            }
        }
        
        return $assoc;
    }
}

class View
{
    public function Output($What, $Data)
    {
        switch ($What) {
            case "DisplayContacts":
                
                $JSONObj = json_decode($Data, true);
                
                // echo $Data;
                
                
                //  print_r($JSONObj );                     
                
                $myform = "";
                
                if (count($JSONObj, COUNT_NORMAL) == 0) {
                    $myform = "No Records To Display.";
                } else {
                    
                    
                    for ($i = 0; $i < count($JSONObj, COUNT_NORMAL); $i++) {
                        $myform .= "<table>";
                        
                        $myform .= "<tr>";
                        $myform .= "<td class='lbl_ID'>";
                        $myform .= "<label for='ID'  >" . $JSONObj[$i]['ID'] . "</label>";
                        $myform .= "</td>";
                        $myform .= "<td class='lbl_general'>";
                        $myform .= "<label for='FirstName'>" . $JSONObj[$i]['FirstName'] . "</label>";
                        $myform .= "</td>";
                        $myform .= "<td class='lbl_general'>";
                        $myform .= "<label for='LastName'>" . $JSONObj[$i]['LastName'] . "</label>";
                        $myform .= "</td>";
                        $myform .= "<td>";
                        $myform .= "<input type='button' class ='btn_Enabled' value='Edit' onclick='DoUpd(" . $JSONObj[$i]["ID"] . ")'/>";
                        $myform .= "<input type='button' class ='btn_Cancel' value='Delete' onclick='DoDel(" . $JSONObj[$i]["ID"] . ")'/>";
                        $myform .= "</td>";
                        $myform .= "</tr>";
                        $myform .= "</table>";
                        
                        $myform .= "<table>";
                        
                        $myform .= "<tr>";
                        for ($j = 0; $j < count($JSONObj[$i]['Numbers'], COUNT_NORMAL); $j++) {
                            $myform .= "<td>";
                            
                            if (strlen($JSONObj[$i]['Numbers'][$j]["Number"]) > 0) {
                                $myform .= "<label for='Numbers'>" . $JSONObj[$i]['Numbers'][$j]["Number"] . "</label>";
                                $myform .= "</td>";
                                $myform .= "<td>";
                                $myform .= "<label for='Numbers'>(" . $JSONObj[$i]['Numbers'][$j]["Type"] . ")</label>";
                                $myform .= "</td>";
                            }
                            
                            
                        }
                        $myform .= "</tr>";
                        $myform .= "</table>";
                        
                        
                        
                        $myform .= "</tr>";
                        
                    }
                    $myform .= "</table>";
                    
                }
                
                $myform .= "<p> <input type='button' class ='btn_OK' value='Add Record' onclick='DoAdd()'/>";
                
                
                break;
                
                
                
        }
        
        
        
        
        
        return $myform;
    }
    
    
}


class Controller
{
    private $Model;
    private $View;
    
    public function RouteCall($CallString)
    {
        $CallerArray = $this->DecodeCallString($CallString);
        
        $SwitchParam = $CallerArray["Route"];
        
        switch ($SwitchParam) {
            case "DisplayContacts":
                $ModelData = $this->Model->GetContacts();
                return $this->View->Output($SwitchParam, $ModelData);
                break;
            
            case "InsertContact":
                
                return $this->Model->InsertContact($CallString);
                
                break;
            
            case "DeleteContact":
                
                return $this->Model->DeleteContact($CallString);
                
                break;
            
            case "RetrieveContact":
                
                return $this->Model->RetrieveContact($CallString);
                
                break;
            
            case "UpdateContact":
                
                return $this->Model->UpdateContact($CallString);
                
                break;
            
            
            default:
                
                return "Error: Unhandled Call to Controller";
                
                break;
                
                
        }
        
        
        
    }
    
    function DecodeCallString($String)
    {
        $assoc = array();
        //$decode = function (s) { return decodeURIComponent(s.replace(/\+/g, " ")); };
        
        $keyValues = explode('&', $String);
        
        for ($i = 0; $i < count($keyValues, COUNT_NORMAL); $i++) {
            $key = explode('=', $keyValues[$i]);
            if (count($key, COUNT_NORMAL) >= 1) {
                $assoc[$key[0]] = $key[1];
                
            }
        }
        
        return $assoc;
    }
    
    public function __construct($view, $model)
    {
        $this->View  = $view;
        $this->Model = $model;
    }
}






class ContactNumber
{
    private $model;
    private $ContactNumber;
    
    public function __construct($ContactNumber)
    {
        $this->ContactNumber = $ContactNumber;
    }
}



class ContactName
{
    private $ID = 0;
    private $FirstName;
    private $LastName;
    private $Contacts;
    
    public function __construct($FirstName, $LastName)
    {
        $this->FirstName = $FirstName;
        $this->LastName  = $LastName;
    }
}







function edit_submit_index()
{
    $action = $_POST['submit'];
    if ($action == 'submit') {
        echo '$action';
        $arg  = $_POST['id'];
        $data = array(
            'id' => null,
            'LastName' => $_POST['LastName'],
            'FirstName' => $_POST['FirstName']
        );
    }
}


//$this->model->submit_index($data);


?>

<script src="js/jquery-3.3.1.js"></script>