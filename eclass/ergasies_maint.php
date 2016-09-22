<!-- Διεπαφή για τη διαχείριση των μαθητών-->

<?php
	$connection = Database::getConnection() 
	or die ('Αδύναμία σύνδεσης με τη βάση δεδομένων.');
	include 'ergasia.php';
	include 'lesson.php';
	include 'keyword.php';


	//Καθαρισμός των αποτελεσμάτων
	$results='';

	// Αν ο χρήστης πατήσει "Αποθήκευση" στη φόρμα
	if (isset($_POST['save']) AND $_POST['save'] =='Αποθήκευση') {
		
		if($_FILES["fileToUpload"]["name"]!=NULL) //κάνει upload τη φωτογραφία του μαθητή στο φάκελο /uploads
		{
				
			include "upload_file.php";
		}
		else echo " Δεν επιλέχθηκε αρχείο για την εργασία!!!";
		
		// Προσθέτει τις φιλτραρισμένες μεταβλητές στον συσχετιζόμενο πίνακα 
		// Η FILTER_SANITIZE_STRING χρησιμοποιείται για να αφαιρέσει τις ετικέτες ή να κωδικοποιήσει τους ειδικούς χαρακτήρες.
		$item  = array (  'erg_id' => (int) $_POST['erg_id'],
				'erg_onoma'  => filter_input(INPUT_POST,'erg_onoma', FILTER_SANITIZE_STRING),
				'erg_perigrafi'  => filter_input(INPUT_POST,'erg_perigrafi', FILTER_SANITIZE_STRING),
				'erg_is_visible'  => filter_input(INPUT_POST,'erg_is_visible', FILTER_SANITIZE_STRING),
				'erg_file' => $target_file,
				'les_les_id'  => filter_input(INPUT_POST,'les_les_id', FILTER_SANITIZE_STRING)
				);

		// Δημιουργεί ένα αντικείμενο Ergasia με βάση τα POST.
		$ergasia = new Ergasia($item);
		if ($ergasia->getId()) {
			$results = $ergasia->add();
			$lastins = $connection->insert_id;
			//echo $lastins;
			// javascript μήνυμα
			echo "
            <script type=\"text/javascript\">
            alert('Επιτυχής προσθήκη της εργασίας!');
            </script>";
		}
		else {
      		echo "Fatal error!";
    	}
			 
		//Εισαγωγή λέξεων κλειδιά στον πίνακα keyword
		$str = $_POST['erg_aKleidia'];  //Αποθηκεύει τις λέξεις κλειδιά σε έναν πίνακα.
		$aKleidia=explode(" ",$str); 
			
		$arrlength = count($aKleidia);
		
		for($x = 0; $x < $arrlength; $x++) {
					
			$keywords = array('keyw_word' => $aKleidia[$x]);
					
			$keyw = new Keyword($keywords); //προσθέτει τα κλειδιά στον πίνακα keyword με κλήση της αντίστοιχης κλάσης
			$keyw->add();
		}
			
		if(empty($aKleidia)){
	    //echo("Δεν υπάρχουν κλειδιά.");
		}
		else{
			$N = count($aKleidia);
			//echo("Προσθέσατε $N κλειδιά: ");
			for($i=0; $i < $N; $i++){
				//echo($aKleidia[$i] . " ");
			}

			// Προσθέτει τις φιλτραρισμένες μεταβλητές στον συσχετιζόμενο πίνακα
			// Η FILTER_SANITIZE_STRING χρησιμοποιείται για να αφαιρέσει τις ετικέτες ή να κωδικοποιήσει τους ειδικούς χαρακτήρες.
			$item1  = array (//'par_id' => (int) $_POST['par_id'], 
	                'ergasia'  => $lastins, 
	                'aKleidia'  => implode(",", $aKleidia)
	                 ); 

	      	foreach ($aKleidia as $value3)  {
	      		//echo " ". $item1['ergasia'];
	      		//echo " ";
	      		//echo " "."$value3";
	      		$keyw1 = new Keyword($value3);
	      		$keyw1_id=$keyw1->getId_by_name($value3);
	      		$keyw2=$keyw1_id->getId();
	      	
				// Εισαγωγή στη βάση των λέξεων κλειδιών που αντιστοιχούν σε κάθε εργασία  
		      	// Προετοιμασία των δεδομένων και δημιουργία ερωτήματος προς τη βάση
		     	$query7 = "INSERT INTO ergasia_has_keyword(erg_erg_id,keyw_keyw_id) 
		      	VALUES ('" . Database::prep($item1['ergasia']) . "',
		       	'" . Database::prep($keyw2) . "' )";

		      	// Τρέχει το query που δημιούργησε
		      	if ($connection->query($query7)) { // αυτό προσθέτει την καινούργια εγγραφή στη βάση
		        	$results=array('','Προστέθηκαν οι λέξεις κλειδιών.', '1');
		       	}
		       	else {
		      		// Στέλνει μήνυμα ότι απέτυχε η προσθήκη της καινούργιας εγγραφής στη βάση
		      		$results = array('', 'Αδύνατη η προσθήκη των λέξεων κλειδιών.', '0');
		      	}
	 		}
		}
	}

?>
<?php

include "lsidebar.php";
?>

<!-- html φόρμα για την προσθήκη εργασίας σε μάθημα  -->
<div class="row">
	<div class="col-sm-8 col-md-8 col-lg-8">
		<!--<center><h3>Καταχωρημένες εργασίες</h3></center>
		<hr />-->
		<div class="well">
			<!--<ul class="ulfancy">-->
			<?php 
			  // Επιστρέφει όλες τις καταχωρημένες εργασίες 	
			  $items = Ergasia::getErgasies();
			  // Επιστρέφει όλες τα καταχωρημένα μαθήματα
			  $lessons = Lesson::getLessons();
			?>
			<center><h3>Προσθήκη Εργασίας</h3></center>
			<hr />
			<form action="index.php?content=ergasies_maint&task=add" method="post" name="maint" id="maint" enctype="multipart/form-data">
				
				<div class="form-group">
					<label for="les_les_id" class="col-sm-4 control-label">* Μάθημα: </label>
					<div class="col-sm-8">
						<center><select id="les_les_id" name="les_les_id" >
							<?php
							foreach ($lessons as $l=>$les){
								 echo '<option value="'.$les->getId().'">'. $les->getOnoma().'</option>'; //Δυναμικό select
							}
							?>
						</select></center>
						<span id="helpBlock" class="help-block text-center">Διαλέξτε το μάθημα στο οποίο θέλετε να προσθέσετε εργασία</span>
					</div>
				</div>


				<div class="form-group">
					<label for="erg_onoma" class="col-sm-4 control-label">* Όνομα: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="erg_onoma" name="erg_onoma" required autofocus value="" />
						<span id="helpBlock" class="help-block text-center">Εισάγετε το όνομα της εργασίας</span>
					</div>
				</div>
				
				<div class="form-group">
					<label for="erg_perigrafi" class="col-sm-4 control-label">* Περιγραφή: </label>
					<div class="col-sm-8">
						<textarea rows="5" cols="50" class="form-control" name="erg_perigrafi"  id="erg_perigrafi" required autofocus  ></textarea>
						<span id="helpBlock" class="help-block text-center">Εισάγετε μια σύντομη περιγραφή για την εργασία</span>
					</div>
				</div>

				<div class="form-group">
					<label for="erg_perigrafi" class="col-sm-4 control-label">* Λέξεις κλειδιά: </label>
					<div class="col-sm-8">
						<textarea rows="5" cols="50" class="form-control" name="erg_aKleidia"  id="erg_aKleidia" required autofocus ></textarea>
						<span id="helpBlock" class="help-block text-center">Εισάγετε τις λέξεις κλειδιά για την εργασία</span>
					</div>
				</div>
				
				<div class="form-group">
					<label for="erg_is_visible" class="col-sm-4 control-label">* Ορατή: </label>
					<div class="col-sm-8">
						<center><input type="radio" id="erg_is_visible" name="erg_is_visible"  required autofocus
						<?php if (isset($erg_is_visible) && $erg_is_visible=="1") echo "checked";?> value="1">Ναι
						<input type="radio" name="erg_is_visible" <?php if (isset($erg_is_visible) && $erg_is_visible=="0") echo "checked";?>
						value="0">Όχι 
						<span id="helpBlock" class="help-block text-center">Ορίστε αν η εργασία θα είναι ορατή από τους μαθητές</span></center>
					</div>
				</div>
				
				<div class="form-group">
					<label for="erg_file" class="col-sm-4 control-label">* Αρχείο: </label>
					<div class="col-sm-8">
						<input type="hidden" name="MAX_FILE_SIZE" value="15000000">
						<input type="file" name="fileToUpload" id="fileToUpload" required autofocus >
						<span id="helpBlock" class="help-block text-center">Ανεβάστε ένα αρχείο για την εργασία</span>
					</div>
				</div>

				<?php
				//έλεγχος session
					$salt = 'SomeSalt';
					$token = sha1(mt_rand(1,1000000) . $salt); 
					$_SESSION['token'] = $token;
				?>

				<input type="hidden" name="erg_id" id="erg_id" value="1" />
				<input type="hidden" name='token' value="<?php echo $token; ?>"/>
				<span id="helpBlock" class="help-block text-center" style="font-weight: bold;">Όλα τα πεδία είναι υποχρεωτικά.</span><br>

				<center>
					<input type="submit" name="save" style="width: 30%;" value="Αποθήκευση" class="btn btn-block btn-success" />
					<a style="width: 30%;" class="btn btn-block btn-primary cancel" href="index.php?content=ergasies_main">Άκυρο</a>
				</center>
			</form>
		</div>
	</div>
	<div class="clearfix visible-sm-block"></div>
	<div class="clearfix visible-md-block"></div>
	<div class="clearfix visible-lg-block"></div><!--τέλος clearfix-->
</div>