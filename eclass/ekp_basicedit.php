<!-- Φόρμα για την επεξεργασία της εγγραφής ενός χρήστη - διαχειριστή -->
<?php
//Σύνδεση με τη βάση δεδομένων
@session_start();

$connection = Database::getConnection() 
or die ('Cannot connect to db');

include 'ekpaideytikos.php'; 

// Αν ο χρήστης πατήσει "Αποθήκευση" της φόρμας
if (isset($_POST['save']) AND $_POST['save'] =='Αποθήκευση') {

// Επεξεργασία και τροποποίηση της εγγραφής ενός χρήστη στη βάση δεδομένων
// Τραβάει τα στοιχεία από τη φόρμα που ακολουθεί
	if ($_GET['task']=='edit'){
		// Η FILTER_SANITIZE_STRING χρησιμοποιείται για να αφαιρέσει τις ετικέτες ή να κωδικοποιήσει τους ειδικούς χαρακτήρες.
		$item  = array (  //'ekp_id' => (int) $_POST['ekp_id'],
				'ekp_username'  => filter_input(INPUT_POST,'ekp_username', FILTER_SANITIZE_STRING),
				'ekp_onoma'  => filter_input(INPUT_POST,'ekp_onoma', FILTER_SANITIZE_STRING),
				'ekp_eponymo'  => filter_input(INPUT_POST,'ekp_eponymo', FILTER_SANITIZE_STRING)
           ); 
	    // Δημιουργεί ένα αντικείμενο Ekpaideytikow
		$ekps = new Ekpaideytikos($item);
		// Δημιουργία ερωτήματος (query)
		$query = 'UPDATE ekpaideytikos SET ekp_username=?, ekp_onoma=?, ekp_eponymo=? WHERE ekp_id=?';
		//Τρέχει το query που δημιούργησε
		$statement = $connection->prepare($query);
		// Δέσμευση των παραμέτρων http://php.net/manual/en/mysqli-stmt.bind-param.php
		$getUsername=$ekps->getUsername();
		$getOnoma=$ekps->getOnoma();
		$getEponymo=$ekps->getEponymo();
		//Εκτέλεση του ερωτήματος
		$statement->bind_param('sssi', $getUsername, $getOnoma, $getEponymo,$_SESSION['ekp_id']);
		if ($statement) {
			$statement->execute();
			$statement->close();
			// Προσθέτει μήνυμα επιτυχίας αν αλλαχθούν με επιτυχία τα στοιχεία του χρήστη
			$results = array('', 'Επιτυχής μεταβολή.','');
		} 
		else {
			$results = array('', 'Δεν έγινε καμία μεταβολή.', '');
		}
	if (isset($results['1'])){
	echo '<p class="results">'.$results['1'].'</p>';}
	}
}
$ekps = new Ekpaideytikos();
$ekp = $ekps->getEkpaideytikos($_SESSION['ekp_id']);
?>
<div class="row">
	<?php
	include 'lsidebar.php';
	?>
	<div class="col-sm-8 col-md-8 col-lg-8">
		<center><h3>Επεξεργασία χρήστη</h3></center>
		<!-- Φόρμα για την επεξεργασία της εγγραφής ενός χρήστη - διαχειριστή -->
		<hr />
		<div class="well">
			<form action="index.php?content=ekp_basicedit&task=edit" method="post" name="maint" id="maint">
				<?php 
					$salt = 'SomeSalt';
					$token = sha1(mt_rand(1,1000000) . $salt); 
					$_SESSION['token'] = $token;
				?>

					<div class="form-group">
						<label class="col-sm-4 control-label">* Όνομα χρήστη: </label>
						<div class="col-sm-8">
							<input type="text" class="form-control text-center" name="ekp_username" required autofocus value="<?php echo $ekp->getUsername() ?>">
							<span id="helpBlock" class="help-block text-center">Επεξεργασία ονόματος χρήστη</span>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-4">* Όνομα: </label>
						<div class="col-sm-8">
							<input type="text" class="form-control text-center" name="ekp_onoma" required autofocus value="<?php echo $ekp->getOnoma() ?>">
							<span id="helpBlock" class="help-block text-center">Επεξεργασία ονόματος</span>
						</div>
					</div>

				
					<div class="form-group">
						<label class="col-sm-4 control-label">* Επώνυμο: </label>
						<div class="col-sm-8">
							<input type="text" class="form-control text-center" name="ekp_eponymo" required autofocus value="<?php echo $ekp->getEponymo() ?>">
							<span id="helpBlock" class="help-block text-center">Επεξεργασία επωνύμου</span>
						</div>
					</div>


				<!--<input type="hidden" name="ekp_id" id="ekp_id" value="<?php echo $_GET['id']; ?>" /> -->
				<input type="hidden" name="token" value="<?php echo $token; ?>"/>
				<span id="helpBlock" class="help-block text-center" style="font-weight: bold;">Αλλάξτε τις τιμές που επιθυμείτε.</span><br>
				<center>
					<input type="submit" name="save" style="width: 30%;" value="Αποθήκευση" class="btn btn-block btn-success" />
					<a style="width: 30%;" class="btn btn-block btn-primary cancel" href="index.php?content=ekp_profile">Άκυρο</a>
				</center>
			</form>
		</div>
	</div>
	<div class="clearfix visible-sm-block"></div>
	<div class="clearfix visible-md-block"></div>
	<div class="clearfix visible-lg-block"></div><!--τέλος clearfix-->
</div>
