<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Post Message</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href=<?php echo base_url("styles/general_styles.css") ?>> -->
</head>
<body>

<nav class="navbar navbar-expand-sm navbar-dark" style="background-color: #2C5E91;">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href=<?php echo site_url("/search"); ?>>Search</a>
            </li>
            <?php if ($this->session->userdata("username")) { ?>
                <li class="nav-item">
                    <a class="nav-link" href=<?php echo site_url("/message"); ?>>Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=<?php echo site_url("/user/feed/".$this->session->userdata("username")) ?>>My Feed</a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="mx-auto order-0">
        <span class="navbar-brand mx-auto" href="#">
            <a href=<?php echo site_url("/search"); ?>><img src=<?php echo base_url("images/sitelogo.svg"); ?> alt="Blogging Site" /></a>
        </span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2" style="margin-left: 10px;">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">

        <?php if ($this->session->userdata("username")) { ?>
            <li class="nav-item">
                <a class="nav-link" href=<?php echo site_url("/user/logout"); ?>>Logout</a>
            </li>
        <?php } else {?>
            <li class="nav-item">
                <a class="nav-link" href=<?php echo site_url("/user/login"); ?>>Login</a>
            </li>
        <?php } ?>
        </ul>
    </div>
</nav>

<form id="postForm" class="container" style="margin-top:30px" action=<?php echo site_url("message/doPost")?> method="post">
    <h2>Make A New Post!</h2>
    <div class="row">
        <div class="col form-group">
            <textarea class="form-control" aria-describedby="postError" form="postForm" name="postInput" placeholder="Write your post here..." rows="10"></textarea>
            <small id="postError" class="form-text text-danger"><?php echo $error_text ?></small>
        </div>
    </div>

    <div>
            <button class="btn btn-outline-primary" type="submit">Post</button>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>