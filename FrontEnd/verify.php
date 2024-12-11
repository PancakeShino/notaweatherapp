<!-- Need to figure out how to get this to work with sessions

<?php
    function validLogin($email, $password) {
        
        //will need to figure this out.
        require_once('database.php');

        //update statement to username
        /*
        $query = 'SELECT username, password, session_id FROM users WHERE username =:username';
        statement = $db->prepare($query);
        $statement->bindValue(':username. $username);
        $row = $statement->fetch();
        $statement->closeCursor();

        
        */
        $query = 'SELECT firstName, lastName, emailAddress, password FROM shoeManagers WHERE emailAddress = :emailAddress';
        $statement = $db->prepare($query);
        $statement->bindValue(':emailAddress', $email);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();
        
        if($row === false) {
            return false;
        } else {
            $hash = $row['password'];
            if (password_verify($password, $hash)) {
                $_SESSION['validLogin'] = true;
                $_SESSION['firstName'] = $row['firstName'];
                $_SESSION['lastName'] = $row['lastName'];
                $_SESSION['emailAddress'] = $row['emailAddress'];
                return true;
            } else {
                return false;
            }
        }
    }
?>