<!-- Διεπαφή για τη διαγραφή μαθήματος-->

<?php
	
$id = (int) $_GET['id'];	
if(isset($_GET['id'])){
	// Τραβάει τις υπάρχουσες πληροφορίες για το αντικείμενο Lesson
	$item = Lesson::getLesson($_GET['id']);
	}
	else{ 
		echo'Εισάγετε μάθημα:'; 
	}
?>

<div class="rs">
	<?php
	include "lsidebar.php";
	?>
	<center>
		<h3 style="border-bottom:2px solid black;margin-bottom:2px;text-weight:bold;font-size:24px;display:block;width:100%;height:50px;line-height:65px;">Διαγραφή μαθήματος</h3>
	</center>
</div><!--class rs-->

<div class="row">
	<div class="col-sm-8 col-md-8 col-lg-8">
		<!--<hr />-->
		<div class="well">
			<!-- Φόρμα με τα στοιχεία του μαθήματος προς διαγραφή--> 
			<form action="index.php?content=mathimata_maint&task=delete" method="post" name="maint" id="maint" enctype="multipart/form-data">
				
				<div class="form-group">
					<label for="les_onoma" class="col-sm-4 control-label">Όνομα: </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="les_onoma" name="les_onoma" value="<?php echo htmlspecialchars($item->getOnoma()) ?>" />
					</div>
				</div>
				
				<div class="form-group">
					<label for="les_perigrafi" class="col-sm-4 control-label">Περιγραφή: </label>
					<div class="col-sm-8">
						<textarea rows="5" cols="50" class="form-control" name="les_perigrafi" id="les_perigrafi" ><?php echo htmlspecialchars($item->getPerigrafi()) ?> </textarea>
					</div>
				</div>
								
				<?php //έλεγχος session
					$salt = 'SomeSalt';
					$token = sha1(mt_rand(1,1000000) . $salt); 
					$_SESSION['token'] = $token;
				?>

				<input type="hidden" name="les_id" id="les_id" value="<?php echo $_GET['id'];?>" />
				<input type="hidden" name='token' value="<?php echo $token; ?>"/>
				<span id="helpBlock" class="help-block text-center" style="font-weight: bold;">Θέλετε να διαγράψετε το μάθημα?</span><br>
				<center>
					<input type="submit" name="delete" style="width: 30%;" onclick="return confirm('Το μάθημα θα διαγραφεί! Είστε σίγουρος;')"  value="Διαγραφή" class="btn btn-block btn-success" /><h6></h6>
					
					<script type="text/javascript"> //javascript για επιβεβαίωση διαγραφής.
    				var elems = document.getElementsByClassName('confirmation');
   				 	var confirmIt = function (e) {
        			if (!confirm('Το μάθημα θα διαγραφεί! Είστε σίγουρος;')) e.preventDefault();
    											};
    				for (var i = 0, l = elems.length; i < l; i++) {
        			elems[i].addEventListener('click', confirmIt, false);
    				}
					</script>
					
					<a style="width: 30%;" class="btn btn-block btn-primary cancel" href="index.php?content=mathimata_maint">Άκυρο</a>
				</center>
			</form>
		</div>
	</div>
	<div class="clearfix visible-sm-block"></div>
	<div class="clearfix visible-md-block"></div>
	<div class="clearfix visible-lg-block"></div><!--τέλος clearfix-->
</div>