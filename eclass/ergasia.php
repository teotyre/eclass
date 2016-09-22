<?php

 # Κλάση Εργασία
class Ergasia
{
    # Το ID της Εργασίας
    protected $erg_id;
    
    # Αρχείο εργασίας. Μεταβλητή string
    protected $erg_onoma;

    # Περιγραφή Εργασίας. Μεταβλητή string
    protected $erg_perigrafi;

    # Ορατότητα εργασίας.Μεταβλητή int
    protected $erg_is_visible;

    # Αρχείο εργασίας. Μεταβλητή string
    protected $erg_file;

    # Ημερομηνία ανάρτησης εργασίας. Μεταβλητή datetime
    protected $erg_datetime;

    # Ημερομηνία ανάρτησης εργασίας. Μεταβλητή int
    protected $les_les_id;
    
    # Αρχικοποίηση της κλάσης Ergasia (Κατασκευαστής)
    public function __construct($input = false) {
        if (is_array($input)) {
            foreach ($input as $key => $val) {
                $this->$key = $val;
            }
        }
    }

    # Συνάρτηση που επιστρέφει το ID της Εργασίας
    public function getId() {
        return $this->erg_id;
    }

    # Συνάρτηση που επιστρέφει το αρχείο της εργασίας.Επιστρέφει μεταβλητή string
    public function getOnoma() {
        return $this->erg_onoma;
    }

    # Συνάρτηση που επιστρέφει την περιγραφή της εργασίας. Επιστρέφει μεταβλητή string
    public function getPerigrafi() {
        return $this->erg_perigrafi;
    }
    
    # Συνάρτηση που επιστρέφει την ορατότητα της εργασίας. Επιστρέφει μεταβλητή int
    public function getVisibility() {
        return $this->erg_is_visible;
    }

    # Συνάρτηση που επιστρέφει το αρχείο της εργασίας.Επιστρέφει μεταβλητή string
    public function getFile() {
        return $this->erg_file;
    }
    
    # Συνάρτηση που επιστρέφει την ημερομηνία της εργασίας. Επιστρέφει μεταβλητή string
    public function getDatetime() {
        return $this->erg_datetime;
    }

    # Συνάρτηση που επιστρέφει την ορατότητα της εργασίας. Επιστρέφει μεταβλητή int
    public function getLesid() {
        return $this->les_les_id;
    }

    # Συνάρτηση που χρησιμοποιείται για την προσθήκη εγγραφής εργασίας στη βάση δεδομένων
    public function add() {
        # Σύνδεση με τη βάση δεδομένων
        $connection = Database::getConnection();
        $time = new DateTime;
        //Καθορίζει τη μορφοποίηση της ημερομηνίας και της ώρας
        $this->erg_datetime=$time->format("Y-m-d H:i:s");
        # Προετοιμασία των δεδομένων και δημιουργία ερωτήματος προς τη βάση
        $query = "INSERT INTO ergasia (erg_onoma,erg_perigrafi,erg_is_visible,erg_file,les_les_id)
                 VALUES (  	'" . Database::prep($this->erg_onoma) . "',
                 '" . Database::prep($this->erg_perigrafi) . "',
                 '" . Database::prep($this->erg_is_visible) . "',
                 '" . Database::prep($this->erg_file) . "',
                 '" . Database::prep($this->les_les_id) . "')";
        # Τρέχει το query που δημιούργησε 
        if ($connection->query($query)) { // αυτό προσθέτει την καινούργια εγγραφή στη βάση
            $return=array('','Επιτυχής εγγραφή της εργασίας.', '1');
            return $return;

        } else {
            # Στέλνει μήνυμα ότι απέτυχε η προσθήκη της καινούργιας εγγραφής στη βάση
            $return = array('', 'Δεν προστέθηκε η εργασία.', '0');
            return $return;
        }
    }

    # Συνάρτηση που χρησιμοποιείται για να τραβήξει όλες τις εργασίες
    public static function getErgasies() {
        # Καθαρισμός των αποτελεσμάτων
        $items = '';
        # Σύνδεση με τη βάση δεδομένων 
        $connection = Database::getConnection();
        # Δημιουργία ερωτήματος (query)
        $query = 'SELECT erg_id, erg_onoma, erg_perigrafi, erg_is_visible, erg_file,erg_datetime,les_les_id FROM ergasia ORDER BY erg_datetime DESC';
        # Τρέχει το query που δημιούργησε
        $result_obj = '';
        $result_obj = $connection->query($query);
        # Δημιουργία πίνακα που περιέχει τα αντικείμενα Ergasies
        try {
            while ($result = $result_obj->fetch_object('Ergasia')) {
                $items[]= $result;
            }
            # Επιστροφή των αποτελεσμάτων
            return($items);
        }
        catch (Exception $e) {
            return false;
        }
    }

    # Συνάρτηση που χρησιμοποιείται για να τραβήξει μια εργασία από τη βάση
    public static function getErgasia($id) {
        # Σύνδεση με τη βάση δεδομένων
        $connection = Database::getConnection();
        # Δημιουργία ερωτήματος (query)
        $query = 'SELECT erg_id, erg,onoma, erg_perigrafi, erg_is_visible, erg_file,erg_datetime,les_les_id FROM ergasia WHERE erg_id="'. (int) $id.'"';
        # Τρέχει την εντολή MySQL  
        $result_obj = '';
        try {
            $result_obj = $connection->query($query);
            if (!$result_obj) {
                throw new Exception($connection->error);
            } else {
                $item = $result_obj->fetch_object('Ergasia');
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