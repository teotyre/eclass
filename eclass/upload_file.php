<?php
// ορίζουμε το φάκελο στον οποίο θα ανεβαίνουν οι φωτογραφίες
$target_dir = "uploads/";
// δίνουμε timestamp στο αρχείο που θα ανέβει για να εξασφαλίσουμε οτι θα είναι διαφορετικό από τα ήδη ανεβασμένα
$target_file = $target_dir . time() . "_" . basename($_FILES["fileToUpload"]["name"]);


$uploadOk = 1; // μεταβλητή ελέγχου της διαδικασίας ανεβάσματος
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// ελέγχουμε αν το αρχείο είναι πράγματι αρχείο τύπου φωτογραφίας
//if(isset($_POST["submit"])) {
//    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//    if($check !== false) {
//        echo "Το αρχείο είναι εικόνα - " . $check["mime"] . ".";
//        $uploadOk = 1;
//    }
//    else {
//        echo "Το αρχείο δεν είναι εικόνα.";
//        $uploadOk = 0;
//    }
//}
// έλέγχουμε την απίθανη περίπτωση το αρχείο να υπάρχει ξανά
//if (file_exists($target_file)) {
//    echo "Sorry, file already exists.";
//    $uploadOk = 0;
//}
// ελέγχουμε το μέγεθος του αρχείου - θα πρέπει να κάνουμε αντίστοιχη ρύθμιση και στον server 
if ($_FILES["fileToUpload"]["size"] > 500000000) {
    echo "Το αρχείο είναι πολύ μεγαλό.";
    $uploadOk = 0;
}
// Επιτρέπουμε συγκεκριμένους τύπους αρχείων
if($imageFileType != "zip" && $imageFileType != "rar" && $imageFileType != "doc" && $imageFileType != "pdf" ) {
    echo "Επιτρέπονται μόνο αρχεία ZIP, RAR, doc και pdf.";
    $uploadOk = 0;
}
// έλεγχος αν είχαμε πρόβλημα στη διαδικασία ανεβάσματος
if ($uploadOk == 0) {
    echo "Το αρχείο σας δυστυχώς δεν ανέβηκε.";
} 
else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        //echo "Το αρχείο ". basename( $_FILES["fileToUpload"]["name"]). " ανέβηκε.";
    } 
    else {
        echo "Παρουσιάστηκε πρόβλημα κατά το ανέβασμα του αρχείου σας.";
    }
}
?>