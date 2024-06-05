<?php
include 'config.php';

// Définir les en-têtes pour permettre les requêtes CORS et les requêtes de type JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Obtenir la méthode HTTP utilisée pour la requête
$method = $_SERVER['REQUEST_METHOD'];

// Switch basé sur la méthode HTTP
switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Récupérer une confirmation par ID
            $id = $_GET['id'];
            $sql = "SELECT * FROM confirmations WHERE Id_confirmations = $id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode($row);
            } else {
                echo json_encode(["message" => "Confirmation non trouvée"]);
            }
        } else {
            // Récupérer toutes les confirmations
            $sql = "SELECT * FROM confirmations";
            $result = $conn->query($sql);
            $confirmations = [];
            while($row = $result->fetch_assoc()) {
                $confirmations[] = $row;
            }
            echo json_encode($confirmations);
        }
        break;
        
    case 'POST':
        // Créer une nouvelle confirmation
        // Note: En l'état, votre table 'confirmations' n'a pas de colonne à insérer. Si cela est correct, laisser le POST vide.
        $sql = "INSERT INTO confirmations () VALUES ()";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Confirmation créée avec succès"]);
        } else {
            echo json_encode(["message" => "Erreur lors de la création de la confirmation: " . $conn->error]);
        }
        break;
        
    case 'PUT':
        // Mettre à jour une confirmation existante
        // Note: En l'état, votre table 'confirmations' n'a pas de colonne à mettre à jour. Si cela est correct, laisser le PUT vide.
        echo json_encode(["message" => "Rien à mettre à jour"]);
        break;
        
    case 'DELETE':
        // Supprimer une confirmation par ID
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM confirmations WHERE Id_confirmations = $id";
            
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Confirmation supprimée avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la suppression de la confirmation: " . $conn->error]);
            }
        }
        break;
        
    default:
        // Méthode non autorisée
        header("HTTP/1.1 405 Method Not Allowed");
        break;
}

// Fermer la connexion
$conn->close();
?>
