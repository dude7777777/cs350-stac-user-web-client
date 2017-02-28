<link rel="stylesheet" type="text/css" href="./assets/css/form-box.css" />

<div class="form-box">
    <div class="centered">
        <div class="row center"><h1>Login</h1></div>

        <form name="login" method="POST" action="./login.php">
            <div class="row topspace"><label for="username">Username</label></div>
            <div class="row"><input type="text" name="username" placeholder="Username" /></div>

            <div class="row topspace"><label for="password">Password</label></div>
            <div class="row"><input type="password" name="password" placeholder="Password" /></div>

            <div class="row center topspace">
                <input type="submit" value="Login" />
            </div>

            <div class="row center topspace">
                <a href="./signup.php">Sign Up</a>
            </div>
        </form>
    </div>
</div>