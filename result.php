<?php 

session_start();

$pdo=new PDO("mysql:host=localhost;dbname=magebit_test", "root", "");

try {
    class View 
    {
        private $model;
        private $controller;
        public $sql="SELECT * FROM emails";
        public $uniquearray= array();

        public function __construct($controller,$model)
        {
            $this->controller = $controller;
            $this->model = $model;
        }

        public function makeSortButtons()
        {
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

        public function makeEmailButtons()
        {
            foreach ($this->model->uniquearray as $value)
            {
                $dotPos=strpos($value, ".");
                $afterDot=substr($value, $dotPos);
                $buttonText=str_replace($afterDot, "", $value);
                $capitalised=ucwords($buttonText);

                echo "<form action='' method='post'><input type='submit'value='$capitalised'name='$capitalised'></form>";
            }
        } 
        
        public function makeDeleteButtonsWork()
        {
            foreach ($this->model->rowIds as $theId)
            {
                if(isset($_POST["$theId"]))
                {
                    $this->sql="DELETE FROM `emails` WHERE `id` = $theId";
                }
            }
        }

        public function showEmails($q)
        {
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
            while ($row = $q->fetch()) {
                echo  '
                <tr>
                    <td>
                        <form action="" method="post">
                            <input type="submit" value="Delete" name="'. htmlspecialchars($row["id"]).'"style="border:none;">
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
    
    if ($_SESSION['input']) {
        $input=$_SESSION['input'];
    } else {
        $input="";
    }
    
    class Model
    {
        public $sql;
        public $name;
        public $myarray=array();
        public $rowIds=array();
        
        public function getEmailEndingsAndRowIds($q)
        {  
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
    
        public function makeEmailButtons()
        {
            foreach ($this->uniquearray as $value) {
                $dotPos=strpos($value, ".");
                $afterDot=substr($value, $dotPos);
                $buttonText=str_replace($afterDot, "", $value);
                $capitalised=ucwords($buttonText);
        
                if (isset($_POST[$capitalised])) {
                    $_SESSION['name']=$value;
                    $this->name=$_SESSION['name'];
                }
            }
        }
            
        public function sortFilter($name, $input){
            if ($this->name != "") {
                $this->sql="SELECT * FROM emails WHERE email REGEXP '$name$' AND email LIKE '%$input%'";    
            } elseif (isset($_POST["by-date"])) {
                $this->sql="SELECT * FROM emails  WHERE email REGEXP '$name$' AND email LIKE '%$input%'";
            } elseif (isset($_POST["by-name"])) {
                $this->sql="SELECT * FROM emails  WHERE email REGEXP '$name$' AND email LIKE '%$input%' ORDER BY email";
            } elseif (isset($_POST["all"])) {
                $this->sql='SELECT * FROM emails';
                $_SESSION['name']="";
                $_SESSION['input']="";
            } else {
                $this->sql='SELECT * FROM emails';           
            }
        
            if (isset($_POST["search"])) {
                $input=$_POST["search-input"];
                $this->sql="SELECT * FROM emails  WHERE email LIKE '%$input%'";
                $_SESSION['name']="";
            }
        
        } 
    }

    if(isset($_POST["search"])) {
        $input=$_POST["search-input"];
        $_SESSION['input']=$input;
    }

    class Controller
    {
        private $model;

        public function __construct($model)
        {
            $this->model = $model;
        }
    }

    $model = new Model( $input);
    $controller = new Controller($model);
    $view = new View($controller, $model);
    
    $q=$pdo->query($view->sql);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    echo $view->makeSortButtons();
    $model->getEmailEndingsAndRowIds($q);
    $model->makeEmailButtons();
    $view->makeDeleteButtonsWork();

    $q=$pdo->query($view->sql);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    $model->sortFilter($_SESSION['name'], $input);
    $view->makeEmailButtons();
    
    $q=$pdo->query($model->sql);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    $view->showEmails($q);

} catch (PDOException $e) {
    die("Could not connect to the database". $e->getMessage());
} 
?>
