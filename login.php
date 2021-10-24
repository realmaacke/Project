<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="GUI/login.css">
  <link rel="stylesheet" href="GUI/universal/style.css">
  <title>Combined</title>
</head>
<body>

<div class="wrapper fadeInDown">

  <h1>Combined Login</h1>

  <div id="formContent">
    <!-- Tabs Titles -->
    <h2 class="active"> Sign In </h2>
    <h2 class="inactive underlineHover"><a href="register.php">Sign up</a></h2>
    <!-- Login Form -->
    <br>  <!-- break line  -->
    <p id="errorMSG" style="color:red; display: none;">Invalid Username or Password</p> <!-- Error MSG  -->
    <form  method="POST">
      <input type="text" id="username" class="fadeIn second" id="username" name="username" placeholder="login">
      <input type="password" id="password" class="fadeIn third" id="password" name="password" placeholder="password">
      <button class="fadeIn fourth" id="login" type="button">log in</button>
    </form>

    <!-- Remind Passowrd -->
    <div id="formFooter">
      <a class="underlineHover" href="#">Forgot Password?</a>
    </div>

  </div>
</div>
</body>
</html>

<!-- librarys -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>



<script type="text/javascript">
    $('#login').click(function() // ajax function that sends json to the api, =>  api/auth
    {
        $.ajax(
          {
            type: "POST",
            url: "api/auth",
            processData: false,
            contentType: "application/json",
            data: '{ "username": "'+ $("#username").val() +'", "password": "'+ $("#password").val() +'" }', 
            success: function(r) 
              {
                      console.log(r)
              },
            error: function(r) // if login failed , add a timeout function, then display error msg, (hidden with css properties)
              {
                setTimeout(function ()
                {
                  document.getElementById("errorMSG").style.display = "unset";
                }, 2000, timer()) // call function to hide the display
              }

          });

    });

async function timer() 
{
  await new Promise(resolve => setTimeout(resolve, 6000));  // yield wait
  document.getElementById("errorMSG").style.display = "none";
}
</script>

