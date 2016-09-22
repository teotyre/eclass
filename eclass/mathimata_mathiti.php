<!-- Διεπαφή για τη διαχείριση των μαθητών-->

<?php
	$connection = Database::getConnection() 
	or die ('Αδύναμία σύνδεσης με τη βάση δεδομένων.');
	include'lesson.php';
	
?>	
	
<div class="row">
	<?php include "lsidebar.php"; ?>
	
	<div class="col-sm-8 col-md-8 col-lg-8">
		<center><h3>Τα μαθήματα μου</h3></center>
		<hr />
		<div class="well">
		<?php 
		// Προβάλλει όλες τις καταχωρημένους μαθητές στον διαχειριστή
		
		$lessons = Lesson::getLessons();
		$mathId=$_SESSION['math_id'];
		// Ερώτημα που επιστρέφει τα μαθήματα που παρακολουθεί ο συνδεδεμένος μαθητής 
		$lessonparquery='SELECT les_id,les_onoma, les_perigrafi FROM lesson WHERE les_id IN (SELECT par_les_id FROM parakolouthei WHERE math_math_id="'.$mathId.'") ';
		
		//καθαρισμός
		$result_obj1 = '';
		$result_obj1 = $connection->query($lessonparquery);
		$nr=$result_obj1->num_rows;

		// Αν το ερώτημα δεν επιστρέψει γραμμές τότε βγαίνει κατάλληλο μήνυμα.
		if ($nr==0){
			echo '<center><h4>Δεν είστε γραμμένος ακόμα σε κάποιο μάθημα.</h4></center>';
		}
		else{ //σε περίπτωση που ο μαθητής παρακολουθεί τουλάχιστον ένα μάθημα
			while($row = $result_obj1->fetch_array()){
				$rows[] = $row;
			}
			//δημιουργία html πίνακα για την εμφάνιση των στοιχείων
			echo '<table width="100%" border="0" cellspacing=2 cellpadding=2  align=center class=maintext>';
			echo("<tr align=center valign=top>");
			echo("<td align=center >");
            echo("<h4>Μάθημα</h4>");
            echo("</td>");
            echo("<td align=center >");
		    echo("<h4>Περιγραφή</h4>");
		    echo("</td>");
            echo("</tr>");
            //Δυναμική απεικόνιση των αποτελεσμάτων
            foreach($rows as $row){
				echo("<tr align=center valign=top>");
				echo("<td align=center >");
	            echo htmlspecialchars($row['les_onoma']);
			    echo("</td>");
			    echo("<td align=center >");
			    echo htmlspecialchars($row['les_perigrafi']);
			    echo("</td>");
   	            echo("</tr>");
	        }
		}   
		// free result set 
		$result_obj1->close();
		// close connection
		$connection->close();    
     	echo '</table >';
     	?>
		</div>
	</div>
	<div class="clearfix visible-sm-block"></div>
	<div class="clearfix visible-md-block"></div>
	<div class="clearfix visible-lg-block"></div><!--τέλος clearfix-->
</div><!--end row-->

