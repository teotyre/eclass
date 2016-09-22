<!-- Διεπαφή για το σύννεφο λέξεων του διαχειριστή-->
<?php
	$connection = Database::getConnection() 
	or die ('Αδύναμία σύνδεσης με τη βάση δεδομένων.');
	
?>	
	
	
<div class="row">
	
	<?php
	include "lsidebar.php";
	?>
	<div class="col-sm-8 col-md-8 col-lg-8">
		<center><h3>Σύννεφο λέξεων</h3></center>
		<hr />
		<div class="well">
			<?php
			// Αποθηκεύουμε τη συχνότητα εμφάνισης των λέξεων σε ένα πίνακα
			$freqData = array();
			//Το ερώτημα επιστρέφει τα Id όλων των λέξεων κλειδιών που είναι καταχωρυμένα στον πίνακα ergasia_has_keyword
			$query = "SELECT keyw_keyw_id FROM ergasia_has_keyword"; 
			$result = $connection->query($query);
			$rows=$result->num_rows; //Πόσες εγγραφές ανακτήθηκαν
			//αρχικοποίηση της μεταβλητής που θα φιλοξενήσει διαχωρισμένες με ένα κενό τις λέξεις κλειδιά
			$lorem = "";
			for ($i=0; $i < $rows; $i++) {
				$row = $result->fetch_array();
				//echo $row[keyw_keyw_id];
				//Ανάκτηση λέξης κλειδί με βάση το Id της
				$query1 = "SELECT keyw_word FROM keyword WHERE keyw_id='$row[keyw_keyw_id]'"; 
				$result1 = $connection->query($query1);
				$row1 = $result1->fetch_array();
				//Γέμισμα του string $lorem με τις λέξεις κλειδιά διαχωρισμένες με κενό
				$lorem = $lorem ." ". strval($row1['keyw_word']); 
			}
			//echo $lorem;
			//δημιουργούμε έναν πίνακα με τις λέξεις κλειδιά
			foreach( explode(" ", $lorem) as $word ){
				// Για κάθε λέξη που υπάρχει στον πίνακα με τις συχότητες την αυξάνουμε κατα 1
				array_key_exists($word, $freqData) ? $freqData[$word]++ : $freqData[$word] = 0;
    	    }
 
    		//Συνάρτηση για τη δημιουργία του σύννεφου λέξεων
		    function getCloud( $data = array(), $minFontSize = 16, $maxFontSize = 40 ) {
		    	
				$connection = Database::getConnection() 
				or die ('Αδύναμία σύνδεσης με τη βάση δεδομένων.');
					
			    $minimumCount = min(array_values($data));//μικρότερη συχνότητα
			    $maximumCount = max(array_values($data));//μεγαλύτερη συχνότητα
			    $evros = $maximumCount - $minimumCount; //εύρος

			    $cloudHTML = '';
			    $cloudTags = array();
			     
			    $evros == 0 && $evros = 1;
			     
			    foreach( $data as $tag => $count )
			    {
			    $size = $minFontSize + ($count - $minimumCount)*($maxFontSize - $minFontSize)/$evros; //ορισμός του διαφορετικού μεγέθους των tags
				//Ανάκτηση όλων των Id των λέξεων κλειδιών
				$query2 = "SELECT keyw_id FROM keyword WHERE keyw_word='$tag'"; 
				$result2 = $connection->query($query2);
				$row2 = $result2->fetch_array(); 
				//Πέρασμα του id της λέξης κλειδί στο αρχείο ergasies_provoli_cloud που εμφανίζει την ή τις εργασίες που συναντάται η λέξη κλειδι 
				$cloudTags[] = '<a style="font-size: ' . floor($size) . 'px' . '" class="tag_cloud" href="index.php?content=ergasies_provoli_cloud&kwd='.$row2['keyw_id'].'&word='.$tag.'">'. htmlspecialchars(stripslashes($tag)) . '</a>';
			    }

			    return join( "\n", $cloudTags ) . "\n";
			}	    
    
     		echo getCloud( $freqData ); 
     	?>
     	</div>
	</div>
	<div class="clearfix visible-sm-block"></div>
	<div class="clearfix visible-md-block"></div>
	<div class="clearfix visible-lg-block"></div><!--τέλος clearfix-->
</div>