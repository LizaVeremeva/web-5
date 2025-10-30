<?php
require_once 'db.php';

try {
    echo " Подключение к базе данных успешно!<br>";
    
    // Проверяем существование таблицы excursions
    $stmt = $pdo->query("SHOW TABLES LIKE 'excursions'");
    if ($stmt->rowCount() > 0) {
        echo " Таблица 'excursions' существует!<br>";
        
        // Показываем структуру таблицы
        $stmt = $pdo->query("DESCRIBE excursions");
        echo " Структура таблицы:<br>";
        while ($row = $stmt->fetch()) {
            echo " - {$row['Field']} ({$row['Type']})<br>";
        }
    } else {
        echo " Таблица 'excursions' не найдена!";
    }
} catch(PDOException $e) {
    echo " Ошибка: " . $e->getMessage();
}
?>