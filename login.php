<?php
include_once("include.php");
?>

<form name='form' method='POST'>
<div name='div' align='middle'>
<input type='hidden' name='id_utilisateur'/>&nbsp;&nbsp;
Login : <input type='text' name='login' placeholder='Login'/>&nbsp;&nbsp;
Mot de passe : <input type='password' name='mdp' placeholder='Mot de passe'/></br></br>

<input type='submit' method='POST' value='Valider' name='valid'/>
</br></br>
</form>

<?php
		
//SI ON APPUIE SUR LE BOUTON DE VALIDATION DE CONNEXION
//CONNEXION 
if(isset($_POST["valid"]))
{		
	//ASSAISONEMENT
	$GraindeSel="57borny";
	$login = $_POST['login']; 
	$recup_caractere = strtolower($login); 
	$recup_caractere = substr("$recup_caractere", 0, 3);
			
	//REQUETE CONNEXION
	$requete_connexion="select * from utilisateur where login = '".$_POST["login"]."' and motdepasse = '".md5($recup_caractere.$_POST["mdp"].$GraindeSel)."'";
	$resultat_connexion=mysql_query($requete_connexion);
		
	if(mysql_num_rows($resultat_connexion)==1)
	{	
		//RECUPERATION D'INFORMATION SUR L'UTILISATEUR QUI SE CONNECTE
		$ligne=mysql_fetch_array($resultat_connexion);
		$_SESSION["id_utilisateur"]=$ligne['id_utilisateur'];
		$_SESSION["login"]=$ligne["login"];
		$_SESSION["email"]=$ligne["email"];
			
		//REQUETE RECUPERATION DE L'ID_CLIENT
		$requete_recup_id_client="select id_client from client_utilisateur where id_utilisateur='".$_SESSION['id_utilisateur']."'";
		$resultat_recup_id_client=mysql_query($requete_recup_id_client);
				
		if(mysql_num_rows($resultat_recup_id_client)==1)
		{
			//RECUPERATION DE L'ID_CLIENT DE L'UTILISATEUR QUI VIENT DE SE CONNECTER
			$lignes=mysql_fetch_array($resultat_recup_id_client);
			$_SESSION["id_client"]=$lignes['id_client'];
		}
			
		//REQUETE RECUPERATION DE L'ID_CLIENT_MD5 
		$requete_recup_id_client_md5="select id_client_md5 from client where id_client='".$_SESSION['id_client']."'";
		$resultat_recup_id_client_md5=mysql_query($requete_recup_id_client_md5);
				
		if(mysql_num_rows($resultat_recup_id_client_md5)==1)
		{
			//REQUETE RECUPERATION DE L'ID_CLIENT_MD5 
			$recup_idmd5=mysql_fetch_array($resultat_recup_id_client_md5);
			$_SESSION["id_client_md5"]=$recup_idmd5['id_client_md5'];
		}
			
		//SI L'UTILISATEUR QUI SE CONNECTE EST :
		//L'ADMIN => ALORS IL EST REDIRIGE VERS GESTION_USER
		if($_POST['login']=='admin')
		{
			header('location: gestion_user.php');
		}
		
		//UN UTILISATEUR LAMBDA => ALORS IL EST REDIREGE VERS CONTACT
		elseif($_POST['login'])
		{
			$log_date=date("Y-m-d H:i:s");
			$requete_login="INSERT INTO log_connexion (log_login,log_date) VALUES ('".$ligne['login']."','$log_date')";
			$resultat_login=mysql_query($requete_login);
			header('Location: contact.php');
		}
		else
		{
			echo "<center><font color='red'>Identifiant ou mot de passe incorrect !</font color></center>";
		}
	}
}
	
include_once("footer.php");
?>

