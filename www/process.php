<?php
session_start();
require_once 'db.php';

// Получаем данные из формы
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$tour_date = $_POST['date'] ?? '';
$route = $_POST['route'] ?? '';
$audio_guide = isset($_POST['audio_guide']) ? 'yes' : 'no';
$language = $_POST['language'] ?? '';

// Базовая валидация
$errors = [];
if(empty($name)) $errors[] = "Имя обязательно";
if(empty($tour_date)) $errors[] = "Дата обязательна";
if(empty($route)) $errors[] = "Выберите маршрут";
if(empty($language)) $errors[] = "Выберите язык экскурсии";

if(!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

// Сохраняем в базу данных
try {
    $sql = "INSERT INTO excursions (name, email, excursion_date, route, audio_guide, language) 
            VALUES (:name, :email, :excursion_date, :route, :audio_guide, :language)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => htmlspecialchars($name),
        ':email' => htmlspecialchars($email),
        ':excursion_date' => $tour_date,
        ':route' => $route,
        ':audio_guide' => $audio_guide,
        ':language' => $language
    ]);
    
    // Сохраняем ID новой записи
    $booking_id = $pdo->lastInsertId();
    
    $_SESSION['success'] = " Запись успешно сохранена в базу данных! ID: " . $booking_id;
    
} catch(PDOException $e) {
    $_SESSION['errors'] = [" Ошибка базы данных: " . $e->getMessage()];
}

header("Location: index.php");
exit();
?>