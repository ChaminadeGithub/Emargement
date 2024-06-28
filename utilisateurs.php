<?php
// Connexion à la base de données avec PDO
$servername = "localhost";
$username = "root"; // Remplacez par votre nom d'utilisateur MySQL
$password = ""; // Remplacez par votre mot de passe MySQL
$dbname = "educheck";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Déterminez la méthode HTTP
    $method = $_SERVER['REQUEST_METHOD'];

    // Effectuez différentes actions en fonction de la méthode
    switch ($method) {
        case 'POST':
            // Créer un nouvel utilisateur
            $data = json_decode(file_get_contents("php://input"));

            // Validation des données
            if (empty($data->nom) || empty($data->email) || empty($data->motpasse) || empty($data->Id_role)) {
                echo json_encode(["message" => "Veuillez remplir tous les champs."]);
                exit;
            }

            $nom = $data->nom;
            $email = $data->email;
            $motpasse = $data->motpasse;
            $Id_role = $data->Id_role;

            $sql = "INSERT INTO utilisateurs (nom, email, motpasse, Id_role) VALUES (:nom, :email, :motpasse, :Id_role)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':motpasse', $motpasse);
            $stmt->bindParam(':Id_role', $Id_role);

            $stmt->execute();

            echo json_encode(["message" => "Utilisateur créé avec succès"]);
            break;

        // Ajoutez d'autres méthodes (GET, PUT, DELETE) ici si nécessaire

        default:
            echo json_encode(["message" => "Méthode non supportée"]);
            break;
    }
} catch(PDOException $e) {
    echo json_encode(["message" => "Erreur de connexion à la base de données: " . $e->getMessage()]);
}

// Fermez la connexion
$conn = null;
?>
