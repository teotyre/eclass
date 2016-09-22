<!-- Φόρμα για την επεξεργασία των βασικών πληροφοριών ενός χρήστη - μαθητή -->
<?php
//Σύνδεση με τη βάση δεδομένων
@session_start();

$connection = Database::getConnection() 
or die ('Cannot connect to db');

include 'user.php'; 

// Αν ο χρήστης πατήσει "Αποθήκευση" της φόρμας
if (isset($_POST['save']) AND $_POST['save'] =='Αποθήκευση') {

// Επεξεργασία και τροποποίηση της εγγραφής ενός χρήστη στη βάση δεδομένων
// Τραβάει τα στοιχεία από τη φόρμα που ακολουθεί
	if ($_GET['task']=='edit'){
		// Η FILTER_SANITIZE_STRING χρησιμοποιείται για να αφαιρέσει τις ετικέτες ή να κωδικοποιήσει τους ειδικούς χαρακτήρες.
		$item  = array (  //'usr_id' => (int) $_POST['usr_id'],
				'math_username'  => filter_input(INPUT_POST,'math_username', FILTER_SANITIZE_STRING),
				'math_onoma'  => filter_input(INPUT_POST,'math_onoma', FILTER_SANITIZE_STRING),
				'math_eponymo'  => filter_input(INPUT_POST,'math_eponymo', FILTER_SANITIZE_STRING)
           ); 
	    // Δημιουργεί ένα αντικείμενο Mathitis
		$mathitis = new Mathitis($item);
		// Δημιουργία ερωτήματος (query)
		$query = 'UPDATE mathitis SET math_username=?, math_onoma=?, math_eponymo=? WHERE math_id=?';
		//Τρέχει το query που δημιούργησε
		$statement = $connection->prepare($query);
		// Δέσμευση των παραμέτρων http://php.net/manual/en/mysqli-stmt.bind-param.php
		$getUsername=$mathitis->getUsername();
		$getOnoma=$mathitis->getOnoma();
		$getEponymo=$mathitis->getEponymo();
		$statement->bind_param('sssi', $getUsername, $getOnoma, $getEponymo,$_SESSION['math_id']);
		if ($statement) {
			$statement->execute();
			$statement->close();
			
			$_SESSION['math_username']=$getUsername;
			$_SESSION['math_name']=$getOnoma;
			$_SESSION['math_eponymo']=$getEponymo;

			// Προσθέτει μήνυμα επιτυχίας αν αλλαχθούν με επιτυχία τα στοιχεία του μαθητή
			$results = array('math_profile', 'Επιτυχής μεταβολή.','');

		} 
		else {
			$results = array('', 'Δεν έγινε καμία μεταβολή.', ''); //ή μήνυμα μη μεταβολής
		}
	if (isset($results['1'])){
	echo '<p class="results">'.$results['1'].'</p>';}
	}
}
$mathitis = new Mathitis();
$math = $mathitis->getMathitis($_SESSION['math_id']);
?>
<div class="row">
	<?php
	include 'lsidebar.php';
	?>
	<div class="col-sm-8 col-md-8 col-lg-8">
		<center><h3>Επεξεργασία χρήστη</h3></center>
		<!-- Φόρμα για την επεξεργασία των βασικών-υποχρεωτικών πληροφοριών του μαθητή -->
		<hr />
		<div class="well">
			<form action="index.php?content=math_basicedit&task=edit" method="post" name="maint" id="maint">
				<?php 
					$salt = 'SomeSalt';
					$token = sha1(mt_rand(1,1000000) . $salt); 
					$_SESSION['token'] = $token;
				?>

					<div class="form-group">
						<label class="col-sm-4 control-label">Όνομα χρήστη: </label>
						<div class="col-sm-8">
							<input type="text" class="form-control text-center" name="math_username" required autofocus value="<?php echo $math->getUsername() ?> ">
							<span id="helpBlock" class="help-block text-center">Επεξεργασία ονόματος χρήστη</span>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-4">Όνομα: </label>
						<div class="col-sm-8">
							<input type="text" class="form-control text-center" name="math_onoma" required autofocus value="<?php echo $math->getOnoma() ?>">
							<span id="helpBlock" class="help-block text-center">Επεξεργασία ονόματος</span>
						</div>
					</div>

				
					<div class="form-group">
						<label class="col-sm-4 control-label">Επώνυμο: </label>
						<div class="col-sm-8">
							<input type="text" class="form-control text-center" name="math_eponymo" required autofocus value="<?php echo $math->getEponymo() ?>">
							<span id="helpBlock" class="help-block text-center">Επεξεργασία επωνύμου</span>
						</div>
					</div>


				<!--<input type="hidden" name="usr_id" id="usr_id" value="<?php echo $_GET['id']; ?>" /> -->
				<input type="hidden" name="token" value="<?php echo $token; ?>"/>
				<span id="helpBlock" class="help-block text-center" style="font-weight: bold;">Αλλάξτε τις τιμές που επιθυμείτε.</span><br>
				<div>
					<center>
						
					<input type="submit" name="save" style="width: 30%;" value="Αποθήκευση" class="btn btn-block btn-success" />
					<a style="width: 30%;" class="btn btn-block btn-primary cancel" href="index.php?content=math_profile">Άκυρο</a>
						
				  </center>
				</div>
			</form>
		</div>
	</div>
	<div class="clearfix visible-sm-block"></div>
	<div class="clearfix visible-md-block"></div>
	<div class="clearfix visible-lg-block"></div><!--τέλος clearfix-->
</div>