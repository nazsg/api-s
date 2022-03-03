<?php
// echo $_SERVER['REMOTE_ADDR'];
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');
Header("Access-Control-Allow-Headers: 'Origin, X-Requested-With, Content-Type, Accept'");
require_once './inc/db_conn.php';

// $_POST = json_decode(file_get_contents('php://input'), true);

if(isset($_GET['menu'])) {
  if($_GET['menu'] == 'main') {
    $condition = "'starters', 'soups', 'chicken_duck', 'pork', 'beef', 'seafood', 'curry', 'salad', 'vegetables', 'rice', 'noodles'";
  }
  
  elseif ($_GET['menu'] == 'set_and_lunch') {
    $condition = " 'lunch_menus', 'choices', 'set_menu' ";
  }
  
  elseif ($_GET['menu'] == 'drinks') {
    $condition = " 'aperitifs', 'brandy', 'ports', 'liqueurs', 'spirits', 'lager', 'soft-drinks', 'water', 'wine' ";
  }

  elseif ($_GET['menu'] == 'all') {
    $smt = $db->query("SELECT * from all_menus");
    echo json_encode($smt->fetchAll(PDO::FETCH_ASSOC));
    exit();

  } else { exit(); }

  $smt = $db->query("select * from all_menus WHERE category IN ($condition) ");  
  $myJSON = json_encode($smt->fetchAll(PDO::FETCH_ASSOC));
  echo $myJSON;
}

?>