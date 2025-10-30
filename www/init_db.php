<?php
require_once 'db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS excursions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        excursion_date DATE NOT NULL,
        route VARCHAR(50) NOT NULL,
        audio_guide ENUM('yes', 'no') DEFAULT 'no',
        language VARCHAR(20) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo " Таблица 'excursions' успешно создана!";
    
    // Проверяем что таблица создалась
    $stmt = $pdo->query("SHOW TABLES LIKE 'excursions'");
    if ($stmt->rowCount() > 0) {
        echo " Проверка: таблица существует!";
    }
} catch(PDOException $e) {
    echo " Ошибка создания таблицы: " . $e->getMessage();
}
?>