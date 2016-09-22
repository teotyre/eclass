<!-- Πλαίσιο αποσύνδεσης που εμφανίζεται όταν ο χρήστης πατήσει το πλήκτρο της αποσύνδεσης για επιβεβαίωση της ενέργειας -->
<div class="row">
	<?php
	include "lsidebar.php";
	?>
	<div class="col-sm-8 col-md-8 col-lg-8">
		<center><h3>Είστε σίγουρος ότι θέλετε να αποσυνδεθείτε?</h3></center>
		<hr style="border: 1px solid white;" />
		<div class="well">
			<center>
				<form action="index.php?task=logout" method="post" name="frmLogout" id="frmLogout">
					<!--<input type='hidden' name='token' value='<?php echo $token; ?> -->
					<input  style="width: 30%;" class="btn btn-block btn-primary" type="submit" name="logout" value="Έξοδος" />
					<a style="width: 30%;" class="btn btn-block btn-primary cancel" href="index.php">Άκυρο</a>
				</form>
			</center>
		</div>
	</div>
	<div class="clearfix visible-sm-8"></div>
	<div class="clearfix visible-md-8"></div>
	<div class="clearfix visible-lg-8"></div><!--τέλος clearfix-->
</div>