<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>View <?php echo ucfirst($username) ?>'s Messages</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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

<div class="container" style="margin-top:30px">

    <?php if ($show_follow) { ?>

    <form class="row d-flex justify-content-center" action=<?php echo site_url($follow_form_loc)?> method="post"> 
        <button class="btn btn-outline-primary" type="submit"><?php echo $follow_but_text ?></button>
    </form>

    <?php } ?>

    <div class="row list-group" style="margin-top:20px">

    <?php foreach($results as $row) { ?>

        <figure class="text-end list-group-item list-group-item-action">
            <blockquote class="blockquote">

            <p class="h6"><?php echo $row["text"] ?></p>

            </blockquote>
            <figcaption class="blockquote-footer">
                By <?php echo "<a href='".site_url("user/view/".$row["user_username"])."'>".$row["user_username"]."</a>" ?> at <?php echo $row["posted_at"] ?>
            </figcaption>
        </figure>

    <?php } ?>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>