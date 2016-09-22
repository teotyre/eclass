<?php
session_start(); // ξεκινάει ένα καινούργιο ή επαναφέρει ένα υπάρχων SESSION
define('MAGIC_QUOTES_ACTIVE', get_magic_quotes_gpc()); 

// προσθήκη των απαιτούμενων αρχείων (συναρτήσεις και κλάσεις)
require_once 'functions.php';
require_once 'database.php';

// Αν το task είναι το login 
if (isset($_GET['task']) AND($_GET['task']=='login')){
$results=UserLogin(); //Συνάρτηση για το login του χρήστη με την οποία γίνεται έναρξη ενός καινούργιου SESSION για την αποθήκευση μεταβλητών που σχετίζονται με αυτόν
$_SESSION['message'] = $results['1'];
}

// Αν το task είναι το logout 
if (isset($_GET['task']) AND($_GET['task']=='logout')){
$results=UserLogout(); //Συνάρτηση για το logout του χρήστη με την οποία γίνεται τερματισμός του τρέχοντος SESSION με τη διαγραφή των μεταβλητών που σχετίζονται με αυτόν
$_SESSION['message'] = $results['1'];
}

/**
 * Σύναρτηση η οποία όταν λάβει ως όρισμα το όνομα μιας κλάσης, φορτώνει το αντίστοιχο αρχείο της
 */
function __autoload($class_name) {
  try {
    $class_file = strtolower($class_name) . '.php';
    if (is_file($class_file)) {
      require_once $class_file;
    } else {
      throw new Exception("Δεν μπόρεσε να γίνει η φόρτωση της κλάσης $class_name με όνομα αρχείου $class_file.");
    }
  } catch (Exception $e) {
    echo 'Exception caught: ',  $e->getMessage(), "\n";
  }
}