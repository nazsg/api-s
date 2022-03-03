<?php
session_start();
// if(isset($_SERVER['HTTP_ORIGIN'])){
//     require('../allowed.php');
//     if (in_array($_SERVER['HTTP_ORIGIN'], $allowed_domains))
//     {  
//         header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
//     }
//     header("Access-Control-Allow-Headers: 'Origin, Content-Type, Accept'");
// }
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');
header("Access-Control-Allow-Headers: 'Origin, X-Requested-With, Content-Type, Accept'");

require_once './inc/db_conn.php';
$_POST = json_decode(file_get_contents('php://input'), true);

if(isset($_GET['id']))
{
    $id = $_GET['id'];
    $smt = $db->query("SELECT * FROM prices WHERE id = $id");
    $r = $smt->fetchAll(PDO::FETCH_ASSOC);
    $myJSON = json_encode($r);
    echo $myJSON;
}

else if(isset($_POST['editHours'])) 
{
    if(isset($_SESSION['loggedIn']))
    {
        $hour_id = $_POST['hour_id']; 
        $start = $_POST['start'] ;
        $end = $_POST['end'] ;
        
        $smt = $db->prepare("UPDATE opening_hours SET 
            start = :start, end = :end
            WHERE hour_id = :hour_id ");
    
        $smt->bindParam(':hour_id', $hour_id);
        $smt->bindParam(':start', $start);
        $smt->bindParam(':end', $end);
    
        results('edited Hours');
    }
}
else if(isset($_POST['editPrice'])) 
{
    if(isset($_SESSION['loggedIn']))
    {
        $id = $_POST['id']; 
        $price = $_POST['price'] ;
        
        $smt = $db->prepare("UPDATE prices SET 
            price = :price
            WHERE id = :id ");
    
        $smt->bindParam(':id', $id);
        $smt->bindParam(':price', $price);
    
        results('edited Price');
    }
}

else if(isset($_POST['editItem'])) 
{
    if(isset($_SESSION['loggedIn']))
    {

        $id = $_POST['id']; 
        $item = $_POST['item'];
        
        $smt = $db->prepare("UPDATE prices SET 
        item = :item
        WHERE id = :id ");

        $smt->bindParam(':id', $id);
        $smt->bindParam(':item', $item);
    }    

    results('edited Item');
}

else if(isset($_POST['addItem']))
{
    if(isset($_SESSION['loggedIn']))
    {
        $item = $_POST['item'];
        $price = $_POST['price'];
        
        // echo "$item $price";
        $smt = $db->prepare("INSERT INTO prices (item, price) VALUES
            (:item, :price) ");
    
        $smt->bindParam(':item', $item);
        $smt->bindParam(':price', $price);

        results('added Item');
    }
}

else if(isset($_POST['loginToOskars']))
{
    $user = $_POST['username']; $pass = $_POST['password'];
    $smt = $db->query("SELECT username FROM users WHERE username = '$user' AND password = SHA1('$pass') ");
    $r = $smt->fetch(PDO::FETCH_ASSOC); 
    //fetchAll will return an array of records if it's just one record, fetch will return just one record
    
    if($smt->rowCount() > 0) {
        $myJSON = json_encode($r);
        // echo $myJSON;
        $_SESSION['loggedIn'] = true;
        echo 'success';
        // echo $_SESSION['loggedIn'];
    } else {
        echo 'user not found';
    }

}

else if(isset($_GET['logout'])) {
    session_destroy();
}

else if(isset($_GET['check_status'])) {
    if(isset($_SESSION['loggedIn'])) {
        echo "loggedin";
    } else {
        echo 'not logged in';
    }
}
// echo print_r( $_SESSION );
// echo var_dump( $_SESSION );

else
{
    $smt = $db->query("SELECT * FROM opening_hours");
    $r = $smt->fetchAll(PDO::FETCH_ASSOC);
    $myJSON = json_encode($r);
    echo $myJSON;
}

function results($i)
{
    global $smt;
    if($smt->execute()) 
	{
        echo 'success : ' . $i;
	}
	else 
	{
        echo 'error : ' . $i;    
	}      
}