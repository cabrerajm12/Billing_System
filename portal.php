<?php
// portal.php
session_start();
if (!isset($_SESSION['student_db_id'])) {
  header("Location: login.html");
  exit;
}

include 'db_connect.php';

$student_db_id = $_SESSION['student_db_id'];

// fetch student details
$stmt = $conn->prepare("SELECT student_id, name, course, year_level, email, contact_number, total_tuition, amount_paid FROM students WHERE id = ?");
$stmt->bind_param("i", $student_db_id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows !== 1) {
  // invalid session, redirect to login
  session_destroy();
  header("Location: login.html");
  exit;
}
$student = $res->fetch_assoc();
$stmt->close();

// compute remaining balance
$total = floatval($student['total_tuition']);
$paid = floatval($student['amount_paid']);
$remaining = max(0, $total - $paid);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Student Portal</title>
  <style>
    body{font-family:Segoe UI,Arial,sans-serif;background:#f5f7fb;margin:0}
    header{background:#1f4b91;color:#fff;padding:18px 28px;display:flex;justify-content:space-between;align-items:center}
    .logout { background:#fff;color:#1f4b91;padding:8px 14px;border-radius:6px;border:none;cursor:pointer; }
    .container { max-width:950px;margin:40px auto;padding:0 16px; }
    .grid { display:flex; gap:20px; flex-wrap:wrap; }
    .card { background:#fff;border-radius:10px;padding:20px;box-shadow:0 6px 20px rgba(0,0,0,0.06); flex:1; min-width:300px }
    .card h4{ color:#1f4b91; margin-top:0; margin-bottom:12px; display:inline-block; border-bottom:2px solid #1f4b91; padding-bottom:6px;}
    table{ width:100%; border-collapse:collapse}
    td{ padding:8px 0 }
    .label{ color:#1f4b91; font-weight:700; width:45%}
    .value{ text-align:right; color:#333 }
    .big { font-size:18px; font-weight:700; color:#1f4b91 }
    .remaining { color:#d9534f; font-weight:700 }
  </style>
</head>
<body>
  <header>
    <div>Student Portal</div>
    <div>
      <form style="display:inline" method="post" action="logout.php">
        <button class="logout" type="submit">Logout</button>
      </form>
    </div>
  </header>

  <div class="container">
    <h2 style="color:#1f4b91">Hello, <?php echo htmlspecialchars($student['name']); ?>!</h2>
    <p style="color:#6c757d">This is your dashboard. Below are your details and tuition summary.</p>

    <div class="grid">
      <div class="card">
        <h4>Student Details</h4>
        <table>
          <tr><td class="label">Student ID:</td><td class="value"><?php echo htmlspecialchars($student['student_id']); ?></td></tr>
          <tr><td class="label">Name:</td><td class="value"><?php echo htmlspecialchars($student['name']); ?></td></tr>
          <tr><td class="label">Course:</td><td class="value"><?php echo htmlspecialchars($student['course']); ?></td></tr>
          <tr><td class="label">Year Level:</td><td class="value"><?php echo htmlspecialchars($student['year_level']); ?></td></tr>
          <tr><td class="label">Email:</td><td class="value"><?php echo htmlspecialchars($student['email']); ?></td></tr>
          <tr><td class="label">Contact:</td><td class="value"><?php echo htmlspecialchars($student['contact_number']); ?></td></tr>
        </table>
      </div>

      <div class="card">
        <h4>Tuition Summary</h4>
        <table>
          <tr><td class="label">Total Tuition:</td><td class="value">₱ <?php echo number_format($total,2); ?></td></tr>
          <tr><td class="label">Amount Paid:</td><td class="value">₱ <?php echo number_format($paid,2); ?></td></tr>
          <tr><td class="label">Remaining Balance:</td><td class="value remaining">₱ <?php echo number_format($remaining,2); ?></td></tr>
        </table>
        <div style="margin-top:12px;text-align:right">
          <span class="big">Status: <?php echo ($remaining <= 0) ? "<span style='color:green'>Paid</span>" : "<span style='color:orange'>Partially Paid</span>"; ?></span>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
