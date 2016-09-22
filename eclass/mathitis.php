<?php

# Κλάση Mathitis
 
class Mathitis 
{
    # Το id του μαθητή, μεταβλητή int
    protected $math_id;

    # Τo username του μαθητή, μεταβλητή string
    protected $math_username;

    # Τo password του μαθητή, μεταβλητή string
    protected $math_password;

    # Τo όνομα του μαθητή, μεταβλητή string
    protected $math_onoma;

    # Τo επώνυμο του μαθητή, μεταβλητή string
    protected $math_eponymo;

    # Ο ρόλος του μαθητή, μεταβλητή string
    protected $math_rolos;

    # Τα χόμπι του μαθητή, μεταβλητή string
    protected $math_endiaferonta;

    # Η ημερομηνία γέννησης του μαθητή, μεταβλητή date
    protected $math_birthday;

    # Ο τόπος διαμονής του μαθητή, μεταβλητή string
    protected $math_diamoni;

    # Η φωτογραφία του μαθητή, μεταβλητή String
    protected $math_photo;
    
    # Αρχικοποίηση της κλάσης Mathitis (Κατασκευαστής)
    public function __construct($input = false) {
        if (is_array($input)) {
            foreach ($input as $key => $val) {
                $this->$key = $val;
            }
        }
    }

    # Συνάρτηση που επιστρέφει τo id του μαθητή. Επιστρέφει μεταβλητή int
    public function getId() {
        return $this->math_id;
    }

    # Συνάρτηση που επιστρέφει τo username του μαθητή στο σύστημα. Επιστρέφει μεταβλητή string
    public function getUsername() {
        return $this->math_username;
    }

    # Συνάρτηση που επιστρέφει τo password του μαθητή στο σύστημα. Επιστρέφει μεταβλητή string
    public function getPassword() {
        return $this->math_password;
    }

    # Συνάρτηση που επιστρέφει τo όνομα του μαθητή. Επιστρέφει μεταβλητή string
    public function getOnoma() {
        return $this->math_onoma;
    }

    # Συνάρτηση που επιστρέφει τo επώνυμο του μαθητή. Επιστρέφει μεταβλητή string
    public function getEponymo() {
        return $this->math_eponymo;
    }

    # Συνάρτηση που επιστρέφει τoν ρόλο του μαθητή στο σύστημα. Επιστρέφει μεταβλητή string
    public function getRolos() {
        return $this->math_rolos;
    }

    # Συνάρτηση που επιστρέφει τα ενδιαφέροντα του μαθητή στο σύστημα. Επιστρέφει μεταβλητή string
    public function getEndiaferonta() {
        return $this->math_endiaferonta;
    }

    # Συνάρτηση που επιστρέφει την ημ/νια γέννησης του μαθητή. Επιστρέφει μεταβλητή string
    public function getBirthday() {
        return $this->math_birthday;
    }

    # Συνάρτηση που επιστρέφει τον τόπο διαμονής του μαθητή. Επιστρέφει μεταβλητή string
    public function getDiamoni() {
        return $this->math_diamoni;
    }

    # Συνάρτηση που επιστρέφει το link της φωτογραφίας του μαθητή. Επιστρέφει μεταβλητή string
    public function getPhoto() {
        return $this->math_photo;
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

    # Συνάρτηση που χρησιμοποιείται για την προσθήκη της εγγραφής ενός νέου μαθητή
    public function add() {
        
            # Σύνδεση με τη βάση δεδομένων
            $connection = Database::getConnection();
			
            # Δημιουργία ερωτήματος (query)
			$query = "INSERT INTO mathitis(math_username, math_password, math_onoma, math_eponymo, math_endiaferonta, math_birthday, math_diamoni, math_photo)
                     VALUES ('" . Database::prep($this->math_username) . "',
                     '" . Database::prep($this->math_password) . "',
                     '" . Database::prep($this->math_onoma) . "',
                     '" . Database::prep($this->math_eponymo) . "',
                     '" . Database::prep($this->math_endiaferonta) . "',   
                     '" . Database::prep($this->math_birthday) . "',
                     '" . Database::prep($this->math_diamoni) . "',
                     '" . Database::prep($this->math_photo) . "')";

            # Τρέχει την εντολή MySQL για προσθήκη ενός χρήστη
            if ($connection->query($query)) { # αυτό προσθέτει την καινούργια εγγραφή στη βάση
                $return=array('','Ο μαθητής προστέθηκε επιτυχώς.', '1');
                return $return;

            } else {
                # Στέλνει μήνυμα ότι απέτυχε η προσθήκη της καινούργιας εγγραφής στη βάση  
                $return = array('', 'Αδύνατη η προσθήκη χρήστη.', '0');
                return $return;
            }
      

    }
    //}

    /**
    * Ενημέρωση μιας εγγραφής
    * @return array (redirect content,message,id)
    */

    public function editRecord() {
        if ($this->_verifyInput()) {
            $connection = Database::getConnection();
            // Ερώτημα ενημέρωσης
            $query = "UPDATE `mathitis`
            SET `math_username` = '" . Database::prep($this->math_username) . "',
            `math_password` = '" . Database::prep($this->math_password) . "',
            `math_onoma` = '" . Database::prep($this->math_onoma) . "',
            `math_eponymo` = '" . Database::prep($this->math_eponymo) . "',
            `math_endiaferonta` = '" . Database::prep($this->mmath_endiaferonta) . "',
            `math_birthday` = '" . Database::prep($this->math_birthday) . "',
            `math_diamoni` = '" . Database::prep($this->math_diamoni) . "',
            `math_photo` = '" .Database::prep($this->math_photo). "'
            WHERE id='" . (int) $this->math_id . "'";
            // Εκτέλεση του ερωτήματος
            if ($connection->query($query)) {
                $return = array('', 'Η ενημέρωση των στοιχείων έγινε επιτυχώς.', '');
                // μήνυμα επιτυχίας
                return $return;
            } else {
                // μήνυμα αποτυχίας
                $return = array('','Αδύνατη η ενημέρωση των στοιχείων.',(int) $this->math_id);
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

    public static function deleteMathitis($id) {
        // σύνδεση με τη βάση
        $connection = Database::getConnection();
        $query = 'DELETE FROM `mathitis` WHERE math_id="'. (int) $id.'"';
        if ($result = $connection->query($query)) {
        $return = array('', 'Η διαγραφή των στοιχείων έγινε επιτυχώς.', '');
        return $return;
        } else {
            $return = array('', 'Αδύνατη η διαγραφή των στοιχείων.', (int) $id);
            return $return;
        }
    }

    /**
    * Προβολή ενός μαθητή με βάση το Id του
    * @param int
    * @return Mathitis
    */

    public static function getMathitis($id) {
        
        $connection = Database::getConnection();
        $query = 'SELECT * FROM `mathitis` WHERE math_id="'. (int) $id.'"';
        $result_obj = '';
        try {
            $result_obj = $connection->query($query);
            if (!$result_obj) {
                throw new Exception($connection->error);
            } else {
                $item = $result_obj->fetch_object('Mathitis');
                if (!$item) {
                    throw new Exception($connection->error);
                } else {
                    // pass back the results
                    return($item);
                }
            }
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
    * Προβάλει τους μαθητές (σε ένα πίνακα)
    * @return array (Mathitis)
    */
    public static function getMathites() {
        // clear the results
        $items = '';
        // Get the connection
        $connection = Database::getConnection();
        $query = 'SELECT * FROM `mathitis` ORDER BY math_eponymo';
        // Run the query
        $result_obj = '';
        $result_obj = $connection->query($query);
        // Loop through the results,
        // passing them to a new version of this class,
        // and making a regular array of the objects
        try {
            while($result = $result_obj->fetch_object('Mathitis')) {
                $items[]= $result;
            }
            // pass back the results
            return($items);
        }
        catch(Exception $e) {
            return false;
        }
    }

}