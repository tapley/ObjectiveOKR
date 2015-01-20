<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["buy_symbol"]) || empty($_POST["buy_shares"]))
        {
            apologize("Missing inputs. You must provide a stock and number of shares to buy.");
        
        }
        else
        {
            // set variable for formatted symbol input
            $buy_symbol_upper = strtoupper ($_POST["buy_symbol"]);
            
            // set variable for stock
            $stock = lookup($buy_symbol_upper);
            
            //set variable for cash remaining in user's account
            $cash_remaining = query("SELECT cash FROM users WHERE id = ?" , $_SESSION["id"]);
            $cash_remaining = $cash_remaining[0]["cash"];           

            
            // check stock symbol is valid.
            if ($stock === false)
            {
                apologize("Invalid symbol. You must provide a valid stock to buy.");          
            }
            // check number of shares is a positive integer
            else if (preg_match("/^\d+$/", $_POST["buy_shares"]) === true)
            {
                apologize("Invalid number of shares. You must provide a positive integer.");          
            };
            
            // now that units are checked, set variable for cost of purchase
            $cash_for_buy = $_POST["buy_shares"] * $stock["price"];
            
            // check for sufficient funds
            if ($cash_remaining < $cash_for_buy)
            {
                apologize("Insufficient cash available for purchase.");                          
            }         
            else
            {                           
                // update portfolio
                query("INSERT INTO portfolios (id, symbol, shares) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE shares = shares + ?", 
                    $_SESSION["id"], 
                    $buy_symbol_upper, 
                    $_POST["buy_shares"], 
                    $_POST["buy_shares"]
                    );
                
                // update cash
                query("UPDATE users SET cash = cash - ? WHERE id = ?", $cash_for_buy, $_SESSION["id"]);
                
                $timestamp = date();
                
                // update history
                query("INSERT INTO history (id, action, symbol, shares, price) VALUES(?, ?, ?, ?, ?)",
                     $_SESSION["id"],
                     "Buy",
                     $buy_symbol_upper,                     
                     $_POST["buy_shares"],
                     $stock["price"]
                );
                                                   
                // redirect to portfolio
                redirect("/");
            };
        };

    }            
    else
    {
        // else render form
        render("buy_form.php", ["title" => "Sell"]);
    };
             
                
?>
