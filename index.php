<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pineapple</title>
    <link rel="stylesheet" href="./styles/styles.css">
    <script src="./scripts/scripts.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <div class="container">
        <section class="left-side">
            <nav>
                <div class="logo">
                    <img src="./img/Union.png" alt="logo">
                    <img class="logo-text" src="./img/pineapple..png" alt="pineapple">
                </div>
                <li><a href="#">About</a></li>
                <li><a href="#">How it works</a></li>
                <li><a href="#">Contact</a></li>
            </nav>

            <div class="content-container">
                <div class="form">
                    <h3>Subscribe to newsletter</h3>
                    <p>Subscribe to our newsletter and get 10% discount on pineapple glasses.</p>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post"
                        onsubmit="return validate(event)" class="contact-us">
                        <div class="text-input-wrapper">
                            <div class="line"></div>
                            <input type="text" class="text-input" placeholder="Type your email address here…"
                                name="email" id="email">
                            <button type="submit" name="submit"
                                style="padding: 0; border: none; width:max-content;height:14px;outline:none;">
                                <img class="arrow" src="./img/ic_arrow.jpg" /></button>
                        </div><br>

                        <input type="checkbox" class="checkbox" name="checkbox">
                        <noscript>
                            <input type="checkbox" class="php-checkbox" name="php-checkbox">
                        </noscript>
                        <span class="mycheckbox"><img src="./img/ic_checkmark.png" alt=""></span><label for="checkbox">I
                            agree to <a href="#">terms of service</a></label>
                    </form>
                </div>
                <div class="success-message">
                    <img src='./img/cup.png' alt='cup' class='cup'>
                    <h3>Thanks for subscribing!</h3>
                    <p>You have successfully subscribed to our email listing. Check your email for the discount code.
                    </p>
                </div>
                <div class="message"></div>
                <div class="message-empty"></div>
                <div class="message-colombia"></div>
                <div class="checkboxErr"> </div>

            <?php

            $email = $checkbox = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (empty($_POST["email"])) {
                    $emailErr = "Email address is required";
                    echo "<noscript><div class='php-message'>$emailErr</div></noscript>";
                } else {
                    $email = test_input($_POST["email"]);
                }
                    // check if e-mail address is well-formed
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Please provide a valid e-mail address";
                    echo "<noscript><div class='php-message'>$emailErr</div></noscript>";
                } elseif (substr($email, -3) == ".co") {
                    $emailErr = "We are not accepting subscriptions from Colombia emails";
                    echo "<noscript><div class='php-message'>$emailErr</div></noscript>";
                }

                if (empty($_POST["php-checkbox"])) {
                    $checkboxlErr = "You must accept the terms and conditions";
                    echo "<noscript><div class='php-message'>$checkboxlErr</div></noscript>";
                } else {
                    $checkbox = test_input($_POST["php-checkbox"]);
                }
            }
            
            function test_input($data) {
              $data = trim($data);
              $data = stripslashes($data);
              $data = htmlspecialchars($data);
              return $data;
            }

            // session_start();
            // $_SESSION['email'] = $email;
            
            ?>
                <hr>
                <div class="icon-wrapper">
                    <div class="icon facebook"><i class="fab fa-facebook-f"></i></div>
                    <div class="icon instagram"><i class="fab fa-instagram"></i></div>
                    <div class="icon twitter"><i class="fab fa-twitter"></i></div>
                    <div class="icon youtube"><i class="fab fa-youtube"></i></div>
                </div>
            </div>
        </section>
        <section class="right-side">
        </section>
    </div>
    <script>
        $(".contact-us").submit(function (e) {
            e.preventDefault();
            let myurl = $(".contact-us").attr('action');
            $.ajax({
                url: myurl, //endpoint
                method: 'POST', //post request
                data: $(".contact-us").serialize(), //get data of form
                success: function (data) { //success function
                    if (validate()) {
                        $(".contact-us").css('display', 'none'); //hide form
                        $(".success-message").css('display', 'block');
                    }
                }
            });
        });
    </script>
    <script src="https://kit.fontawesome.com/61c651c0ef.js" crossorigin="anonymous"></script>
</body>

</html>

<?php 
    $email = test_input($_POST["email"]?? null);
    if (!empty($_POST["email"]) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($_POST["checkbox"]) && substr($email, -3) != ".co"){
        $pdo = new PDO('mysql:host=localhost;dbname=magebit_test', 'root', '', array(PDO::ATTR_PERSISTENT => 'unbuff', PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));
        $sql = "INSERT INTO `emails` (email) VALUES (:email)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":email", $email);
        $email = $_POST['email'];
        $form = $_POST;
        $id = $form[ 'email' ];                    
        $result = $stmt->execute();
    }
?>