<!-- Διεπαφή για τη διαχείριση μαθημάτων-->

<?php
	$connection = Database::getConnection() 
	or die ('Αδύναμία σύνδεσης με τη βάση δεδομένων.');
	include 'lesson.php';
		
	//Καθαρισμός των αποτελεσμάτων
	$results='';
	// Αν ο χρήστης πατήσει το κουμπί "Ενημέρωση" στην φόρμα
	if (isset($_POST['save']) AND $_POST['save'] =='Ενημέρωση') {
    
		// Προσθέτει τις φιλτραρισμένες μεταβλητές στον συσχετιζόμενο πίνακα 
		// Η FILTER_SANITIZE_STRING χρησιμοποιείται για να αφαιρέσει τις ετικέτες ή να κωδικοποιήσει τους ειδικούς χαρακτήρες.
		$item  = array (  'les_id' => (int) $_POST['les_id'],
				'les_onoma' => filter_input(INPUT_POST,'les_onoma', FILTER_SANITIZE_STRING),
				'les_perigrafi'  => filter_input(INPUT_POST,'les_perigrafi', FILTER_SANITIZE_STRING)			
		);
		// Ερώτημα ενημέρωσης
		$query = 'UPDATE lesson SET les_onoma=?, les_perigrafi=? WHERE les_id=?';
			$statement = $connection->prepare($query);
			// Δέσμευση των παραμέτρων
			$statement->bind_param('ssi', $item['les_onoma'], $item['les_perigrafi'], $item['les_id']);
			if ($statement) {
				$statement->execute();
				$statement->close();
				// Αν τα στοιχεία περαστούν, προσθέτει μήνυμα επιτυχίας. Αντίθετα προσθέτει μήνυμα αποτυχίας.
				$results = array('', 'Επιτυχής μεταβολή.');
			} 
			else {
				$results = array('', 'Δεν έγινε καμία μεταβολή.', '');
			 }

		}		
	
	if (isset($results['1'])){
		echo '<p class="results">'.$results['1'].'</p>';
	}
	
?>

<div class="rs">
	<?php
	include "lsidebar.php";
	?>

	<center>
		<h3 style="border-bottom:2px solid black;margin-bottom:2px;text-weight:bold;font-size:24px;display:block;width:100%;height:50px;line-height:65px;">Ενημέρωση μαθήματος</h3>
	</center>
</div><!--class rs-->

<div class="row">
	<?php $id = (int) $_GET['id']; ?>
	<div class="col-sm-8 col-md-8 col-lg-8">
		<!--<hr />-->
		<div class="well">
			<!--Φόρμα επεξεργασίας των στοιχείων ενός μαθήματος-->
			<form action="index.php?content=mathimata_edit&id=<?php echo $_GET['id'] ?>" method="post" name="maint" id="maint" enctype="multipart/form-data">
				<?php 
					if(isset($_GET['id'])){
						// Τραβάει τις υπάρχουσες πληροφορίες για το αντικείμενο Lesson
						$item = Lesson::getLesson($_GET['id']);
					}
					else{ 
						echo'Εισάγετε μάθημα:'; 
					}
				?>
				
				<div class="form-group">
					<label for="les_onoma" class="col-sm-4 control-label">* Όνομα: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="les_onoma" name="les_onoma" required autofocus value="<?php echo htmlspecialchars($item->getOnoma()) ?>" />
						<span id="helpBlock" class="help-block text-center">Ενημερώστε το όνομα του μαθήματος</span>
					</div>
				</div>
				
				<div class="form-group">
					<label for="les_perigrafi" class="col-sm-4 control-label">Περιγραφή: </label>
					<div class="col-sm-8">
						<textarea rows="5" cols="50" class="form-control" name="les_perigrafi" id="les_perigrafi" ><?php echo htmlspecialchars($item->getPerigrafi()) ?> </textarea>
						<span id="helpBlock" class="help-block text-center">Ενημερώστε τα ενδιαφέροντα του μαθητή</span>
					</div>
				</div>
								
				<?php
					$salt = 'SomeSalt'; //έλεγχος session
					$token = sha1(mt_rand(1,1000000) . $salt); 
					$_SESSION['token'] = $token;
				?>

				<input type="hidden" name="les_id" id="les_id" value="<?php echo $_GET['id'];?>" />
				
				<input type="hidden" name='token' value="<?php echo $token; ?>"/>
				<span id="helpBlock" class="help-block text-center" style="font-weight: bold;">Τα πεδία με το αστεράκι (*) είναι υποχρεωτικά.</span><br>
				<center>
					<input type="submit" name="save" style="width: 30%;" value="Ενημέρωση" class="btn btn-block btn-success" />
					<a style="width: 30%;" class="btn btn-block btn-primary cancel" href="index.php?content=mathimata_maint">Άκυρο</a>
				</center>
			</form>
		</div>
	</div>
	<div class="clearfix visible-sm-block"></div>
	<div class="clearfix visible-md-block"></div>
	<div class="clearfix visible-lg-block"></div><!--τέλος clearfix -->
</div>