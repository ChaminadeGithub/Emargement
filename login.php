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
    $motpasse = $data->motpasse;

    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($motpasse, $user['motpasse'])) {
            echo json_encode(["message" => "Connexion réussie", "user" => $user]);
        } else {
            echo json_encode(["message" => "Mot de passe incorrect"]);
        }
    } else {
        echo json_encode(["message" => "Utilisateur non trouvé"]);
    }
} else {
    header("HTTP/1.1 405 Method Not Allowed");
}

$conn->close();
?>
