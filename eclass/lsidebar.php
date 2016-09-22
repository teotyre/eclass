
<div class="col-sm-3 col-md-3 col-lg-3"> <!-- Grid options for Bootstrap-->
	<div>
		<center><h3>Επιλογές</h3></center>
		<!--<hr />-->
	</div> <!--Επιλογές-->

	<?php
		if (isset($_SESSION['message'])) {
			echo '<h5>'.$_SESSION['message'].'</h5>';

		}

		if (!isset($_SESSION['math_id']) and !isset($_SESSION['ekp_id'])){
			# Κουμπί για είσοδο χρήστη το οποίο καλεί την κλάση "bootstrap modal"  
			echo '<br><center>';
			echo '<a href="#myModal1" style="font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif;" class="btn btn-primary" data-toggle="modal"><span class="glyphicon glyphicon-user"></span> Είσοδος</a>  ';
			echo '</center>';
		} elseif (isset($_SESSION['ekp_id']))  {
			# Επιλογές αν έχει συνδεθεί ο διαχειριστής
		 	#echo "epiloges admin";
		 	#echo $_SESSION['ekp_id']; 
		 	echo '<li style="list-style: none; font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif; font-size: 18px;"><a href="index.php?"> Αρχική Σελίδα</a></li>';
		 	echo '<li style="list-style: none; font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif; font-size: 18px;"><a href="index.php?content=ekp_profile"> Επεξεργασία προφίλ</a></li>';
		 	echo '<li style="list-style: none; font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif; font-size: 18px;"><a href="index.php?content=mathites_maint"> Διαχείριση μαθητών</a></li>';
		 	echo '<li style="list-style: none; font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif; font-size: 18px;"><a href="index.php?content=mathimata_maint"> Διαχείριση μαθημάτων</a></li>';
		 	echo '<li style="list-style: none; font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif; font-size: 18px;"><a href="index.php?content=ergasies_main"> Διαχείριση εργασιών</a></li>';
			echo '<li style="list-style: none; font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif; font-size: 19px;"><a href="index.php?content=adminkeywords_cloud"> Keywords Cloud</a></li>';
			echo '<br><center><a href="index.php?content=logout" style="font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif;" class="btn btn-primary" data-toggle="modal"><span class="glyphicon glyphicon-user"></span> Αποσύνδεση</a></center>';
			
		 } 
		 elseif (isset($_SESSION['math_id'])){  
		 	# Επιλογές αν έχει συνδεθεί μαθητής 
			echo '<ul>';
			echo '<li style="list-style: none; font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif; font-size: 18px;"><a href="index.php?"> Αρχική Σελίδα</a></li>';
			echo '<li style="list-style: none; font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif; font-size: 18px;"><a href="index.php?content=math_profile"> Επεξεργασία προφίλ</a></li>';
			echo '<li style="list-style: none; font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif; font-size: 18px;"><a href="index.php?content=mathimata_mathiti"> Τα μαθημάτα μου</a></li>';
			echo '<li style="list-style: none; font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif; font-size: 18px;"><a href="index.php?content=ergasies_mathiti"> Οι εργασίες μου</a></li>';
			echo '<li style="list-style: none; font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif; font-size: 19px;"><a href="index.php?content=keywords_cloud"> Keywords Cloud</a></li>';
			echo '<br><center><a href="index.php?content=logout" style="font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif;" class="btn btn-primary" data-toggle="modal"><span class="glyphicon glyphicon-user"></span> Αποσύνδεση</a></center>';
			
			}
		else{
			echo "Fatal error.";
		}

		?>
	<!--Κλάση "Bootstrap Modal" η οποία καλείτε όταν πατάμε είσοδο χρήστη-->
	<div id="myModal1" class="modal fade">
		<form id="frmLogin" action="index.php?task=login" method="post" name="frmLogin" >
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Είσοδος χρήστη</h4>
					</div>
					<div class="modal-body">
						<form id="frmLogin" name="frmLogin" action="index.php?task=login" method="post">
							<div class="form-group">
								<input type="text" style="font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif;" class="form-control" name="txtUserName" placeholder="Εισάγετε όνομα χρήστη" autocomplete="on" required autofocus>
							</div>
							<div class="form-group">
								<input type="password" style="font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif;" class="form-control" name="pwdPassword" placeholder="Εισάγετε κωδικό χρήστη" autocomplete="off" required autofocus>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<input name="btnEisodos" type="submit" value="Είσοδος"	style="font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif;" class="btn btn-primary" name="token"/>
						<button type="button" style="font-weight: bold; font-family: Garamond, Verdana, Helvetica, Geneva, sans-serif;" class="btn btn-default" data-dismiss="modal">Άκυρο</button>
					</div>
				</div>
			</div>
		</form>				
	</div> <!--τέλος modal για είσοδο χρήστη-->
	
</div>