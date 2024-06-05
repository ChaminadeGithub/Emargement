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
        if (isset($_GET['Id_emargements']) && isset($_GET['Id_niveaux'])) {
            // Récupérer une relation concerner par ID d'émargement et ID de niveau
            $Id_emargements = $_GET['Id_emargements'];
            $Id_niveaux = $_GET['Id_niveaux'];
            $sql = "SELECT * FROM concerner WHERE Id_emargements = $Id_emargements AND Id_niveaux = $Id_niveaux";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode($row);
            } else {
                echo json_encode(["message" => "Relation non trouvée"]);
            }
        } else {
            // Récupérer toutes les relations concerner
            $sql = "SELECT * FROM concerner";
            $result = $conn->query($sql);
            $relations = [];
            while($row = $result->fetch_assoc()) {
                $relations[] = $row;
            }
            echo json_encode($relations);
        }
        break;
        
    case 'POST':
        // Créer une nouvelle relation concerner
        $data = json_decode(file_get_contents("php://input"));
        $Id_emargements = $data->Id_emargements;
        $Id_niveaux = $data->Id_niveaux;
        
        $sql = "INSERT INTO concerner (Id_emargements, Id_niveaux) VALUES ($Id_emargements, $Id_niveaux)";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Relation créée avec succès"]);
        } else {
            echo json_encode(["message" => "Erreur lors de la création de la relation: " . $conn->error]);
        }
        break;
        
    case 'PUT':
        // Mettre à jour une relation existante
        $data = json_decode(file_get_contents("php://input"));
        $Id_emargements = $data->Id_emargements;
        $Id_niveaux = $data->Id_niveaux;
        
        $sql = "UPDATE concerner SET Id_niveaux='$Id_niveaux' WHERE Id_emargements='$Id_emargements'";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Relation mise à jour avec succès"]);
        } else {
            echo json_encode(["message" => "Erreur lors de la mise à jour de la relation: " . $conn->error]);
        }
        break;
        
    case 'DELETE':
        // Supprimer une relation par ID d'émargement et ID de niveau
        if (isset($_GET['Id_emargements']) && isset($_GET['Id_niveaux'])) {
            $Id_emargements = $_GET['Id_emargements'];
            $Id_niveaux = $_GET['Id_niveaux'];
            $sql = "DELETE FROM concerner WHERE Id_emargements = $Id_emargements AND Id_niveaux = $Id_niveaux";
            
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Relation supprimée avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la suppression de la relation: " . $conn->error]);
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
