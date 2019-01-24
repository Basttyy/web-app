<?php
    $page_tittle = "User Authentication - {$_GET['u']}'s Profile";
    include_once "partials/headers.php";
    include_once "partials/parse_profile.php"
?>
    <div class="container">
            <h1><?php if(isset($username)) echo "{$username}'s"; ?></h1><hr>

                <section class="col col-lg-7">
                    <div class="row col-lg-3" style="margin-bottom: 10;">
                        <img src="<?php if(isset($profile_pic)) echo $profile_pic; ?>" alt="" class="img img-rounded" width="100" height="100">
                    </div>
                    <br>
                    <table class="table table-bordered tabel-condensed">
                        <tr><th>Username:</th><td><?php if(isset($username)) echo $username; ?></td></tr><tr><th>Status:</th><td><?php if(isset($status)) echo $status; ?></td></tr><tr><th>Date Joined:</th><td><?php if(isset($date_joined)) echo $date_joined; ?></td></tr>
                    </table>
                </section>
        <p>Go to Home Page <a href="index.php">Back</a></p>
    </div>
    
    <?php include_once "partials/footers.php"; ?>
</body>
</html>