<form name='forms' method='GET'>

<?php
// connexion à la base de données
$mysql = mysql_connect("localhost","dev","dev");
mysql_select_db("dev", $mysql);

//REQUETE 
$requette_md5="select id_client from client where id_client_md5='".$_GET['id']."'";
$resultat_requette_md5 = mysql_query($requette_md5);
$tableau_resultat_requette_md5[] = mysql_fetch_assoc($resultat_requette_md5);

$generation_xml ="select * from contact inner join client_contact ON contact.id_contact=client_contact.id_contact where client_contact.id_client='".$tableau_resultat_requette_md5[0]['id_client']."' ";
$generation_xml_groupe ="select * from groupe inner join client_groupe ON groupe.id_groupe=client_groupe.id_groupe where client_groupe.id_client='".$tableau_resultat_requette_md5[0]['id_client']."' ";

//EXECUTION DES REQUETES
$resultat_groupe = mysql_query($generation_xml_groupe) or exit(mysql_error());
$resultat_contact = mysql_query($generation_xml) or exit(mysql_error());

$xml = '<?xml version="1.0" encoding="UTF-8"?>';
$xml .= '<AddressBook>';
$xml .='<version>1</version>';

//BOUCLES
while( $ligne = mysql_fetch_assoc($resultat_groupe) )
{
	$xml .='<pbgroup>';
	$xml .= '<id>' .($ligne['id_groupe']) . '</id>';
	$xml .= '<name>' .($ligne['nom_groupe']) . '</name>';
	$xml .='</pbgroup>';
}

while( $ligne = mysql_fetch_assoc($resultat_contact) )
{
	$generation_xml_idgroupe="SELECT id_groupe from contact_groupe WHERE contact_groupe.id_contact=".$ligne['id_contact']."";
	$resultat_idgroupe= mysql_query($generation_xml_idgroupe) or exit(mysql_error());
	
	$xml .='<Contact>';
	$xml .= '<FirstName>' .($ligne['prenom']) . '</FirstName>';
	$xml .= '<LastName>' .($ligne['nom']) . '</LastName>';
	$xml .='<Frequent>'.$ligne['favori'].'</Frequent>';
	$xml .='<Phone type="Work">';
	$xml .='<phonenumber>'.$ligne['num_travail'].'</phonenumber>';
	$xml .='<accountindex>1</accountindex>';
	$xml .='</Phone>';
	$xml .='<Phone type="Home">';
	$xml .='<phonenumber>'.$ligne['num_domicile'].'</phonenumber>';
	$xml .='<accountindex>1</accountindex>';
	$xml .='</Phone>';
	$xml .='<Phone type="Mobile">';
	$xml .='<phonenumber>'.$ligne['num_mobile'].'</phonenumber>';
	$xml .='<accountindex>1</accountindex>';
	$xml .='</Phone>';
	$xml .='<downloaded>1</downloaded>';
	
	while( $ligne = mysql_fetch_assoc($resultat_idgroupe) )
	{
		$xml .= '<Group>'.$ligne['id_groupe'].'</Group>';
	}
	$xml .='</Contact>';
}

$xml .= '</AddressBook>';

//FONCTION QUI PERMET D'ECRIRE DANS UN FICHIER PHONEBOOK.XML
file_put_contents ('phonebook.xml', $xml);

//AFFICHAGE DU FICHIER XML

echo $xml;
header('Refresh: 3; listing.php');

?>
</form>