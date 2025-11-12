<?php
include 'db_connect.php';

// Handle form submission
if (isset($_POST['submit'])) {
  $student_id = $_POST['student_id'];
  $name = $_POST['name'];
  $course = $_POST['course'];
  $year_level = $_POST['year_level'];
  $email = $_POST['email'];
  $contact_number = $_POST['contact_number'];

  $sql = "INSERT INTO students (student_id, name, course, year_level, email, contact_number)
          VALUES ('$student_id', '$name', '$course', '$year_level', '$email', '$contact_number')";

  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Student added successfully!');</script>";
  } else {
    echo "Error: " . $conn->error;
  }
}

// Fetch all students
$result = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Add Students</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f7fb;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #1f4b91;
      color: white;
      padding: 15px 30px;
    }

    h2 {
      margin: 0;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 30px;
    }

    input[type="text"], input[type="email"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      width: 100%;
    }

    input[type="submit"] {
      grid-column: span 2;
      padding: 12px;
      background-color: #1f4b91;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
    }

    input[type="submit"]:hover {
      background-color: #163b73;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #1f4b91;
      color: white;
    }
  </style>
</head>
<body>
  <header>
    <h2>Admin Panel - Add Student</h2>
  </header>

  <div class="container">
    <form method="POST" action="">
      <input type="text" name="student_id" placeholder="Student ID" required>
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="text" name="course" placeholder="Course" required>
      <input type="text" name="year_level" placeholder="Year Level" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="contact_number" placeholder="Contact Number">
      <input type="submit" name="submit" value="Add Student">
    </form>

    <h3>Student List</h3>
    <table>
      <tr>
        <th>ID</th>
        <th>Student ID</th>
        <th>Name</th>
        <th>Course</th>
        <th>Year</th>
        <th>Email</th>
        <th>Contact</th>
      </tr>
      <?php
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['id']}</td>
                  <td>{$row['student_id']}</td>
                  <td>{$row['name']}</td>
                  <td>{$row['course']}</td>
                  <td>{$row['year_level']}</td>
                  <td>{$row['email']}</td>
                  <td>{$row['contact_number']}</td>
                </tr>";
        }
      } else {
        echo "<tr><td colspan='7'>No students added yet</td></tr>";
      }
      ?>
    </table>
  </div>
</body>
</html>
