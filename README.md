ADISE19_CholidouA

A Tic-Tac-Toe game written in PHP and SQL that runs in a browser.

Index/conf/session
Για να ξεκινήσει να παίζει ο παίκτης πρέπει πρώτα να κάνει Login. Αν κάποιος χρήστης προσπαθήσει να προσπεράσει αυτό το στάδιο και να μπει κατευθείαν στο παιχνίδι, τον επιστρέφει
στην καρτέλα του Login. Στις επιλογές Username και Password έχω βάλει περιορισμό να μη μπορεί ο χρήστης να γράφει κώδικα, ώστε να μην μπορεί να πειράξει τον δικό μας. 
Έχουν δημιουργηθεί 2 χρήστες στη βάση δεδομένων. Αφού πληκτρολογήσει ο παίκτης το username και το password πατάει το κουμπί submit. Αν ταυτοποιηθεί με κάποιο από αυτά που υπάρχουν 
στη βάση, τον μεταφέρει στην οθόνη του παιχνιδιού, αλλιώς εμφανίζει το μήνυμα το username ή το password είναι λάθος. Εφόσον συνδεθεί ο χρήστης,του εμφανίζει το σκορ του μέχρι στιγμής,
τον εισάγει στο παιχνίδι και του δείχνει σε ποιο παιχνίδι είναι, ποιός παίκτης είναι και ποιός κάνει την επόμενη κίνηση. 

game
Υπάρχουν 3 επιλογές παιχνιδιού. Η 1η επιλογή είναι να βάλει τον παίκτη σε ένα παιχνίδι που είχε ξεκινήσει και δεν το ολοκλήρωσε. Η 2η επιλογή είναι να μπει σε ένα παιχνίδι που έχει 
ξεκινήσει κάποιος άλλος και υπάρχει κενή θέση. Η 3η επιλογή είναι να ξεκινήσει ο παίκτης ένα καινούριο παιχνίδι και να περιμένει κάποιον αντίπαλο να μπει να παίξουν. Μόλις ξεκινήσει
κάποιο παιχνίδι εμφανίζεται ένα drop down μενού με τις διαθέσιμες κινήσεις του εκάστοτε παίκτη, οι οποίες ενημερώνονται και μειώνονται όσο προχωράμε. Διαλέγει ο παίκτης που θα κάνει
την κίνηση του και στη συνέχεια πατάει submit. Όσο περιμένει τον αντίπαλο να κάνει την κίνηση του, μπορεί να πατάει το κουμπί refresh για να ανανεώνεται η σελίδα και να δει πότε θα
έρθει ξανα η σειρά του. Το παιχνίδι τελειώνει όταν νικήσει κάποιος από τους δύο παίκτες ή αν έρθει ισοπαλία. 

game/logout
Για να ολοκληρωθεί το παιχνίδι και να υπάρχει νικητής, πρέπει ένας παίκτης να βάλει 3 σύμβολα έιτε στην ίδια σειρά, ή στην ίδια στήλη ή διαγώνια και λήγει ισοπαλία όταν δεν συμβεί 
κάποιο από αυτά. Τότε εμφανίζει το αντίστοιχο μήνυμα στην οθόνη και το παιχνίδι ολοκλήρώνεται, ενώ ενημερώνονται και οι νίκες του παίκτη. Για να μπορέσουν να παίξουν νέο παιχνίδι 
θα πρέπει να πατήσουν Log out όπου τερματίζει τη συνεδρία και να συνδεθούν ξανά από την αρχή.