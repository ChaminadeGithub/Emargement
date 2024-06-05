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
            // Récupérer une matière par ID
            $id = $_GET['id'];
            $sql = "SELECT * FROM matieres WHERE Id_matieres = $id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode($row);
            } else {
                echo json_encode(["message" => "Matière non trouvée"]);
            }
        } else {
            // Récupérer toutes les matières
            $sql = "SELECT * FROM matieres";
            $result = $conn->query($sql);
            $matieres = [];
            while($row = $result->fetch_assoc()) {
                $matieres[] = $row;
            }
            echo json_encode($matieres);
        }
        break;
        
    case 'POST':
        // Créer une nouvelle matière
        $data = json_decode(file_get_contents("php://input"));
        $libelle = $data->libelle;
        $nbre_heure = $data->nbre_heure;
        
        $sql = "INSERT INTO matieres (libelle, nbre_heure) VALUES ('$libelle', $nbre_heure)";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Matière créée avec succès"]);
        } else {
            echo json_encode(["message" => "Erreur lors de la création de la matière: " . $conn->error]);
        }
        break;
        
    case 'PUT':
        // Mettre à jour une matière existante
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $libelle = $data->libelle;
        $nbre_heure = $data->nbre_heure;
        
        $sql = "UPDATE matieres SET libelle='$libelle', nbre_heure='$nbre_heure' WHERE Id_matieres='$id'";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Matière mise à jour avec succès"]);
        } else {
            echo json_encode(["message" => "Erreur lors de la mise à jour de la matière: " . $conn->error]);
        }
        break;
        
    case 'DELETE':
        // Supprimer une matière par ID
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM matieres WHERE Id_matieres = $id";
            
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Matière supprimée avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la suppression de la matière: " . $conn->error]);
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
