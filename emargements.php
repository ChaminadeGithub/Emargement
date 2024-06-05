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
            // Récupérer un émargement par ID
            $id = $_GET['id'];
            $sql = "SELECT * FROM emargements WHERE Id_emargements = $id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode($row);
            } else {
                echo json_encode(["message" => "Émargement non trouvé"]);
            }
        } else {
            // Récupérer tous les émargements
            $sql = "SELECT * FROM emargements";
            $result = $conn->query($sql);
            $emargements = [];
            while($row = $result->fetch_assoc()) {
                $emargements[] = $row;
            }
            echo json_encode($emargements);
        }
        break;
        
    case 'POST':
        // Créer un nouvel émargement
        $data = json_decode(file_get_contents("php://input"));
        $dates = $data->dates;
        $heure_debut = $data->heure_debut;
        $heure_fin = $data->heure_fin;
        $Id_matieres = $data->Id_matieres;
        $Id_utilisateurs = $data->Id_utilisateurs;
        
        $sql = "INSERT INTO emargements (dates, heure_debut, heure_fin, Id_matieres, Id_utilisateurs) VALUES ('$dates', '$heure_debut', '$heure_fin', $Id_matieres, $Id_utilisateurs)";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Émargement créé avec succès"]);
        } else {
            echo json_encode(["message" => "Erreur lors de la création de l'émargement: " . $conn->error]);
        }
        break;
        
    case 'PUT':
        // Mettre à jour un émargement existant
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $dates = $data->dates;
        $heure_debut = $data->heure_debut;
        $heure_fin = $data->heure_fin;
        $Id_matieres = $data->Id_matieres;
        $Id_utilisateurs = $data->Id_utilisateurs;
        
        $sql = "UPDATE emargements SET dates='$dates', heure_debut='$heure_debut', heure_fin='$heure_fin', Id_matieres='$Id_matieres', Id_utilisateurs='$Id_utilisateurs' WHERE Id_emargements='$id'";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Émargement mis à jour avec succès"]);
        } else {
            echo json_encode(["message" => "Erreur lors de la mise à jour de l'émargement: " . $conn->error]);
        }
        break;
        
    case 'DELETE':
        // Supprimer un émargement par ID
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM emargements WHERE Id_emargements = $id";
            
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Émargement supprimé avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la suppression de l'émargement: " . $conn->error]);
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
