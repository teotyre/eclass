<!-- Διεπαφή για τη διαχείριση των μαθητών-->
<!doctype html>
<html >
   <head>
      <meta charset="utf-8">
      <!--Links για τα απαραίτητα στοιχεία JQuery (Javascript framework) για την επιλογή της ημερομηνίας-->
      <link href="http://code.jquery.com/ui/1.10.4/themes/flick/jquery-ui.css" rel="stylesheet">
      <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
      <script src="datepicker.js"></script>
      <!-- Javascript -->
      <script>
         $(function() {
            $( "#datepicker" ).datepicker({
               changeMonth:true,
               changeYear:true,
               dateFormat:"yy-mm-dd",
                });
         });
      </script>
   </head>
   <body>
<!-- HTML --> 
      
<?php
	$connection = Database::getConnection() 
	or die ('Αδύναμία σύνδεσης με τη βάση δεδομένων.');
	include 'mathitis.php';

	//Καθαρισμός των αποτελεσμάτων
	$results='';

	// Αν ο χρήστης πατήσει "Αποθήκευση" στη φόρμα
	if (isset($_POST['save']) AND $_POST['save'] =='Αποθήκευση') {
		
		if($_FILES["fileToUpload"]["name"]!=NULL) //κάνει upload τη φωτογραφία του μαθητή στο φάκελο /uploads αν έχει επιλεγεί
		{
		include "upload_photo.php";
		// Προσθέτει τις φιλτραρισμένες μεταβλητές στον συσχετιζόμενο πίνακα 
		// Η FILTER_SANITIZE_STRING χρησιμοποιείται για να αφαιρέσει τις ετικέτες ή να κωδικοποιήσει τους ειδικούς χαρακτήρες.
		$item  = array (  'math_id' => (int) $_POST['math_id'],
				'math_username'  => filter_input(INPUT_POST,'math_username', FILTER_SANITIZE_STRING),
				'math_password' => filter_input(INPUT_POST,'math_password', FILTER_SANITIZE_STRING),
				'math_onoma' => filter_input(INPUT_POST,'math_onoma', FILTER_SANITIZE_STRING),
				'math_eponymo'  => filter_input(INPUT_POST,'math_eponymo', FILTER_SANITIZE_STRING),
				'math_endiaferonta'  => filter_input(INPUT_POST,'math_endiaferonta', FILTER_SANITIZE_STRING),				
				'math_birthday' => $_POST['math_birthday'],
				'math_diamoni' => filter_input(INPUT_POST,'math_diamoni', FILTER_SANITIZE_STRING),
				'math_photo' => $target_file
				);
		}
		
		else{ //διαφορετικά αν δεν υπάρχει φωτογραφία
			$item  = array (  'math_id' => (int) $_POST['math_id'],
						'math_username'  => filter_input(INPUT_POST,'math_username', FILTER_SANITIZE_STRING),
						'math_password' => filter_input(INPUT_POST,'math_password', FILTER_SANITIZE_STRING),
						'math_onoma' => filter_input(INPUT_POST,'math_onoma', FILTER_SANITIZE_STRING),
						'math_eponymo'  => filter_input(INPUT_POST,'math_eponymo', FILTER_SANITIZE_STRING),
						'math_endiaferonta'  => filter_input(INPUT_POST,'math_endiaferonta', FILTER_SANITIZE_STRING),				
						'math_birthday' => $_POST['math_birthday'],
						'math_diamoni' => filter_input(INPUT_POST,'math_diamoni', FILTER_SANITIZE_STRING)
						
				);
		}
		
		// Δημιουργεί ένα αντικείμενο Mathiti με βάση τα POST.
		$mathitis = new Mathitis($item);
		if ($mathitis->getId()) {
			$results = $mathitis->add();
			// μηνύματα με javascript
			echo "
            <script type=\"text/javascript\">
            alert('Ο μαθητής προσθέθηκε με επιτυχία!');
            </script>
        ";
			
		}
		else {
      		echo "
            <script type=\"text/javascript\">
            alert('Σφάλμα.');
            </script>
        ";
      }
	}

	// Διαγραφή της εγγραφής ενός μαθητή από τη βάση δεδομένων
	// Τραβάει τα στοιχεία από τη φόρμα που υπάρχει στο αρχείο mathitis_delete.php	

	// Αν ο χρήστης πατήσει "Διαγραφή" της φόρμας
	if (isset($_POST['delete']) AND $_POST['delete'] =='Διαγραφή') {	
		// Το task ελέγχει ποια διαδικασία ακολουθείται. Εδώ γίνεται διαγραφή μαθητή.
		if ($_GET['task']=='delete'){
			// Σύνδεση με τη βάση δεδομένων
			//Αν υπάρχει ήδη φωτογραφία τότε τη διαγράφει
			$query5 = "SELECT math_photo FROM mathitis WHERE math_id='$_POST[math_id]' "; 
			$result5 = $connection->query($query5);
			$row5 = $result5->fetch_array();
			$link = $row5[math_photo];
			  
			  if($link!=""){
			  unlink($link);   //διαγραφή φωτογραφίας
			  }
					
			  // Δημιουργία ερωτήματος (query)
			  $id=filter_input(INPUT_POST,'math_id', FILTER_SANITIZE_STRING);
			  $query = 'DELETE FROM parakolouthei WHERE mathiti_math_id="'.$id.'"';
			  $result = $connection->query($query);
			  $query = 'DELETE FROM mathitis WHERE math_id="'.$id.'"';
			  // Τρέχει το query που δημιούργησε
			  if ($result = $connection->query($query)) { 
			  // Αν τα στοιχεία διαγραφούν, προσθέτει μήνυμα επιτυχίας. Αντίθετα προσθέτει μήνυμα αποτυχίας.
			  
			  $results = array('', 'Επιτυχής διαγραφή.', '');

			  } else {
				$results = array('', 'Δεν έγινε καμία διαγραφή.', '');

			  }
		}
	}  
	if (isset($results['1'])){
		echo '<p class="results">'.$results['1'].'</p>';
	}

	
?>

<!-- Προβολή αποτελεσμάτων για το διαχειριστή -->
<div class="row">
	<?php
	include "lsidebar.php";
	?>
	<div class="col-sm-8 col-md-8 col-lg-8">
		<center><h3>Καταχωρημένοι μαθητές</h3></center>
		<hr />
		<div class="well">
			<ul class="ulfancy">
				<?php 
			  	// Προβάλλει όλους τους καταχωρημένους μαθητές
			  	$items = Mathitis::getMathites();
			  	if (!$items)
			  	echo 'Δεν βρέθηκαν αποτελέσματα';
			  	else{
			  		foreach ($items as $i=>$item) : ?>
					    <li class="row<?php echo $i % 2; ?>">
					    	<!-- Εμφανίζεται δυναμικά μια λίστα με τους καταχωρημένους μαθητές στο διαχειριστή και τις επιλογές της "Επεξεργασία" και της "Διαγραφής"-->
					    	<?php echo htmlspecialchars($item->getEponymo()." ".$item->getOnoma()); ?>
					    	<div><span><a class="btn" href="index.php?content=mathitis_edit&id=<?php echo $item->getId(); ?>" >Επεξεργασία</a></span>
					    		<span><a class="btn" href="index.php?content=mathitis_delete&id=<?php echo $item->getId(); ?>" >Διαγραφή</a></span>
					    		<span><a class="btn" href="index.php?content=mathitis_anathesi&id=<?php echo $item->getId(); ?>" >Διαχείριση μαθημάτων που παρακολουθεί...</a></span>
					    	</div>
					    </li>
			  <?php endforeach; }?>
			</ul>
			<hr />
			<center><h3>Προσθήκη μαθητή</h3></center>
			<hr />
			<form action="index.php?content=mathites_maint&task=add" method="post" name="maint" id="maint" enctype="multipart/form-data">
				
				<div class="form-group">
					<label for="math_username" class="col-sm-4 control-label">* Όνομα χρήστη: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="math_username" name="math_username" required autofocus value=""/>
						<span id="helpBlock" class="help-block text-center">Εισάγετε όνομα χρήστη</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_password" class="col-sm-4 control-label">* Κωδικός χρήστη: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="math_password" name="math_password" required autofocus value="" />
						<span id="helpBlock" class="help-block text-center">Εισάγετε τον κωδικό χρήστη</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_onoma" class="col-sm-4 control-label">* Όνομα: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="math_onoma" name="math_onoma" required autofocus value="" />
						<span id="helpBlock" class="help-block text-center">Εισάγετε το όνομα του μαθητή</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_eponymo" class="col-sm-4 control-label">* Επώνυμο: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="math_eponymo" name="math_eponymo" required autofocus value="" />
						<span id="helpBlock" class="help-block text-center">Εισάγετε το επώνυμο του μαθητή</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_endiaferonta" class="col-sm-4 control-label">Ενδιαφέροντα: </label>
					<div class="col-sm-8">
						<textarea rows="5" cols="50" class="form-control" name="math_endiaferonta"  id="math_endiaferonta" value=""></textarea>
						<span id="helpBlock" class="help-block text-center">Εισάγετε τα ενδιαφέροντα του μαθητή</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_birthday" class="col-sm-4 control-label">Ημ. Γέννησης: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="datepicker" name="math_birthday"  value="" />
						<span id="helpBlock" class="help-block text-center">Εισάγετε την ημερομηνία γέννησης του μαθητή</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_diamoni" class="col-sm-4 control-label">Τόπος Διαμονής: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="math_diamoni" name="math_diamoni" value="" />
						<span id="helpBlock" class="help-block text-center">Εισάγετε τον τόπο διαμονής του μαθητή</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_photo" class="col-sm-4 control-label">Φωτογραφία: </label>
					<div class="col-sm-8">
						<input type="hidden" name="MAX_FILE_SIZE" value="15000000">
						<input type="file" name="fileToUpload" id="fileToUpload" value="" >
						
						
						<span id="helpBlock" class="help-block text-center">Ανεβάστε μια φωτογραφία του μαθητή</span>
					</div>
				</div>
				
				<?php // έλεγχος session
					$salt = 'SomeSalt';
					$token = sha1(mt_rand(1,1000000) . $salt); 
					$_SESSION['token'] = $token;
				?>

				<input type="hidden" name="math_id" id="math_id" value="1" />
				<input type="hidden" name='token' value="<?php echo $token; ?>"/>
				<span id="helpBlock" class="help-block text-center" style="font-weight: bold;">Τα πεδία με το αστεράκι (*) είναι υποχρεωτικά.</span><br>
				<center>
					<input type="submit" name="save" style="width: 30%;" value="Αποθήκευση" class="btn btn-block btn-success" />
					<a style="width: 30%;" class="btn btn-block btn-primary cancel" href="index.php">Άκυρο</a>
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