<html>
<head>
    <title>Server Connection - CS 350 User Client</title>
    <link rel="stylesheet" type="text/css" href="./assets/css/styles.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/form-box.css" />
</head>
<body>
    <?php include_once('./partials/nav.php'); ?>
    <main>
        <div class="form-box">
            <div class="centered">
                <form name="register" method="POST" action="./register.php">
                    <div class="row center"><h1>Sign Up</h1></div>
                    <div class="row center">Fill out the information below to sign up for an account</div>

                    <div class="row topspace"><label for"firstname">First name:</label></div>
                    <div class="row"><input placeholder="Firstname" name="first" type="text" /></div>

                    <div class="row topspace"><label for"lastname">Last name:</label></div>
                    <div class="row"><input placeholder="Lastname" name="last" type="text" /></div>

                    <div class="row topspace"><label for="username">Username:</label></div>
                    <div class="row"><input placeholder="Username" name="username" type="text" /></div>

                    <div class="row topspace"><label for"password">Password:</label></div>
                    <div class="row"><input placeholder="Password" name="password" type="password" /></div>

                    <div class="row topspace center"><input type="submit" text="Register"/></div>
                    <div class="row topspace center"><a href="./index.php">I already have an account</a></div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>