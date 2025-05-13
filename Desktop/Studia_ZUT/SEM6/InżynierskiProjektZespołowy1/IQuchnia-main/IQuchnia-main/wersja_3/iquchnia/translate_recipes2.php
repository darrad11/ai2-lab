<?php
// Ścieżki do plików
$jsonFile = 'E:/DariaR/IQuchnia/wersja_3/iquchnia/fully_translated_recipes.json';
$dbFile = 'E:/DariaR/IQuchnia/wersja_3/iquchnia/var/data.db';

// Wczytaj dane z pliku JSON
$jsonData = file_get_contents($jsonFile);
$recipes = json_decode($jsonData, true);

try {
    // Połączenie z bazą danych SQLite
    $db = new PDO("sqlite:$dbFile");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Przygotowanie zapytania SQL do wstawienia lub aktualizacji danych
    $stmt = $db->prepare("
        INSERT INTO recipe (id, name, instructions, cuisine, difficulty, is_vegetarian, image, description, user_id)
        VALUES (:id, :name, :instructions, :cuisine, :difficulty, :is_vegetarian, :image, :description, :user_id)
        ON CONFLICT(id) DO UPDATE SET
            name = excluded.name,
            instructions = excluded.instructions,
            cuisine = excluded.cuisine,
            difficulty = excluded.difficulty,
            is_vegetarian = excluded.is_vegetarian,
            image = excluded.image,
            description = excluded.description,
            user_id = excluded.user_id
    ");

    // Wstawianie lub aktualizowanie rekordów od ID 22
    foreach ($recipes as $recipe) {
        if ($recipe['id'] >= 22) {
            $stmt->execute([
                ':id' => $recipe['id'],
                ':name' => $recipe['nazwa'], // Użycie spolszczonego klucza
                ':instructions' => $recipe['instrukcje'],
                ':cuisine' => $recipe['kuchnia'],
                ':difficulty' => $recipe['trudność'],
                ':is_vegetarian' => $recipe['jest_wegetariańskie'],
                ':image' => $recipe['obraz'],
                ':description' => $recipe['opis'],
                ':user_id' => 1
            ]);
        }
    }

    echo "Dane zostały pomyślnie zaimportowane lub zaktualizowane od ID 22.";
} catch (PDOException $e) {
    echo "Błąd: " . $e->getMessage();
}
