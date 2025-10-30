<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все записи на экскурсии</title>
    <style>
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        th { 
            background-color: #4CAF50; 
            color: white; 
            font-weight: bold;
        }
        tr:nth-child(even) { 
            background-color: #f2f2f2; 
        }
        tr:hover {
            background-color: #e9f7e9;
        }
        .no-data { 
            text-align: center; 
            padding: 40px; 
            color: #666;
            font-size: 18px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .back-button {
            padding: 10px 20px;
            background: #666;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Все записи на экскурсии</h1>
        
        <?php
        // Функции для преобразования значений в читаемый вид
        function getRouteDisplay($route) {
            $routes = [
                "historic" => "Рыбная деревня",
                "museum" => "Амалиенау", 
                "parks" => "Подземелья и оборонительные валы",
                "architecture" => "Куршкая коса"
            ];
            return $routes[$route] ?? $route;
        }

        function getAudioGuideDisplay($audio_guide) {
            return $audio_guide === 'yes' ? 'Да (платно)' : 'Нет';
        }

        function getLanguageDisplay($language) {
            $languages = [
                "russian" => "Русский",
                "english" => "Английский", 
                "german" => "Немецкий"
            ];
            return $languages[$language] ?? $language;
        }

        // Проверяем существует ли файл с записями
        if(file_exists("bookings.txt") && filesize("bookings.txt") > 0) {
            // Читаем все строки из файла
            $lines = file("bookings.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            // Переворачиваем массив, чтобы новые записи были сверху
            $lines = array_reverse($lines);
        } else {
            $lines = [];
        }
        ?>

        <?php if(empty($lines)): ?>
            <div class="no-data">
                Записей пока нет
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Дата и время записи</th>
                        <th>Имя</th>
                        <th>Дата экскурсии</th>
                        <th>Маршрут</th>
                        <th>Аудиогид</th>
                        <th>Язык</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lines as $line): ?>
                        <?php 
                        // Разбиваем строку на части
                        $data = explode('|', $line);
                        if(count($data) >= 6): 
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($data[0]) ?></td>
                            <td><?= htmlspecialchars($data[1]) ?></td>
                            <td><?= date('d.m.Y', strtotime($data[2])) ?></td>
                            <td><?= getRouteDisplay($data[3]) ?></td>
                            <td><?= getAudioGuideDisplay($data[4]) ?></td>
                            <td><?= getLanguageDisplay($data[5]) ?></td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <p style="color: #666; margin-top: 20px;">
                Всего записей: <strong><?= count($lines) ?></strong>
            </p>
        <?php endif; ?>
        
        <br>
        <a href="index.php" class="back-button">← На главную</a>
    </div>
</body>
</html>