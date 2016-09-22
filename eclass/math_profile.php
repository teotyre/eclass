<?php
@session_start();
?>
<div class="row">
	<?php
		include "lsidebar.php";
	?>
	<div class="col-sm-8 col-md-8 col-lg-8">
		<center><h3>Επεξεργασία προφίλ</h3></center>
		<hr/>
		<div class="well">
			<div class="usrtype">
				<center><h4>Επεξεργασία πληροφοριών χρήστη</h4></center>
				<!-- <p class="text-center"><a href="index.php?content=math_basicedit" title="Βασικές πληροφορίες">Βασικές πληροφορίες</a></p> -->
				<p class="text-center"><a href="index.php?content=change_math_password" title="Aλλαγή password">Aλλαγή κωδικού πρόσβασης</a></p>
			</div>
			<hr />
			
				<center><h4>Eπιπλέον πληροφορίες</h4></center>
				<div class="usrtype"><p class="text-center"><a href="index.php?content=math_more_edit" title="Επεξεργασία επιπλέον πληροφοριών">Προβολή - επεξεργασία</a></p></div>
			
		</div>
	</div>
	<div class="clearfix visible-sm-block"></div>
	<div class="clearfix visible-md-block"></div>
	<div class="clearfix visible-lg-block"></div><!--τέλος clearfix-->
</div>