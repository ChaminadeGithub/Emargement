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
            // Récupérer un rôle par ID
            $id = $_GET['id'];
            $sql = "SELECT * FROM roles WHERE Id_roles = $id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode($row);
            } else {
                echo json_encode(["message" => "Rôle non trouvé"]);
            }
        } else {
            // Récupérer tous les rôles
            $sql = "SELECT * FROM roles";
            $result = $conn->query($sql);
            $roles = [];
            while($row = $result->fetch_assoc()) {
                $roles[] = $row;
            }
            echo json_encode($roles);
        }
        break;
        
    case 'POST':
        // Créer un nouveau rôle
        $data = json_decode(file_get_contents("php://input"));
        $libelle_role = $data->libelle_role;
        
        $sql = "INSERT INTO roles (libelle_role) VALUES ('$libelle_role')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Rôle créé avec succès"]);
        } else {
            echo json_encode(["message" => "Erreur lors de la création du rôle: " . $conn->error]);
        }
        break;
        
    case 'PUT':
        // Mettre à jour un rôle existant
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $libelle_role = $data->libelle_role;
        
        $sql = "UPDATE roles SET libelle_role='$libelle_role' WHERE Id_roles='$id'";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Rôle mis à jour avec succès"]);
        } else {
            echo json_encode(["message" => "Erreur lors de la mise à jour du rôle: " . $conn->error]);
        }
        break;
        
    case 'DELETE':
        // Supprimer un rôle par ID
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM roles WHERE Id_roles = $id";
            
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Rôle supprimé avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la suppression du rôle: " . $conn->error]);
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
