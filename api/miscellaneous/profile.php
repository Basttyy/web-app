<?php
    $page_tittle = "Power - Profile";
    include_once "partials/headers.php";
    include_once "partials/parse_profile.php"
?>
    <div class="container">
            <h1 class="grey-text text-darken-2">Profile</h1><hr>
            <?php if(!isset($_SESSION['username'])): ?>
                    <p class="lead">You are not authorized to view this page <a href="login.php">Login </a>Not yet a member? <a href="signup.php">Signup</a></p>
            <?php else: ?>
                <section class="col col-lg-7">
                    <div class="row col-lg-3" style="margin-bottom: 10;">
                        <img src="<?php if(isset($profile_pic)) echo $profile_pic; ?>" alt="" class="img img-rounded" width="100" height="100">
                    </div>
                    <br>
                    <table class="table table-bordered tabel-condensed">
                        <tr><th>Username:</th><td><?php if(isset($username)) echo $username; ?></td></tr><tr><th>Email:</th><td><?php if(isset($email)) echo $email; ?></td></tr><tr><th>Date Joined:</th><td><?php if(isset($date_joined)) echo $date_joined; ?></td></tr><tr><th></th><td><a class="pull-right" href="edit_profile.php?user_identity=<?php if(isset($encode_id)) echo $encode_id; ?>"><span class="material-icons medium">edit</span> Edit Profile</a></td></tr>
                    </table>
                </section>
            <?php endif ?>
    </div>
    
    <?php include_once "partials/footers.php"; ?>
</body>
</html>