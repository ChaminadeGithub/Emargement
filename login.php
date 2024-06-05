<?php
include 'config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $email = $data->email;
    $motpass = $data->motpass;

    // Vérifier si l'utilisateur existe dans la base de données
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Vérifier si le mot de passe correspond
        if ($motpass === $user['motpass']) {
            // Authentification réussie
            echo json_encode(["message" => "Connexion réussie", "user" => $user]);
        } else {
            // Mot de passe incorrect
            echo json_encode(["message" => "Mot de passe incorrect"]);
        }
    } else {
        // Utilisateur non trouvé
        echo json_encode(["message" => "Utilisateur non trouvé"]);
    }
} else {
    // Méthode non autorisée
    header("HTTP/1.1 405 Method Not Allowed");
}
?>
