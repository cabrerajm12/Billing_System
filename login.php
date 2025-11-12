<?php
// login.php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $student_id = trim($_POST['student_id']);
  $password = $_POST['password'];

  // prepared statement
  $stmt = $conn->prepare("SELECT id, student_id, password FROM students WHERE student_id = ?");
  $stmt->bind_param("s", $student_id);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res && $res->num_rows === 1) {
    $row = $res->fetch_assoc();
    if (password_verify($password, $row['password'])) {
      // success
      $_SESSION['student_db_id'] = $row['id'];        // internal PK
      $_SESSION['student_id'] = $row['student_id'];   // student ID
      header("Location: portal.php");
      exit;
    } else {
      $error = "Invalid student ID or password.";
    }
  } else {
    $error = "Invalid student ID or password.";
  }
  $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"/><title>Login</title></head>
<body>
  <p><?php echo isset($error) ? htmlspecialchars($error) : "Invalid credentials or please submit the form."; ?></p>
  <p><a href="login.html">Back to login</a></p>
</body>
</html>
