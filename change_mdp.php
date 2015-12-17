<?php

include_once("include.php");
include_once("header.php");

//AFFICHAGE DU LOGIN DE L'UTILISATEUR CONNECTE
if(isset($_SESSION["login"]))
{
	echo"<font color=\"green\">Vous êtes connecté en tant que ".$_SESSION["login"]."</font>";
}
else
{
	//SINON REDIRECTION VERS LOGIN
	header('Location: login.php');
}

?>

<form name='form' method='POST'>

<!-- BOUTON OUVERTURE FORMULAIRE DE MODIFICATION -->
<input type='submit' name='modifinfo' value='Modifier mes informations'>

<!-- BOUTON REDIRECTION VERS CONTACT -->
<input type='submit' name='retour_contact' value='Page précédente'>

<?php

//SI ON APPUIE SUR LE BOUTON RETOUR CONTACT
//REDIRECTION VERS CONTACT.PHP
if(isset($_POST["retour_contact"]) )
{
	header('Location: contact.php');
}

echo"</br>";

//SI ON APPUIE SUR LE BOUTON DE MODIFICATION
//ON OUVRE LE FORMULAIRE DE MODIFICATION
if(isset($_POST["modifinfo"]) )
{	
	//REQUETE SELECT UTILISATEUR
	$requete_slct_uti="select * from utilisateur";
	$resultat_slct_uti=mysql_query($requete_slct_uti)or die( "ERREUR SQL!! ");
	
	//DEBUT DU FORMULAIRE DE MODIFICATION
	echo"<input type='hidden' name='id_utilisateur' value='".$_SESSION["id_utilisateur"]."'>";
	echo"</br>";
	echo"<label for='motdepasse'>Mot de passe</label><input type='text' name='motdepasse'>";
	echo"</br>";
	echo"<label for='email'>E-mail</label><input type='text' name='email' value='".$_SESSION["email"]."'>";
	echo"</br>";
	
	//REQUETE SELECT ID_UTILISATEUR
	$requete_slct_id_uti="SELECT id_utilisateur FROM utilisateur WHERE id_utilisateur";
	$resultat_slct_id_uti=mysql_query($requete_slct_id_uti);
	
	//BOUCLE DE RECUP DE L'ID UTILISATEUR
	while($val = mysql_fetch_array($resultat_slct_id_uti))
	{
		$utilisateur[] = $val['id_utilisateur'];
	}
	echo"</br>";
	//BOUTON DE VALIDATION DE LA MODIFICATION
	echo"<input type='submit' value='Valider' name='valid'>";
}

//SI ON APPUIE SUR BOUTON DE VALIDATION DE LA MODIFICATION
//MISE A JOUR DU MOT DE PASSE
if(isset($_POST["valid"]))
{
	//ASSAISONEMENT
	$GraindeSel="57borny";
	$login = $_SESSION['login'];
	$recup_caractere = strtolower($login); 
	$recup_caractere = substr("$recup_caractere", 0, 3);
	
	//SI L'UTILISATEUR CHANGE UNIQUEMENT SON ADRESSE MAIL
	if(empty($_POST["motdepasse"]))
	{
		//REQUETE MODIF EMAIL
		$requete_modif_email="UPDATE utilisateur SET email ='".$_POST['email']."' WHERE id_utilisateur ='".$_SESSION['id_utilisateur']."'";
		$resultat_modif_email=mysql_query($requete_modif_email)or die( "ERREUR SQL!! ");
		//RAFRAICHISSEMENT DE LA PAGE
		header('Location: change_mdp.php');
	}
	//SINON SI L'UTILISATEUR CHANGE SON MDP
	else
	{
		//DEFINITION DES VARIABLES POUR LE LOG_MAIL DE CHANGEMENT DE MOT DE PASSE
		$destinataire=$_POST['email'];
		$email_expediteur='admin@admin.fr';
		$email_reply='admin@admin.fr';
		$sujet = 'Confirmation: Changement de mot de passe';
		
		//HEADERS DU MAIL
		$headers = 'From:<'.$email_expediteur.'>'."\n";
		$headers .= 'Return-Path: <'.$email_reply.'>'."\n";
		$headers .= 'MIME-Version: 1.0'."\n";

		//MESSAGE TEXTE
		$message ='Bonjour,'."\n\n".'Votre mot de passe a changer'."\n\n".'';
		
		//DECLARATION DE LA DATE
		$date=date("Y-m-d H:i:s");
		
		//SI LE MAIL S'ENVOIE
		if(mail($destinataire,$sujet,$message,$headers))
		{
			echo "Le mail a ete envoye";
		}
		//SINON SI LE MAIL N'A PAS PU ETRE ENVOYE
		else
		{
			echo "Le mail n\'a pu etre envoye";
		}
		
		//REQUETE LOG MAIL CHANGEMENT DE MOT DE PASSE
		$requete_log_mail="INSERT INTO log_email (destinataire,objet,contenu,horodatage) VALUES ('$destinataire','$sujet','$message','$date')";
		$resultat_log_mail=mysql_query($requete_log_mail)or die( "ERREUR SQL!! ");
		
		//REQUETE MISE A JOUR DES INFOS DE L'UTILISATEUR
		$requete_maj_user="UPDATE utilisateur SET motdepasse = '".md5($recup_caractere.$_POST["motdepasse"].$GraindeSel)."', email = '".$_POST['email']."' WHERE id_utilisateur ='".$_SESSION['id_utilisateur']."'";
		$resultat_maj_user=mysql_query($requete_maj_user)or die( "ERREUR SQL!! ");

		//RAFRAICHISSEMENT DE LA PAGE
		header('Location: change_mdp.php');
	}
}

?>

</form>

<?php

include_once("footer.php")

?>
