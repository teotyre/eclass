<!-- Διεπαφή για το σύννεφο λέξεων των μαθητών-->
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
			//το id του μαθητή για να δείξουμε τις εργασίες που τον αφορούν
			$mathId=$_SESSION['math_id'];
			//Το ερώτημα επιστρέφει τις εργασίες για τα μαθήματα του μαθητή που είναι ορατές
			$ergasiesparquery='SELECT erg_id,erg_is_visible,les_les_id  FROM ergasia WHERE erg_is_visible=1
			AND les_les_id IN (SELECT par_les_id FROM parakolouthei WHERE math_math_id="'.$mathId.'") ';

			$result1 = ''; //καθαρισμός
			$result1 = $connection->query($ergasiesparquery);
			$rows1=$result1->num_rows; //ο αριθμος των εγγραφών που ανακτήθηκαν από το ερώτημα

			//echo $rows1;
			$lorem = ""; //αρχικοποίηση της μεταβλητής που θα φιλοξενήσει διαχωρισμένες με ένα κενό τις λέξεις κλειδιά
			for ($i=0; $i < $rows1; $i++) {
				$row1 = $result1->fetch_array();
				
				//Ανάκτηση όλων των id των λέξεων κλειδιών
				$query = 'SELECT keyw_keyw_id FROM ergasia_has_keyword WHERE erg_erg_id="'.$row1['erg_id'].'"';
				$result = $connection->query($query);
				$rows=$result->num_rows; //Πόσες εγγραφές ανακτήθηκαν

				//echo $rows;
				for ($j=0; $j < $rows; $j++) {
					$row = $result->fetch_array();
					//echo $row[keyw_keyw_id];
					//Ανάκτηση της λέξης κλειδί με βάση το id της
					$query1 = 'SELECT keyw_word FROM keyword WHERE keyw_id="'.$row['keyw_keyw_id'].'"'; 
					$result2 = $connection->query($query1);
					$row2 = $result2->fetch_array();
					//Γέμισμα του string $lorem με τις λέξεις κλειδιά διαχωρισμένες με κενό
					$lorem = $lorem ." ". strval($row2['keyw_word']); 
				}
			}
			//echo $lorem;
			//δημιουργούμε έναν πίνακα με τις λέξεις κλειδιά
			foreach(explode(" ", $lorem) as $word ){
				// Για κάθε λέξη που υπάρχει στον πίνακα με τις συχότητες την αυξάνουμε κατα 1
				 array_key_exists($word, $freqData) ? $freqData[$word]++ : $freqData[$word] = 0;
    	    }
    	    //Συνάρτηση για τη δημιουργία του σύννεφου λέξεων
    	    function getCloud( $data = array(), $minFontSize = 16, $maxFontSize = 40){
    	    	$connection = Database::getConnection()
    	    	or die ('Αδύναμία σύνδεσης με τη βάση δεδομένων.');
		
			    $minimumCount = min(array_values($data));//μικρότερη συχνότητα
			    $maximumCount = max(array_values($data));//μεγαλύτερη συχνότητα
			    $evros = $maximumCount - $minimumCount; //εύρος

			    $cloudHTML = '';
			    $cloudTags = array();
			     
			    $evros == 0 && $evros = 1;
			     
			    foreach( $data as $tag => $count ){
			    	$size = $minFontSize + ($count - $minimumCount)*($maxFontSize - $minFontSize)/$evros; //ορισμός του διαφορετικού μεγέθους των tags
					//Ανάκτηση όλων των Id των λέξεων κλειδιών
					$query2 = "SELECT keyw_id FROM keyword WHERE keyw_word='$tag'"; 
					$result2 = $connection->query($query2);
					$row2 = $result2->fetch_array();
					//Πέρασμα του id της λέξης κλειδί στο αρχείο ergasies_provoli_cloud_mathiti που εμφανίζει την ή τις εργασίες που συναντάται η λέξη κλειδι
					$cloudTags[] = '<a style="font-size: '.floor($size).'px'.'"class="tag_cloud" href="index.php?content=ergasies_provoli_cloud_mathiti&kwd='.$row2['keyw_id'].'&word='.$tag.'">' 
			    . htmlspecialchars(stripslashes($tag)) . '</a>';
			    }
			    
				return join( "\n", $cloudTags ) . "\n";
			}	
    
     		echo getCloud($freqData); 
    		?>
     	</div>
	</div>
	<div class="clearfix visible-sm-block"></div>
	<div class="clearfix visible-md-block"></div>
	<div class="clearfix visible-lg-block"></div><!--τέλος clearfix-->
</div>