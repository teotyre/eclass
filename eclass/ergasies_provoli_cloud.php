

<!-- Διεπαφή για τη διαχείριση των μαθητών-->
<style type="text/css">
<!--
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
-->
</style>
<?php
	$connection = Database::getConnection()
	or die ('Αδύναμία σύνδεσης με τη βάση δεδομένων.');
	include 'mathitis.php';
include "lsidebar.php";
?>	
	
	
	<div class="row">
	<div class="col-sm-8 col-md-8 col-lg-8">
		<center><h3>Εργασίες που περιέχουν τη λέξη: <?php echo $_GET['word']  ?></h3></center>
	<hr />
	<div class="well">
		<?php 
		// Προβάλλει όλες τις καταχωρημένους μαθητές στον διαχειριστή
		
		$keyw= $_GET['kwd'];
		$query2 = "SELECT erg_erg_id FROM ergasia_has_keyword WHERE keyw_keyw_id='$keyw' "; //Ανάκτηση όλων των Id των λέξεων κλειδιά
	
		$result2 = $connection->query($query2);
		
		//echo gettype($result2);
	
		$row2 = $result2->fetch_array();
		$nr=$result2->num_rows;
		
		//echo $nr;
		if (isset($_GET['pn'])) { // Get pn from URL vars if it is present
    		$pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers for security(new)
    		
    		} else { // If the pn URL variable is not present force it to be value of page number 1
    			$pn = 1;
    			
    		} 


		$itemsPerPage=5;
		$lastPage=ceil($nr/$itemsPerPage);
		//echo $lastPage;
		if ($pn < 1) { // If it is less than 1
    		$pn = 1; // force if to be 1
    		} else if ($pn > $lastPage) { // if it is greater than $lastpage
    			$pn = $lastPage; // force it to be $lastpage's value

		}

		// This creates the numbers to click in between the next and back buttons
		// This section is explained well in the video that accompanies this script
		$centerPages = "";
		$sub1 = $pn - 1;
		$sub2 = $pn - 2;
		$add1 = $pn + 1;
		$add2 = $pn + 2;
		if ($pn == 1) {
		    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
		    $centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli_cloud&kwd='.$keyw.'&word='.$_GET['word'].'&pn='. $add1 . '">' . $add1 . '</a> &nbsp;';
		} else if ($pn == $lastPage) {
		    $centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli_cloud&kwd='.$keyw.'&word='.$_GET['word'].'&pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
		    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
		} else if ($pn > 2 && $pn < ($lastPage - 1)) {
		    $centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli_cloud&kwd='.$keyw.'&word='.$_GET['word'].'&pn=' . $sub2 . '">' . $sub2 . '</a> &nbsp;';
		    $centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli_cloud&kwd='.$keyw.'&word='.$_GET['word'].'&pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
		    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
		    $centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli_cloud&kwd='.$keyw.'&word='.$_GET['word'].'&pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
		    $centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli_cloud&kwd='.$keyw.'&word='.$_GET['word'].'&pn=' . $add2 . '">' . $add2 . '</a> &nbsp;';
		} else if ($pn > 1 && $pn < $lastPage) {
		    $centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli_cloud&kwd='.$keyw.'&word='.$_GET['word'].'&pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
		    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
		    $centerPages .= '&nbsp; <a href="' . 'index.php?content=ergasies_provoli_cloud&kwd='.$keyw.'&word='.$_GET['word'].'&pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
		}

		// This line sets the "LIMIT" range... the 2 values we place to choose a range of rows from database in our query
		$limit = 'LIMIT ' .($pn - 1) * $itemsPerPage .',' .$itemsPerPage; 



		$query = "SELECT erg_onoma, erg_perigrafi, erg_is_visible, erg_file,erg_datetime,les_les_id FROM ergasia  
		WHERE erg_id IN (SELECT erg_erg_id FROM ergasia_has_keyword WHERE keyw_keyw_id= '$keyw')    ORDER BY erg_datetime DESC $limit";
		
		$result = $connection->query($query);
		while($row = $result->fetch_array())
{
$rows[] = $row;
}

		$paginationDisplay = ""; // Initialize the pagination output variable
		// This code runs only if the last page variable is ot equal to 1, if it is only 1 page we require no paginated links to display
		if ($lastPage != "1"){
   		 // This shows the user what page they are on, and the total number of pages
    	$paginationDisplay .= 'Σελίδα <strong>' . $pn . '</strong> από ' . $lastPage. '&nbsp;  &nbsp;  &nbsp; ';
    	// If we are not on page 1 we can place the Back button
    	if ($pn != 1) {
        $previous = $pn - 1;
        $paginationDisplay .=  '&nbsp;  <a href="' . 'index.php?content=ergasies_provoli_cloud&kwd='.$keyw.'&word='.$_GET['word'].'&pn=' /*. '?pn='*/ . $previous . '"> Προηγούμενη</a> ';
    	} 
		    // Lay in the clickable numbers display here between the Back and Next links
		    $paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
		    // If we are not on the very last page we can place the Next button
		    if ($pn != $lastPage) {
		        $nextPage = $pn + 1;
		        $paginationDisplay .=  '&nbsp;  <a href="' . 'index.php?content=ergasies_provoli_cloud&kwd='.$keyw.'&word='.$_GET['word'].'&pn=' /*. '?pn='*/ . $nextPage . '"> Επόμενη</a> ';
		    } 
		}

	
		if (!$row2){
			echo '<center><h4>Δεν έχουν καταχωρηθεί εργασίες</h4></center>';
			}
		else{


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
		            	echo ("<h4>Ορατή</h4>");
		            echo("</td>");
                echo("</tr>");

                foreach($rows as $row){
      			
					echo("<tr align=center valign=top>");

						echo("<td align=center >");
							//echo "1";
			                //echo htmlspecialchars($item->getOnoma());
						echo htmlspecialchars($row['erg_onoma']);
			            echo("</td>");
			            
			            echo("<strong><td align=center ></strong>");
			            	//echo htmlspecialchars($item->getPerigrafi());
			            echo htmlspecialchars($row['erg_perigrafi']);
			            echo("</td>");
			            
			            echo("<td align=center >");
			            	echo("<a href=".$row['erg_file']." class=mainlink>".substr($row['erg_file'], 19)."</a>");
			            //echo htmlspecialchars($row['erg_file']);
			            echo("</td>");
			            
			            echo("<td align=center >");
			                echo htmlspecialchars(date('d-m-Y  H:i:s' ,strtotime($row['erg_datetime'])));
			            echo("</td>");
			            
			            echo("<td align=center >");
			            	$id=$row['les_les_id'];
			            	$lesson=Lesson::getLesson($id);
			            	echo htmlspecialchars($lesson->getOnoma());
			            echo("</td>");
			             
			            echo("<td align=center >");
			            	if($row['erg_is_visible']==1){
							echo("Ναι");}
              				else{
								echo("Όχι");}
			            echo("</td>");
		          
		           echo("</tr>");
		         }  
		         /* free result set */
$result->close();

/* close connection */
$connection->close();
			 }   
    
     echo '</table >';?>

	</div>
	<!--<center><a style="width: 30%;" class="btn btn-block btn-primary cancel" href="index.php?content=ergasies_main">Επιστροφή</a></center>-->
	<!--<div style="margin-left:64px; margin-right:64px;">-->
	<div style="margin-left:5px; margin-right:5px; padding:6px; background-color:#FFF; border:#999 1px solid;"><?php echo $paginationDisplay; ?></div>
<!--<div style="margin-left:64px; margin-right:64px;"><?php print "$outputList"; ?></div>-->
	</div>
	
</div>

     
   </div> 
     
      
      