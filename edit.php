<?php
require_once "config.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=Задача не найдена");
    exit;
}

$task_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $year = (int) $_POST['year'] ?? 0;
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

    if (empty($title)) {
        header("Location: index.php?message=Название задачи не может быть пустым");
    } else {
        $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, year = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssisi", $title, $author, $year, $status, $task_id);

        if ($stmt->execute()) {
            header("Location: index.php?message=Задача обновлена успешно $status");
        } else {
            header("Location: index.php?error=Ошибка при обновлении задачи");
        }
        $stmt->close();
    }
}
?>