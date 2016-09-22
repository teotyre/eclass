<!-- Φόρμα για την αλλαγή του password ενός χρήστη -->
<?php	
# Σύνδεση με τη βάση δεδομένων
$connection = Database::getConnection() 
or die ('Cannot connect to db');
	include 'mathitis.php';
?>

<?php 

# Καθαρισμός των αποτελεσμάτων
$results='';
#  Αν ο χρήστης πατήσει "Αλλαγή" στη φόρμα
if (isset($_POST['save']) AND $_POST['save']=='Αλλαγή') {
      # Προσθέτει τις φιλτραρισμένες μεταβλητές στον συσχετιζόμενο πίνακα 
	  # Η FILTER_SANITIZE_STRING χρησιμοποιείται για να αφαιρέσει τις ετικέτες ή να κωδικοποιήσει τους ειδικούς χαρακτήρες.
      $item  = array (  
				'password' => filter_input(INPUT_POST,'password1', FILTER_SANITIZE_STRING)
           ); 
		  $old_password  = filter_input(INPUT_POST,'password', FILTER_SANITIZE_STRING); 
		  #$old_password = hash_hmac('sha512', $old_password. '!hi#HUde9','dsghdsjhsjs34d3'); //υλοποίηση σε επόμενη έκδοση
	# Επεξεργασία και τροποποίηση του κωδικου

	if ($_GET['task']=='edit'){
		$query = 'SELECT math_password FROM mathitis WHERE math_id="'.$_SESSION['math_id'].'"';
		$result = $connection->query($query);
		$row = $result->fetch_assoc();
		if ($row['math_password']!=$old_password ){
			$results = array('', 'Δεν έχει επικυρωθεί το τρέχον password.', '0');
		} else {
		
		$error=false; //μεταβλητή ελέγχου για το αν το password που δίνει ο χρήστης είναι ίδιο με το αποθηκεύμενο στο σύστημα 
		$same=false; //μεταβλητή ελέγχου για το αν το password που δίνει ο χρήστης δύο φορές προς επιβεβαίωση της αλλαγής είναι ίδιο

		$password = trim($_POST['password1']);
        if ($password) {
            if ($password != trim($_POST['password2'])) {
                $error = true;
            }
            if ($password == $old_password) {
                $same = true;
            }
        }

        if ($error==true) {
        	$results = array('', 'Τα δυο password δεν είναι ίδια. Παρακαλώ προσπαθείστε ξανά.', '0');
        }
        elseif ($same==true) {
        	$results = array('', 'Δεν έγινε καμία μεταβολή.', '0');
        }
        else{
		
        # Προετοιμασία για το κρυπτογραφημένο password
        $password = trim($item['password']);
		#$password = hash_hmac('sha512', $password. '!hi#HUde9','dsghdsjhsjs34d3'); //υλοποίηση σε επόμενη έκδοση

		# Αν ο χρήστης έχει γράψει καινούργιο κωδικό, τον ανανεώνει. 
	 	# Δημιουργία ερωτήματος (query)
		$query = 'UPDATE mathitis SET math_password=? WHERE math_id=?';
      	# Τρέχει το query που δημιούργησε
	  	$statement = $connection->prepare($query);
	  	# Δέσμευση των παραμέτρων
	  	$statement->bind_param('si', $password, $_SESSION['math_id']);
	
	 	if ($statement) {
	 		$statement->execute();
	 		$statement->close();
	 		#  Προσθέτει μήνυμα επιτυχίας αν αλλαχθούν με επιτυχία τα στοιχεία του χρήστη
	 		$results = array('', 'Επιτυχής μεταβολή.');
	 		}
	 		else {
	 			$results = array('', 'Δεν έγινε καμία μεταβολή. Πρόβλημα κατά την ενημέρωση.', '');
            }
        }
	}
	  
  	if (isset($results['1'])){
  		echo '<p class="results">'.$results['1'].'</p>';
  	}
	
	}
}
?>
<div class="row">
	<?php
	include 'lsidebar.php';
	?>
	<div class="col-sm-8 col-md-8 col-lg-8">
		<center><h3>Αλλαγή κωδικού πρόσβασης</h3></center>
		<hr />
		<div class="well">
			<form action="index.php?content=change_math_password&task=edit" method="post" name="maint" id="maint">
				
				<div class="form-group">
					<label for="password" class="col-sm-4 control-label">* Τρέχον κωδικός: </label>
					<div class="col-sm-8">
						<input type="password" class="form-control" name="password" required autofocus id="password"/>
						<span id="helpBlock" class="help-block text-center">Εισάγετε τον τρέχον κωδικό</span>
					</div>
				</div>

				<div class="form-group">
					<label for="password1" class="col-sm-4 control-label">* Νέος κωδικός: </label>
					<div class="col-sm-8">
						<input type="password" class="form-control" name="password1" required autofocus id="password1"/>
						<span id="helpBlock" class="help-block text-center">Εισάγετε νέο κωδικό που επιθυμείτε</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-4 control-label" for="password2">* Επιβεβαίωση νέου κωδικού: </label>
					<div class="col-sm-8">
						<input type="password" class="form-control" name="password2" required autofocus id="password2"/>
						<span id="helpBlock" class="help-block text-center">Εισάγετε ξανά τον νέο κωδικό</span>
					</div>
				</div>

				<?php
					//διαδικασία εγκυροποίησης του session 
					$salt = 'SomeSalt';
					$token = sha1(mt_rand(1,1000000) . $salt); 
					$_SESSION['token'] = $token;
				?>

				<!--<input type="hidden" name="math_id" id="math_id" value='<?php echo $_GET['id']; ?> --> 
				<input type='hidden' name='token' value='<?php echo $token; ?>'/>
				<span id="helpBlock" class="help-block text-center" style="font-weight: bold;">Όλα τα πεδία είναι υποχρεωτικά προς συμπλήρωση.</span><br>
				<center>
					<input type="submit" name="save" style="width: 30%;" value="Αλλαγή" class="btn btn-block btn-success" />
					<a style="width: 30%;" class="btn btn-block btn-primary cancel" href="index.php?content=math_profile">Άκυρο</a>
				</center>
			</form>
		</div>
	</div>
	<div class="clearfix visible-sm-block"></div>
	<div class="clearfix visible-md-block"></div>
	<div class="clearfix visible-lg-block"></div><!--τέλος clearfix-->
</div>