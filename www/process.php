<?php
// Включаем сессии для хранения данных между страницами
session_start();

// Получаем данные из формы
$name = $_POST['name'] ?? '';
$date = $_POST['date'] ?? '';
$route = $_POST['route'] ?? '';
$audio_guide = isset($_POST['audio_guide']) ? 'yes' : 'no';
$language = $_POST['language'] ?? '';

// Очищаем данные от лишних пробелов и HTML-тегов
$name = htmlspecialchars(trim($name));
$date = htmlspecialchars(trim($date));
$route = htmlspecialchars(trim($route));
$language = htmlspecialchars(trim($language));

// Проверяем данные на ошибки 
$errors = [];

// Проверка имени
if(empty($name)) {
    $errors[] = "Имя не может быть пустым";
}

// Проверка даты
if(empty($date)) {
    $errors[] = "Дата экскурсии обязательна";
} elseif (strtotime($date) < strtotime('today')) {
    $errors[] = "Дата экскурсии не может быть в прошлом";
}

// Проверка маршрута
if(empty($route)) {
    $errors[] = "Выберите маршрут экскурсии";
}

// Проверка языка
if(empty($language)) {
    $errors[] = "Выберите язык экскурсии";
}

// Если есть ошибки - сохраняем их и возвращаем на главную
if(!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

// Преобразуем технические значения в красивые названия (как было в JS)
$route_display = [
    "historic" => "Рыбная деревня",
    "museum" => "Амалиенау", 
    "parks" => "Подземелья и оборонительные валы",
    "architecture" => "Куршкая коса"
][$route] ?? $route;

$audio_guide_display = $audio_guide === 'yes' ? 'Да (платно)' : 'Нет';

$language_display = [
    "russian" => "Русский",
    "english" => "Английский", 
    "german" => "Немецкий"
][$language] ?? $language;

$date_display = date('d.m.Y', strtotime($date));

// Сохраняем данные в сессию для показа на главной странице
$_SESSION['last_booking'] = [
    // Исходные данные
    'name' => $name,
    'date' => $date,
    'route' => $route,
    'audio_guide' => $audio_guide,
    'language' => $language,
    // Данные для красивого отображения
    'name_display' => $name,
    'date_display' => $date_display,
    'route_display' => $route_display,
    'audio_guide_display' => $audio_guide_display,
    'language_display' => $language_display
];

// Сохраняем данные в файл для истории всех записей
$data_line = date('Y-m-d H:i:s') . "|" . $name . "|" . $date . "|" . $route . "|" . $audio_guide . "|" . $language . "\n";
file_put_contents("bookings.txt", $data_line, FILE_APPEND);

// GitHub API - получаем репозитории
require_once 'ApiClient.php';
$api = new ApiClient();
$url = 'https://api.opentripmap.com/0.1/en/places/radius?radius=3000&lon=37.6173&lat=55.7558&apikey=5ae2e3f221c38a28845f05b6';
$apiData = $api->request($url);
$_SESSION['api_data'] = $apiData;

// Сохраняем время отправки формы в куки (на 1 час)
setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");

// Перенаправляем пользователя на главную страницу
header("Location: index.php");
exit();
?>