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
        log_evevnt($msg);
        return true;
    } catch (PDOException $e) {
        log_error($qry  . $e->getMessage());
        return false;
        log_error($e->getMessage());
    }
}

function log_error($error = "Error")
{
    $logfile = fopen("./logs/errors.log", "w") or die("Unable to open file!");
    $new_log = date("d/m/Y  H:i:s") . " - " . $error . "\n";
    fwrite($logfile, $new_log);
    fclose($logfile);
    return null;
}


function log_evevnt($msg = "")
{
    $event_log = fopen("./logs/events.log", "w") or die("Unable to open file!");
    $new_log = date("d/m/Y  H:i:s") . " - " . $msg . "\n";
    fwrite($event_log, $new_log);
    fclose($event_log);
    return null;
}


function add_user($firstname = "John", $lastname = "Doe", $username = "JohnDoe", $email = "john.doe@mail.com", $password = "Azerty123*", $admin = 0)
{
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    $querry = "INSERT INTO users (first_name, last_name, username, email, pass_hash, is_admin) VALUES ('$firstname', '$lastname', '$username', '$email', '$pass_hash', '$admin')";
    return execute_querry($qry = $querry);
}




global $conn;
$conn = connect_sqlite("./db/database.sqlite");

$create_users = file_get_contents("./queries/users.sql");
$create_profiles = file_get_contents("./queries/profiles.sql");
execute_querry($create_users);
// execute_querry($create_profiles);

add_user("Alexandre", "Balakirev", "alexbalak", "alex.balak@outloo.com", "Azerty123+", 1);


$conn = null;
