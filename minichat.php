<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Mini-chat</title>
    </head>
    <body>
    
    <form action="minichat_post.php" method="post">
        <p>
        <label for="pseudo">Pseudo</label> : <input type="text" name="pseudo" id="pseudo" /><br/>
        <label for="message">Message</label> :  <input type="text" name="message" id="message" /><br/>

        <input type="submit" value="Envoyer" id="envoi"/>
	</p>
    </form>

<?php
// Connexion à la base de données
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', 'root');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

// Récupération des 10 derniers messages
$reponse = $bdd->query('SELECT pseudo, message FROM minichat ORDER BY ID DESC LIMIT 0, 10');


// Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
while ($donnees = $reponse->fetch()) {
    echo '<p><strong>' . htmlspecialchars($donnees['pseudo']) . '</strong> : ' . htmlspecialchars($donnees['message']) . '</p>';
}

$reponse->closeCursor();

?>

<script type="text/javascript" src="jquery.js"></script>
<script>
$('#envoi').click(function(e){
    e.preventDefault(); // on empêche le bouton d'envoyer le formulaire

    var pseudo = encodeURIComponent( $('#pseudo').val() ); // on sécurise les données
    var message = encodeURIComponent( $('#message').val() );

    if(pseudo != "" && message != ""){ // on vérifie que les variables ne sont pas vides
        $.ajax({
            url : "minichat_post.php", // on donne l'URL du fichier de traitement
            type : "POST", // la requête est de type POST
            data : "pseudo=" + pseudo + "&message=" + message // et on envoie nos données
        });
    $('#messages').append("<p>" + pseudo + " dit : " + message + "</p>"); // on ajoute le message dans la zone prévue    
    }
});

function charger(){

    setTimeout( function(){
        // on lance une requête AJAX
        $.ajax({
            url : "traitement.php",
            type : POST,
            success : function(html){
                $('#messages').prepend(html); // on veut ajouter les nouveaux messages au début du bloc #messages
            }
        });

        charger(); // on relance la fonction

    }, 5000); // on exécute le chargement toutes les 5 secondes

}

charger();


</script>

    </body>
</html>