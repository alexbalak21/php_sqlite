<?php

function connect_sqlite($db_file_name = "database.sqlite")
{
    $conn = null;
    try {
        $conn = new PDO("sqlite:$db_file_name");
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        log_error($e->getMessage());
        return null;
    }
    return $conn;
}

function execute_querry(string $qry)
{
    global $conn;
    if ($conn == null) {
        return false;
    }
    try {
        $conn->exec($qry);
        $msg = "EXECUETD QRY : " . $qry . "\n";
        return true;
    } catch (PDOException $e) {
        log_error($qry  . $e->getMessage());
        return false;
        log_error($e->getMessage());
    }
}

function select_all(string $qry = "SELECT * FROM profiles")
{
    global $conn;
    if ($conn == null) {
        return false;
    }
    $stmt = $conn->query($qry);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

function log_error($error = "Error")
{
    $logfile = fopen("./logs/errors.log", "w") or die("Unable to open file!");
    $new_log = date("d/m/Y  H:i:s") . " - " . $error . "\n \n";
    fwrite($logfile, $new_log);
    fclose($logfile);
    return null;
}


function log_evevnt($msg = "")
{
    $event_log = fopen("./logs/events.log", "a") or die("Unable to open file!");
    $new_log = date("d/m/Y  H:i:s") . " - " . $msg . "\n";
    fwrite($event_log, $new_log);
    fclose($event_log);
    return null;
}


function add_user($firstname = "John", $lastname = "Doe", $username = "JohnDoe", $email = "john.doe@mail.com", $password = "Azerty123*", $admin = 0)
{
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    $querry = "INSERT INTO users (first_name, last_name, username, email, pass_hash, is_admin) VALUES ('$firstname', '$lastname', '$username', '$email', '$pass_hash', '$admin')";
    $result = execute_querry($qry = $querry);
    if ($result) {
        log_evevnt("USER ADDED :  $firstname $lastname");
        return true;
    } else {
        log_error("FAILED QUERRY : $querry");
        return false;
    }
}



//PROFILES
//CREATE
function add_profile($user_id, $first_name, $last_name, $tech, $experiance, $location, $description, $img_src, $active)
{
    $querry = "INSERT INTO profiles (created_by_id, first_name, last_name, tech, experiance, location, description, img_src, active) VALUES ('$user_id', '$first_name', '$last_name', '$tech', '$experiance', '$location', '$description', '$img_src', '$active')";
    $result = execute_querry($qry = $querry);
    if ($result) {
        log_evevnt("PROFILE ADDED :  $first_name $last_name BY USER $user_id");
        return true;
    } else {
        log_error("FAILED QUERRY : $querry");
        return false;
    }
}






global $conn;
$conn = connect_sqlite("./db/database.sqlite");

$create_users = file_get_contents("./queries/users.sql");
$create_profiles = file_get_contents("./queries/profiles.sql");
execute_querry($create_users);
execute_querry($create_profiles);

add_user("Alexandre", "Balakirev", "alexbalak", "alex.balak@outloo.com", "Azerty123+", 1);
add_profile(1, "prenom", "nom", "java", "3 ans", "Lyon", "Descript...", "img location", 1);
$result = select_all();

foreach ($result as $profile) {
    print_r($profile);
    echo "<br>";
}



$conn = null;
