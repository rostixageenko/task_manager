<?php
require_once("config.php");

if (isset($_POST['id'])) {
    $id_user = $_POST['id'];
    $r = $conn->prepare("SELECT status FROM books WHERE id=?");
    $r->bind_param('i', $id_user);
    $r->execute();
    $result = $r->get_result();
    $row = $result->fetch_assoc();
    if ($row['status'] == 'прочитана') {
        header('Location: index.php?message=Задача уже выполнена');
    } else {
        $stmt = $conn->prepare("UPDATE books SET status='прочитана' WHERE id=? ");
        $stmt->bind_param("i", $id_user);

        if ($stmt->execute()) {
            header("Location: index.php?message=Cтатус успешно изменён");
        } else {
            echo "Ошибка: " . $stmt->error; // Выводим ошибку
            header('Location: index.php?error=Ошибка изменения статуса задачи');
        }
        $stmt->close();
    }
} else {
    header('Location: index.php?error=Идентификатор задачи не указан');
}
?>