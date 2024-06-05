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
            // Récupérer un niveau par ID
            $id = $_GET['id'];
            $sql = "SELECT * FROM niveaux WHERE Id_niveaux = $id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode($row);
            } else {
                echo json_encode(["message" => "Niveau non trouvé"]);
            }
        } else {
            // Récupérer tous les niveaux
            $sql = "SELECT * FROM niveaux";
            $result = $conn->query($sql);
            $niveaux = [];
            while($row = $result->fetch_assoc()) {
                $niveaux[] = $row;
            }
            echo json_encode($niveaux);
        }
        break;
        
    case 'POST':
        // Créer un nouveau niveau
        $data = json_decode(file_get_contents("php://input"));
        $nom_niveaux = $data->nom_niveaux;
        
        $sql = "INSERT INTO niveaux (nom_niveaux) VALUES ('$nom_niveaux')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Niveau créé avec succès"]);
        } else {
            echo json_encode(["message" => "Erreur lors de la création du niveau: " . $conn->error]);
        }
        break;
        
    case 'PUT':
        // Mettre à jour un niveau existant
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $nom_niveaux = $data->nom_niveaux;
        
        $sql = "UPDATE niveaux SET nom_niveaux='$nom_niveaux' WHERE Id_niveaux='$id'";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Niveau mis à jour avec succès"]);
        } else {
            echo json_encode(["message" => "Erreur lors de la mise à jour du niveau: " . $conn->error]);
        }
        break;
        
    case 'DELETE':
        // Supprimer un niveau par ID
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM niveaux WHERE Id_niveaux = $id";
            
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Niveau supprimé avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la suppression du niveau: " . $conn->error]);
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
