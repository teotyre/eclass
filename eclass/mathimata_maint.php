<!-- Διεπαφή για τη διαχείριση των μαθηματών-->

<?php
	$connection = Database::getConnection() 
	or die ('Αδύναμία σύνδεσης με τη βάση δεδομένων.');
	include 'lesson.php';
	
	//Καθαρισμός των αποτελεσμάτων
	$results='';

	// Αν ο χρήστης πατήσει "Αποθήκευση" στη φόρμα
	if (isset($_POST['save']) AND $_POST['save'] =='Αποθήκευση') {
    
		// Προσθέτει τις φιλτραρισμένες μεταβλητές στον συσχετιζόμενο πίνακα 
		// Η FILTER_SANITIZE_STRING χρησιμοποιείται για να αφαιρέσει τις ετικέτες ή να κωδικοποιήσει τους ειδικούς χαρακτήρες.
		$item  = array (  'les_id' => (int) $_POST['les_id'],
				'les_onoma' => filter_input(INPUT_POST,'les_onoma', FILTER_SANITIZE_STRING),
				'les_perigrafi'  => filter_input(INPUT_POST,'les_endiaferonta', FILTER_SANITIZE_STRING)				
				
		);


		// Δημιουργεί ένα αντικείμενο Lesson με βάση τα POST.
		$lesson = new Lesson($item);
		if ($lesson->getId()) {
			$results = $lesson->add();
		}
		else {
      		echo "Πρόβλημα στην κλήση της μεθόδου.";
      }
	}
	
	// Αν ο χρήστης πατήσει "Διαγραφή" της φόρμας
	if (isset($_POST['delete']) AND $_POST['delete'] =='Διαγραφή') {	
		// Το task ελέγχει ποια διαδικασία ακολουθείται. Εδώ γίνεται διαγραφή μαθητή.
		if ($_GET['task']=='delete'){
			  // Δημιουργία ερωτημάτων διαγραφής - χρησιμοποιούμε μηχανή InnoDB και δεν απαιτούνται επιπλέον ερωτήματα διαγραφής για τους πίνακες που συσχετίζονται με τα μαθήματα
			  $id=filter_input(INPUT_POST,'les_id', FILTER_SANITIZE_STRING);
			  $query = 'DELETE FROM lesson WHERE les_id="'.$id.'"	';
			  //Τρέχει το query που δημιούργησε
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

<!-- Προβολή αποτελεσμάτων -->
<div class="row">
	<?php
include "lsidebar.php";
?>
	<div class="col-sm-8 col-md-8 col-lg-8">
		<center><h3>Καταχωρημένα μαθήματα</h3></center>
		<hr />
		<div class="well">
			<ul class="ulfancy">
				<?php 
			    // Προβάλλει όλους τους καταχωρημένους μαθητές στον διαχειριστή
			  	$items = Lesson::getLessons();
			  	if (!$items)
			  		echo 'Δεν βρέθηκαν αποτελέσματα';
			  	else{
			  		foreach ($items as $i=>$item) : ?>
			  		<li class="row<?php echo $i % 2; ?>">
			    	<!-- Εμφανίζεται μια λίστα με τους καταχωρημένους μαθητές στο διαχειριστή και τις επιλογές της "Επεξεργασία" και της "Διαγραφής"-->
			    	<?php echo htmlspecialchars($item->getOnoma()); ?>
			    	<div><span><a class="btn" href="index.php?content=mathimata_edit&id=<?php echo $item->getId(); ?>" >Επεξεργασία</a></span>
			    	<span><a class="btn" href="index.php?content=mathimata_delete&id=<?php echo $item->getId(); ?>" >Διαγραφή</a></span></div>
			    	</li>
			  	<?php endforeach; }?>
			</ul>
			<hr />
			<center><h3>Προσθήκη νέου μαθήματος</h3></center>
			<hr />
			<!--Φόρμα προσθήκης νέου μαθήματος -->
			<form action="index.php?content=mathimata_maint&task=add" method="post" name="maint" id="maint" enctype="multipart/form-data">

				<div class="form-group">
					<label for="les_onoma" class="col-sm-4 control-label">* Όνομα μαθήματος: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="les_onoma" name="les_onoma" required autofocus value=""/>
						<span id="helpBlock" class="help-block text-center">Εισάγετε το όνομα του μαθήματος</span>
					</div>
				</div>
				
				<div class="form-group">
					<label for="les_endiaferonta" class="col-sm-4 control-label">Περιγραφή: </label>
					<div class="col-sm-8">
						<textarea rows="5" cols="50" class="form-control" name="les_endiaferonta" id="les_endiaferonta" value=""></textarea>
						<span id="helpBlock" class="help-block text-center">Εισάγετε μια περιγραφή για το μάθημα</span>
					</div>
				</div>
				
				<?php
					$salt = 'SomeSalt'; // έλεγχος session
					$token = sha1(mt_rand(1,1000000) . $salt); 
					$_SESSION['token'] = $token;
				?>

				<input type="hidden" name="les_id" id="les_id" value="1" />
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