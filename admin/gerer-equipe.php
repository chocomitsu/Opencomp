<?php

/*
 * ========================================================================
 * Copyright (C) 2010 Traullé Jean
 *
 * This file is part of Gnote.
 *
 * Gnote is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with Gnote. If not, see <http://www.gnu.org/licenses/>
 * ========================================================================
 */

require_once("../core/init.php");

$extrajavascript = <<<extrajavascript
<script type='text/javascript'>

			function MajusculeEnDebutDeChaine(Obj){
				chaine=Obj.value
					Obj.value=chaine.substr(0,1).toUpperCase()+	chaine.substr(1,chaine.length).toLowerCase()}

			var check1;
				window.onload = function()
				{
					check1 = new CheckForm("form_ajouter_enseignant");
					check1.addReg("nom","text","alpha","blur","Vous devez compléter ce champ avec des lettres",[1,20]);
					check1.addReg("prenom","text","alpha","blur","Vous devez compléter ce champ avec des lettres",[1,20]);
					check1.addReg("email","text","email","blur","Vous devez inscrire une adresse de type adresse@fournisseur.tld",[1,50]);
					check1.addReg("identifiant","text","alphanum","blur","Vous devez compléter ce champ avec des chiffres et des lettres",[1,20]);
					check1.addReg("motdepasse","password","required","blur","Vous devez compléter ce champ au moins 5 caractères",[5,20]);
					check1.addReg("motdepasse2","password","required","blur","Les mots de passe ne correspondent pas",'','',['motdepasse']);
				}


 			</script>
extrajavascript;

printHead('Gérer l\'équipe éducative', 'admin', 'extrajavascript', $dbprefixe);

?>

<h2>Gérer l'équipe éducative</h2>

<?php
/*****************************************************************
 * Si l'utilisateur a demandé gerer-equipe.php?ajouter_enseignant *
 ****************************************************************/

if (isset($_GET['ajouter_enseignant']))
{
				?>
				<h3>Ajouter des enseignants à l'équipe éducative</h3>
				<p>Saisissez les informations concernant l'enseignant que vous souhaitez ajouter à l'équipe éducative.</p>

				<form method="post" id="form_ajouter_enseignant" action="gerer-equipe.php?ajouter_enseignant">
				<table>
					<tr>
						<td style="text-align:right;"><label for="nom">Nom :</label></td>
						<td><input type="text" size="25" name="nom" id="nom" style="border:1px solid #cacaca; " OnKeyUp="javascript:this.value=this.value.toUpperCase();" /><br /></td>
					</tr>
					<tr>
						<td style="text-align:right;"><label for="prenom">Prénom :</label></td>
						<td><input type="text" size="25" name="prenom" id="prenom" style="border:1px solid #cacaca;" OnKeyUp="MajusculeEnDebutDeChaine(this)" /></td>
					</tr>
					<tr>
						<td style="text-align:right;"><label for="pseudo">Email :</label><br /><br /></td>
						<td><input type="text" size="37" name="email" id="email" style="border:1px solid #cacaca;" /><br /><br /></td>
					</tr>
					<tr>
						<td style="text-align:right;"><label for="identifiant">Identifiant :</label></td>
						<td><input type="text" name="identifiant" id="identifiant" style="border:1px solid #cacaca;" /></td>
					</tr>
					<tr>
						<td style="text-align:right;"><label for="motdepasse">Mot de passe :</label></td>
						<td><input type="password" name="motdepasse" id="motdepasse" style="border:1px solid #cacaca;" /></td>
					</tr>
					<tr>
						<td style="text-align:right;"><label for="motdepasse2">Confirmez le&nbsp;&nbsp;<br />mot de passe :</label></td>
						<td><input type="password" name="motdepasse2" id="motdepasse2" style="border:1px solid #cacaca;" /></td>
					</tr>
				</table>
				<input type="submit" id="ajouter_un_enseignant" name="ajouter_un_enseignant" value="Ajouter cet enseignant" style="margin-top:15px; margin-bottom:15px;"/>
				</form>
				<?php
}
else
{
	echo '<input type="button" value="Ajouter des enseignants" title="Ajouter des enseignants" class="ajouter" onclick="window.location=\'gerer-equipe.php?ajouter_enseignant\';"></input>';
}

if (isset ( $_POST['ajouter_un_enseignant'] ))
{
	
	//On définit un grain de sel pour l'utilisateur aléatoirement et on hâche le mot de passe.
	$graindesel = rand();
	$hashmotdepasse = sha1(mysql_real_escape_string($_POST['motdepasse']).$graindesel);

	$nom = mysql_real_escape_string($_POST['nom']);
	$prenom = mysql_real_escape_string($_POST['prenom']);
	$identifiant = mysql_real_escape_string($_POST['identifiant']);
	$email = mysql_real_escape_string($_POST['email']);

	//On ajoute le nouvel utilisateur à la BDD
	
	mysql_query("INSERT INTO ".$dbprefixe."enseignant (nom, prenom, identifiant, mot_de_passe, email, salt) VALUES ('$nom', '$prenom', '$identifiant', '$hashmotdepasse', '$email', '$graindesel');");
	
	echo 'L\'utilisateur a été correctement ajouté !';

}

printFooter();

?>
