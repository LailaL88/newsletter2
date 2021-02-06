<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="./styles/result-styles.css">
</head>

<body>
    <form action="" method="post">
        <input type="submit" value="All" name="all">
    </form>
    <form action="" method="post">
        <input type="submit" value="Sort by date" name="by-date">
    </form>
    <form action="" method="post">
        <input type="submit" value="Sort by name" name="by-name">
    </form>
    <br> -->
<?php session_start();

$pdo=new PDO("mysql:host=localhost;dbname=magebit_test", "root", "");


try {
    class View {
        private $model;
        private $controller;

        public function __construct($controller,$model) {
        $this->controller = $controller;
        $this->model = $model;
        }
        public $sql="SELECT * FROM emails";
        public $myarray=array();
        public $rowIds=array();
        public $uniquearray=array();
        public static $name;

        function makeSortButtons(){
            return '
            <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="./styles/result-styles.css">
</head>

<body>
    <form action="" method="post">
        <input type="submit" value="All" name="all">
    </form>
    <form action="" method="post">
        <input type="submit" value="Sort by date" name="by-date">
    </form>
    <form action="" method="post">
        <input type="submit" value="Sort by name" name="by-name">
    </form>
    <br>
            ';
        }

        function getEmailEndingsAndRowIds($q){  
            while ($row=$q->fetch()) {
                $email=$row['email'];
                $a="@";
                $pos=strpos($email, $a)+1;
                $mailending=substr($email, $pos);
        
                array_push($this->myarray, $mailending);
                array_push($this->rowIds, $row['id']);

                $this->uniquearray=array_unique($this->myarray);
            }
        }

        function makeEmailButtons(){
            foreach ($this->uniquearray as $value) {
                $dotPos=strpos($value, ".");
                $afterDot=substr($value, $dotPos);
                $buttonText=str_replace($afterDot, "", $value);
                $capitalised=ucwords($buttonText);

                echo "<form action='' method='post'><input type='submit'value='$capitalised'name='$capitalised'></form>";
        
                if(isset($_POST["$capitalised"])) {
                    $_SESSION['name']=$value;
                    $name=$_SESSION['name'];
                }
            }
        } 
        
        function makeDeleteButtons($pdo){
            foreach ($this->rowIds as $theId) {
                if(isset($_POST["$theId"])) {
                    $this->sql="DELETE FROM `emails` WHERE `id` = $theId";
                }
            }
        }

        function showEmails($q){
            echo '
            <br>
            <form action="" method="post">
                <input type="text" placeholder="search for..." name="search-input">
                <input type="submit" value="Search" name="search">
            </form>
            
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Emails</th>
                    </tr>
                </thead>
                <tbody>';
                while ($row = $q->fetch()){
                    echo  '<tr>
                    <td>
                        <form action="" method="post">
                            <input type="submit" value="Delete" name="';echo htmlspecialchars($row["id"]).'"
                                style="border:none;">
                        </form>';
                        echo htmlspecialchars($row["email"]).'
                    </td>
                </tr>';
                } 
                    echo '
                </tbody>
            </table>
            </body>
            
            </html>
            ';

        }
    }

    // $myButtons = new Buttons();
    // $q=$pdo->query($myButtons->sql);
    // $q->setFetchMode(PDO::FETCH_ASSOC);
    // $myButtons->getEmailEndingsAndRowIds($q);
    // $myButtons->makeEmailButtons();
    // $myButtons->makeDeleteButtons($pdo);
    
    if($_SESSION['input']) {
        $input=$_SESSION['input'];
    }

    else {
        $input="";
    }
    
    class Model {
        public $sql;
        public function __construct($name, $input){
            if(isset($_POST["by-date"])) {
                $this->sql="SELECT * FROM emails  WHERE email REGEXP '$name$' AND email LIKE '%$input%'";
            }
        
            else if(isset($_POST["by-name"])) {
                $this->sql="SELECT * FROM emails  WHERE email REGEXP '$name$' AND email LIKE '%$input%' ORDER BY email";
            }
        
            else if(isset($_POST["all"])) {
                $this->sql='SELECT * FROM emails';
                $_SESSION['name']="";
                $_SESSION['input']="";
            }
            
            else if($name !="") {
                $this->sql="SELECT * FROM emails WHERE email REGEXP '$name$' AND email LIKE '%$input%'";
                $_SESSION['name']=$name;
            } 
            
            else {
                $this->sql='SELECT * FROM emails';           
            }
         
            if(isset($_POST["search"])) {
                $input=$_POST["search-input"];
                $this->sql="SELECT * FROM emails  WHERE email LIKE '%$input%'";
            
        }
    } 
    }

    if(isset($_POST["search"])) {
        $input=$_POST["search-input"];
        $_SESSION['input']=$input;
    }

    // $myresults = new Results();
    // $myresults->sortAndFilterEmails($name, $input);
    // $myresults->searchEmails($input);
    // $q=$pdo->query($myresults->sql);
    // $q->setFetchMode(PDO::FETCH_ASSOC);

    class Controller {
        private $model;

        public function __construct($model) {
        $this->model = $model;
        }
    }
    // $name=$_SESSION['name'];
    // echo $name;
    $model = new Model($_SESSION['name'], $input);
    $controller = new Controller($model);
    $view = new View($controller, $model);
    
    $q=$pdo->query($view->sql);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    echo $view->makeSortButtons();
    $view->getEmailEndingsAndRowIds($q);
    $view->makeEmailButtons();
    $view->makeDeleteButtons($pdo);
    $q=$pdo->query($model->sql);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    
    $view->showEmails($q);
    


}

catch (PDOException $e) {
    die("Could not connect to the database". $e->getMessage());
} ?>

<!-- <br>
<form action="" method="post">
    <input type="text" placeholder="search for..." name="search-input">
    <input type="submit" value="Search" name="search">
</form>

<table class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th>Emails</th>
        </tr>
    </thead>
    <tbody>
        <?php //while ($row = $q->fetch()): ?>
        <tr>
            <td>
                <form action="" method="post">
                    <input type="submit" value="Delete" name="<?php //echo htmlspecialchars($row['id'])?>"
                        style="border:none;">
                </form>
                <?php //echo htmlspecialchars($row['email']);?>
            </td>
        </tr>
        <?php //endwhile; ?>
    </tbody>
</table>
</body>

</html> -->