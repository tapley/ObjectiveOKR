<?php

    // configuration
    require("../includes/config.php");

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        
        // check for username/passwords
        if ($_POST["username"] === "" || $_POST["password"] === "" || $_POST["confirmation"] === "")
        {
            apologize("Please enter both username, password, and password confirmation.");
        
        }
        
        // check to see password is same as confirmation
        else if ($_POST["password"] !== $_POST["confirmation"])
        {
            apologize("Password and password confirmation do not match.");
        }
       
        else
        {       
            // save username and password
            $insert_check = query("INSERT INTO users (username, hash, cash) VALUES(?, ?, 10000.00)", $_POST["username"], crypt($_POST["password"]));
            
            // if unsuccessful, apologize
            if ($insert_check === false)
            {                
                apologize("Register unsuccessful. Username may be taken."); 
                
            }           
            // if successfull, log user in
            else
            {
                $rows = query("SELECT LAST_INSERT_ID() AS id");
                $id = $rows[0]["id"];
                // TODO hopefully this logs the user in. Need to check later.
                $_SESSION["id"];
                redirect("index.php");   
            };                
        };       
    }
    else
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }

?>
