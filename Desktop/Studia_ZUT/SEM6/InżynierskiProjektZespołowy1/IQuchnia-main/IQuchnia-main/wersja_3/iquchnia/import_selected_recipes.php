<?php

// Ścieżka do pliku JSON
$jsonFile = __DIR__ . '/recipes.json';

// Załaduj dane JSON
$jsonData = json_decode(file_get_contents($jsonFile), true);

// Połączenie z bazą SQLite
$pdo = new PDO('sqlite:' . __DIR__ . '/var/data.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Przygotowanie zapytania SQL do wstawiania danych
$stmt = $pdo->prepare('INSERT INTO recipe (id, name, cuisine, difficulty, is_vegetarian, instructions, description, image, user_id) 
                       VALUES (:id, :name, :cuisine, :difficulty, :is_vegetarian, :instructions, :description, :image, :user_id)');

// Iteracja po danych i wybór przepisów do importu
foreach ($jsonData as $recipe) {
    // Wybierz tylko przepisy spełniające warunki

        $stmt->execute([

            ':id' => $recipe['id'],
            ':name' => $recipe['name'],
            ':cuisine' => $recipe['cuisine'],
            ':difficulty' => $recipe['difficulty'],
            ':is_vegetarian' => strtolower($recipe['diet']) === 'vegetarian' ? 1 : 0,
            ':instructions' => 1,
            ':description' => $recipe['description'],
            ':image' => $recipe['image'],
            'user_id' => 1,
        ]);

}

echo "Wybrane przepisy zostały zaimportowane do bazy danych.\n";

