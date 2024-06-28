<?php
include 'config.php';

// Définir les en-têtes pour permettre les requêtes CORS et les requêtes de type JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Gérer les requêtes OPTIONS (pré-vol) pour les CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Obtenir la méthode HTTP utilisée pour la requête
$method = $_SERVER['REQUEST_METHOD'];

// Switch basé sur la méthode HTTP
switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Récupérer un émargement par ID
            $id = intval($_GET['id']);
            $sql = "SELECT * FROM emargements WHERE id_emargements = $id";
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
            while ($row = $result->fetch_assoc()) {
                $emargements[] = $row;
            }
            echo json_encode($emargements);
        }
        break;

    case 'POST':
        // Créer un nouvel émargement
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['dates'], $data['heure'],  $data['matiere'], $data['nom'], $data['niveaux'], $data['option'])) {
            $dates = $data['dates'];
            $heure = $data['heure'];
            $matiere = $data['matiere'];
            $nom = $data['nom'];
            $niveaux = json_decode($data['niveaux'], true); // Assuming niveaux is a JSON string
            $option = $data['option'];

            // Vérifiez que niveaux est bien un tableau après décodage JSON
            if (!is_array($niveaux)) {
                echo json_encode(["message" => "Données incomplètes. Les niveaux doivent être un tableau."]);
                exit();
            }

            // Convertir le tableau niveaux en une chaîne séparée par des virgules
            $niveaux_str = implode(',', $niveaux);

            $sql = "INSERT INTO emargements (dates, heure,  matiere, nom, niveaux, options) 
                    VALUES ('$dates', '$heure', '$matiere', '$nom', '$niveaux_str', '$option')";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Émargement créé avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la création de l'émargement: " . $conn->error]);
            }
        } else {
            echo json_encode(["message" => "Données incomplètes. Les champs requis sont: dates, heure_debut, heure_fin, matiere, nom, niveaux, option."]);
        }
        break;

    case 'PUT':
        // Mettre à jour un émargement existant
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['id'], $data['dates'], $data['heure_debut'], $data['heure_fin'], $data['matiere'], $data['nom'], $data['niveaux'], $data['option'])) {
            $id = intval($data['id']);
            $dates = $data['dates'];
            $heure_debut = $data['heure_debut'];
            $heure_fin = $data['heure_fin'];
            $matiere = $data['matiere'];
            $nom = $data['nom'];
            $niveaux = json_decode($data['niveaux'], true); // Assuming niveaux is a JSON string
            $option = $data['option'];

            // Vérifiez que niveaux est bien un tableau après décodage JSON
            if (!is_array($niveaux)) {
                echo json_encode(["message" => "Données incomplètes. Les niveaux doivent être un tableau."]);
                exit();
            }

            // Convertir le tableau niveaux en une chaîne séparée par des virgules
            $niveaux_str = implode(',', $niveaux);

            $sql = "UPDATE emargements SET dates='$dates', heure_debut='$heure_debut', heure_fin='$heure_fin', matiere='$matiere', nom='$nom', niveaux='$niveaux_str', options='$option' WHERE id_emargements=$id";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Émargement mis à jour avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la mise à jour de l'émargement: " . $conn->error]);
            }
        } else {
            echo json_encode(["message" => "Données incomplètes. Les champs requis sont: id, dates, heure_debut, heure_fin, matiere, nom, niveaux, option."]);
        }
        break;

    case 'DELETE':
        // Supprimer un émargement par ID
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "DELETE FROM emargements WHERE id_emargements = $id";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Émargement supprimé avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la suppression de l'émargement: " . $conn->error]);
            }
        } else {
            echo json_encode(["message" => "ID non spécifié"]);
        }
        break;

    default:
        // Méthode non autorisée
        header("HTTP/1.1 405 Method Not Allowed");
        echo json_encode(["message" => "Méthode non autorisée"]);
        break;
}

// Fermer la connexion
$conn->close();
?>