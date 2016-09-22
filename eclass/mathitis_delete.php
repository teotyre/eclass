<!-- Διεπαφή για τη διαγραφή ενός μαθητή-->
<html>
	<body>
		<?php
		$id = (int)$_GET['id'];
		if (isset($_GET['id'])) {
			// Τραβάει τις υπάρχουσες πληροφορίες για τον μαθητή από την αντίστοιχη κλαση
			$item = Mathitis::getMathitis($_GET['id']);
		} else {
			echo 'Εισάγετε μαθητή:';
		}
		?>
		<div class="rs">
			<?php

			include "lsidebar.php";
			?>

			<center>
				<h3 style="border-bottom:2px solid black;margin-bottom:2px;
				text-weight:bold;font-size:24px;
				display:block;width:100%;height:50px;line-height:65px;">Διαγραφή μαθητή</h3>
			</center>

		</div><!--class rs-->
		<div class="row">
			<div class="col-sm-7 col-md-7 col-lg-7">
				<!--<hr />-->
				<div > <!--φόρμα που εμφανίζει τα στοιχεία του μαθητή προς διαγραφή -->
					<form action="index.php?content=mathites_maint&task=delete" method="post" name="maint" id="maint" enctype="multipart/form-data">

						<div class="form-group">
							<label for="math_username" class="col-sm-4 control-label">Όνομα χρήστη: </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="math_username" name="math_username" value="<?php echo $item->getUsername() ?>"/>

							</div>
						</div>

						<div class="form-group">
							<label for="math_password" class="col-sm-4 control-label">Κωδικός χρήστη: </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="math_password" name="math_password" value="<?php echo $item->getPassword() ?>" />

							</div>
						</div>

						<div class="form-group">
							<label for="math_onoma" class="col-sm-4 control-label">Όνομα: </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="math_onoma" name="math_onoma" value="<?php echo $item->getOnoma() ?>" />

							</div>
						</div>

						<div class="form-group">
							<label for="math_eponymo" class="col-sm-4 control-label">Επώνυμο: </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="math_eponymo" name="math_eponymo" value="<?php echo $item->getEponymo() ?>" />

							</div>
						</div>

						<div class="form-group">
							<label for="math_endiaferonta" class="col-sm-4 control-label">Ενδιαφέροντα: </label>
							<div class="col-sm-8">
								<textarea rows="5" cols="50" class="form-control" name="math_endiaferonta" id="math_endiaferonta" ><?php echo htmlspecialchars($item->getEndiaferonta()) ?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label for="math_birthday" class="col-sm-4 control-label">Ημ. Γέννησης: </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="math_birthday" name="math_birthday" value="<?php echo htmlspecialchars($item->getBirthday()) ?>" />

							</div>
						</div>

						<div class="form-group">
							<label for="math_diamoni" class="col-sm-4 control-label">Τόπος Διαμονής: </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="math_diamoni" name="math_diamoni" value="<?php echo $item->getDiamoni() ?>" />

							</div>
						</div>

						<div class="form-group">
							<label for="math_photo" class="col-sm-4 control-label">Φωτογραφία: </label>
							<div class="col-sm-8">
								<input type="hidden" name="MAX_FILE_SIZE" value="15000000">
								<img src="<?php echo $item->getPhoto() ?>" style="width:64px;height:64px" alt="Δεν υπάρχει φωτογραφία." >

							</div>
						</div>

						<?php // έλεγχος session
						$salt = 'SomeSalt';
						$token = sha1(mt_rand(1, 1000000) . $salt);
						$_SESSION['token'] = $token;
						?>
						<input type="hidden" name="math_id" id="math_id" value="<?php echo $_GET['id']; ?>" />
						<input type="hidden" name='token' value="<?php echo $token; ?>"/>
						<span id="helpBlock" class="help-block text-center" style="font-weight: bold;">Ο μαθητής θα διαγραφεί! Είστε σίγουρος?</span>
						<br>

						<center>
							<input type="submit" name="delete" style="width: 30%;" onclick="return confirm('Ο μαθητής θα διαγραφεί! Είστε σίγουρος;')" value="Διαγραφή" class="btn btn-block btn-success" />

							<script type="text/javascript">
								//javascript για επιβεβαίωση διαγραφής.
								var elems = document.getElementsByClassName('confirmation');
								var confirmIt = function(e) {
									if (!confirm('Ο μαθητής θα διαγραφεί! Είστε σίγουρος;'))

										e.preventDefault();

								};

								for (var i = 0,
								    l = elems.length; i < l; i++) {
									elems[i].addEventListener('click', confirmIt, false);
								}
							</script>
							<h6></h6>
							<a style="width: 30%;" class="btn btn-block btn-primary cancel" href="index.php?content=mathites_maint">Άκυρο</a>
						</center>
					</form>
				</div>
			</div>
			<div class="clearfix visible-sm-block"></div>
			<div class="clearfix visible-md-block"></div>
			<div class="clearfix visible-lg-block"></div><!--τέλος clearfix-->
		</div>
	</body>
</html>