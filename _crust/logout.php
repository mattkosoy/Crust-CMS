<? session_start();

$result = session_unregister("valid_user");
session_destroy();

header("location: index.php?logout=true");

?>