<?php
require_once('config.php');

// Vérifie la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données JSON
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

    // Requête SQL pour insérer un utilisateur
    $sql = "INSERT INTO utilisateurs (nom, email, motpasse, Id_role) VALUES (:nom, :email, :motpasse, :Id_role)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':motpasse', $motpasse);
        $stmt->bindParam(':Id_role', $Id_role);
        $stmt->execute();
        echo json_encode(["message" => "Utilisateur créé avec succès"]);
    } catch(PDOException $e) {
        echo json_encode(["message" => "Erreur lors de la création de l'utilisateur: " . $e->getMessage()]);
    }
}
?>
