<?php
require_once __DIR__ . '/config/database.php';

$db = getDatabaseConnection();

$testEvents = [
    [
        'title' => 'Concierto de Música Clásica',
        'description' => 'Un concierto maravilloso con piezas clásicas de Beethoven y Mozart.',
        'venue' => 'Auditorio Municipal',
        'event_date' => '2024-05-15 19:00:00',
        'total_seats' => 200,
        'available_seats' => 200
    ],
    [
        'title' => 'Feria del Libro',
        'description' => 'Ven a la feria del libro y descubre nuevas lecturas.',
        'venue' => 'Plaza Central',
        'event_date' => '2024-06-10 10:00:00',
        'total_seats' => 0, 
        'available_seats' => 0
    ],
    [
        'title' => 'Taller de Cocina Italiana',
        'description' => 'Aprende a cocinar pasta fresca y pizzas tradicionales.',
        'venue' => 'Centro Cultural',
        'event_date' => '2024-07-05 16:00:00',
        'total_seats' => 50,
        'available_seats' => 50
    ],
    [
        'title' => 'Exposición de Arte Moderno',
        'description' => 'Disfruta de obras de artistas locales e internacionales.',
        'venue' => 'Museo de Arte',
        'event_date' => '2024-08-20 11:00:00',
        'total_seats' => 100,
        'available_seats' => 100
    ]
];

foreach ($testEvents as $event) {
    $stmt = $db->prepare('INSERT INTO events (title, description, venue, event_date, total_seats, available_seats) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $event['title'],
        $event['description'],
        $event['venue'],
        $event['event_date'],
        $event['total_seats'],
        $event['available_seats']
    ]);
}

echo 'Eventos de prueba insertados correctamente.';
?></content>
<parameter name="filePath">c:\Users\jgarr\Desktop\TFG\TFG\insert_test_events.php