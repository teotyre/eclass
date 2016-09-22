<!-- Σύνδεση στη Βάση Δεδομένων -->

<?php
/**
 * Κλάση Database
 * Για τη σύνδεση με τη βάση δεδομένων
 */
class Database {
    /**
     * Όνομα χρήστη της βάσης δεδομένων
     * Μεταβλητή string $_mysqlUser
     */
	 
    private static $_mysqlUser = 'root';
    /**
     * Κωδικός πρόσβασης για τη σύνδεση στη βάση δεδομένων
     * Μεταβλητήstring $_mysqlPass
     */
	 
    private static $_mysqlPass = '';
    /**
     * Το όνομα της βάσης
     * Μεταβλητή string $_mysqlDb
     */
	 
    private static $_mysqlDb = 'eclass';
    /**
     * Κωδική ονομασία του server
     * Μεταβλητή string $_hostName
     */
	 
    private static $_hostName = 'localhost';
	
    /**
     * Μεταβλητή στην οποία αποθηκεύονται οι πληροφορίες της σύνδεσης
     * Μεταβλητή Mysqli $connection
     */ 
    private static $_connection = NULL;

    /**
     * Κατασκευαστής
     */
    private function __construct() {
    }

    /**
     * Συνάρτηση δημιουργίας σύνδεσης με τη βάση δεδομένων
     * Επιστρέφει αντικείμενο Mysqli
     */
    public static function getConnection() {
	//Αν δεν υπάρχει σύνδεση φτιάχνει μια καινούργια, εισάγωντας τους απαραίτητους κωδικούς
        if (!self::$_connection) {
            self::$_connection = @new mysqli(self::$_hostName, self::$_mysqlUser, self::$_mysqlPass, self::$_mysqlDb);
            if (self::$_connection->connect_error) {
                die('Πρόβλημα σύνδεσης: ' . self::$_connection->connect_error);
            }
        //έλεγχος αν το charset είναι το utf8 και αλλαγή σε αυτό http://php.net/manual/en/mysqli.set-charset.php
        if (!self::$_connection->set_charset("utf8")) {
            printf("Πρόβλημα στην φόρτωση του character set utf8: %s\n", self::$_connection->error);
            } else {
            self::$_connection->character_set_name();
            }

        }

        return self::$_connection;
    }

	//Συνάρτηση προετοιμασίας μιας μεταβλητής για εισαγωγή στη βάση
    public static function prep($value) {
        if (get_magic_quotes_gpc()) {
            // Αν magic quotes είναι ενεργοποιημένα, απομακρύνει τα κενά
            $value = stripslashes($value);
        }
        // Παρακάμπτει τους ειδικούς χαρακτήρες για αποφυγή επιθέσεων στη βάση
        @$value = self::$_connection->real_escape_string($value);
        return $value;
    }

}
