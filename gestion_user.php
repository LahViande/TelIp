<?php
include_once("include.php");
include_once("header.php");

//ACCES RESTREINT , SEUL ADMIN PEUT ACCEDER A CETTE PAGE
if($_SESSION['login']!=='admin') 
{
	header('Location: contact.php');
}
elseif(isset($_SESSION["login"]))
{
	echo"</br>";
	echo"<font color=\"green\">Vous êtes connecté en tant que ".$_SESSION["login"]."</font>";
}
else
{
	header('Location: login.php');
}

?>

<form name='forms' method='POST'>
				
<table border='3' width='1000px' height='300px' BORDERCOLOR=black>
<tr>
<td style='background-color:grey'><b><font size=5 >Identifiant</font><b></td>
<td><b><font size=5 >Login</font><b></td>
<td><b><font size=5 >E-mail</font><b></td>
</tr>	
</br>

<!-- BOUTON FORMULAIRE D'AJOUT D'UN UTILISATEUR -->
<input type='submit' name='ajouter' value='Ajouter un utilisateur'>&nbsp;&nbsp;
<input type='submit' value ='Supprimer' name='supprim'>&nbsp;&nbsp;

<!-- BOUTON REDIRECTION VERS LE JOURNAL DES LOGS -->
<input type='submit' name='retour_journal' value='Journal d&apos;événements'>&nbsp;&nbsp;

<!--BOUTON REDIRECTION VERS GESTION_CLIENT -->
<input type='submit' name='gestionc' value='Gestion client'>&nbsp;&nbsp;

<!-- BOUTON DE DECONNEXION -->
<input type='submit' name='retour_log' value='Deconnexion'>&nbsp;&nbsp;
					
<?php
//REQUETE AFFICHAGE UTILISATEURS
$requete_aff_uti="select * from utilisateur";
$resultat_aff_uti=mysql_query($requete_aff_uti);

//AFFICHAGE DE TOUS LES UTILISATEURS
while ($ligne = mysql_fetch_array($resultat_aff_uti))
{ 
	echo"<tr>";
	echo"<td style='background-color:grey'>";
	echo $ligne['id_utilisateur'];
	echo"</td>"; 
	echo"<td style='background-color:white'>";
	echo $ligne['login'];
	echo"</td>";
	echo"<td style='background-color:white'>";
	echo $ligne['email'];
	echo"</td>"; 
	echo"<td></font>";

	//LIEN VERS LE FORMULAIRE DE MODIFICATION DE L'UTILISATEUR
	echo"<a href='gestion_user.php?act=modif&id_utilisateur=". $ligne["id_utilisateur"] . "'><font color='black'>Modifier</a>";
	echo"<td>";
	echo '<input type="checkbox" name="check[]" value=' . $ligne["id_utilisateur"] . '/>';
	echo"</td>";
	echo "</tr>";
}

//REDIRECTIONS
if(isset($_POST['retour_journal']))
{
	header('Location: Journal_evenements.php');
}

if(isset($_POST['gestionc']))
{
	header('Location: gestion_client.php');
}

if(isset($_POST['retour_log']))
{
	header('Location: login.php');
}
						
//SI ON APPUIE SUR LE BOUTON D'AJOUT D'UN UTILISATEUR
//OUVERTURE DU FORMULAIRE D'AJOUT D'UN UTILISATEUR
if(isset($_POST["ajouter"]))
{
	echo"</br>";
	echo"</br>";
	echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i><u><b><font color='black'>Ajouter un utilisateur :</font><b></u></i>";
	echo"</br>";
	echo"</br>";
	echo "<b><label for='login'>Login  : </label><b><input type='text' name='login_ajout' minlength=3  ><b><br/>";
	echo "<b><label for='motdepasse'>Mot de Passe : </label><b><input type='password' name='motdepasse_ajout'><b><br/>";
	echo "<b><label for='email'>E-mail : </label><b><input type='text' name='email1'><b><br/>";
	echo"</br>";
	


	//REQUETE POUR AFFICHER LES SOCIETES DANS LES CHECKBOX
	$requete_check="select * from client where société NOT LIKE 'admin'";
	$resultat_check=mysql_query($requete_check);

	//BOUCLE POUR AFFICHER LES SOCIETES 
	while($ligne= mysql_fetch_array($resultat_check))
	{
		echo"<input type='checkbox' name='checkb[]' value='".$ligne['id_client']."'>".$ligne['société']."";
	}
	echo"</br>";
	echo"</br>";
	//BOUTON DE VALIDATION DE LA CREATION D'UN UTILISATEUR
	echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='valider_creation' value='Valider'>";
}

//SI ON APPUIE SUR LE BOUTON DE VALIDATION DE CREATION D'UN UTILISATEUR
if(isset($_POST["valider_creation"]))
{
	//ASSAISONEMENT
	$GraindeSel="57borny"; 
	$login = $_POST['login_ajout']; 
	$recup_caractere = strtolower($login); 
	$recup_caractere = substr("$recup_caractere", 0, 3);

	//REQUETE CREATION UTILISATEUR
	$requete_user="INSERT INTO utilisateur (login,motdepasse,email) VALUES ('".$_POST["login_ajout"]."','".md5($recup_caractere.$_POST["motdepasse_ajout"].$GraindeSel)."','".$_POST["email1"]."')";
	$resultat_user=mysql_query($requete_user);
	//RECUPERATION ID DE L'UTILISATEUR QUI VIENT D'ETRE CREE
	$resid=mysql_insert_id();

	//POUR CHAQUE CHECKBOX COCHER 
	//ASSOCIER L'UTILISATEUR AU CLIENT
	foreach($_POST['checkb'] AS $value)
	{
		//REQUETE INSERTION CLIENT_UTILISATEUR
		$sql_check="INSERT INTO client_utilisateur (id_utilisateur,id_client) VALUES('".$resid."','".$value."')";
		$res_check=mysql_query($sql_check); 	
	}

		//DECLARE LES VARIABLES
		$destinataire=$_POST['email1'];
		$email_expediteur='admin@admin.fr';
		$email_reply='admin@admin.fr';
		$sujet = 'Votre Compte';

		//HEADERS DU MAIL

		$headers = 'From: "'.$_SESSION['email'].'" <'.$email_expediteur.'>'."\n";
		$headers .= 'Return-Path: <'.$email_reply.'>'."\n";
		$headers .= 'MIME-Version: 1.0'."\n";

		//MESSAGE TEXTE
		$message .='Bonjour,'."\n\n".'Voici votre mot de passe et votre identifiant'."\n\n".'';
		$message .= 'Identifiant :'.$_POST['login_ajout']."\n\n";
		$message .= 'Mot de passe :'.$_POST['motdepasse_ajout']."\n\n";
		$date=date("Y-m-d H:i:s");
		
		if(mail($destinataire,$sujet,$message,$headers))
		{
			echo "Le mail a ete envoye";
		}
		else
		{
			echo "Le mail n\'a pu etre envoye";
		}

		//REQUETE ENVOIE DE LOG_MAIL
		$requete_insert="INSERT INTO log_email (destinataire,objet,contenu,horodatage) VALUES ('$destinataire','$sujet','$message','$date')";
		$resultat_insert=mysql_query($requete_insert)or die( "ERREUR SQL!! ");
		header('Location: gestion_user.php');
}

//SI ON APPUIE SUR LE LIEN DE MODIFICATION DE L'UTILISATEUR
//OUVERTURE DU FORMULAIRE DE MODIFICATION
if($_GET["act"] == "modif")
{	
	echo"</br>";
	echo"</br>";
	echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i><u><b><font color='black'>Modifier utilisateur :</font><b></u></i>";
							
	//REQUETE RECUP INFO UTILISATEUR
	$requete_recup_uti="select * from utilisateur where id_utilisateur=".$_GET['id_utilisateur'];
	$resultat_recup_uti=mysql_query($requete_recup_uti)or die( "ERREUR SQL!! ");
	$val1 = mysql_fetch_array($resultat_recup_uti);
							
	//REQUETE RECUP CLIENT OU POURRA ETRE AFFECTE L'UTILISATEUR
	$requete_recup_cli="select * from client where société NOT LIKE 'admin'";
	$resultat_recup_cli=mysql_query($requete_recup_cli);

	//DEBUT DU FORMULAIRE DE MODIFICATION
	echo"</br>";
	echo"<b><label for='id_utilisateur'></label><input type='hidden' name='id_utilisateur' value='". $val1['id_utilisateur'] . "'>";
	echo"</br>";
	echo"<label for='login'>Login</label><input type='text' name='login' minlength=3 value='". $val1['login'] . "'>";
	echo"</br>";
	echo"<label for='motdepasse'>Mot de Passe</label><input type='text' name='motdepasse' value=''>";
	echo"</br>";
	echo"<label for='email'>E-mail</label><input type='text' name='email' value='". $val1['email'] . "'>";
	echo"</br>";
	echo"</br>";
							
	//REQUETE RECUP ID_UTILISATEUR
	$requete_recup_id_uti="SELECT id_utilisateur FROM utilisateur WHERE id_utilisateur='".$_GET['id_utilisateur']."'";
	$resultat_recup_id_uti=mysql_query($requete_recup_id_uti);
							
	while($ligne= mysql_fetch_array($resultat_recup_cli))
	{
		if(in_array($ligne['id_client'], $client))
		{
			echo"<input type='checkbox' name='checkb[]' value='".$ligne['id_client']."' checked>".$ligne['société']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		else
		{
			echo"<input type='checkbox' name='checkb[]' value='".$ligne['id_client']."'>".$ligne['société']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
	}
	echo"</br>";		
	echo"</br>";		
	//BOUTON VALIDATION DE LA MAJ
	echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='Valider' name='valid_modif'>";
}

//SI ON APPUIE SUR LE BOUTON DE VALIDATION DE LA MODIFICATION DE L'UTILISATEUR
//MISE A JOUR DES ELEMENTS 
if(isset($_POST["valid_modif"]))
{	 
	//ASSAISONEMENT
	$GraindeSel="57borny";
	$login = $_POST['login']; 
	$recup_caractere = strtolower($login); 
	$recup_caractere = substr("$recup_caractere", 0, 3);
							
	//SI LE MOT DE PASSE N'EST PAS MODIFIER
	if(empty($_POST["motdepasse"]))
	{
		$requete_maj="UPDATE utilisateur SET login ='".$_POST['login']."', email ='".$_POST['email']."' WHERE id_utilisateur ='".$_GET['id_utilisateur']."'";
		$resultat_maj=mysql_query($requete_maj)or die( "ERREUR SQL!! ");
	}
	//SINON SI LE MOT DE PASSE EST MODIFIER
	else
	{
		//DECLARE LES VARIABLES
		$destinataire=$_POST['email'];
		$email_expediteur='admin@admin.fr';
		$email_reply='admin@admin.fr';
		$sujet = 'Confirmation: Changement de mot de passe';

		//HEADERS DU MAIL
		$headers = 'From:<'.$email_expediteur.'>'."\n";
		$headers .= 'Return-Path: <'.$email_reply.'>'."\n";
		$headers .= 'MIME-Version: 1.0'."\n";

		//MESSAGE TEXTE
		$message .='Bonjour,'."\n\n".'Votre mot de passe a changer'."\n\n".'';
		$message .= 'Identifiant :'.$_POST['login']."\n\n";
		$message .= 'Mot de passe :'.$_POST['motdepasse']."\n\n";
								
		//DECLARATION DE LA DATE
		$date=date("Y-m-d H:i:s");
								
		//ENVOIE DU MAIL
		if(mail($destinataire,$sujet,$message,$headers))
		{
			echo "Le mail a ete envoye";
		}
		//OU PAS
		else
		{
			echo "Le mail n\'a pu etre envoye";
		}

		//REQUETE LOG_MAIL_MODIF_MDP
		$requete_insert="INSERT INTO log_email (destinataire,objet,contenu,horodatage) VALUES ('$destinataire','$sujet','$message','$date')";							
		$resultat_insert=mysql_query($requete_insert);
								
		//REQUETE MAJ 
		$requete_maj_uti="UPDATE utilisateur SET login ='".$_POST['login']."',motdepasse ='".md5($recup_caractere.$_POST["motdepasse"].$GraindeSel)."', email = '".$_POST['email']."' WHERE id_utilisateur ='".$_GET['id_utilisateur']."'";								
		$resultat_maj_uti=mysql_query($requete_maj_uti);
								
		//REQUETE SUPPRESSION DU LIEN ENTRE L'UTILISATEUR ET LES CLIENTS
		$requete_supp_checkbox="DELETE FROM client_utilisateur where id_utilisateur=".$_POST['id_utilisateur']."";
		$resultat_supp_checkbox=mysql_query($requete_supp_checkbox)or die( "ERREUR SQL!! ");

		foreach($_POST['checkb'] AS $value)
		{
			//REQUETE CREATION D'UN NOUVEAU LIEN ENTRE L'UTILISATEUR ET LES CLIENTS CHECKER
			$requete_change_societe="INSERT INTO client_utilisateur (id_client,id_utilisateur) VALUES('".$value."','".$_POST['id_utilisateur']."')";
			$resultat_change_societe=mysql_query($requete_change_societe);
		}
	}
		//REFRAICHISSEMENT DE LA PAGE
		header('Location: gestion_user.php');
}


//FIN UPDATE DES CONTACTS
						
echo "</br>";
echo "</br>";
						
//DEBUT SUPPRESSION AVEC CHECKBOX

$check= isset($_POST['check']) ? $_POST['check'] : "";
if (isset($_POST['supprim']))
{
	foreach($check as $check)
	{ 
		if(!empty($check))
		{	
			$requette_dl_cli_uti="DELETE FROM client_utilisateur WHERE id_utilisateur='$check'";
			$requette_dl_uti="DELETE FROM utilisateur WHERE id_utilisateur='$check'";
			$resulstat_dl_cli_uti= mysql_query($requette_dl_cli_uti); 
			$resultat_dl_uti= mysql_query($requette_dl_uti);
									
			//RAFRAICHISSEMENT DE LA PAGE
			header ("Refresh: 1;URL=gestion_user.php");
		}
	}
}
//FIN DES SUPPRESSION AVEC CHECKBOX

// FIN DU FORM
echo "</form>";
echo "</table>";					
?>
<?php
include_once("footer.php");
?>