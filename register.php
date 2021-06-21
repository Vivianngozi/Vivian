<?php require 'header.php'; ?>
<?php
//Define variables and initialize with empty values
$username=$email = $password = $confirm_password  = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

//processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //validate username
  if (empty(trim($_POST["username"]))){
    $username_err = "Please enter a username.";
  } else {
// Prepare a select statement
$sql = "SELECT id FROM users WHERE username = ? AND email = ?";
if($stmt = mysqli_prepare($connect, $sql)){
// Bind variables to the prepared statement as parameters
mysqli_stmt_bind_param($stmt, "ss",
$param_username, $param_email);
// Set parameters
$param_username = trim($_POST["username"]);
$param_email = trim($_POST['email']);
// Attempt to execute the prepared statement
if(mysqli_stmt_execute($stmt)){
/* store result */
mysqli_stmt_store_result($stmt);
if(mysqli_stmt_num_rows($stmt) == 1){
$email_err= $username_err = "This username/email is already taken.";
} else{
$username = trim($_POST["username"]);
$email = trim($_POST['email']);
}
} else{
echo "Oops! Something went wrong. Please try again later.";
}
// Close statement
mysqli_stmt_close($stmt);
}
}
// Validate password
if(empty(trim($_POST["password"]))){
$password_err = "Please enter a password.";
} elseif(strlen(trim($_POST["password"])) < 10){
$password_err = "Password must have atleast 10 characters.";
} else{
$password = trim($_POST["password"]);
}
// Validate confirm password
if(empty(trim($_POST["confirm_password"]))){
$confirm_password_err = "Please input password again.";
} else{
$confirm_password = trim($_POST["confirm_password"]);
if(empty($password_err) && ($password != $confirm_password)){
$confirm_password_err = "Password did not match.";
}
}
// Check input errors before inserting in database
if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
// Prepare an insert statement
$sql = "INSERT INTO users (username, email,password) VALUES (?, ?, ?)";
if($stmt = mysqli_prepare($connect, $sql)){
// Bind variables to the prepared statement as parameters
mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email,
$param_password);
// Set parameters
$param_username = $username;
$param_email = $email;
$param_password = password_hash($password, PASSWORD_DEFAULT);
// Creates a password hash
// Attempt to execute the prepared statement
if(mysqli_stmt_execute($stmt)){
// Redirect to login page
header("location: login.php");
} else{
echo "Something went wrong. Please try again later.";
}
// Close statement
mysqli_stmt_close($stmt);
}
}
// Close connection
mysqli_close($connect);
}
?>



    <div class="row p-5 m-5">
        <div class="col-12">
            <div class="card p-5">
                <h2>Sign Up</h2>
                <p>Please fill this form to create an account</p>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
    <label for="username">Username</label>
    <input type="username" class="form-control" name="username" value="<?php echo $username;
?>">
<span class="help-block"><?php echo $username_err; ?></span>
  </div>
  <div class="form-group <?php echo (!empty($email_err)) ?  'has-error'  :  '' ; ?>">
    <label for="email">email</label>
    <input type="email" class="form-control"  name="email">
    <span class="help-block"><?php echo $email_err; ?></span>
  </div>
  <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control"  name="password" value="<?php echo
$password; ?>">
<span class="help-block"><?php echo $password_err; ?></span>
  </div>
  <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'haserror'
: ''; ?>">
    <label for="exampleInputPassword1">Confirm Password</label>
    <input type="password" class="form-control" name="confirm_password" value="<?php
echo $confirm_password; ?>">
<span class="help-block"><?php echo $confirm_password_err; ?></span>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
  <input type="reset" value="Reset" class="btn btn-default">
  <p>Already have an account? <a href="login.php">Login here.</a></p>
</form>
            </div>
        
</div>
</div>
<?php require 'footer.php'; ?>