<?php
require_once __DIR__ . '/../middleware/file_guard.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/notifications.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id            = $_SESSION['user_id'];
  $category           = $_POST['category'] ?? '';
  $requesting_student = trim($_POST['requesting_student'] ?? '');
  $section            = $_POST['section'] ?? '';
  $request_date       = $_POST['request_date'] ?? '';
  $request_time       = $_POST['request_time'] ?? '';
  $purpose            = trim($_POST['purpose'] ?? '');
  $instructor         = $_POST['instructor'] ?? '';
  $class_adviser      = $_POST['class_adviser'] ?? '';
  $technology_head    = $_POST['technology_head'] ?? '';
  $note               = $_POST['note'] ?? null;

  // Server-side validation — required fields must not be empty.
  // This mirrors the `required` attributes in the form, but the browser
  // check alone can be bypassed, so this is the real enforcement.
  $errors = [];

  if ($category === '')           $errors[] = 'Category is required.';
  if ($requesting_student === '') $errors[] = 'Requesting student is required.';
  if ($section === '')            $errors[] = 'Section is required.';
  if ($request_date === '')       $errors[] = 'Request date is required.';
  if ($request_time === '')       $errors[] = 'Request time is required.';
  if ($purpose === '')            $errors[] = 'Purpose is required.';
  if ($instructor === '')         $errors[] = 'Instructor is required.';
  if ($class_adviser === '')      $errors[] = 'Class adviser is required.';
  if ($technology_head === '')    $errors[] = 'Technology head is required.';

  // Validate that each selected approver exists and has the expected role.
  foreach ([
    'instructor' => [$instructor, 'instructor'],
    'class adviser' => [$class_adviser, 'adviser'],
    'technology head' => [$technology_head, 'technology_head'],
  ] as $label => [$approverId, $expectedRole]) {
    if (!ctype_digit((string) $approverId) || (int) $approverId < 1) {
      $errors[] = ucfirst($label) . ' is invalid.';
      continue;
    }
    $approverStmt = $conn->prepare('SELECT id FROM users WHERE id = ? AND role = ?');
    $approverStmt->bind_param('is', $approverId, $expectedRole);
    $approverStmt->execute();
    $validApprover = $approverStmt->get_result()->num_rows === 1;
    $approverStmt->close();
    if (!$validApprover) $errors[] = ucfirst($label) . ' is invalid.';
  }

  if (!empty($errors)) {
    $_SESSION['slipError'] = implode(' ', $errors);
    header("Location: " . url('/dashboard/student/create'));
    exit;
  }

  $stmt = $conn->prepare("
    INSERT INTO pass_slips 
      (user_id, category, requesting_student, section, request_date, request_time, purpose, instructor, class_adviser, technology_head, note)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");

  $stmt->bind_param(
    "issssssssss",
    $user_id,
    $category,
    $requesting_student,
    $section,
    $request_date,
    $request_time,
    $purpose,
    $instructor,
    $class_adviser,
    $technology_head,
    $note
  );

  if ($stmt->execute()) {

    // Get the new slip id after successful insert
    $slip_id = $conn->insert_id;

    // Notify only the instructor selected for this slip.
    createNotification($conn, (int) $instructor, $slip_id, "A new pass slip has been submitted and requires your approval.");

    $stmt->close();
    $conn->close();
    header("Location: " . url('/dashboard/student'));
    exit;
  } else {
    // Store error and redirect back
    $_SESSION['slipError'] = "Failed to create pass slip. Please try again.";
    header("Location: " . url('/dashboard/student/create'));
    exit;
  }
}

// Block direct access
header("Location: " . url('/dashboard/student'));
exit;
