<?php
include 'config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    // Vérifier si toutes les données nécessaires sont présentes
    if (isset($data->nom) && isset($data->email) && isset($data->motpass) && isset($data->id_roles)) {
        $nom = $data->nom;
        $email = $data->email;
        $motpass = $data->motpass;
        $id_roles = $data->id_roles;

        // Vérifier si l'utilisateur existe déjà
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(["message" => "Cet email est déjà utilisé. Veuillez utiliser un autre email."]);
        } else {
            // Insérer l'utilisateur dans la base de données
            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, email, motpass, id_roles) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $nom, $email, $motpass, $id_roles);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Inscription réussie"]);
            } else {
                echo json_encode(["message" => "Erreur lors de l'inscription"]);
            }
        }
    } else {
        echo json_encode(["message" => "Données manquantes"]);
    }
} else {
    // Méthode non autorisée
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["message" => "Méthode non autorisée"]);
}
?>