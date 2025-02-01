<?php
$output = shell_exec("python3 script.py");
echo $output;
?>
<?php
$handle = popen("python3 script.py", "r");
while (!feof($handle)) {
    echo fgets($handle);
}
pclose($handle);
?>
<?php
$response = file_get_contents("http://localhost:5000/data");
$data = json_decode($response, true);
echo $data['message'];
?>
<!-- $conn = new mysqli("localhost", "user", "password", "database"); -->
<?php
$conn = new mysqli("localhost", "root", "", "test");
$conn->query("INSERT INTO my_table (data) VALUES ('Hello')");
$conn->close();
?>
