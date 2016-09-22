<!-- Διεπαφή για την προβολή όλων των εργασιών-->
<!--css στοιχεία για την σελιδοποίηση των αποτελεσμάτων -->
<style type="text/css">
.pagNumActive {
    color: #000;
    border:#060 1px solid; background-color: #D2FFD2; padding-left:3px; padding-right:3px;
}
.paginationNumbers a:link {
    color: #000;
    text-decoration: none;
    border:#999 1px solid; background-color:#F0F0F0; padding-left:3px; padding-right:3px;
}
.paginationNumbers a:visited {
    color: #000;
    text-decoration: none;
    border:#999 1px solid; background-color:#F0F0F0; padding-left:3px; padding-right:3px;
}
.paginationNumbers a:hover {
    color: #000;
    text-decoration: none;
    border:#060 1px solid; background-color: #D2FFD2; padding-left:3px; padding-right:3px;
}
.paginationNumbers a:active {
    color: #000;
    text-decoration: none;
    border:#999 1px solid; background-color:#F0F0F0; padding-left:3px; padding-right:3px;
}
</style>
<?php
$connection = Database::getConnection() or die('Αδύναμία σύνδεσης με τη βάση δεδομένων.');
include 'mathitis.php';
include "lsidebar.php";
?>	
<div class="row">
	<div class="col-sm-8 col-md-8 col-lg-8">
		<center><h3>Καταχωρημένες εργασίες</h3></center>
		<hr />
		<div class="well">
		<?php
		// Επιστρέφει όλες τις καταχωρημένες εργασίες
		$items = Ergasia::getErgasies();
		// Επιστρέφει όλες τα καταχωρημένα μαθήματα
		$lessons = Lesson::getLessons();
		$nr = count($items);
		//echo $nr;
		if (isset($_GET['pn'])) {// Αν υπάρχει η παράμετρος pn = page number
			$pn = preg_replace('#[^0-9]#i', '', $_GET['pn']);// Φιλτράρισμα με regex για ασφάλεια
		} else {// Αν δεν υπάρχει η pn στο URL τότε το θέτουμε ίσο με 1
			$pn = 1;
		}

		$itemsPerPage = 5;//Αριθμός ορισμού σελιδοποίησης
		$lastPage = ceil($nr / $itemsPerPage);
		if ($pn < 1) { // Αν είναι μικρότερη του 1
			$pn = 1;// τότε την θέτουμε ίση με 1
		} else if ($pn > $lastPage) {// αν είναι μεγαλύτερη της $lastpage
			$pn = $lastPage;
			// τότε την θέτουμε ίση με την τιμή της $lastpage
		}

		// Δημιουργούμε τους αριθμούς των σελίδων που εμφανίζονται καθώς και τα κουμπιά επόμενη και προηγούμενη
		$centerPages = "";
		$sub1 = $pn - 1;
		$sub2 = $pn - 2;
		$add1 = $pn + 1;
		$add2 = $pn + 2;
		if ($pn == 1) {
			$centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
			$centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli&pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
		} else if ($pn == $lastPage) {
			$centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli&pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
			$centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
		} else if ($pn > 2 && $pn < ($lastPage - 1)) {
			$centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli&pn=' . $sub2 . '">' . $sub2 . '</a> &nbsp;';
			$centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli&pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
			$centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
			$centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli&pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
			$centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli&pn=' . $add2 . '">' . $add2 . '</a> &nbsp;';
		} else if ($pn > 1 && $pn < $lastPage) {
			$centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli&pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
			$centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
			$centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli&pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
		}

		// Δημιουργούμε το όρισμα LIMIT που είναι απαραίτητο για τη σελιδοποίηση
		$limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;
		// Το ερώτημα επιστρέφει όλες τις εργασίες ταξινομημένες από τη νεώτερη προς την παλαιότερη (με σελιδοποίηση)
		$query = "SELECT erg_onoma, erg_perigrafi, erg_is_visible, erg_file,erg_datetime,les_les_id FROM ergasia ORDER BY erg_datetime DESC $limit";
		$result = $connection -> query($query);
		while ($row = $result -> fetch_array()) {
			$rows[] = $row;
		}

		

		$paginationDisplay = "";// Αρχικοποίηση της μεταβλητής που κρατάει την έξοδο της σελιδοποίησης
		// Αν η τελευταία σελίδα είναι διάφορη του 1
		if ($lastPage != "1") {
			// Δείχνουμε στον χρήστη σε ποια σελίδα είναι και πόσες σελίδες συνολικά καταλαμβάνουν οι εργασίες
			$paginationDisplay .= 'Σελίδα <strong>' . $pn . '</strong> από ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';
			// If we are not on page 1 we can place the Back button
			if ($pn != 1) {
				$previous = $pn - 1;
				$paginationDisplay .= '&nbsp;  <a href="' . 'index.php?content=ergasies_provoli&pn=' . $previous . '"> Προηγούμενη</a> ';
			}
			// Οι αριθμοί των σελίδων ανάμεσα στο "Προηγούμενη" και στο "Επόμενη"
			$paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
			// Προσθέτουμε την επιλογή "Επόμενη" αν δεν είμαστε στην τελευταία σελίδα
			if ($pn != $lastPage) {
				$nextPage = $pn + 1;
				$paginationDisplay .= '&nbsp;  <a href="' . 'index.php?content=ergasies_provoli&pn=' . $nextPage . '"> Επόμενη</a> ';
			}
		}

		if (!$items) {
			echo '<center><h4>Δεν έχουν καταχωρηθεί εργασίες</h4></center>';
		} else {
			//δημιουργία html πίνακα για την εμφάνιση των στοιχείων
			echo '<table width="100%" border="0" cellspacing=2 cellpadding=2  align=center class=maintext>';
			echo("<tr align=center valign=top>");
			echo("<td align=center >");
			echo("<h4>Όνομα</h4>");
			echo("</td>");
			echo("<td align=center >");
			echo("<h4>Περιγραφή</h4>");
			echo("</td>");
			echo("<td align=center >");
			echo("<h4>Αρχείο</h4>");
			echo("</td>");
			echo("<td align=center >");
			echo("<h4>Ημερομηνία Ανάρτησης</h4>");
			echo("</td>");
			echo("<td align=center >");
			echo("<h4>Μάθημα</h4>");
			echo("</td>");
			echo("<td align=center >");
			echo("<h4>Ορατή?</h4>");
			echo("</td>");
			echo("</tr>");
			// Δυναμική απεικόνιση των αποτελσμάτων
			foreach ($rows as $row) {

				echo("<tr align=center valign=top>");
				echo("<td align=center >");
				echo htmlspecialchars($row['erg_onoma']);
				echo("</td>");
				echo("<strong><td align=center ></strong>");
				echo htmlspecialchars($row['erg_perigrafi']);
				echo("</td>");
				echo("<td align=center >");
				echo("<a href=" . $row['erg_file'] . " class=mainlink>" . substr($row['erg_file'], 19) . "</a>");
				echo("</td>");
				echo("<td align=center >");
				echo htmlspecialchars(date('d-m-Y  H:i:s', strtotime($row['erg_datetime'])));
				echo("</td>");
				echo("<td align=center >");
				$id = $row['les_les_id'];
				$lesson = Lesson::getLesson($id);
				echo htmlspecialchars($lesson -> getOnoma());//συσχετισμός του ξένου κλειδιού με το όνομα του μαθήματος
				echo("</td>");
				echo("<td align=center >");
				if ($row['erg_is_visible'] == 1) {
					echo("Ναι");
				} else {
					echo("Όχι");//decode του radio button από 0,1 σε οχι, ναι αντίστοιχα 
				}
				echo("</td>");
				echo("</tr>");
			}
			/* free result set */
			$result -> close();
			/* close connection */
			$connection -> close();
		}
		echo '</table >';
		?>

		</div>
		<?php
				// Αν οι σελίδες είναι λιγότερες ή ίσες της μονάδας δεν εμφανίζεται το μενού της σελιδοποίησης.
		if ($paginationDisplay!="") {
			echo '<div style="margin-left:5px; margin-right:5px; padding:6px; background-color:#FFF; border:#999 1px solid;">';
			echo $paginationDisplay; echo '</div>';
		}

		?>
	</div>
	<div class="clearfix visible-sm-block"></div>
	<div class="clearfix visible-md-block"></div>
	<div class="clearfix visible-lg-block"></div><!--τέλος clearfix-->
</div>     