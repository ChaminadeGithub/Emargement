document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");

    form.addEventListener("submit", async (event) => {
        event.preventDefault(); // Empêche le formulaire de soumettre de manière traditionnelle

        const username = document.getElementById("username").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const role = document.getElementById("role").value;

        try {
            const response = await fetch('http://localhost:8080/Emargement_Lbs/utilisateurs.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nom: username,
                    email: email,
                    motpasse: password,
                    Id_role: parseInt(role)
                })
            });

            const result = await response.json();
            if (response.ok) {
                alert(result.message);
                // Redirigez ou effectuez d'autres actions si nécessaire
            } else {
                console.error('Erreur:', result);
                alert("Erreur : " + result.message);
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert("Une erreur s'est produite lors de la création du compte.");
        }
    });
});
