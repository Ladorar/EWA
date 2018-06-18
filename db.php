<?php
/**
 * Einfaches Skript das sich mit der lokalen Datenbank verbindet
 * und die in den Praktikumsaufgaben beschriebenen Tabellen anlegt.
 */
try {
    $database = new MySQLi("localhost", "root", "", "pizzaservice");
    $database->set_charset("utf8");
    if ( $database->query("create table if not exists bestellung(
                                bid VARCHAR(32),
                                name VARCHAR(256) NOT NULL,
                                strasse VARCHAR(256) NOT NULL,
                                stadt VARCHAR(32) NOT NULL,
                                email VARCHAR(32) NOT NULL,
                                status VARCHAR(32) NOT NULL,
                                sid VARCHAR(32) NOT NULL,
                                PRIMARY KEY (bid)
    );"))
    {
        echo("Success creating Table Bestellungen!\n");
    } else {
        echo("Error creating Table Bestellungen!\n");
    }
    if ( $database->query("create table if not exists angebot(
                               name VARCHAR(128),
                               pfad VARCHAR(256),
                               preis FLOAT(10),
                               PRIMARY KEY (name)   
    );")) 
    {
        echo("Success creating Table Angebot!\n");
    } else {
        echo("Error creating Table Angebot!\n");
    }
    if ( $database->query("create table if not exists bestelltepizza(
                                pid VARCHAR(32),
                                bid VARCHAR(32),
                                pizzaname VARCHAR(128) NOT NULL,
                                PRIMARY KEY (pid),
                                FOREIGN KEY (bid) REFERENCES bestellung (bid),
                                FOREIGN KEY (pizzaname) REFERENCES angebot (name)        
    );")) 
    {
        echo("Success creating Table BestelltePizza!\n");
    } else {
        echo("Error creating Table BestelltePizza!\n");
    }
    $database->query("insert into angebot values('Margherita', 'img/magarita.png', 6.50);");
    $database->query("insert into angebot values('Mozzarella', 'img/mozzarella.png', 7.00);");
    $database->query("insert into angebot values('Pepperoni', 'img/pepperoni.png', 7.50);");
    
    $database->close();
} catch (Exception $e) {
    header("Content-type: text/plain; charset=UTF-8");
    echo $e->getMessage();
}