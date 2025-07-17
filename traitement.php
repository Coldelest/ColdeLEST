<?php
// Clé secrète reCAPTCHA
$recaptchaSecret = '6LcNYoYrAAAAAA3MwLhzvBEwvKNCTIERobM6ZWo2';

// Vérifie que le formulaire a été soumis avec le token reCAPTCHA
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['g-recaptcha-response'])) {
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Vérification côté Google
    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($verifyUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
    $responseKeys = json_decode($response, true);

    if ($responseKeys["success"]) {
        // ✅ Le reCAPTCHA est valide, on peut traiter les données

        // Nettoyage et récupération des données du formulaire
        $firstName = htmlspecialchars($_POST['firstName'] ?? '');
        $lastName = htmlspecialchars($_POST['lastName'] ?? '');
        $email = htmlspecialchars($_POST['email'] ?? '');
        $phone = htmlspecialchars($_POST['phone'] ?? '');
        $carModel = htmlspecialchars($_POST['carModel'] ?? '');
        $message = htmlspecialchars($_POST['message'] ?? '');

        // Vérification des champs obligatoires
        if ($firstName && $lastName && $email && $carModel) {
            // Exemple : envoi par email
            $to = "ton-adresse@mail.com";
            $subject = "Nouvelle inscription rallye : $firstName $lastName";
            $body = "Prénom : $firstName\nNom : $lastName\nEmail : $email\nTéléphone : $phone\nModèle : $carModel\nMessage : $message";
            $headers = "From: noreply@tonsite.com";

            mail($to, $subject, $body, $headers);

            // Réponse JSON (si tu veux gérer la réponse via JS)
            echo json_encode(["status" => "success", "message" => "Formulaire envoyé avec succès."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Veuillez remplir tous les champs obligatoires."]);
        }
    } else {
        // ❌ Le reCAPTCHA a échoué
        echo json_encode(["status" => "error", "message" => "Échec de la vérification reCAPTCHA."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Formulaire invalide."]);
}
?>
