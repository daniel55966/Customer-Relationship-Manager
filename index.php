<?php 
  if (!isset($_SESSION)) {
    session_start();
  }

  $nameErr = $emailErr = $contBackErr = "";
  $name = $email = $contBack = $comment = "";
  $formErr = false;   

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["name"])) {
      $nameErr = "Name is required.";
      $formErr = true;
    } else {
      $name = cleanInput($_POST["name"]);
        //Use REGEX to only accept letters and spaces
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
          $nameErr = "Only letters and standard spaces allowed."; 
          $formErr = true;    
        }
    }
    
    if (empty($_POST["email"])) {
      $emailErr = "Email is required.";
      $formErr = true;
    } else {
      $email = cleanInput($_POST["email"]);
      //Check if email is formatted correctly
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Please enter a valid email address.";
        $formErr = true;
      }
    }
    
    if (empty($_POST["contact-back"])) {
      $contBackErr = "Please let us know if we can contact you back.";
      $formErr = true;
    } else {
      $contBack = cleanInput($_POST["contact-back"]);
    }
    
    $comment = cleanInput($_POST["comments"]);
  }

  //Clean and sanitize form inputs
  function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  if (($_SERVER["REQUEST_METHOD"] == "POST") && (!($formErr)))  {  
    $hostname = "php-mysql-exercisedb.slccwebdev.com";
    $username = "phpmysqlexercise";
    $password = "mysqlexercise";
    $databasename = "php_mysql_exercisedb";

    try {
    //Create new PDO Object
    $conn = new PDO("mysql:host=$hostname;dbname=$databasename", 
            $username, $password);

    //Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Variable containing SQL command
    $sql = "INSERT INTO da_fa2022_Contacts (
        name,
        email,
        contactBack,
        comments
    )
    
    VALUES (
        :name,
        :email,
        :contactBack,
        :comments   
    );";

    //Create prepared statement
    $stmt = $conn->prepare($sql);

    //Bind parameters to variables
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':contactBack', $contBack, PDO::PARAM_STR);
    $stmt->bindParam(':comments', $comments, PDO::PARAM_STR);

    //Execute SQL Statement on server
    $stmt->execute();

    //Create thank you message
    $_SESSION['message'] = '<p class="font-weight-bold">Thank you
    for your submission!</p><p class="font-weight-light">Your
    request has been sent.</p>';
    
    //Redirect
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;

    } catch (PDOException $error) {
      
      //Return error code if one is created
      $_SESSION['message'] = '<p>We apologize, the form was not
      submitted successfully. Please try again later.</p>';

      $_SESSION['complete'] = true;

      //Redirect
      header('Location: ' . $_SERVER['REQUEST_URI']);
      exit;
    }

    $conn = null;
  } 

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Personal Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>  
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="script.js"></script>
  </head>

  <body>
    <header class="mainHeader" id="home">
      <div class="container-fluid h-100">
        <div class="row h-100 align-items-center justify-content-center text-center text-white">
          <div class="col-lg-8">
            <h1 class="display-1 font-weight-bold">Daniel's <span class="font-weight-light">Portfolio Website</span></h1>
            <hr class="bg-white my-5" />
            <p class="font-weight-light">A portfolio site meant to showcase the web development 
              practices and lessons I have learned while taking the Web Dev Certificate Course through 
              Salt Lake Community College. The Final Project link goes to the final project I did for 
              the certificate course, with an explanation for it on its main page. The portfolio section 
              below has a few projects that I did in my free time.
            </p>
            <a href="#about" class="btn btn-primary btn-large mt-3" role="button">About Me</a>
            <a href="#portfolio" class="btn btn-primary btn-large mt-3" role="button">Portfolio</a>
            <a href="#contact" class="btn btn-primary btn-large mt-3" role="button">Contact Me</a>
            <a href="/project.php" class="btn btn-primary btn-large mt-3" role="button" target="_blank">Final Project</a>
          </div>
        </div>
      </div>
    </header>

  <!-- About -->
  <section class="aboutMe" id="about">
    <div class="container">
      <div class="row align-items-center justify-content-center text-center
      py-5">
        <!-- About Me Summary -->
        <div class="col-lg-8 my-3">
          <h2 class="font-weight-bold">Daniel Anderson</h2>
          <hr class="my-4" />
          <p class="font-weight-light mx-5">I am a student at Salt Lake Community College. I'm 
            working towards gaining skills to enter a new field of work. I have experience in HTML5, 
            CSS3/Bootstrap, Javascript, PHP, and MySQL. I have also been a leader for a small team (3-5 people) 
            and a co-leader of a larger team (10-20 people), and am currently a moderator for a music 
            community I'm a part of. I can work hard and am willing to learn new skills in my free time.
          </p>
          <a class="btn btn-primary btn-lg mt-3” role="button"
          href="#home">Home</a>
          <a class="btn btn-primary btn-lg mt-3” role="button"
          href="#portfolio">Portfolio</a>
          <a class="btn btn-primary btn-lg mt-3” role="button"
          href="#contact">Contact Me</a>
          
        </div>
      </div>
    </div>
  </section>

  <!-- Portfolio -->
  <section id="portfolio" class="bg-primary">
    <div class="container-fluid py-5">
      <!-- Portfolio Section Title -->
      <div class="row text-white text-center">
        <div class="col">
          <h2 class="display-4 font-weight-bold">Portfolio</h2>
          <hr class="bg-white mb-5" />
        </div>
      </div>
      <!-- Portfolio Items Row Start-->
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        <!-- Portfolio Item -->
        <div class="col mb-4">
          <div class="card bg-light text-center border-light shadow h-100">
            <div class="card-body">
              <h3 class="card-title">TTRPG Dice Roller</h3>
              <hr class="bg-primary" />
              <p class="card-text">A Dice Roller for Dungeons & Dragons 
              I coded and designed. It lets you roll an inputted number of a specific 
              dice that are used in most TTRPG rulesets, adds them together then outputs 
              the total.</p>
            </div>
            <div class="card-footer">
              <a class="btn btn-outline-primary btn-lg mt-2" role="button" href="/dndroller.html" target="_blank">Dice Roller</a>
            </div>
          </div>
        </div>
        <!-- Portfolio Item End -->

        <!-- Portfolio Item -->
        <div class="col mb-4">
          <div class="card bg-light text-center border-light shadow h-100">
            <div class="card-body">
              <h3 class="card-title">Tic-Tac-Toe</h3>
              <hr class="bg-primary" />
              <p class="card-text">A version of Tic-Tac-Toe that outputs whether a player 
              wins a round and which player or if the round ends in a tie.
              </p>
            </div>
            <div class="card-footer">
              <a class="btn btn-outline-primary btn-lg mt-2" role="button" href="/tictactoe.html" target="_blank">Tic-Tac-Toe</a>
            </div>
          </div>
        </div>
        <!-- Portfolio Item End -->

        <!-- Portfolio Item -->
        <div class="col mb-4">
          <div class="card bg-light text-center border-light shadow h-100">
            <div class="card-body">
              <h3 class="card-title">Calculator</h3>
              <hr class="bg-primary" />
              <p class="card-text">A simple 10-digit calculator.</p>
            </div>
            <div class="card-footer">
              <a class="btn btn-outline-primary btn-lg mt-2" role="button" href="/calculator.html" target="_blank">Calculator</a>
            </div>
          </div>
        </div>
        <!-- Portfolio Item End -->
      </div>
    </div><br>

    <div id="References" class="row h-100 align-items-center justify-content-center text-center text-white" style="font-size: large;">
      <script src="script.js"></script>
    </div>

  </section>

	<!-- Contact Form Section -->
	<section class="contactMe" id="contact">
		<div class="container py-5">
			<!-- Section Title -->
			<div class="row justify-content-center text-center">
				<div class="col-md-6">
					<h2 class="display-4 font-weight-bold">Contact Me</h2>
					<hr />
				</div>
			</div>
			<!-- Contact Form Row -->
			<div class="row justify-content-center">
				<div class="col-6">
					<!-- Contact Form Start -->
					<form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method="POST" novalidate>
						
						<!-- Name Field -->
						<div class="form-group">
							<label for="name">Full Name:</label>
              <span class="text-danger">*<?php echo $nameErr; ?></span>
							<input type="text" class="form-control" id="name" placeholder="Full Name" name="name"
              value="<?php if (isset($name)) {echo $name;} ?>" />
						</div>
						
						<!-- Email Field -->
						<div class="form-group">
							<label for="email">Email address:</label>
              <span class="text-danger">*<?php echo $emailErr; ?></span>
							<input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" 
              value="<?php if (isset($email)) {echo $email;} ?>"/>
						</div>
						
						<!-- Radio Button Field -->
						<div class="form-group">
							<label class="control-label">Can we contact you back?</label>
              <span class="text-danger">*<?php echo $contBackErr; ?></span>
							<div class="form-check">
								<input type="radio" class="form-check-input" name="contact-back" id="yes" value="Yes" <?php if ((isset($contBack)) && ($contBack == "Yes")) {echo "checked";} ?> />
								<label class="form-check-label" for="yes">Yes</label>
							</div>
							<div class="form-check">
								<input type="radio" class="form-check-input" name="contact-back" id="no" value="No" <?php if ((isset($contBack)) && ($contBack == "No")) {echo "checked";} ?>/>
								<label class="form-check-label" for="no">No</label>
							</div>
						</div>
						
						<!-- Comments Field -->
						<div class="form-group">
							<label for="comments">Comments:</label>
							<textarea id="comments" class="form-control" rows="3" name="comments">
              <?php if (isset($comment)) {echo $comment;} ?></textarea>
						</div>

            <!-- Required Fields Note -->
            <div class="text-danger text-right">* Indicates required fields</div>
						
						<!-- Submit Button -->
						<button class="btn btn-primary mb-2" type="submit" role="button" name="submit">Submit</button>
					</form>
				</div>
			</div>
		</div>

      <!-- Modal -->  
      <div class="modal fade" id="thankYouModal" tabindex="-1" aria-labelledby="thankYouModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="thankYouModalLabel">Thank You</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <?php echo $_SESSION['message']; ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
	</section>

  <!-- Show Modal -->
  <?php
    if (isset($_SESSION['complete']) && $_SESSION['complete']) {
      echo "<script>$('#thankYouModal').modal('show');</script>";
      session_unset();
    }
  ?>

  <!-- Link to Data Table -->
  <div class="row align-items-center justify-content-center text-center
      py-5">
    <div class="col-lg-8 my-3">
        <a href="/sqlexercise.php" class="btn btn-primary btn-large mt-3" role="button" target="_blank">Data Table</a>
    </div>
  </div>

  <!-- Footer -->
  <footer class="py-4 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; Daniel's Personal Website 2023</p>
    </div>
  </footer>

    
  </body>
</html>
