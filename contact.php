<?php
include_once("include.php");

include_once("header.php");

echo"</br>";
if(isset($_SESSION["login"]))
{
	echo"<font color=\"green\">Vous êtes connecté en tant que ".$_SESSION["login"]."</font>";
	echo"</br>";
	echo"<font color=\"green\">Votre hachage est  ".$_SESSION["id_client_md5"]."</font>";
}
	else
	{
	header('Location: login.php');
	}
?>
<br> 
<br>
<b><span style='border:1px solid black;padding:0%'>RECHERCHE</span></b>
<hr>
<form name="frm_recherche" action="listing.php" method="post">
<input type="text" name="recherche" />
<input type="submit" name="smt_recherche" value="Rechercher" />
</form>
	
<?php

//DEBUT FORMULAIRE D'AJOUT DE CONTACT
echo"<form name='formulaire_champsvide' method='POST'>";
echo"<b><span style='border:1px solid black;padding:0%'>CONTACT</span></b>";
echo"</br>";
echo "<hr size='3' color='black'>";
echo "<b>";
echo "<label for='nom'>Nom : </label><b><input type='text' name='nom' ><b><br/>";
echo "<label for='prenom'>Prenom : </label><b><input type='text' name='prenom' ><b><br/>";
echo "<label for='pays'>Pays : </label><b><input type='text' name='pays' ><b><br/>";
echo "Favorite : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>oui<input type='radio' name='favori' value='1'><b>non<input type='radio' name='favori' value='0' checked><b><br/>";
echo "<label for='num_travail'>Numero Travail: </label><b><input type='text' name='num_travail'<b><br />";
echo "<label for='num_domicile'>Numero Domicile : </label><b><input type='text' name='num_domicile'><b><br />";
echo "<label for='num_mobile'>Numero Mobile : <b></label><input type='text' name='num_mobile'><b><br />";
echo "</br>";
//FIN DU FORMULAIRE DE L'AJOUT DE CONTACT

//REQUETE JOINTURE GROUPE CLIENT_GROUPE
$requete_groupe_clientgroupe="select * from groupe inner join client_groupe ON groupe.id_groupe=client_groupe.id_groupe where client_groupe.id_client='".$_SESSION['id_client']."'";
$resultat_groupe_clientgroupe=mysql_query($requete_groupe_clientgroupe);

//TANT QUE POUR RECUPERER L'ID GROUPE ET LE NOM DU GROUPE
while($ligne= mysql_fetch_array($resultat_groupe_clientgroupe))
{
	echo"<td><input type='checkbox' name='checkb[]' value='".$ligne['id_groupe']."'>".$ligne['nom_groupe']."</td>";
}	
echo"</form>";
?>
</br>
</br>
<form name='formulaire_affichage' method='POST'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='ajouter' value='Ajouter un contact'>&nbsp;&nbsp;<input type='submit' name='aff' value='Afficher les contacts'>
<hr size='3' color='black'>
<span style='border:1px solid black;padding:0%'>GROUPE</span>
</br>
</br>
Nom du groupe : <b><input type='text' name='ngroupe'><b><br/>
</br>
</br>
<input type='submit' name='grp' value='Créer un groupe'>&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;<input type='submit' name='affgr' value='Afficher les groupes'>

</br>
</br>
<?php

//REDIRECTION VERS LISTING.PHP
if(isset($_POST["aff"]))
	{
		header('Location:listing.php');
	}

//TABLEAU REDIRECTION GROUPE.PHP
if(isset($_POST["affgr"]))
	{	
		header('Location: groupe.php');
	}
							
//AJOUTER UN CONTACT
//REQUETE INSERT INTO
if(isset($_POST["ajouter"]))
{
	$requete_contact_insert="INSERT INTO contact (nom,prenom,pays,favori,num_travail,num_domicile,num_mobile) VALUES ('".$_POST["nom"]."','".$_POST["prenom"]."','".$_POST["pays"]."','".$_POST["favori"]."','".$_POST["num_travail"]."','".$_POST["num_domicile"]."','".$_POST["num_mobile"]."')";
	$resultat_contact_insertion=mysql_query($requete_contact_insert);
	$resid=mysql_insert_id();
	$client_contact="INSERT INTO client_contact (id_contact,id_client) VALUES('".$resid."','".$_SESSION['id_client']."')";
	$resultat_contact=mysql_query($client_contact); 

	//INSERTION DES ID_CONTACT ET ID_GROUPE
	foreach($_POST['checkb'] AS $value)
	{
		$contact_groupe_insert="INSERT INTO contact_groupe (id_contact,id_groupe) VALUES('".$resid."','".$value."')";
		$resultat_contact_groupe_insert=mysql_query($contact_groupe_insert); 	
	}
		echo "<script>alert('Le contact a été ajouté avec succès')</script>";
}
//FIN AJOUT CONTACT

//REQUETE POUR AJOUTER UN GROUPE
if(isset($_POST["grp"]))
{
	$requete_groupe_insert="INSERT INTO groupe (nom_groupe) VALUES ('".$_POST["ngroupe"]."')";
	$resultat_groupe_insert=mysql_query($requete_groupe_insert);
	$resid=mysql_insert_id();
	$client_groupe="INSERT INTO client_groupe (id_client,id_groupe) VALUES('".$_SESSION['id_client']."','".$resid."')";
	$resultat_groupe=mysql_query($client_groupe); 
	header('Location: contact.php');
}
echo"</form>";
?>
<?php
include_once("footer.php");
?>