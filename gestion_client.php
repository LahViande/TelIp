<?php
include_once("include.php");
include_once("header.php");

//ACCESSIBLE UNIQUEMENT PAR L'ADMINISTRATEUR
					
if( $_SESSION['login']!=='admin') 
{
	header('Location: contact.php');
}

if(isset($_SESSION["login"]))
{
	echo"</br>";
	echo"<font color=\"green\">Vous êtes connecté en tant que ".$_SESSION["login"]."</font>";
}
else
{
	header('Location: login.php');
}

?>		
<!--DEBUT DU FORM-->
<form name='forms' method='POST'>
					
<!--TABLEAU POUR AFFICHER LES CLIENTS-->
<table border='3' BORDERCOLOR=black>
<tr>
<td style='background-color:grey'><b>Identifiant<b></td>
<td><b>Société<b></td>
<td><b>MD5<b></td>
</tr>
</br>
</br>

<!--BOUTON REDIRECTION GESTION_USER-->
<input type='submit' name='retour_user' value='Page précédente'>&nbsp;&nbsp;
<!--BOUTON AFFICHAGE FORMULAIRE CREATION CLIENT-->
<input type='submit' name='ajouter' value='Ajouter un client'>&nbsp;&nbsp;
<!--BOUTON VALIDATION SUPRESSION-->
<input type='submit'  name='supprim' value ='Supprimer'>&nbsp;&nbsp;
<!--BOUTON DE DECONNEXION-->
<input type='submit' name='retour_log' value='Deconnexion'>&nbsp;&nbsp;
</br>
</br>

<?php
//REQUETE AFFICHAGE CLIENTS
$requete_gestion_client="select * from client";
$resultat_gestion_client=mysql_query($requete_gestion_client);
					
//BOUCLE AFFICHAGE CLIENTS 
while ($ligne = mysql_fetch_array($resultat_gestion_client))
{ 
	echo"<tr>";
	echo"<td style='background-color:grey'>";
	echo $ligne['id_client'];
	echo"</td>"; 
	echo"<td style='background-color:white'>";
	echo $ligne['société'];
	echo"</td>";
	echo"<td>";
	echo $ligne['id_client_md5'];
	echo"</td>";
	echo"<td>";
	//LIEN VERS FORMULAIRE DE MODIFICATION
	echo"<a href='gestion_client.php?act=modif&id_client=". $ligne['id_client'] . "'><font color='black'>Modifier</a>";
	echo"</td>";
	echo"<td>";
	//CHECKBOX POUR SUPPRESSION DU CLIENT
	echo '<input type="checkbox" name="check[]" value=' . $ligne["id_client"] . '/>';
	echo"</td>";
	echo "</tr>";
}
					
//SI ON APPUIE SUR LE BOUTON AJOUTER UN CLIENT
//AFFICHAGE FORMULAIRE AJOUT CLIENT

if(isset($_POST["ajouter"]))
{
	echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i><u><b><font color='black'>Ajouter une société :</font><b></u></i>";
	echo "<b><label for='id_client'></label><b><input type='hidden' name='id_client'><b><br/>";
	echo "<b><label for='id_client_md5'></label><b><input type='hidden' name='id_client_md5'><b><br/>";
	echo "<b><label for='soc'>Nom société: </label><b><input type='text' name='société'><b><br/>";
	echo"</br>";
	echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='valider_creation' value='Valider'>";
	echo"</br>";
	echo"</br>";
}
					
//SI ON VALIDE LA CREATION
//CREATION D'UN CLIENT
if(isset($_POST["valider_creation"]))
{
	echo"</br>";
	//ASSAISONEMENT
	$GraindeSel="phone";
	$GraindePoivre="book";
							
	$requete_ajout_client="INSERT INTO client (société) VALUES ('".$_POST["société"]."')";
	$resultat_ajout_client=mysql_query($requete_ajout_client);
	$resid=mysql_insert_id();
						
	$requete_maj_client="UPDATE client SET id_client_md5='".md5($GraindeSel.$resid.$GraindePoivre)."' where id_client='".$resid."'";
	$resultat_maj_client=mysql_query($requete_maj_client);
	header ("Location :gestion_client.php");
	echo"</br>";
}

//SI ON APPUIE SUR LE LIEN DE MODIFICATION
//AFFICHAGE DU FORMULAIRE DE MODIFICATION
if($_GET["act"] == "modif")
{	
	echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i><u><b><font color='black'>Modifier une société :</font><b></u></i>";
	$requete_affichage_client="select * from client where id_client=".$_GET['id_client'];
	$resultat_affichage_client=mysql_query($requete_affichage_client)or die( "ERREUR SQL!! ");
	$val1 = mysql_fetch_array($resultat_affichage_client);

	//DEBUT DU FORMULAIRE DE MODIFICATION
	echo"<b><label for='id_client'></label><input type='hidden' name='id_client' value='". $val1['id_client'] . "'>";
	echo"</br>";
	echo"<b><label for='id_client_md5'></label><input type='hidden' name='id_client_md5' value='". $val1['id_client_md5'] . "'>";
	echo"</br>";
	echo"<label for='société'>Nom société:</label><input type='text' name='société' minlength=3 value='". $val1['société'] . "'>";

	$requete_slct_client="SELECT id_client FROM client WHERE id_client='".$_GET['id_client']."'";
	$resultat_slct_client=mysql_query($requete_slct_client);
					
	//BOUCLE AFFICHAGE CLIENT
	while($val = mysql_fetch_array($resultat_slct_client))
	{
		$client[] = $val['id_client'];
	}

	echo"</br>";
	echo"</br>";
	echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='Valider' name='valid_modif'>";
	echo"</br>";
	echo"</br>";
}
					
//MODIFICATION DU NOM DE LA SOCIETE
if(isset($_POST["valid_modif"]))
{ 
	//REQUETE MODIF CLIENT
	$requete_modif_client="UPDATE client SET société ='".$_POST['société']."' WHERE id_client ='".$_GET['id_client']."'";
	$resultat_modif_client=mysql_query($requete_modif_client);			
	header ("Location: gestion_client.php");
}								
//SI ON APPUIE SUR DE VALIDATION DE SUPPRESSION
//SUPRESSION D'UN CLIENT AINSI QUE LE CONTENU LIE 
$check= isset($_POST['check']) ? $_POST['check'] : "";
if (isset($_POST['supprim']))
{
	foreach($check as $check)
	{ 
		if(!empty($check))
		{	
			$requete_delete_contact="DELETE FROM contact WHERE id_contact IN ( SELECT id_contact FROM client_contact WHERE client_contact.id_client='$check')";
			$requete_delete_groupe="DELETE FROM groupe WHERE id_groupe IN ( SELECT id_groupe FROM client_groupe WHERE client_groupe.id_client='$check')";
			$requete_delete_utilisateur="DELETE FROM utilisateur WHERE id_utilisateur IN ( SELECT id_utilisateur FROM client_utilisateur WHERE client_utilisateur.id_client='$check')";
			$requete_delete_client="DELETE FROM client where id_client='$check'";
			
			$resultat_dl_contact=mysql_query($requete_delete_contact);
			$resultat_dl_groupe=mysql_query($requete_delete_groupe);
			$resultat_dl_utilisateur=mysql_query($requete_delete_utilisateur);
			$resultat_dl_client=mysql_query($requete_delete_client);
			header('Location: gestion_client.php');
		}
	}
}
					
//REDIRECTION VERS GESTION_USER
if(isset($_POST["retour_user"]))
{
	header("Location :gestion_user.php");
}
					
//DECONNEXION
if(isset($_POST['retour_log']))
{
	header('Location: login.php');
}
					
//FIN DU FORM
echo"</form>";

?>
