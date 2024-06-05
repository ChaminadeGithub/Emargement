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
            // Récupérer un utilisateur par ID
            $id = $_GET['id'];
            $sql = "SELECT * FROM utilisateurs WHERE Id_utilisateurs = $id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode($row);
            } else {
                echo json_encode(["message" => "Utilisateur non trouvé"]);
            }
        } else {
            // Récupérer tous les utilisateurs
            $sql = "SELECT * FROM utilisateurs";
            $result = $conn->query($sql);
            $users = [];
            while($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            echo json_encode($users);
        }
        break;
        
    case 'POST':
        // Créer un nouvel utilisateur
        $data = json_decode(file_get_contents("php://input"));
        $nom = $data->nom;
        $prenoms = $data->prenoms;
        $telephone = $data->telephone;
        $email = $data->email;
        $adresse = $data->adresse;
        $motpass = password_hash($data->motpass, PASSWORD_BCRYPT); // Hash du mot de passe
        $Id_roles = $data->Id_roles;
        $Id_confirmations = $data->Id_confirmations;
        
        $sql = "INSERT INTO utilisateurs (nom, prenoms, telephone, email, adresse, motpass, Id_roles, Id_confirmations) VALUES ('$nom', '$prenoms', '$telephone', '$email', '$adresse', '$motpass', $Id_roles, $Id_confirmations)";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Utilisateur créé avec succès"]);
        } else {
            echo json_encode(["message" => "Erreur lors de la création de l'utilisateur: " . $conn->error]);
        }
        break;
        
    case 'PUT':
        // Mettre à jour un utilisateur existant
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $nom = $data->nom;
        $prenoms = $data->prenoms;
        $telephone = $data->telephone;
        $email = $data->email;
        $adresse = $data->adresse;
        $motpass = password_hash($data->motpass, PASSWORD_BCRYPT); // Hash du mot de passe
        $Id_roles = $data->Id_roles;
        $Id_confirmations = $data->Id_confirmations;
        
        $sql = "UPDATE utilisateurs SET nom='$nom', prenoms='$prenoms', telephone='$telephone', email='$email', adresse='$adresse', motpass='$motpass', Id_roles='$Id_roles', Id_confirmations='$Id_confirmations' WHERE Id_utilisateurs='$id'";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Utilisateur mis à jour avec succès"]);
        } else {
            echo json_encode(["message" => "Erreur lors de la mise à jour de l'utilisateur: " . $conn->error]);
        }
        break;
        
    case 'DELETE':
        // Supprimer un utilisateur par ID
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM utilisateurs WHERE Id_utilisateurs = $id";
            
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Utilisateur supprimé avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la suppression de l'utilisateur: " . $conn->error]);
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
