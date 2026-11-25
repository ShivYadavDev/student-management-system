<?php
require_once __DIR__ . '/includes/functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    set_flash('Invalid student ID.', 'error');
    header('Location: index.php');
    exit;
}

$statement = $pdo->prepare('SELECT * FROM students WHERE id = :id');
$statement->execute(['id' => $id]);
$student = $statement->fetch();

if (!$student) {
    set_flash('Student record not found.', 'error');
    header('Location: index.php');
    exit;
}

$pageTitle = 'Edit Student';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    foreach (['student_name', 'roll_no', 'address', 'mobile_no', 'course'] as $field) {
        $student[$field] = trim($_POST[$field] ?? '');
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
        $errors[] = 'Enter a valid mobile number.';
    }
    if (!in_array($student['course'], ['BIT', 'BCA', 'BIM', 'BSc CSIT'], true)) {
        $errors[] = 'Please select a valid course.';
    }

    if (!$errors) {
        try {
            $sql = 'UPDATE students
                    SET student_name = :student_name,
                        roll_no = :roll_no,
                        address = :address,
                        mobile_no = :mobile_no,
                        course = :course
                    WHERE id = :id';
            $update = $pdo->prepare($sql);
            $update->execute([
                'student_name' => $student['student_name'],
                'roll_no' => $student['roll_no'],
                'address' => $student['address'],
                'mobile_no' => $student['mobile_no'],
                'course' => $student['course'],
                'id' => $id,
            ]);

            set_flash('Student details updated successfully.');
            header('Location: index.php');
            exit;
        } catch (PDOException $exception) {
            $errors[] = $exception->getCode() === '23000'
                ? 'That roll number is already used by another student.'
                : 'Could not update the student. Please try again.';
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">UPDATE RECORD</p>
        <h1>Edit Student</h1>
        <p class="muted">Change the details and save your updates.</p>
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

    <form method="POST" action="edit.php?id=<?= (int)$id ?>" class="student-form">
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
            <button class="button primary" type="submit">Update Student</button>
            <a class="button light" href="index.php">Cancel</a>
        </div>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
