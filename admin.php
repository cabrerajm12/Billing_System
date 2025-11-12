<?php
// admin.php
include 'db_connect.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // sanitize / trim
  $student_id = trim($_POST['student_id']);
  $name = trim($_POST['name']);
  $course = trim($_POST['course']);
  $year_level = trim($_POST['year_level']);
  $email = trim($_POST['email']);
  $contact_number = trim($_POST['contact_number']);
  $total_tuition = floatval($_POST['total_tuition']);
  $amount_paid = floatval($_POST['amount_paid']);
  $password_plain = $_POST['password'];

  if (!$student_id || !$name || !$course || !$year_level || !$email || !$password_plain) {
    $errors[] = "Please fill required fields (Student ID, Name, Course, Year, Email, Password).";
  } else {
    // hash password
    $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO students (student_id, password, name, course, year_level, email, contact_number, total_tuition, amount_paid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssdd", $student_id, $password_hashed, $name, $course, $year_level, $email, $contact_number, $total_tuition, $amount_paid);

    if ($stmt->execute()) {
      $success = "Student added successfully.";
    } else {
      $errors[] = "Error adding student: " . $stmt->error;
    }
    $stmt->close();
  }
}

// Fetch all students
$rows = [];
$res = $conn->query("SELECT id, student_id, name, course, year_level, email, contact_number, total_tuition, amount_paid FROM students ORDER BY id DESC");
if ($res) {
  while ($r = $res->fetch_assoc()) $rows[] = $r;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Admin - Add Student</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f5f7fb; margin:0; padding:20px; }
    .container { max-width:1000px; margin:20px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 6px 20px rgba(0,0,0,0.06); }
    form { display:grid; grid-template-columns: repeat(2, 1fr); gap:12px; }
    input, select { padding:10px; border-radius:6px; border:1px solid #ccc; }
    label { font-weight:600; color:#1f4b91; margin-bottom:4px; display:block; }
    .full { grid-column: span 2; }
    .btn { background:#1f4b91; color:#fff; padding:12px; border:none; border-radius:6px; cursor:pointer; }
    .errors { color:#d9534f; margin-bottom:10px; }
    .success { color:green; margin-bottom:10px; }
    table { width:100%; border-collapse:collapse; margin-top:18px; }
    th { background:#1f4b91; color:#fff; padding:8px; text-align:left; }
    td { padding:8px; border-bottom:1px solid #eee; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Admin — Add Student</h2>

    <?php if ($errors): ?>
      <div class="errors"><?php echo implode("<br>", $errors); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <div>
        <label for="student_id">Student ID *</label>
        <input id="student_id" name="student_id" type="text" required>
      </div>
      <div>
        <label for="name">Full Name *</label>
        <input id="name" name="name" type="text" required>
      </div>

      <div>
        <label for="course">Course *</label>
        <input id="course" name="course" type="text" required>
      </div>
      <div>
        <label for="year_level">Year Level *</label>
        <input id="year_level" name="year_level" type="text" required>
      </div>

      <div>
        <label for="email">Email *</label>
        <input id="email" name="email" type="email" required>
      </div>
      <div>
        <label for="contact_number">Contact Number</label>
        <input id="contact_number" name="contact_number" type="text">
      </div>

      <div>
        <label for="total_tuition">Total Tuition (₱)</label>
        <input id="total_tuition" name="total_tuition" type="number" step="0.01" value="0.00">
      </div>
      <div>
        <label for="amount_paid">Amount Paid (₱)</label>
        <input id="amount_paid" name="amount_paid" type="number" step="0.01" value="0.00">
      </div>

      <div class="full">
        <label for="password">Password *</label>
        <input id="password" name="password" type="password" required>
      </div>

      <div class="full">
        <button class="btn" type="submit">Add Student</button>
      </div>
    </form>

    <h3>Students List</h3>
    <table>
      <thead>
        <tr>
          <th>#</th><th>Student ID</th><th>Name</th><th>Course</th><th>Year</th><th>Email</th><th>Contact</th><th>Total</th><th>Paid</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($rows) === 0): ?>
          <tr><td colspan="9">No students yet.</td></tr>
        <?php else: ?>
          <?php foreach ($rows as $r): ?>
          <tr>
            <td><?php echo htmlspecialchars($r['id']); ?></td>
            <td><?php echo htmlspecialchars($r['student_id']); ?></td>
            <td><?php echo htmlspecialchars($r['name']); ?></td>
            <td><?php echo htmlspecialchars($r['course']); ?></td>
            <td><?php echo htmlspecialchars($r['year_level']); ?></td>
            <td><?php echo htmlspecialchars($r['email']); ?></td>
            <td><?php echo htmlspecialchars($r['contact_number']); ?></td>
            <td>₱ <?php echo number_format($r['total_tuition'],2); ?></td>
            <td>₱ <?php echo number_format($r['amount_paid'],2); ?></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
