<!-- 
HTML Event registration Site.
CSC 235
Dylan Theis theisd@csp.edu
11/11/2023
Revisions: 11/18/23 Dylan Theis added my tables with sample data
11/25/23 Dylan Theis added form to collect user input
12/1/23 Dylan Theis added security measures(stored procedure)
12/2/23 Dylan Theis added import and interactivty with database
12/9/23 Dylan Theis added a link to the JSON data
-->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Registration</title>
  <link rel="stylesheet" type="text/css" href="../styles.css">
  
</head>
<body>
<?php
    
    // database connection details
    $servername = "sql201.byethost33.com";
    $username = "b33_35408430";
    $password = "Reaching12!";
    $dbname = "b33_35408430_funRun";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    

    // get data from database
    $sql = "SELECT * FROM runner";

    $result = $conn->query($sql);
    
    // Check if the query was successful
    if ($result === false) {
      die("Error executing query: " . $conn->error);
    }
  
    function formatPhone( $phoneNumber ) {
      $formattedPhone = "";
      if($phoneNumber > "") {
          $formattedPhone = substr($phoneNumber,0,3) . "-" . substr($phoneNumber,3,3) . "-" . substr($phoneNumber,6,4);
      }
      return $formattedPhone;
    }
    
          
    // Close statement and connection
    $conn->close();

    ?>
  <!-- Header of Web Page -->
  <header>
    <img id="headerPhoto" src="../headerPhoto.png" alt="running people">
    <h1>Frozen Fish Fun Run</h1>
  </header>

    <!-- Introduction text to the cause -->
    <p id="intro"> Aimed at American people who would like to participate 
        in a frozen fish fun run on December 12/21/2023 in Angle Inlet, Minnesota. This 
        is a free event and it helps bring attention to fish that are dying, 
        in snow and ice covered lakes and ponds due to global warming.
    </p>
    
    <!-- Read Me Link -->
    <p id="link"> Read Me <a href="../readMe.html" target="_blank">here</a>! If you would like you can view the JSON data <a href="../showJSONData.php" target="_blank">here</a>. You can view the reflection <a href="../reflection.html" target="_blank">here</a>.</p>
    
    <!-- Sub Heading-->
    <section>
    <h2>Frozen Fish Fun Run</h2>
    </section>

    <!-- Date & Time -->
    <section>
    <h3>Date & Time</h3>
    <p>Thursday, December 21st 10am-Noon</p>
    </section>

    <!-- Where -->
    <section>
    <h3>Where</h3>
    <p>Angle Inlet, Minnesota</p>
    </section>

    <!-- Ticketing -->
    <section>
    <h3>This is a two hour mobile e-ticket fun run! Join us and show your support!</h3>
    </section>

    <!-- Registration Form -->
    <section>
        <form action="" method="post">
          <feildset>
            <!-- Form Title-->
            <legend>Register For The Fun Run!</legend>

            <!-- First Name -->
            <label for="fName">First Name:</label>
            <input type="text" id="fName" name="fName" value="<?php echo $thisRunner['fName']; ?>" required>
    
            <!-- Last Name -->
            <label for="lName">Last Name:</label>
            <input type="text" id="lName" name="lName" value="<?php echo $thisRunner['lName']; ?>" required>
          
            <!-- gender radio buttons -->
            <div id="radioBoxes">
            <label>Male    <input type="radio" name="gender" id="gender" value="male"></label>
            <label>Female<input type="radio" name="gender" id="gender" value="female"></label>
            <label>Other<input type="radio" name="gender" id="gender" value="other"></label>
            </div>
    
            <!-- Phone number collect -->
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo formatPhone($thisRunner['phone']); ?>" required>
    
            <!-- Email collect -->
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $thisRunner['email']; ?>" required>

            <!-- Best way to contact checklist -->
            <p>Preferred method of communication:</p>
            <div id="chkBoxes">
            <label>Phone<input type="checkbox" id="prefComm" name="prefComm" value="phone" required></label>
            <label>Email<input type="checkbox" id="prefComm" name="prefComm" value="email" required></label>
            </div>

            <!-- Race selection -->
            <label for="selRace">Select race to register for:</label>
            <select id="selRace" name="selRace" required>
              <option value="10k">10K</option>
              <option value="5k">5K</option>
              <option value="marathon">Marathon</option>
              <option value="halfMarathon">Half Marathon</option>
            </select>
    
            <!-- Button to register runner -->
            <button type="submit" id="btn" name="btn" value="register" onclick="this.form.submit();">Register</button>

          </feildset>
          <?php
          // database connection details
          $servername = "sql201.byethost33.com";
          $username = "b33_35408430";
          $password = "Reaching12!";
          $dbname = "b33_35408430_funRun";

          // Create connection
          $conn = new mysqli($servername, $username, $password, $dbname);

          
          // Form data
          $fName = isset($_POST['fName']) ? $_POST['fName'] : null;
          $fName = mysqli_real_escape_string($conn, $fName);
          $lName = isset($_POST['lName']) ? $_POST['lName'] : null;
          $lName = htmlentities($lName, ENT_QUOTES, 'UTF-8');
          $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
          $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
          $email = isset($_POST['email']) ? $_POST['email'] : null;
          $email = base64_encode($email);
          $email = base64_decode($email);
          $prefComm = isset($_POST['prefComm']) ? $_POST['prefComm'] : null;
          $selRace = isset($_POST['selRace']) ? $_POST['selRace'] : null;

          // Check for null values
          if ($fName !== null && $lName !== null && $gender !== null && $phone !== null && $email !== null && $prefComm !== null && $selRace !== null) {
              // Prepare and bind the SQL statement
              $sql = $conn->prepare("INSERT INTO runner (fName, lName, gender, phone, email, prefComm, selRace) VALUES (?, ?, ?, ?, ?, ?, ?)");
              $sql->bind_param("sssssss", $fName, $lName, $gender, $phone, $email, $prefComm, $selRace);

              // Execute the statement
              if ($sql->execute()) {
                  //echo "<strong>Thank you for registering!</strong>";
              } else {
                  echo "Error: " . $sql->error;
              }

              // Close the database connection
              $sql->close();
          } else {
              //echo "<b>Error: All form fields must be filled</b>";
          }



          ?>
          </form>
      </section>

      <!-- Desrciptive text after form -->
      <p id="tagline">Get ready for an icy adventure at the frozen fish fun run in Angle Inlet, Minnesota.
        Lace up your best running shoes and join us for a fun run!
      </p>

      <!-- Tables -->
      <section>

        <!-- Runner Table -->
        <h2>Runner Table</h2>

<?php
    // get data from database
    $sql = "SELECT * FROM runner";

    $result = $conn->query($sql);
    
    // Check if the query was successful
    if ($result === false) {
      die("Error executing query: " . $conn->error);
    }
   
    // Check if $result is an object before accessing num_rows
if (is_object($result)) {
  // Check if there are rows in the result set
  if ($result->num_rows > 0) {
      // Output data of each row
      echo "<table border='1'>
              <tr>
                  <th>Runner ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Gender</th>
                  <th>Phone</th>
              </tr>";

      while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['id_runner']}</td>
                  <td>{$row['fName']}</td>
                  <td>{$row['lName']}</td>
                  <td>{$row['gender']}</td>
                  <td>{$row['phone']}</td>
              </tr>";
      }

      echo "</table>";
  } else {
      echo "0 results";
  }
} else {
  echo "Invalid result set";
}

    //$conn->close();
?>
        <!-- Race Table -->
        <h2>Race Table</h2>
        
  <?php
    // get data from database
    $sql = "SELECT * FROM race";

    $result = $conn->query($sql);
    
    // Check if the query was successful
    if ($result === false) {
      die("Error executing query: " . $conn->error);
    }
   
    // Check if $result is an object before accessing num_rows
if (is_object($result)) {
  // Check if there are rows in the result set
  if ($result->num_rows > 0) {
      // Output data of each row
      echo "<table border='1'>
              <tr>
                  <th>Race ID</th>
                  <th>Race Name</th>
                  <th>Entrance Fee</th>
              </tr>";

      while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['id_race']}</td>
                  <td>{$row['raceName']}</td>
                  <td>{$row['entranceFee']}</td>
              </tr>";
      }

      echo "</table>";
  } else {
      echo "0 results";
  }
} else {
  echo "Invalid result set";
}

    //close connection
    //$conn->close();
?>
        <!-- Sponsor Table -->
        <h2>Sponsor Table</h2>
        
<?php
    // get data from database
    $sql = "SELECT * FROM sponsor";

    $result = $conn->query($sql);
    
    // Check if the query was successful
    if ($result === false) {
      die("Error executing query: " . $conn->error);
    }
   
    // Check if $result is an object before accessing num_rows
if (is_object($result)) {
  // Check if there are rows in the result set
  if ($result->num_rows > 0) {
      // Output data of each row
      echo "<table border='1'>
              <tr>
                  <th>Sponsor ID</th>
                  <th>Sponsor Name</th>
                  <th>Sponsor Email</th>
                  <th>Runner ID</th>
              </tr>";

      while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['id_sponsor']}</td>
                  <td>{$row['sponsorName']}</td>
                  <td>{$row['sponsorEmail']}</td>
                  <td>{$row['id_runner']}</td>
              </tr>";
      }

      echo "</table>";
  } else {
      echo "0 results";
  }
} else {
  echo "Invalid result set";
}
    // close connection
    $conn->close();
?>
      </section>

      <!-- Pictures of layout and my database -->
      <section> 
        <a href="../database.png" target="_blank">
        <img id="myDatabase" src="../database.png" alt="My database" width="300px" height="200px"></a>
        <a href="../layout.png" target="_blank">
        <img id="myLayout" src="../layout.png" alt="How I want my layout to look" width="200px" height="300px"></a>
        <a href="../runnerUpdateProcedure.png" target="_blank">
        <img id="myProcedure" src="../runnerUpdateProcedure.png" alt="Procedure for adding runner" width="300px" height="300px"></a>
      </section>

      <!-- About section -->
      <section id="about">
        <h3>About the Organizer</h3>
        <p>He is trying his best and desperately wants to pass the class.</p>
      </section>

</body>
</html>

