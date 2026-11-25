<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Dashboard | Student Management System';
$search = trim($_GET['search'] ?? '');

$totalStatement = $pdo->query('SELECT COUNT(*) FROM students');
$totalStudents = (int)$totalStatement->fetchColumn();

if ($search !== '') {
    $statement = $pdo->prepare(
        'SELECT * FROM students
         WHERE student_name LIKE :search
            OR roll_no LIKE :search
            OR address LIKE :search
         ORDER BY id DESC'
    );
    $statement->execute(['search' => '%' . $search . '%']);
} else {
    $statement = $pdo->query('SELECT * FROM students ORDER BY id DESC');
}

$students = $statement->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">OVERVIEW</p>
        <h1>Student Dashboard</h1>
        <p class="muted">Manage student records in one place.</p>
    </div>
    <a class="button primary" href="create.php">+ Add New Student</a>
</section>

<section class="stat-card">
    <div class="stat-icon">👩‍🎓</div>
    <div>
        <p class="muted">Total registered students</p>
        <h2><?= $totalStudents ?></h2>
    </div>
</section>

<section class="panel">
    <div class="panel-heading">
        <div>
            <h2>Student Records</h2>
            <p class="muted">Search, edit or delete student information.</p>
        </div>
        <form class="search-form" method="GET" action="index.php">
            <input type="search" name="search" placeholder="Search name, roll no, address"
                   value="<?= e($search) ?>">
            <button class="button dark" type="submit">Search</button>
            <?php if ($search !== ''): ?>
                <a class="button light" href="index.php">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Roll Number</th>
                    <th>Address</th>
                    <th>Mobile Number</th>
                    <th>Course</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$students): ?>
                <tr>
                    <td colspan="7" class="empty">No student records found. Add your first student.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= (int)$student['id'] ?></td>
                        <td><strong><?= e($student['student_name']) ?></strong></td>
                        <td><?= e($student['roll_no']) ?></td>
                        <td><?= e($student['address']) ?></td>
                        <td><?= e($student['mobile_no']) ?></td>
                        <td><span class="tag"><?= e($student['course']) ?></span></td>
                        <td class="actions">
                            <a class="button small light" href="edit.php?id=<?= (int)$student['id'] ?>">Edit</a>
                            <form method="POST" action="delete.php"
                                  onsubmit="return confirm('Are you sure you want to delete this student?');">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="id" value="<?= (int)$student['id'] ?>">
                                <button class="button small danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
