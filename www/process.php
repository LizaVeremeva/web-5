<?php
session_start();
require_once 'db.php';

// Получаем данные из формы
$name = $_POST['name'] ?? '';
$tour_date = $_POST['date'] ?? '';
$route = $_POST['route'] ?? '';
$audio_guide = isset($_POST['audio_guide']) ? 'yes' : 'no';
$language = $_POST['language'] ?? '';

// Очищаем данные
$name = htmlspecialchars(trim($name));
$tour_date = htmlspecialchars(trim($tour_date));
$route = htmlspecialchars(trim($route));
$language = htmlspecialchars(trim($language));

// Преобразуем технические значения в читаемые (как было в ЛР3)
$route_display = [
    "historic" => "Рыбная деревня",
    "museum" => "Амалиенау", 
    "parks" => "Подземелья и оборонительные валы",
    "architecture" => "Куршская коса"
][$route] ?? $route;

$language_display = [
    "russian" => "Русский",
    "english" => "Английский", 
    "german" => "Немецкий"
][$language] ?? $language;

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

// Сохраняем в БД ПРЕОБРАЗОВАННЫЕ данные
try {
    $sql = "INSERT INTO excursions (name, excursion_date, route, audio_guide, language) 
            VALUES (:name, :excursion_date, :route, :audio_guide, :language)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':excursion_date' => $tour_date,
        ':route' => $route_display,  // Сохраняем "Рыбная деревня" вместо "historic"
        ':audio_guide' => $audio_guide,
        ':language' => $language_display  // Сохраняем "Русский" вместо "russian"
    ]);
    
    $excursion_id = $pdo->lastInsertId();
    
    // Сохраняем время в куки
    setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");
    
    $_SESSION['success'] = "Запись на экскурсию успешно сохранена в базу данных! ID: " . $excursion_id;
    
} catch(PDOException $e) {
    $_SESSION['errors'] = ["Ошибка базы данных: " . $e->getMessage()];
}

header("Location: index.php");
exit();
?>