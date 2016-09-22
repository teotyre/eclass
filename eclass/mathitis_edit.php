<!-- Διεπαφή για την επεξεργασία ενός μαθητή-->

<!doctype html>
<html lang="en">
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
               dateFormat:"yy-mm-dd"
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
	// Αν ο χρήστης πατήσει"Αποθήκευση" στη φόρμα
	if (isset($_POST['save']) AND $_POST['save'] =='Αποθήκευση') {
		
		if($_FILES["fileToUpload"]["name"]!=NULL) //κάνει upload τη φωτογραφία του μαθητή στο φάκελο /uploads
		{
			
			include "upload_photo.php";
			
		}
		
		
    	// Προσθέτει τις φιλτραρισμένες μεταβλητές στον συσχετιζόμενο πίνακα 
		// Η FILTER_SANITIZE_STRING χρησιμοποιείται για να αφαιρέσει τις ετικέτες ή να κωδικοποιήσει τους ειδικούς χαρακτήρες.
		$item  = array (  'math_id' => (int) $_POST['math_id'],
				'math_username'  => filter_input(INPUT_POST,'math_username', FILTER_SANITIZE_STRING),
				'math_password' => filter_input(INPUT_POST,'math_password', FILTER_SANITIZE_STRING),
				'math_onoma' => filter_input(INPUT_POST,'math_onoma', FILTER_SANITIZE_STRING),
				'math_eponymo'  => filter_input(INPUT_POST,'math_eponymo', FILTER_SANITIZE_STRING),
				'math_endiaferonta'  => filter_input(INPUT_POST,'math_endiaferonta', FILTER_SANITIZE_STRING),				
				'math_birthday' => $_POST['math_birthday'],
				'math_diamoni' => filter_input(INPUT_POST,'math_diamoni', FILTER_SANITIZE_STRING)
				);
				
				
				$item1 = array ('math_id' => (int) $_POST['math_id'],
						'math_photo' => $target_file
							
		);
				
		$query = 'UPDATE mathitis SET math_username=?, math_password=?, math_onoma=?, math_eponymo=?, math_endiaferonta=?, math_birthday=?, math_diamoni=?  WHERE math_id=?';
		$statement = $connection->prepare($query);
		// Δέσμευση των παραμέτρων
		$statement->bind_param('sssssssi',$item['math_username'], $item['math_password'], $item['math_onoma'], $item['math_eponymo'], $item['math_endiaferonta'], $item['math_birthday'], $item['math_diamoni'],  $item['math_id']);
		if ($statement) {
			$statement->execute();
			$statement->close();
				// Αν τα στοιχεία περαστούν, προσθέτει μήνυμα επιτυχίας. Αντίθετα προσθέτει μήνυμα αποτυχίας.
			$results = array('', 'Επιτυχής μεταβολή.');
			
		} 
		else {
			$results = array('', 'Δεν έγινε καμία μεταβολή.', '');
		}
		//κάνει upload τη φωτογραφία του μαθητή στο φάκελο /uploads 
		if($_FILES["fileToUpload"]["name"]!=NULL) {
			//Αν υπάρχει ήδη φωτογραφία τότε τη διαγράφει
			$query5 = "SELECT math_photo FROM mathitis WHERE math_id='$item1[math_id]' "; //Ανάκτηση όλων των Id των λέξεων κλειδιά
			$result5 = $connection->query($query5);
			$row5 = $result5->fetch_array();
			$link = $row5['math_photo'];
			unlink($link); //διαγραφή φωτογραφίας
						
			//κάνει upload τη φωτογραφία του μαθητή στο φάκελο /uploads
			$query1 = 'UPDATE mathitis SET math_photo=?  WHERE math_id=?';
			$statement1 = $connection->prepare($query1);
			// Δέσμευση των παραμέτρων
			$statement1->bind_param('si', $item1['math_photo'],   $item1['math_id']);
			if ($statement1) {
			 	$statement1->execute();
			 	$statement1->close();
			 	// Αν τα στοιχεία περαστούν, προσθέτει μήνυμα επιτυχίας. Αντίθετα προσθέτει μήνυμα αποτυχίας.
			 	$results = array('', 'Επιτυχής μεταβολή.');
			 
			}
			else {
			 	$results = array('', 'Δεν έγινε καμία μεταβολή.', '');
			}
		}
	}		
	
	if (isset($results['1'])){
		//μηνύματα Javascript
		if ($results[1]=="Επιτυχής μεταβολή."){
			echo "
            <script type=\"text/javascript\">
            alert('Επιτυχής μεταβολή!');
            </script>
        ";
		}
		else 
		{echo "
            <script type=\"text/javascript\">
            alert('Δεν έγινε καμία μεταβολή.');
            </script>
        ";}
	}
?>

<!-- Προβολή αποτελεσμάτων -->
<div class="rs">
	<?php
	include "lsidebar.php";
	?>
	<center><h3 style="border-bottom:2px solid black;margin-bottom:2px;text-weight:bold;font-size:24px;display:block;width:100%;height:50px;line-height:65px;">Επεξεργασία μαθητή</h3></center>
</div><!--class rs-->
<div class="row">
<?php $id = (int) $_GET['id']; ?>
	<div class="col-sm-8 col-md-8 col-lg-8">
		<!--<hr />-->
		<div class="well">
			<!--Φόρμα επεξεργασίας του μαθητή -->
			<form action="index.php?content=mathitis_edit&id=<?php echo $_GET['id'] ?>" method="post" name="maint" id="maint" enctype="multipart/form-data">
				<?php 
					if(isset($_GET['id'])){
						// Τραβάει τις υπάρχουσες πληροφορίες για τον μαθητή
						$item = Mathitis::getMathitis($_GET['id']);
					}
					else{ 
						echo'Εισάγετε μαθητή:'; 
					}
				?>
				<div class="form-group">
					<label for="math_username" class="col-sm-4 control-label">* Όνομα χρήστη: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="math_username" name="math_username" required autofocus value="<?php echo $item->getUsername() ?>"/>
						<span id="helpBlock" class="help-block text-center">Εισάγετε όνομα χρήστη</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_password" class="col-sm-4 control-label">* Κωδικός χρήστη: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="math_password" name="math_password" required autofocus value="<?php echo $item->getPassword() ?>" />
						<span id="helpBlock" class="help-block text-center">Εισάγετε τον κωδικό χρήστη</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_onoma" class="col-sm-4 control-label">* Όνομα: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="math_onoma" name="math_onoma" required autofocus value="<?php echo $item->getOnoma() ?>" />
						<span id="helpBlock" class="help-block text-center">Εισάγετε το όνομα του μαθητή</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_eponymo" class="col-sm-4 control-label">* Επώνυμο: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="math_eponymo" name="math_eponymo" required autofocus value="<?php echo $item->getEponymo() ?>" />
						<span id="helpBlock" class="help-block text-center">Εισάγετε το επώνυμο του μαθητή</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_endiaferonta" class="col-sm-4 control-label">Ενδιαφέροντα: </label>
					<div class="col-sm-8">
						<textarea rows="5" cols="50" class="form-control" name="math_endiaferonta" id="math_endiaferonta" ><?php echo htmlspecialchars($item->getEndiaferonta()) ?></textarea>
						<span id="helpBlock" class="help-block text-center">Εισάγετε τα ενδιαφέροντα του μαθητή</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_birthday" class="col-sm-4 control-label">Ημ. Γέννησης: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="datepicker" name="math_birthday" value="<?php echo htmlspecialchars($item->getBirthday()) ?>" />
						
						
						
						<span id="helpBlock" class="help-block text-center">Εισάγετε την ημερομηνία γέννησης του μαθητή</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_diamoni" class="col-sm-4 control-label">Τόπος Διαμονής: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="math_diamoni" name="math_diamoni" value="<?php echo $item->getDiamoni() ?>" />
						<span id="helpBlock" class="help-block text-center">Εισάγετε τον τόπο διαμονής του μαθητή</span>
					</div>
				</div>

				<div class="form-group">
					<label for="math_photo" class="col-sm-4 control-label">Φωτογραφία: </label>
					<div class="col-sm-8">
						<input type="hidden" name="MAX_FILE_SIZE" value="15000000">
						<img src="<?php echo $item->getPhoto() ?>" style="width:64px;height:64px" alt="Δεν υπάρχει φωτογραφία." >
						<input type="file" name="fileToUpload" id="fileToUpload"  >
						<span id="helpBlock" class="help-block text-center">Ανεβάστε μια φωτογραφία του μαθητή</span>
					</div>
				</div>

				<?php
					$salt = 'SomeSalt'; // έλεγχος session
					$token = sha1(mt_rand(1,1000000) . $salt); 
					$_SESSION['token'] = $token;
				?>
				<input type="hidden" name="math_id" id="math_id" value="<?php echo $_GET['id'];?>" />
				<input type="hidden" name='token' value="<?php echo $token; ?>"/>
				<span id="helpBlock" class="help-block text-center" style="font-weight: bold;">Τα πεδία με το αστεράκι (*) είναι υποχρεωτικά.</span><br>
				<center>
					<input type="submit" name="save" style="width: 30%;" value="Αποθήκευση" class="btn btn-block btn-success" />
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