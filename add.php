<?php
require_once("config.php");

$title = $_POST['title'];
$author = $_POST['author'];
$year = (int) $_POST['year']; 
$status_code = (int) $_POST['status'];

switch ($status_code) {
    case 0:
        $status = 'в планах';
        break;
    case 1:
        $status = 'в процессе';
        break;
    case 2:
        $status = 'прочитана';
        break;
    default:
        $status = 'в планах'; 
}

$stmt = $conn->prepare('INSERT INTO books (title, author, `year`, status) VALUES (?,?,?,?)');
$stmt->bind_param('ssis', $title, $author, $year, $status);

if ($stmt->execute()) {
    header("Location: index.php?message=Книга успешно добавлена");
    exit;
} else {
    header('Location: index.php?error=' . urlencode("Ошибка: " . $stmt->error));
    exit;
}
?>
