<?php
@session_start();
?>
<div class="row">
	<?php
		include "lsidebar.php";
	?>
	<div class="col-sm-8 col-md-8 col-lg-8">
		<center><h3>Διαχείριση εργασιών</h3></center>
		<hr/>
		<div class="well">
			<div class="usrtype">
				<center><h4>Επιλογές</h4></center>

				<p class="text-center"><a href="index.php?content=ergasies_maint" title="Εισαγωγή εργασίας σε μάθημα">Εισαγωγή εργασίας σε μάθημα</a></p>
				<p class="text-center"><a href="index.php?content=ergasies_provoli" title="Προβολή όλων των εργασιών">Προβολή όλων των εργασιών</a></p>
			</div>
			
		</div>
	</div>
	<div class="clearfix visible-sm-block"></div>
	<div class="clearfix visible-md-block"></div>
	<div class="clearfix visible-lg-block"></div><!--τέλος clearfix-->
</div>