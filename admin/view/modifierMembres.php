<?php
include_once '/view/includes/header.php';
?>
			<div class="contentWrapper changeMember">
				<h1>Modifier <em><?php echo $infoMembre['nomMembre']; ?></em></h1>
				<a href="index.php?section=gestionMembres">Retour</a>
				<?php if($infoMembre!=NULL){ ?>
				<!-- Vérifie que le membre existe -->
				<form method="post">
					<fieldset id="changeMember">
						<p id="info_changeMember">
							<?php if(!empty($message))
							{ echo '<em>'.$message.'</em><br />'; } ?>
						</p>
						<input type="hidden" name="id" value="<?php echo $infoMembre['id']; ?>" />
						<p class="form-field">
						<label for="nom">Nom : </label><input type="text" id="nom" name="nom" value="<?php echo $infoMembre['nomMembre']; ?>"  />
						</p>
						<p class="form-field">
						<label for="prenom">Prénom : </label><input type="text" id="prenom" name="prenom" value="<?php echo $infoMembre['prenomMembre']; ?>" />
						</p>
						<p class="form-field">
						<label for="email">Email : </label><input type="text" id="email" name="email" value="<?php echo $infoMembre['mail']; ?>" disabled />
						</p>
						<p class="form-field">
						<label for="adresse">Adresse : </label><input type="text" id="adresse" name="adresse" value="<?php echo $infoMembre['adresse']; ?>"  />
						</p>
						<p class="form-field">
						<label for="dateNaissance">Date de naissance : </label><input type="text" name="dateNaissance" id="dateNaissance" value="<?php echo $infoMembre['dateNaissance']; ?>"  />
						</p>
						<p class="form-field">
						<label for="telFixe">Téléphone fixe : </label><input type="text" name="telFixe" id="telFixe" value="<?php echo $infoMembre['telFixe']; ?>" />
						</p>
						<p class="form-field">
						<label for="telPortable">Téléphone portable : </label><input type="text" name="telPortable" id="telPortable" value="<?php echo $infoMembre['telPortable']; ?>" />
						</p>
						<p class="form-field">
						<label>Fonction : </label>
						<select>
					<?php 	foreach($infoMembre['fonction'] as $k => $v)
								{ ?>
									<option><?php echo $v['nom']; ?></option>
					<?php		} ?>
						</select>
						<a href="index.php?section=modifierFonctionMembres&amp;id=<?php echo $infoMembre['id']; ?>">Changer les fonctions du membres</a>
						<br></p>
						<p class="form-field">
						<label for="changePassword1">Nouveau mot de passe : </label><input type="password" id="changePassword1" name="password"/>
						</p>
						<p class="form-field">
						<label for="changePassword2">Répéter le mot de passe :</label><input type="password" id="changePassword2" name="password2"/>
						</p>
						<p class="form-field">							
						<label for="isSuperAdmin">Super Administrateur : </label><input type="checkbox" id="isSuperAdmin" name="isSuperAdmin" <?php if($infoMembre['isSuperAdmin']) { echo 'checked'; } ?> />
						</p>
						<input type="submit" value="Enregistrer" />
						<br>
					</fieldset>
				</form>
				<?php } ?>
				<script>
				$("#changePassword1").bind('copy cut paste', function(e) {
				e.preventDefault();
				});
				$("#changePassword2").bind('copy cut paste', function(e) {
				e.preventDefault();
				});
				</script>
			</div>
		</div>
	</body>
</html>