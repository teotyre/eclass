<?php

$message='';

# loadContent  Φόρτωση περιεχομένου
  
# Συνάρτηση που επιστρέφει κείμενο το οποίο οδηγεί στο αρχείο που έχει πάρει σαν όρισμα η συνάρτηση
function loadContent($where, $default='') {
    # Αποθήκευση ορισμάτων στις μεταβλητές 
    $content = filter_input(INPUT_GET, $where, FILTER_SANITIZE_STRING);
    $default = filter_var($default, FILTER_SANITIZE_STRING);
    # Αν δεν υπάρχει όρισμα, χρησιμοποιεί την τιμή default 
    $content = (empty($content)) ? $default : $content;
    # Αν υπάρχει  όρισμα 
    if ($content) {
        # δημιουργία του path προς το επιθυμητό αρχείο και επιστροφή της τιμής του
        $html = include $content.'.php';
        return $html;
    }
}

# Συνάρτηση για το login του χρήστη με την οποία γίνεται έναρξη ενός καινούργιου SESSION για την αποθήκευση μεταβλητών που σχετίζονται με αυτόν
function userLogin() {
    $results = array('login','','');
	# Αποθήκευση των μεταβλητών στον πίνακα item 
    if (isset($_POST['btnEisodos']) AND $_POST['btnEisodos'] == 'Είσοδος') {
        
        $item  = array ( 'usr_username'  => filter_input(INPUT_POST,'txtUserName', FILTER_SANITIZE_STRING),
                         'usr_password'  => filter_input(INPUT_POST,'pwdPassword', FILTER_SANITIZE_STRING)
                       );
		#  Ελέγχει αν έχουν εισαχθεί σωστά το username και το password
        if (!$item['usr_username'] || !$item['usr_password']) {
            $results=array('login','Το username ή το password είναι λάθος.');
        }
        #  Σύνδεση με τη βάση δεδομένων
        $connection = Database::getConnection();
        #  Λαμβάνει το id και το password του Εκπαιδευτικού-Διαχειριστή με βάση το username που δόθηκε
        $query1 = 'SELECT ekp_id, ekp_password FROM ekpaideytikos WHERE ekp_username="'. $item['usr_username'] .'" LIMIT 1';
        $result_obj1 = $connection->query($query1);
        $row1 = $result_obj1->fetch_assoc();

        $query2 = 'SELECT math_id, math_password FROM mathitis WHERE math_username="'. $item['usr_username'] .'" LIMIT 1';
        $result_obj2 = $connection->query($query2);
        $row2 = $result_obj2->fetch_assoc();

        $id1 = $row1['ekp_id'];
        $id2 = $row2['math_id'];

        $admin=false;

        if (!$id1) {
            #echo "Δεν είναι διαχειριστής";
            if (!$id2) {
                #echo "Δεν βρέθηκε τέτοιος χρήστης";
                $results=array('login','Το username είναι λάθος ή ο χρήστης δεν υπάρχει.',''); # Αν δε βρεθεί το username στον πίνακα, εμφανίζει μήνυμα λάθους
            }
            else {
            # $password = hash_hmac('sha512', $item['ekp_password'] . '!hi#HUde9',SITE_KEY);
                #echo "einai mathitis me id:". $id2;
            $password = $item['usr_password'] ;
            if ($row2['math_password']!=$password){
                #echo "του μαθητή einai ". $row2['math_password'];
                #echo "η τιμή που έβαλε είναι". $password;
                $results=array('login','Το password είναι λάθος.',''); # Αν δε ταυτίζεται το password, εμφανίζει μήνυμα λάθους
                }
                else {
                    # Δημιουργία query
                    #echo "trexei to sql query2";
                    $query2 = 'SELECT math_id, math_username, math_password, math_onoma, math_eponymo
                       FROM mathitis
                       WHERE math_username="'. $item['usr_username'] .'"
                       AND math_password= "'. $password .'"
                       LIMIT 1';
                    # Τρέχει το query που δημιούργησε παραπάνω
                    $result_obj = '';
                    $result_obj = $connection->query($query2);

                    try {
                        while ($result = $result_obj->fetch_array(MYSQLI_ASSOC)) {
                            # Δημιουργία των μεταβλητών SESSION για τον μαθητή
                            $_SESSION['math_id'] = $result['math_id'];
                            $_SESSION['math_username'] = $result['math_username'];
                            $_SESSION['math_onoma'] = $result['math_onoma'];
                            $_SESSION['math_eponymo'] = $result['math_eponymo'];
                            #echo "prepei na kalsorisei mathiti";
                            #echo $_SESSION['math_id'];
                            $results=array('',"Καλωσήρθατε, έχετε συνδεθεί ως μαθητής.", '');
                        }
                    }
                    catch (Exception $e){
                        $results=array('',"Απόπειρα κακόβουλης σύνδεσης. Η IP σας έχει καταγραφεί!", '');
                    }
                }
            }
        }
        else {    
            $results=array('login','Το username είναι λάθος ή ο χρήστης δεν υπάρχει.','');# Αν δε βρεθεί το username στον πίνακα, εμφανίζει μήνυμα λάθου
            $password = $item['usr_password'] ; 
            if ($row1['ekp_password']!=$password){
                #echo "του διαxειριστή einai ". $row1['ekp_password'];
                $results=array('login','Το password είναι λάθος.',''); # Αν δε ταυτίζεται το password, εμφανίζει μήνυμα λάθους
                }
                else {
                    $admin=true;
                    # Δημιουργία query
                    $query1 = 'SELECT ekp_id, ekp_username, ekp_password, ekp_onoma, ekp_eponymo
                       FROM ekpaideytikos
                       WHERE ekp_username="'. $item['usr_username'] .'"
                       AND ekp_password= "'. $password .'"
                       LIMIT 1';
                    # Τρέχει το query που δημιούργησε παραπάνω
                    $result_obj = '';
                    $result_obj = $connection->query($query1);

                    try {
                        while ($result = $result_obj->fetch_array(MYSQLI_ASSOC)) {
                            # Δημιουργία των μεταβλητών SESSION για τον Διαχειριστή
                            $_SESSION['ekp_id'] = $result['ekp_id'];
                            $_SESSION['ekp_username'] = $result['ekp_username'];
                            $_SESSION['ekp_onoma'] = $result['ekp_onoma'];
                            $_SESSION['ekp_eponymo'] = $result['ekp_eponymo'];
                            #echo "prepei na kalsorisei kathigiti";
                            #echo "session admin".$_SESSION['ekp_id'];
                            $results=array('',"Καλωσήρθατε, έχετε συνδεθεί ως διαχειριστής.", '');
                        }
                    }
                    catch (Exception $e){
                        $results=array('',"Απόπειρα κακόβουλης σύνδεσης. Η IP σας έχει καταγραφεί!", '');
                    }
                }
            }
        
    }
    else {
        $results=array('',"Δεν επιτρέπεται η είσοδος με αυτόν τον τρόπο!", '');
    }
    return $results;
}

# Συνάρτηση για το logout του χρήστη με την οποία γίνεται τερματισμός του τρέχοντος SESSION με τη διαγραφή των μεταβλητών που σχετίζονται με αυτόν
function userLogout() {
   $results = array('login','','');;
    if (isset($_POST['logout']) AND $_POST['logout'] == 'Έξοδος') {
	# Διαγραφή όλων των μεταβλητών που δημιουργήθηκαν στο SESSION
        if (isset($_SESSION['math_id'])) {
            unset($_SESSION['math_id']);
            unset($_SESSION['math_onoma']);
            unset($_SESSION['math_eponymo']);
            unset($_SESSION['math_username']);
        }
        

        if (isset($_SESSION['ekp_id'])) {
            unset($_SESSION['ekp_id']);
            unset($_SESSION['ekp_onoma']);
            unset($_SESSION['ekp_eponymo']);
            unset($_SESSION['ekp_username']);
        }
                
    $results = array('','','');
    }

    return $results;
}

# Συνάρτηση που ελέγχει αν ο χρήστης που συνδέθηκε είναι διαχειριστής
function isAdmin() {

    $connection = Database::getConnection();

    $query = 'SELECT ekp_rolos
             FROM ekpaideytikos
             WHERE ekp_id="'. $_SESSION['ekp_id'] .'"';
    $result_obj = '';
    $result_obj = $connection->query($query);
    $admin=false;
    while ($result = $result_obj->fetch_array(MYSQLI_ASSOC)) {

        if ($result['ekp_rolos']=="Εκπαιδευτικός") {
            $admin=true;
        }
    }
    return $admin;
}