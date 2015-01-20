<?php
    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["current_password"]))
        {
            apologize("You must provide your current password.");
        }
        else if (empty($_POST["new_password_original"]))
        {
            apologize("You must provide your new password.");
        }
        else if (empty($_POST["new_password_confirm"]))
        {
            apologize("You must confirm your new password.");
        }
        else if ($_POST["new_password_original"] !== $_POST["new_password_confirm"])
        {
            apologize("New password and new password confirmation don't match.");        
        }

        // query database for user
        $rows = query("SELECT hash FROM users WHERE user_id = ?", $_SESSION["user_id"]);
        
        // if we found id, check password
        if (count($rows) == 1)
        {
            // first (and only) row
            $row = $rows[0];

            // compare hash of user's input against hash that's in database
            if (crypt($_POST["current_password"], $row["hash"]) == $row["hash"])
            {
                // update password in database
                query("UPDATE users SET hash = ? WHERE user_id = ?", crypt($_POST["new_password_original"]), $_SESSION["user_id"]);
                            
                // redirect to portfolio
                redirect("/~tapleystephenson/objectiveOKR");   
            }
        }

        // else apologize
        apologize("Invalid username and/or password.");
    }
    else
    {
        // else render change passowrd form
        render("change_password_form.php", ["title" => "Change Password"]);
    }
?>
