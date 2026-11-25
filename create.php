<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Add Student';
$errors = [];
$student = [
    'student_name' => '',
    'roll_no' => '',
    'address' => '',
    'mobile_no' => '',
    'course' => 'BIT',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    foreach ($student as $field => $default) {
        $student[$field] = trim($_POST[$field] ?? $default);
    }

    if ($student['student_name'] === '') {
        $errors[] = 'Student name is required.';
    }
    if ($student['roll_no'] === '') {
        $errors[] = 'Roll number is required.';
    }
    if ($student['address'] === '') {
        $errors[] = 'Address is required.';
    }
    if (!preg_match('/^[0-9+ -]{7,15}$/', $student['mobile_no'])) {
        $errors[] = 'Enter a valid mobile number (7 to 15 digits, +, spaces or hyphens).';
    }
    if (!in_array($student['course'], ['BIT', 'BCA', 'BIM', 'BSc CSIT'], true)) {
        $errors[] = 'Please select a valid course.';
    }

    if (!$errors) {
        try {
            $sql = 'INSERT INTO students (student_name, roll_no, address, mobile_no, course)
                    VALUES (:student_name, :roll_no, :address, :mobile_no, :course)';
            $statement = $pdo->prepare($sql);
            $statement->execute($student);

            set_flash('Student registered successfully.');
            header('Location: index.php');
            exit;
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $errors[] = 'That roll number is already registered.';
            } else {
                $errors[] = 'Could not save the student. Please try again.';
            }
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">REGISTRATION</p>
        <h1>Add New Student</h1>
        <p class="muted">Enter the student's details below.</p>
    </div>
    <a class="button light" href="index.php">← Back to Dashboard</a>
</section>

<section class="panel form-panel">
    <?php if ($errors): ?>
        <div class="alert error">
            <strong>Please correct the following:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="create.php" class="student-form">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

        <div class="form-grid">
            <div class="field">
                <label for="student_name">Student Name *</label>
                <input id="student_name" name="student_name" maxlength="100"
                       value="<?= e($student['student_name']) ?>" required>
            </div>
            <div class="field">
                <label for="roll_no">Roll Number *</label>
                <input id="roll_no" name="roll_no" maxlength="30"
                       value="<?= e($student['roll_no']) ?>" required>
            </div>
            <div class="field">
                <label for="address">Address *</label>
                <input id="address" name="address" maxlength="200"
                       value="<?= e($student['address']) ?>" required>
            </div>
            <div class="field">
                <label for="mobile_no">Mobile Number *</label>
                <input id="mobile_no" name="mobile_no" maxlength="15"
                       value="<?= e($student['mobile_no']) ?>" required>
            </div>
            <div class="field">
                <label for="course">Course *</label>
                <select id="course" name="course" required>
                    <?php foreach (['BIT', 'BCA', 'BIM', 'BSc CSIT'] as $course): ?>
                        <option value="<?= e($course) ?>"
                            <?= $student['course'] === $course ? 'selected' : '' ?>>
                            <?= e($course) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button class="button primary" type="submit">Save Student</button>
            <a class="button light" href="index.php">Cancel</a>
        </div>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
