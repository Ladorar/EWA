<?php	// UTF-8 marker äöüÄÖÜß€
session_start();
/**
 * Class PageTemplate for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 * 
 * PHP Version 5
 *
 * @category File
 * @package  Pizzaservice
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de> 
 * @author   Ralf Hahn, <ralf.hahn@h-da.de> 
 * @license  http://www.h-da.de  none 
 * @Release  1.2 
 * @link     http://www.fbi.h-da.de 
 */

// to do: change name 'PageTemplate' throughout this file
require_once './Page.php';

/**
 * This is a template for top level classes, which represent 
 * a complete web page and which are called directly by the user.
 * Usually there will only be a single instance of such a class. 
 * The name of the template is supposed
 * to be replaced by the name of the specific HTML page e.g. baker.
 * The order of methods might correspond to the order of thinking 
 * during implementation.
 
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de> 
 * @author   Ralf Hahn, <ralf.hahn@h-da.de> 
 */
class PageTemplate extends Page
{
    // to do: declare reference variables for members 
    // representing substructures/blocks
    
    protected $pizzaName;
    protected $pizzaPfad;
    protected $pizzaPreis;
    protected $head;

    /**
     * Instantiates members (to be defined above).   
     * Calls the constructor of the parent i.e. page class.
     * So the database connection is established.
     *
     * @return none
     */
    protected function __construct() 
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks

        $pizzaName = array();
        $pizzaPfad = array();
        $pizzaPreis = array();
    }
    
    /**
     * Cleans up what ever is needed.   
     * Calls the destructor of the parent i.e. page class.
     * So the database connection is closed.
     *
     * @return none
     */
    protected function __destruct() 
    {
        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData()
    {
        // to do: fetch data for this view from the database
        $SQLAbfrage = "SELECT * FROM angebot";
        $index = 0;
        $Recordset = $this->_database->query($SQLAbfrage);

        while($record = $Recordset->fetch_assoc()) {
            $this->pizzaName[$index] = $record["name"];
            $this->pizzaPreis[$index] = $record["preis"];
            $this->pizzaPfad[$index] = $record["pfad"];
            $index = $index + 1;
        }

        $Recordset->free();
    }
    
    /**
     * First the necessary data is fetched and then the HTML is 
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if avaialable- the content of 
     * all views contained is generated.
     * Finally the footer is added.
     *
     * @return none
     */
    protected function generateView() 
    {
        $this->getViewData();
        $this->generatePageHeader('Bestellen');
        // to do: call generateView() for all members
        // to do: output view of this page
        $this->generateEchoView();
        $this->generatePageFooter();
    }
    
    protected function generateEchoView() {
        echo <<<EOT
        
            <main class="inhalt roundedCorners white background">
                <div class="row">
EOT;

$this->insert_pizza();

        echo <<<EOT
        </div>
                <div class="wahrenkorb">
                <form action="Bestellung.php" id="form1" accept-charset="UTF-8" method="POST">
                    <select id="korb" name="warenkorb[]" size="5" multiple>      
                    </select> </br>
                    <label> Name: </br>
                        <input type="text" name="name" size="30" maxlength="40" placeholder="Nachname"  />
                    </label> </br>
                    <label> Stadt: </br>
                        <input type="text" name="stadt" size="30" maxlength="40" placeholder="Stadt"  />
                    </label> </br>
                    <label> Strasse: </br>
                        <input type="text" name="strasse" size="30" maxlength="40" placeholder="Strasse"  />
                    </label> </br>
                    <label> Email: </br>
                        <input type="text" name="email" size="30" maxlength="40" placeholder="Email"  />
                    </label>   
                </form>
                </div>
                <div>
                    <input type="submit" name="Bestellen" onclick="bestellen()"/>
                    <button onclick="deleteAll()">Löschen</button>
                </div>
                
            </main>
        </div>
        <footer class="footer roundedCorners white background">

        </footer>
    </div>
</body>
EOT;
    }

    protected function insert_pizza() {
        static $id = 0;
        for($i = 0; $i < count($this->pizzaName); $i++) {
            $name = $this->pizzaName[$i];
            $preis = $this->pizzaPreis[$i];
            $pfad = $this->pizzaPfad[$i];
            echo <<<EOT
            <div id="$id" data-name="$name" data-preis="$preis" onclick="addCart(this)"><img class="img" src="$pfad" /></div>
EOT;
        }
    }
    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this page is supposed to do something with submitted
     * data do it here. 
     * If the page contains blocks, delegate processing of the 
	 * respective subsets of data to them.
     *
     * @return none 
     */
    protected function processReceivedData() 
    {
        if ($_POST) {
            parent::processReceivedData();
            // to do: call processReceivedData() for all members

            $name = 0;
            $strasse = 0;
            $stadt = 0;
            $content = 0;
            $email = 0;
            $pizza = 0;
            $sid = 0;

            if (isset($_POST['name'])) {
                $name = $_POST['name'];
            }

            if (isset($_POST['stadt'])) {
                $stadt = $_POST['stadt'];
            }


            if (isset($_POST['strasse'])) {
                $strasse = $_POST['strasse'];
            }

            if (isset($_POST['email'])) {
                $email = $_POST['email'];
            }

            if (isset($_POST['warenkorb'])) {
                $content = $_POST['warenkorb'];
            }

            $sid = session_id();
            $bid = uniqid();
            $success = $this->_database->query("insert into bestellung values (
                                                    '$bid',
                                                    '$name',
                                                    '$strasse',
                                                    '$stadt',
                                                    '$email',
                                                    'bestellt',
                                                    '$sid'
                                                );"
                                            );
            
            foreach($content AS $name) {
                $pid = uniqid();
                $success = $this->_database->query("insert into bestelltepizza values (
                                                    '$pid',
                                                    '$bid',
                                                    '$name'
                                                );"
                                            );
                
            }

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();


        }
        
        
        
    }

    /**
     * This main-function has the only purpose to create an instance 
     * of the class and to get all the things going.
     * I.e. the operations of the class are called to produce
     * the output of the HTML-file.
     * The name "main" is no keyword for php. It is just used to
     * indicate that function as the central starting point.
     * To make it simpler this is a static function. That is you can simply
     * call it without first creating an instance of the class.
     *
     * @return none 
     */    
    public static function main() 
    {
        try {
            $page = new PageTemplate();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page. 
// That is input is processed and output is created.
PageTemplate::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >