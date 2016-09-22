<!-- Διεπαφή ανάθεσης μαθημάτων σε μαθητή-->

<?php
	


// Σύνδεση με τη βάση δεδομένων
$connection = Database::getConnection()
or die ('Cannot connect to db');

//Καθαρισμός των αποτελεσμάτων	
$results='';
$error=0; //διαγνωστική μεταβλητή για τον έλεγχο ροής των μηνυμάτων
// Αν ο χρήστης πατήσει το κουμπί "Ανάθεση" στη φόρμα
if (isset($_POST['save']) AND $_POST['save'] =='Ανάθεση' AND isset($_POST['check_maths']) AND $_POST['check_maths']!=[]	) {

  $aLesson = $_POST['check_maths'];
  
   
	// Προσθέτει τις φιλτραρισμένες μεταβλητές στον συσχετιζόμενο πίνακα
	// Η FILTER_SANITIZE_STRING χρησιμοποιείται για να αφαιρέσει τις ετικέτες ή να κωδικοποιήσει τους ειδικούς χαρακτήρες.
	$item  = array (//'par_id' => (int) $_POST['par_id'],
	 				'lesson'  => implode(",", $aLesson),
	                'mathitis'  => $_POST['math_id']
	                );
	      
	foreach ($aLesson as $value2){
		// Εισαγωγή στη βάση της εγγραφής του μαθήματος που παρακολουθεί ο μαθητής
		// Προετοιμασία των δεδομένων και δημιουργία ερωτήματος προς τη βάση
		$query6 = "INSERT INTO parakolouthei(par_les_id,math_math_id)
		VALUES ('" . Database::prep($value2) . "',
		'" . Database::prep($item['mathitis']) . "' )";

		// Τρέχει το query που δημιούργησε
		if ($connection->query($query6)) { // αυτό προσθέτει την καινούργια εγγραφή στη βάση
			$results=array('','Προστέθηκε η ανάθεση μαθήματος στον μαθητή.', '1');
		}else {
			// Στέλνει μήνυμα ότι απέτυχε η προσθήκη της καινούργιας εγγραφής στη βάση
			$results = array('', 'Αδύνατη η προσθήκη ανάθεσης μαθήματος.', '0');
			$error=1;
		}
	}

}
elseif (isset($_POST['save']) AND $_POST['save'] =='Ανάθεση' ){
	echo "<h4>Δεν επιλέξατε κάποιο μάθημα!</h4>";
	$error=1; //για να απενεργοποιηθεί το κουμπί της ανάθεσης	
}
  
// Διαγραφή από τη βάση της εγγραφής ενός μαθήματος που παρακολουθεί ο μαθητής	
// Αν ο χρήστης πατήσει την "Διαγραφή" του μαθήματος
if (isset($_GET['task']) and ($_GET['task']=='delete')){
	// Σύνδεση με τη βάση δεδομένων και δημιουργία ερωτήματος (query) προς τη βάση
	$query = 'DELETE FROM parakolouthei WHERE par_id="'.$_GET['parid'].'" ';
	// Τρέχει το query που δημιούργησε
	if ($result = $connection->query($query)) {
		// Αν τα στοιχεία διαγραφούν, προσθέτει μήνυμα επιτυχίας. Αντίθετα προσθέτει μήνυμα αποτυχίας.
		$results = array('', 'Επιτυχής διαγραφή από μάθημα.', '');
	} 
	else {
		$results = array('delete', 'Δεν έγινε καμία διαγραφή.', '');
		$error=1;//για να απενεργοποιηθεί το κουμπί της ανάθεσης
	}
}
	  
if (isset($results['1'])){
	echo '<p class="results">'.$results['1'].'</p>';
}
?>

<!-- Προβολή αποτελεσμάτων για το διαχειριστή -->
<div class="row">
	<?php include "lsidebar.php"; ?>
	
	<div class="col-sm-8 col-md-8 col-lg-8">
		<?php
				//echo $_GET['id']; 
				//Επιστρέφει τα στοιχεία από τον πίνακα παρακολουθεί για τον μαθητή
				// Δημιουργία ερωτήματος (query) προς τη βάση
				if (isset($_GET['id'])) {
					echo '<center><h3>Μαθήματα που παρακολουθεί</h3></center>';
					echo '<hr />
		 				  	<div class="well">
							<ul class="ulfancy">';
					$query1='SELECT par_id,par_les_id,math_math_id FROM parakolouthei WHERE math_math_id="'.$_GET['id'].'"';
					// Τρέχει το query που δημιούργησε
					$mathimatap = $connection->query($query1);
					// Εκτυπώνει σε περίπτωση που δε βρεθούν αποτελέσματα.
					if($mathimatap->num_rows == '0'){
						echo '<center><h4>Ο μαθητής δεν παρακολουθεί κάποιο μάθημα.</h4></center>';
					}
					else{
						while ($row = $mathimatap->fetch_assoc()) { ?>
						<li class="row<?php echo $row % 2; ?> ">
							<?php
							//Επιστρέφει ονομαστικά όλα τα μαθήματα που παρακολουθεί ο μαθητής 
							// Δημιουργία ερωτήματος (query) προς τη βάση
							$query2='SELECT les_id,les_onoma FROM lesson WHERE les_id="'.$row['par_les_id'].'" order by les_onoma';
							// Τρέχει το query που δημιούργησε
							$act=$connection->query($query2);
							$row2 = $act->fetch_assoc();
							$label=$row2['les_onoma']; 
							echo $label; ?>
							<a class="button" onclick="return confirm('Ο μαθητής θα διαγραφεί από το μάθημα! Είστε σίγουρος;')" href="index.php?content=mathitis_anathesi&task=delete&parid=<?php echo $row['par_id']; ?>">Διαγραφή από το μάθημα</a>

							<script type="text/javascript"> //javascript για επιβεβαίωση διαγραφής.
								var elems = document.getElementsByClassName('confirmation');
								var confirmIt = function (e) {
									if (!confirm('Ο μαθητής θα διαγραφεί από το μάθημα! Είστε σίγουρος;')) e.preventDefault();
									};
									for (var i = 0, l = elems.length; i < l; i++) {
										elems[i].addEventListener('click', confirmIt, false);
									}
							</script>
						</li>

						<?php
						}
					}
				}?>
		</ul>
		<hr />
		<center><h3>Ανάθεση μαθήματος στον μαθητή</h3></center>
		<hr />
		<div >
			<form action="index.php?content=mathitis_anathesi&task" method="post" id="maint" name="maint" enctype="multipart/form-data">
			<!--Όλα αυτά θα εμφανίζονται 3 ανά γραμμή και θα μπορεί να επιλέγει περισσότερες από μια επιλογές-->

				<?php
					if (isset($_GET['id'])) { //σε περίπτωση που ύπαρχει το id του μαθητή (στην πρώτη εμφάνιση της φόρμας)
					$query5='SELECT les_id FROM lesson';
					$lessons=$connection->query($query5);
						if ($lessons->num_rows == '0') {

						 	echo "<center><h4> Δεν υπάρχουν καταχωρημένα μαθήματα.  </h4>";
						 	echo "<p>Παρακαλώ επισκεφθείτε τον αντίστοιχο σύνδεσμο για να προσθέσετε.</p></center>";
						 }
						 else{
						 	// Δημιουργία ερωτήματος (query) προς τη βάση
						  	@$query3='SELECT les_id,les_onoma FROM lesson WHERE les_id NOT IN (SELECT par_les_id FROM parakolouthei WHERE math_math_id="'.$_GET['id'].'") order by les_onoma';

							// Τρέχει το query που δημιούργησε
							$mathimatanp = $connection->query($query3);
							 // Εκτυπώνει σε περίπτωση που δε βρεθούν αποτελέσματα.

							if($mathimatanp->num_rows == '0'){
								echo '<center><h4>Ο μαθητής παρακολουθεί όλα τα διαθέσιμα μαθήματα.</h4></center>';
								$mnop=1; //μεταβλητή ελέγχου ροής - δηλώνει οτι ο μαθητής παρακολουθεί όλα τα μαθήματα
							}
							else{
								echo '<center><h4> Διαθέσιμα μαθήματα.</h4></center>';
								while ($row3 = $mathimatanp->fetch_assoc()) {
									
									// Δημιουργία ερωτήματος (query) προς τη βάση
									$query4='SELECT les_id, les_onoma FROM lesson WHERE les_id="'.$row3['les_id'].'"';
									// Τρέχει το query που δημιούργησε
									$act2=$connection->query($query4);
									$row4 = $act2->fetch_assoc();
									$label2=$row4['les_onoma'];?>
									<center>
									<div class="form-group">
										<div >
											<div class="checkbox">
												<label>
													
													<input type="checkbox" name="check_maths[]" value="<?php echo $row4['les_id']?>" ><?php echo $label2;?>
												</label>
											</div>
										</div>
									</div>
									</center>
								<?php
								}
								$mnop=0;
								$error=0;
						}
						
						}
					}
					else{
						$error=1;
					}
				?>	
						
			<?php 
				$salt = 'SomeSalt'; //έλεγχος session
				$token = sha1(mt_rand(1,1000000) . $salt); 
				$_SESSION['token'] = $token;
				@$idt=$_GET['id']
			?>

			<input type="hidden" name="par_id" id="par_id" value="1" />
			<input type="hidden" name="math_id" id="math_id" value="<?php echo $idt;?>"/>
			<input type='hidden' name='token' value='<?php echo $token; ?>'/>
					
			
			<?php
			//έλεγχος για το αν θα εμφανίζεται ή όχι το κουμπί της ανάθεσης
			if (@$mnop==0 AND $error==0) {
				
			echo '<center>';
			echo '<input type="submit" name="save" style="width: 30%;" value="Ανάθεση" class="btn btn-block btn-success" />';
			echo '</center><h6></h6>';
			
			}?>
			<center><a style="width: 30%;" class="btn btn-block btn-primary cancel" href="index.php?content=mathites_maint">Άκυρο</a></center>
			<span id="helpBlock" class="help-block text-center" style="font-weight: bold;">Μπορείτε να επιλέξετε περισσότερα από ένα μαθήματα.</span>		
			</form>
		</div>
		</div>
	</div>
	<div class="clearfix visible-sm-block"></div>
	<div class="clearfix visible-md-block"></div>
	<div class="clearfix visible-lg-block"></div><!--τέλος clearfix-->
</div>

	