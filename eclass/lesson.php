<?php

/**
 * Κλάση Lesson
 */
class Lesson
{

    /**
   * Το ID του μαθήματος
      */
    protected $les_id;

    /**
     * Όνομα μαθήματος
     * Μεταβλητή string
     */
    protected $les_onoma;

    /**
     * Περιγραφή του μαθήματος
     * Μεταβλητή string
     */
    protected $les_perigrafi;

    /**
   * Αρχικοποίηση της κλάσης Lesson (Κατασκευαστής)
   */
    public function __construct($input = false) {
        if (is_array($input)) {
            foreach ($input as $key => $val) {
                $this->$key = $val;
            }
        }
    }

    /**
    * Συνάρτηση που επιστρέφει το ID του μαθήματος
    */
    public function getId() {
        return $this->les_id;
    }

    /**
     * Συνάρτηση που επιστρέφει το όνομα του μαθήματος
     * Επιστρέφει μεταβλητή string
     */
    public function getOnoma() {
        return $this->les_onoma;
    }

    /**
     * Συνάρτηση που επιστρέφει την περιγραφή του μαθήματος
     * Επιστρέφει μεταβλητή string
     */
    public function getPerigrafi() {
        return $this->les_perigrafi;
    }

    // Συνάρτηση που χρησιμοποιείται για την προσθήκη εγγραφής μαθήματος στη βάση δεδομένων
    public function add() {


        // Σύνδεση με τη βάση δεδομένων
        $connection = Database::getConnection();
        // Προετοιμασία των δεδομένων και δημιουργία ερωτήματος προς τη βάση
        $query = "INSERT INTO lesson (les_onoma,les_perigrafi)
                 VALUES (  	'" . Database::prep($this->les_onoma) . "',
                 '" . Database::prep($this->les_perigrafi) . "')";
        // Τρέχει το query που δημιούργησε 
        if ($connection->query($query)) { // αυτό προσθέτει την καινούργια εγγραφή στη βάση
            $return=array('','Το μάθημα προστέθηκε επιτυχώς.', '1');
            return $return;

        } else {
            // Στέλνει μήνυμα ότι απέτυχε η προσθήκη της καινούργιας εγγραφής στη βάση
            $return = array('', 'Δεν προστέθηκε μάθημα.', '0');
            return $return;
        }
    }

    public function editRecord() {
        if ($this->_verifyInput()) {
            $connection = Database::getConnection();
            // Ερώτημα ενημέρωσης
            $query = "UPDATE `lesson`
            SET `les_onoma` = '" . Database::prep($this->les_onoma) . "',
                `les_perigrafi` = '" .Database::prep($this->les_perigrafi). "
            WHERE id='" . (int) $this->les_id . "'";
            if ($connection->query($query)) {
                $return = array('', 'Η ενημέρωση των στοιχείων έγινε επιτυχώς.', '');
                // μήνυμα επιτυχίας
                return $return;
            } else {
                // μήνυμα αποτυχίας
                $return = array('','Αδύνατη η ενημέρωση των στοιχείων.',(int) $this->les_id);
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

    public static function deleteLesson($id) {
        // σύνδεση με τη βάση
        $connection = Database::getConnection();
        // Ερώτημα διαγραφής
        $query = 'DELETE FROM `lesson` WHERE les_id="'. (int) $id.'"';
        if ($result = $connection->query($query)) {
        $return = array('', 'Η διαγραφή των στοιχείων έγινε επιτυχώς.', '');
        return $return;
        } else {
            $return = array('', 'Αδύνατη η διαγραφή των στοιχείων.', (int) $id);
            return $return;
        }
    }


    // Συνάρτηση που χρησιμοποιείται για να τραβήξει όλες τις εγγραφές μαθημάτων
    public static function getLessons() {
        // Καθαρισμός των αποτελεσμάτων
        $items = '';
        // Σύνδεση με τη βάση δεδομένων 
        $connection = Database::getConnection();
        // Δημιουργία ερωτήματος (query)
        $query = 'SELECT les_id, les_onoma, les_perigrafi FROM lesson ORDER BY les_onoma';
        // Τρέχει το query που δημιούργησε
        $result_obj = '';
        $result_obj = $connection->query($query);
        // Δημιουργία πίνακα που περιέχει τα αντικείμενα Lessons
        try {
            while ($result = $result_obj->fetch_object('Lesson')) {
                $items[]= $result;
            }
            // Επιστροφή των αποτελεσμάτων
            return($items);
        }

        catch (Exception $e) {
            return false;
        }
    }

    // Συνάρτηση που χρησιμοποιείται για να τραβήξει ένα συγκεκριμένο μάθημα από τη βάση
    public static function getLesson($id) {
        // Σύνδεση με τη βάση δεδομένων
        $connection = Database::getConnection();
        // Δημιουργία ερωτήματος (query)
        $query = 'SELECT les_id, les_onoma, les_perigrafi FROM lesson WHERE les_id="'. (int) $id.'"';
        // Τρέχει το ερώτημα  
        $result_obj = '';
        try {
            $result_obj = $connection->query($query);
            if (!$result_obj) {
                throw new Exception($connection->error);
            } else {
                $item = $result_obj->fetch_object('Lesson');
                if (!$item) {
                    throw new Exception($connection->error);
                } else {
                    // Επιστροφή των αποτελεσμάτων
                    return($item);
                }
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}