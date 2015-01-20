<?php

    // configuration
    require("../includes/config.php");
            
    // search for user's history
    $rows = query("SELECT action, symbol, shares, price, time FROM history WHERE id = ?", $_SESSION["id"]);
    
    // set up history array
    $history = [];
    
    // TODO something is fucked here
    foreach ($rows as $row)
    {
        $history[] = [
            "action" => $row["action"],
            "symbol" => $row["symbol"],
            "shares" => $row["shares"],
            "price" => $row["price"],
            "time" => $row["time"]
        ];
    };

    // render history
    render("history_form.php", ["title" => "History", "history" => $history]);
?>
