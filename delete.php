<?php
require_once("config.php");

if (isset($_POST['id'])) {
    $taskId = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $taskId);

    if ($stmt->execute()) {
        header('Location: index.php?message=Задача удалена успешно');
    } else {
        echo "Ошибка: " . $stmt->error; // Выводим ошибку
        header('Location: index.php?error=Ошибка удаления задачи');
    }

    $stmt->close();
} else {
    header('Location: index.php?error=Идентификатор задачи не указан');
}
?>