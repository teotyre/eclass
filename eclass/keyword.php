<?php
 # Κλάση Keyword
class Keyword
{
    
    # Το ID της λέξης κλειδί. Μεταβλητή int
    protected $keyw_id;
    
    # Η λέξη κλειδί. Μεταβλητή string
    protected $keyw_word;
   
    # Αρχικοποίηση της κλάσης Keyword (Κατασκευαστής)
    public function __construct($input = false) {
        if (is_array($input)) {
            foreach ($input as $key => $val) {
                $this->$key = $val;
            }
        }
    }

    # Συνάρτηση που επιστρέφει το ID της λέξης κλειδί.Επιστρέφει μεταβλητή int
    public function getId() {
        return $this->keyw_id;
    }

    # Συνάρτηση που επιστρέφει τη λέξη κλειδί. Επιστρέφει μεταβλητή string
    public function getKeyword() {
        return $this->keyw_word;
    }

    # Συνάρτηση που χρησιμοποιείται για την προσθήκη λέξης κλειδί στη βάση δεδομένων
    public function add() {

        # Σύνδεση με τη βάση δεδομένων
        $connection = Database::getConnection();
        // Προετοιμασία των δεδομένων και δημιουργία ερωτήματος προς τη βάση
        $query = "INSERT INTO keyword (keyw_word)
                 VALUES (  	'" . Database::prep($this->keyw_word) . "')";
        # Τρέχει το query που δημιούργησε 
        if ($connection->query($query)) { // αυτό προσθέτει την καινούργια εγγραφή στη βάση
            $return=array('','Η λέξη κλειδί προστέθηκε επιτυχώς.', '1');
            return $return;

        } else {
            # Στέλνει μήνυμα ότι απέτυχε η προσθήκη της καινούργιας εγγραφής στη βάση
            $return = array('', 'Δεν προστέθηκε η λέξη κλειδί.', '0');
            return $return;
        }
    }

    # Συνάρτηση που χρησιμοποιείται για να τραβήξει όλες τις λέξεις κλειδιά
    public static function getLexeis() {
        # Καθαρισμός των αποτελεσμάτων
        $items = '';
        # Σύνδεση με τη βάση δεδομένων 
        $connection = Database::getConnection();
        # Δημιουργία ερωτήματος (query)
        $query = 'SELECT keyw_id, keyw_word FROM keyword ORDER BY keyw_word';
        # Τρέχει το query που δημιούργησε
        $result_obj = '';
        $result_obj = $connection->query($query);
        # Δημιουργία πίνακα που περιέχει τα αντικείμενα Keyword
        try {
            while ($result = $result_obj->fetch_object('Keyword')) {
                $items[]= $result;
            }
            # Επιστροφή των αποτελεσμάτων
            return($items);
        }

        catch (Exception $e) {
            return false;
        }
    }

    # Συνάρτηση που χρησιμοποιείται για να τραβήξει μια συγκεκριμένη εγγραφή λέξης κλειδί από τη βάση
    public static function getLexi($id) {
        # Σύνδεση με τη βάση δεδομένων
        $connection = Database::getConnection();
        # Δημιουργία ερωτήματος (query)
        $query = 'SELECT keyw_id, keyw_word FROM keyword WHERE keyw_id="'. (int) $id.'"';
        # Τρέχει την εντολή MySQL  
        $result_obj = '';
        try {
            $result_obj = $connection->query($query);
            if (!$result_obj) {
                throw new Exception($connection->error);
            } else {
                $item = $result_obj->fetch_object('Keyword');
                if (!$item) {
                    throw new Exception($connection->error);
                } else {
                    # Επιστροφή των αποτελεσμάτων
                    return($item);
                }
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    # Συνάρτηση που χρησιμοποιείται για να τραβήξει μια συγκεκριμένη εγγραφή λέξης κλειδί από τη βάση
    public static function getId_by_name($word) {
        # Σύνδεση με τη βάση δεδομένων
        $connection = Database::getConnection();
        # Δημιουργία ερωτήματος (query)
        $query = 'SELECT keyw_id FROM keyword WHERE keyw_word="'. $word.'"';
        
        # Τρέχει την εντολή MySQL  
        $result_obj = '';
        try {
            $result_obj = $connection->query($query);
            if (!$result_obj) {
                throw new Exception($connection->error);
            } else {
                $item = $result_obj->fetch_object('Keyword');
                
                //print_r($item) ;
                if (!$item) {
                    throw new Exception($connection->error);
                } else {
                    # Επιστροφή των αποτελεσμάτων
                    
                    return($item);

                }
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}