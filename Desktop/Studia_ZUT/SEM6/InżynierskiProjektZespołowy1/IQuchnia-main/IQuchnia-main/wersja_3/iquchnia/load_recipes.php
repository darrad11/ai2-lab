<?php

// Ścieżka do bazy SQLite
$db = new PDO('sqlite:C:/Users/darra/Desktop/Studia_ZUT/SEM6/InżynierskiProjektZespołowy1/IQuchnia-main/IQuchnia-main/wersja_3/iquchnia/var/data.db');

// Włączenie błędów PDO
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Otwieramy plik JSON
$jsonData = file_get_contents('recipes.json');
$recipes = json_decode($jsonData, true); // Dekodowanie JSON do tablicy asocjacyjnej

// Przygotowanie zapytania INSERT
$query = "INSERT INTO recipe (id, user_id, name, instructions, cuisine, diet, difficulty, people, description, image, isFavorite, views) 
          VALUES (:id, :user_id, :name, :instructions, :cuisine, :diet, :difficulty, :people, :description, :image, :isFavorite, :views)";

// Przebiegamy przez każdy przepis
foreach ($recipes as $recipe) {
    $stmt = $db->prepare($query);

    // Przypisujemy wartości do zapytania
    $stmt->bindParam(':id', $recipe['id']);
    $stmt->bindParam(':user_id', $recipe['user_id']);
    $stmt->bindParam(':name', $recipe['name']);
    $stmt->bindParam(':instructions', $recipe['instructions']);
    $stmt->bindParam(':cuisine', $recipe['cuisine']);
    $stmt->bindParam(':diet', $recipe['diet']);
    $stmt->bindParam(':difficulty', $recipe['difficulty']);
    $stmt->bindParam(':people', $recipe['people']);
    $stmt->bindParam(':description', $recipe['description']);
    $stmt->bindParam(':image', $recipe['image']);
    $stmt->bindParam(':isFavorite', $recipe['isFavorite']);
    $stmt->bindParam(':views', $recipe['views']);

    // Wykonanie zapytania
    $stmt->execute();
}

echo "Dane zostały wczytane do bazy!";
