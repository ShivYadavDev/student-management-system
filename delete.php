<?php
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

verify_csrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    set_flash('Invalid student ID.', 'error');
    header('Location: index.php');
    exit;
}

$statement = $pdo->prepare('DELETE FROM students WHERE id = :id');
$statement->execute(['id' => $id]);

set_flash($statement->rowCount() ? 'Student deleted successfully.' : 'Student record not found.',
          $statement->rowCount() ? 'success' : 'error');

header('Location: index.php');
exit;
