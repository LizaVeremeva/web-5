<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная - Экскурсии Калининград</title>
    <style>
        .booking-card {
            border: 2px solid #4CAF50;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            background: #f9fff9;
        }
        .booking-card h3 {
            color: #2E7D32;
            margin-top: 0;
        }
        .error-box {
            color: red; 
            background: #ffe6e6; 
            padding: 10px; 
            margin: 10px 0; 
            border: 1px solid red;
        }
        .nav-button {
            padding: 10px 15px; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px;
            margin: 5px;
            display: inline-block;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать на сайт экскурсий по Калининграду!</h1>
        
        <!-- Блок для вывода ошибок -->
        <?php if(isset($_SESSION['errors'])): ?>
            <div class="error-box">
                <strong>Ошибки при записи:</strong>
                <ul>
                    <?php foreach($_SESSION['errors'] as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
        
        <!-- Вывод последней брони из сессии -->
        <?php if(isset($_SESSION['last_booking'])): ?>
            <div class="booking-card">
                <h3>Ваша запись принята!</h3>
                <p><strong>Имя:</strong> <?= $_SESSION['last_booking']['name_display'] ?></p>
                <p><strong>Дата экскурсии:</strong> <?= $_SESSION['last_booking']['date_display'] ?></p>
                <p><strong>Маршрут:</strong> <?= $_SESSION['last_booking']['route_display'] ?></p>
                <p><strong>Аудиогид:</strong> <?= $_SESSION['last_booking']['audio_guide_display'] ?></p>
                <p><strong>Язык экскурсии:</strong> <?= $_SESSION['last_booking']['language_display'] ?></p>
            </div>
        <?php endif; ?>

        <!-- Информация о пользователе -->
        <?php
        require_once 'UserInfo.php';
        $info = UserInfo::getInfo();
        ?>
        <div style="margin: 20px 0; padding: 15px; background: #e3f2fd; border-radius: 5px; border-left: 4px solid #2196F3;">
            <h3 style="margin-top: 0; color: #1976D2;"> Информация о посетителе:</h3>
            <?php foreach($info as $key => $val): ?>
                <p style="margin: 5px 0;">
                    <strong><?= htmlspecialchars($key) ?>:</strong> 
                    <?= htmlspecialchars($val) ?>
                </p>
            <?php endforeach; ?>
        </div>

        <!-- Информация о последней отправке формы из куки -->
        <?php if(isset($_COOKIE['last_submission'])): ?>
            <div style="margin: 20px 0; padding: 15px; background: #f3e5f5; border-radius: 5px; border-left: 4px solid #9C27B0;">
                <h3 style="margin-top: 0; color: #7B1FA2;">🕒 Ваша последняя запись:</h3>
                <p style="margin: 5px 0;">
                    <strong>Время:</strong> <?= htmlspecialchars($_COOKIE['last_submission']) ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Навигация -->
        <nav style="margin: 30px 0; text-align: center;">
            <a href="form.html" class="nav-button" style="background: #4CAF50;">
                Записаться на экскурсию
            </a> 
            <a href="view.php" class="nav-button" style="background: #2196F3;">
                Посмотреть все записи
            </a>
        </nav>

        <!-- Информация о экскурсиях -->
        <div style="margin-top: 40px;">
            <h2>Наши экскурсии:</h2>
            <ul>
                <li><strong>Рыбная деревня</strong> - исторический центр города</li>
                <li><strong>Амалиенау</strong> - район немецких вилл</li>
                <li><strong>Подземелья и оборонительные валы</strong> - военная история</li>
                <li><strong>Куршская коса</strong> - уникальный природный заповедник</li>
            </ul>
        </div>

        <!-- Блок с достопримечательностями из API -->
        <?php if(isset($_SESSION['api_data'])): ?>
            <div style="margin-top: 40px; border: 2px solid #FF9800; border-radius: 10px; padding: 20px; background: #fffaf0;">
                <h2 style="color: #FF9800;"> Достопримечательности Калининграда:</h2>
                <?php 
                if(isset($_SESSION['api_data']['error'])) {
                    echo "<p style='color: red;'>Ошибка загрузки достопримечательностей: " . htmlspecialchars($_SESSION['api_data']['error']) . "</p>";
                } elseif(isset($_SESSION['api_data']['features']) && !empty($_SESSION['api_data']['features'])) {
                    $attractions = array_slice($_SESSION['api_data']['features'], 0, 5); // Берем первые 5
                    foreach($attractions as $attraction): 
                        $name = $attraction['properties']['name'] ?? 'Без названия';
                        $kinds = $attraction['properties']['kinds'] ?? '';
                ?>
                    <div style="margin-bottom: 15px; padding: 10px; background: white; border-radius: 5px; border-left: 4px solid #FF9800;">
                        <strong> <?= htmlspecialchars($name) ?></strong><br>
                        <small> <?= htmlspecialchars(str_replace(',', ', ', $kinds)) ?></small>
                    </div>
                <?php 
                    endforeach; 
                    echo "<p><small>Данные предоставлены OpenTripMap API</small></p>";
                } else {
                    echo "<p>Достопримечательности не найдены</p>";
                }
                ?>
            </div>
        <?php endif; ?>

        <!-- Текущее время (как было в старом файле) -->
        <p style="margin-top: 30px; color: #666;">Текущее время: <span id="time"></span></p>
    </div>

    <script>
        document.getElementById('time').textContent = new Date().toLocaleTimeString();
    </script>
</body>
</html>