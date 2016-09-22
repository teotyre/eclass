<!-- Περιεχόμενα που εμφανίζονται στην κεντρική σελίδα-->	
<!DOCTYPE html>
<html>
	<body>
		<!--Εδώ ξεκινάει το container όπου περιέχει ότι βλέπουμε στην οθόνη ΕΚΤΟΣ από το navbar-->
		<div class="container">
			<!--Σε αυτό το σημείο γίνεται το κάλεσμα της εναλλαγής του περιεχομένου της ιστοσελίδας μας-->
			<div class="main-content">
				<section>
					<?php
						include 'ptop.php'; 
						loadContent('content', 'home');
						include 'pbottom.php';
					?>
					<div class="clearfix"></div>
				</section>
			</div><!--τέλος εναλλαγής σελίδων-->
		</div><!--τέλος container-->
	</body>
</html>