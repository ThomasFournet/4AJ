<?php
include_once '/view/includes/header.php';
?>
			<div class="contentWrapper fonction">
				<script type="text/javascript">
				function changerFonction(type, id)
				{	/*Fonction redirige sur la même page en mettant les paramètres en GET */
					javascript:location.href='index.php?section=fonction&type='+type+'&id='+id;
				}
				</script>
				<h1>Fonctions des membres</h1>
				<p>
					<em>-Vous pouvez modifier les droits de chaque fonction en cliquant sur les cases (les super administrateurs ont eux tous les droits).</em><br>
					<em>-Assignez ou supprimez des fonctions aux membres en cliquant sur l'intitulé de la fonction (dans la colonne "Nom fonction").</em><br>
					<em>-Toute personne qui s'inscrit sur le site reçoit la fonction "public" par défaut.</em><br>
					<em>-Un membre peut cumuler plusieurs fonctions à la fois, on ne peut pas lui retirer la fonction "public"</em><br>
				</p>
				<table>
					<tr>
						<th>
							Nom Fonction
						</th>
						<th>
							Admin Livre Or
						</th>
						<th>
							Admin Actualité
						</th>
						<th>
							Admin Repas
						</th>
						<th>
							Autorisation manger midi
						</th>
						<th>
							Autorisation manger soir et week end
						</th>
					</tr>
					<?php
					foreach ($allFonction as $key => $value) { ?>
					<tr>
						<td>
							<div>
								<a href="index.php?section=fonction&amp;fonction=<?php echo $value['id']; ?>">
									<!-- Ceci permettra d'afficher les membres de la fonction -->
									<?php echo $value['nom']; ?>
								</a>
							</div>
						</td>	
						<!-- Création du tableau affichant, ça affiche la couleur, et pouvant être cliqué en utilisant la fonction changerFonction -->
						<td onclick="changerFonction(2, <?php echo $value['id'] ?>);" <?php if($value['isAdminLivreOr']) { echo 'class="true"><img src="view/graphicRessources/true.png" alt="true"/>'; } else { echo 'class="false"><img src="view/graphicRessources/false.png" alt="false"/>'; } ?>
						</td>
						<td onclick="changerFonction(3, <?php echo $value['id'] ?>);" <?php if($value['isAdminActualite']) { echo 'class="true"><img src="view/graphicRessources/true.png" alt="true"/>'; } else { echo 'class="false"><img src="view/graphicRessources/false.png" alt="false"/>'; } ?>
						</td>
						<td onclick="changerFonction(4, <?php echo $value['id'] ?>);" <?php if($value['isAdminRepas']) { echo 'class="true"><img src="view/graphicRessources/true.png" alt="true"/>'; } else { echo 'class="false"><img src="view/graphicRessources/false.png" alt="false"/>'; } ?>
						</td>	
						<td onclick="changerFonction(5, <?php echo $value['id'] ?>);" <?php if($value['autorisationMangerMidi']) { echo 'class="true"><img src="view/graphicRessources/true.png" alt="true"/>'; } else { echo 'class="false"><img src="view/graphicRessources/false.png" alt="false"/>'; } ?>
						</td>
						<td onclick="changerFonction(6, <?php echo $value['id'] ?>);" <?php if($value['autorisationMangerSoir']) { echo 'class="true"><img src="view/graphicRessources/true.png" alt="true"/>'; } else { echo 'class="false"><img src="view/graphicRessources/false.png" alt="false"/>'; } ?>
						</td>

						<?php if($value['id'] != 1) { ?>
						<!-- On ne peut pas supprimer la fonction "public" -->
						<td>
							<a href="index.php?section=fonction&amp;delete=<?php echo $value['id']; ?>" onclick="return(confirm('Attention ! Si vous supprimez la fonction, cette fonction sera retirée de tous les membres qui la possèdent. Voulez-vous continuer ?'))">Supprimer</a>
						</td>
						<?php }
						else {?>
						<td>
							<p id="impossible">Supprimer</p>
						</td>
						<?php } ?>
					</tr>
			<?php	} ?>
				</table>
				<form method="post">
					<!-- Ajout d'une nouvelle fonction -->
					<label for="nom">Ajouter une nouvelle fonction : </label><input type="text" name="nom" id="nom" />
					<input type="submit" />
				</form>
				<hr />
				<?php if(!empty($_GET['fonction']) && !empty($allFonction[$_GET['fonction']]['nom']))
					// Si l'utilisateur à choisi une fonction, on affiche la liste des membres 
				{ ?>
					<h3>
						Fonction <?php echo $allFonction[$_GET['fonction']]['nom']; ?>
					</h3>
			<?php	if(!empty($allMembreIn)) 
					{ ?>
						<!-- Si il ya des membres dans la fonction, on les affiches -->
						<h4>Liste des membres</h4>
						<ul>
							<?php foreach ($allMembreIn as $key => $value) { ?>
							<!-- On affiche la liste des membres -->
							<li><?php echo $value['nom']; ?>
								<?php if($_GET['fonction'] != 1) { ?>
								<!-- On ne peut supprimer un membre d'une fonction que si ce n'est pas la fonction public -->
								, <a href="index.php?section=fonction&amp;fonction=<?php echo $_GET['fonction']; ?>&amp;supprimerMembre=<?php echo $value['id']; ?>">Supprimer</a></li>
								<?php } ?>
					<?php	} ?>
						</ul>
						<p>	
							<em>Page : 
								<?php 
								$j = 1;
								for($j; $j <= $nbrePageIn; $j++) 
								{ ?>
									<?php if($j == $pageSupprimer) { echo '<b>'; } ?>
									<a href="index.php?section=fonction&amp;fonction=<?php echo $_GET['fonction']; ?>&amp;pageSupprimer=<?php echo $j; ?>"><?php echo $j; ?></a> 
									<?php if($j == $pageSupprimer) { echo '</b>'; } ?>
						<?php 	} ?>
							</em>
						</p>
			<?php 	} 
					/* FIN DE SI */
					/* --------- */
					if(!empty($allMembreNotInFonction))
						// Si il reste des membres qui ne sont pas dans la fonction, on propose de les ajouters 
					{ ?>
						<h4>Ajouter un membre</h4>
						<ul>
							<?php foreach ($allMembreNotInFonction as $key => $value) {  ?>
								<li>
									<?php echo $value['nom']; ?>
									, <a href="index.php?section=fonction&amp;fonction=<?php echo $_GET['fonction']; ?>&amp;ajouterMembre=<?php echo $value['id']; ?>">Ajouter</a>
								</li>
							<?php } ?>
						</ul>
						<p>	
							<em>Page : 
								<?php 
								$j = 1;
								for($j; $j <= $nbrePageNotIn; $j++) 
								{ ?>
									<?php if($j == $pageAjouter) { echo '<b>'; } ?>
									<a href="index.php?section=fonction&amp;fonction=<?php echo $_GET['fonction']; ?>&amp;pageAjouter=<?php echo $j; ?>"><?php echo $j; ?></a> 
									<?php if($j == $pageAjouter) { echo '</b>'; } ?>
						<?php 	} ?>
							</em>
						</p>
			<?php 	} 
				} ?>
			</div>
		</div>
	</body>
</html>