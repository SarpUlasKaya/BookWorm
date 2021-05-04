<?php
require_once('config.php')
?>
<DOCTYPE! html>
<html>
<head>
    <title>
        SignUpPage
    </title>
</head>
<body>
  <h1>
    Congrats! Welcome to BookWorm Family
  </h1>
  <div>
      <?php
      if (isset($_POST['signup'])) {
          echo 'isset entered.';
          $name = $_POST['firstName'];
          $name .= " ";
          $name .= $_POST['lastName'];
          $email = $_POST['email'];
          $password = $_POST['password'];
          $birthday = $_POST['birthday'];
          $gender = $_POST['gender'];
          /*
          $query = "INSERT INTO users( user_id, name, mail_address, password, last_login,
                             created_at, birthday, gender ) VALUES (LAST_INSERT_ID(), ?, ?, ?, ?, ?, ?, ?)";
          $stmtinsert = $mysqli->prepare($query);
          $stmtinsert->bind_param($name, $email, $password, $current_date, $current_date,
                                    $birthday, $gender);
          $result = $stmtinsert->execute();
          */

          $query = "INSERT INTO users( user_id, name, mail_address, password, last_login,
                             created_at, birthday, gender ) VALUES (LAST_INSERT_ID(),'$name', '$email',
                                                                    '$password', NOW(), NOW(),
                                                                    '$birthday', '$gender')";
          $stmtinsert = $mysqli->prepare($query);
          $result = $stmtinsert->execute();
          $stmtinsert->close();
          if($result) {
              echo 'Successfully inserted new user.';
          }
          switch ($_POST['accountType']) {
              case 'librarian' :
                  echo 'librarian';
                  $libQuery = "INSERT INTO librarian_account( user_id ) SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1";
                  $stmtinsert = $mysqli->prepare($libQuery);
                  $result = $stmtinsert->execute();
                  $stmtinsert->close();
                  if($result) {
                      echo 'Successfully inserted new librarian.';
                  }
                  break;
              case 'author' :
                  echo 'author';
                  $autQuery = "INSERT INTO author_account( user_id ) SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1";
                  $stmtinsert = $mysqli->prepare($autQuery);
                  $result = $stmtinsert->execute();
                  $stmtinsert->close();
                  if($result) {
                      echo 'Successfully inserted new author.';
                  }
                  break;
              case 'reader' :
                  echo 'reader';
                  $readQuery = "INSERT INTO reader_account( user_id ) SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1";
                  $stmtinsert = $mysqli->prepare($readQuery);
                  $result = $stmtinsert->execute();
                  $stmtinsert->close();
                  if($result) {
                      echo 'Successfully inserted new reader.';
                  }
                  break;
          }
      }
      ?>
  </div>
  <form class="button" action="signup.php" method="post">
  <div>
    <input type="text"
           id="firstName"
           name="firstName"
           placeholder="First name">
    <br></br>
    <input type="text"
           id="lastName"
           name="lastName"
           placeholder="Last name"
           style="margin-top: 5px;">
    <br></br>
    <input type="text"
           id="email"
           name="email"
           placeholder="Email"
           style="margin-top: 5px;">
    <br></br>
    <input type="text"
           id="password"
           name="password"
           placeholder="Password"
           style="margin-top: 5px;">
    <br></br>
    <input type="text"
           id="confirmPassword"
           name="confirmPassword"
           placeholder="Confirm Password"
           style="margin-top: 5px;">
    <br></br>
    <input type="date"
           id="birthday"
           name="birthday"
           placeholder="Birthday"
           style="margin-top: 5px;">
    <br></br>
    <select id="gender" name="gender" placeholder="Gender" style="margin-top: 5px">
      <option value="male">Male</option>
      <option value="female">Female</option>
      <option value="other">Other</option>
    </select>
    <br></br>
    <select id="accountType" name="accountType" placeholder="Account Type" style="margin-top: 5px">
          <option value="librarian">Librarian</option>
          <option value="author">Author</option>
          <option value="reader">Reader</option>
    </select>
    <br></br>
    <input type="submit" name="signup" onclick="checkInputs()">
  </div>
  </form>
</body>
</html>
<script>
  function checkForEmptyInputs() {
      var errorMsg = "";
      if ( document.getElementById('firstName').value == "" ) {
        errorMsg += "Please fill out the firstname field. \n";
      }
      if ( document.getElementById('lastName').value == "" ) {
        errorMsg += "Please fill out the lastname field. \n";
      }
      if ( document.getElementById('email').value == "" ) {
        errorMsg += "Please fill out the email field. \n";
      }
      if ( document.getElementById('password').value == "" ) {
        errorMsg += "Please fill out the password field. \n";
      }
      if ( document.getElementById('confirmPassword').value == "" ) {
        errorMsg += "Please fill out the confirm password field. \n";
      }
      if ( document.getElementById('birthday').value == "" ) {
        errorMsg += "Please fill out the birthday field. \n";
      }
      if ( document.getElementById('gender').value == "" ) {
        errorMsg += "Please fill out the gender field. \n";
      }
      if ( document.getElementById('accountType').value == "" ) {
              errorMsg += "Please fill out the account type field. \n";
      }
      if ( errorMsg != "" ) {
        alert(errorMsg);
        return false;
      }
      return true;
  }
  function checkPasswordConfirmation() {
    var errorMsg = "Password confirmation failed";
    if ( document.getElementById('password').value == document.getElementById('confirmPassword').value) {
      return true
    }
    alert(errorMsg);
    return false;
  }
  function checkInputs() {
    return (checkForEmptyInputs() && checkPasswordConfirmation());
  }
</script>