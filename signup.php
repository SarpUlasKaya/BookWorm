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
    <input type="text"
           id="firstName"
           placeholder="First name">
    <br></br>
    <input type="text"
           id="lastName"
           placeholder="Last name"
           style="margin-top: 5px;">
    <br></br>
    <input type="text"
           id="email"
           placeholder="Email"
           style="margin-top: 5px;">
    <br></br>
    <input type="text"
           id="password"
           placeholder="Password"
           style="margin-top: 5px;">
    <br></br>
    <input type="text"
           id="confirmPassword"
           placeholder="Confirm Password"
           style="margin-top: 5px;">
    <br></br>
    <input type="date"
           id="birthday"
           placeholder="Birthday"
           style="margin-top: 5px;">
    <br></br>
    <select id="gender" placeholder="Gender" style="margin-top: 5px">
      <option value="male">Male</option>
      <option value="female">Female</option>
      <option value="other">Other</option>
    </select>
    <br></br>
    <select id="accountType" placeholder="Account Type" style="margin-top: 5px">
          <option value="librarian">Librarian</option>
          <option value="Author">Author</option>
          <option value="Reader">Reader</option>
    </select>
    <br></br>
    <button type="button" onclick="checkInputs()">
      Sign Up
    </button>
  </div>
  <?php

  ?>
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
              errorMsg += "Please fill out the acount type field. \n";
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
    checkForEmptyInputs();
    checkPasswordConfirmation();
  }
</script>