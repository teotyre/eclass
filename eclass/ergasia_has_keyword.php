<?php
 # Κλάση Ergasia_has_keyword
class Ergasia_has_keyword
{
    
    # Id εργασίας
    protected $erg_erg_id;
    
    # Id λέξης κλειδί
    protected $keyw_keyw_id;
   
    # Αρχικοποίηση της κλάσης Ergasia_has_keyword (Κατασκευαστής)
    public function __construct($input = false) {
        if (is_array($input)) {
            foreach ($input as $key => $val) {
                $this->$key = $val;
            }
        }
    }

    # Συνάρτηση που επιστρέφει το Id εργασίας
    public function getErg_Id() {
        return $this->erg_erg_id;
	}

    # Συνάρτηση που επιστρέφει Id λέξης κλειδί
    public function getKeyword_Id() {
        return $this->keyw_keyw_id;
    }

    # Συνάρτηση που χρησιμοποιείται για την προσθήκη λέξης κλειδί στη βάση δεδομένων
    public function add() {

        # Σύνδεση με τη βάση δεδομένων
        $connection = Database::getConnection();
        // Προετοιμασία των δεδομένων και δημιουργία ερωτήματος προς τη βάση
        $query = "INSERT INTO ergasia_has_keyword(erg_erg_id, keyw_keyw_id)
                     VALUES ('" . Database::prep($this->erg_erg_id) . "',
                     '" . Database::prep($this->keyw_keyw_id) . "')";
					 
			
			
						
					 
        # Τρέχει το query που δημιούργησε 
        if ($connection->query($query)) { // αυτό προσθέτει την καινούργια εγγραφή στη βάση
            $return=array('','Η σχέση προστέθηκε επιτυχώς.', '1');
		
		
            return $return;

        } else {
            # Στέλνει μήνυμα ότι απέτυχε η προσθήκη της καινούργιας εγγραφής στη βάση
            $return = array('', 'Δεν προστέθηκε η σχέση.', '0');
			
            return $return;
        }
    }
    


}

?>