<?php

# Κλάση Ekpaideytikos
 
class Ekpaideytikos 
{
    # Το id του εκπαιδευτικού, μεταβλητή int
    protected $ekp_id;

    # Το username του εκπαιδευτικού, μεταβλητή string
    protected $ekp_username;

    # Το password του εκπαιδευτικού, μεταβλητή string
    protected $ekp_password;

    # Τα χόμπι του εκπαιδευτικού, μεταβλητή string
    protected $ekp_onoma;

    # Η ημερομηνία γέννησης του εκπαιδευτικού, μεταβλητή date
    protected $ekp_eponymo;
   
    # Αρχικοποίηση της κλάσης Ekpaideytikos (Κατασκευαστής)
    public function __construct($input = false) {
        if (is_array($input)) {
            foreach ($input as $key => $val) {
                $this->$key = $val;
            }
        }
    }

    # Συνάρτηση που επιστρέφει τo id του εκπαιδευτικού. Επιστρέφει μεταβλητή int
    public function getId() {
        return $this->ekp_id;
    }

    # Συνάρτηση που επιστρέφει τo password του εκπαιδευτικού. Επιστρέφει μεταβλητή int
    public function getUsername() {
        return $this->ekp_username;
    }

    # Συνάρτηση που επιστρέφει το password του εκπαιδευτικού. Επιστρέφει μεταβλητή string
    public function getPassword() {
        return $this->ekp_password;
    }

    # Συνάρτηση που επιστρέφει τα ενδιαφέροντα του εκπαιδευτικού στο σύστημα. Επιστρέφει μεταβλητή string
    public function getOnoma() {
        return $this->ekp_onoma;
    }

    # Συνάρτηση που επιστρέφει την ημ/νια γέννησης του εκπαιδευτικού. Επιστρέφει μεταβλητή string
    public function getEponymo() {
        return $this->ekp_eponymo;
    }

    public function _verifyInput() {
        $error = false;
        if (!trim($this->title)) {
            $error = true;
        }
        if (!trim($this->text)) {
            $error = true;
        }
        if ($error) {
            return false;
        } else {
            return true;
        }
    }

    # Συνάρτηση που χρησιμοποιείται για την προσθήκη της εγγραφής ενός νέου εκπαιδευτικού
    public function add() {

        #Επαληθεύει τα πεδία με τη συνάρτηση _verifyInput
        if ($this->_verifyInput()) {
            # Προετοιμάζεται για το κρυπτογραφημένο password
            #$password = trim($_POST['password1']);

            # Σύνδεση με τη βάση δεδομένων
            $connection = Database::getConnection();
			
            # Δημιουργία ερωτήματος (query)
			$query = "INSERT INTO ekpaideytikos(ekp_username, ekp_password, ekp_onoma, ekp_eponymo)
                     VALUES ('" . Database::prep($this->ekp_username) . "',
                     '" . Database::prep($this->ekp_password) . "',
                     '" . Database::prep($this->ekp_onoma) . "',
                     '" . Database::prep($this->ekp_eponymo) . "')";

            # Τρέχει την εντολή MySQL για προσθήκη ενός χρήστη
            if ($connection->query($query)) { # αυτό προσθέτει την καινούργια εγγραφή στη βάση
                $return=array('','Ο εκπαιδευτικός προστέθηκε επιτυχώς.', '1');
                return $return;

            } else {
                # Στέλνει μήνυμα ότι απέτυχε η προσθήκη της καινούργιας εγγραφής στη βάση  
                $return = array('', 'Αδύνατη η προσθήκη χρήστη.', '0');
                return $return;
            }
        } else {
            # Στέλνει μήνυμα ότι απέτυχε η προσθήκη της καινούργιας εγγραφής στη βάση 
            $return = array('', 'Δεν προστέθηκε ο εκπαιδευτικός. Ελλιπείς πληροφορίες ή πρόβλημα με το username/password', '0');
            return $return;
        }

    }

    /**
    * Ενημέρωση εγγραφής
    * @return array (redirect content,message,id)
    */

    public function editRecord() {
        if ($this->_verifyInput()) {
            $connection = Database::getConnection();
            // Ερώτημα ενημέρωσης
            $query = "UPDATE `ekpaideytikos`
            SET `ekp_username` = '" . Database::prep($this->ekp_username) . "',
            `ekp_password` = '" . Database::prep($this->ekp_password) . "',
            `ekp_onoma` = '" . Database::prep($this->ekp_onoma) . "',
            `ekp_eponymo` = '" . Database::prep($this->ekp_eponymo) . "'
             WHERE id='" . (int) $this->ekp_id . "'";
            if ($connection->query($query)) {
                $return = array('', 'Η ενημέρωση των στοιχείων έγινε επιτυχώς.', '');
                // μήνυμα επιτυχίας
                return $return;
            } else {
                // μήνυμα αποτυχίας
                $return = array('','Αδύνατη η ενημέρωση των στοιχείων.',(int) $this->ekp_id);
                return $return;
            }
        } else {
            // μήνυμα αποτυχίας και επιστροφή
            $return = array('', 'Τα στοιχεία δεν ενημερώθηκαν. Ελλιπείς πληροφορίες.','0');
            return $return;
        }
    }

    /**
    * Διαγραφή μιας εγγραφής
    * @param int
    * @return array (redirect content,message,id)
    */

    public static function deleteEkpaideytikos($id) {
        $connection = Database::getConnection();
        // Ερώτημα διαγραφής
        $query = 'DELETE FROM `ekpaideytikos` WHERE ekp_id="'. (int) $id.'"';
        if ($result = $connection->query($query)) {
        $return = array('', 'Η διαγραφή των στοιχείων έγινε επιτυχώς.', '');
        return $return;
        } else {
            $return = array('', 'Αδύνατη η διαγραφή των στοιχείων.', (int) $id);
            return $return;
        }
    }

    /**
    * Επιλογή εκπαιδευτικού με βάση το id του - στην παρούσα έκδοση υπάρχει μόνο ένας
    * @param int
    * @return Ekpaideytikos
    */

    public static function getEkpaideytikos($id) {
        $connection = Database::getConnection();
        // Ερώτημα επιλογής
        $query = 'SELECT * FROM `ekpaideytikos` WHERE ekp_id="'. (int) $id.'"';
        $result_obj = '';
        try {
            $result_obj = $connection->query($query);
            if (!$result_obj) {
                throw new Exception($connection->error);
            } else {
                $item = $result_obj->fetch_object('Ekpaideytikos');
                if (!$item) {
                    throw new Exception($connection->error);
                } else {
                    return($item);
                }
            }
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
    * Επιλογή όλων των εκπαιδευτικών - στην παρούσα έκδοση υπάρχει μόνο ένας
    * @return array (Ekpaideytikos)
    */
    public static function getEkpaideytikoi() {
        $items = '';
        $connection = Database::getConnection();
        $query = 'SELECT * FROM `ekpaideytikos` ORDER BY ekp_id';
        $result_obj = '';
        $result_obj = $connection->query($query);
        try {
            while($result = $result_obj->fetch_object('Ekpaideytikos')) {
                $items[]= $result;
            }
            return($items);
        }
        catch(Exception $e) {
            return false;
        }
    }
}