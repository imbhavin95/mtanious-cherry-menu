<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title -->
    <title><?php echo $data['name'] ? $data['name'].' - ' : '' ?> Cherry Menu</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="CherryMenu">
    <meta name="robots" content="index, follow">
    <link rel="manifest" id="my-manifest-cherry-menu">

    <meta name="keywords" content="android, ios, mobile, mobile template, mobile app, ui kit, dark layout, app, delivery, ecommerce, material design, mobile, mobile web, order, phonegap, pwa, store, web app, Ombe, coffee app, coffee template, coffee shop, mobile UI, coffee design, app template, responsive design, coffee showcase, style app, trendy app, modern UI, technology, User-Friendly Interface, Coffee Shop App, PWA (Progressive Web App), Mobile Ordering, Coffee Experience, Digital Menu, Innovative Technology, App Development, Coffee Experience, cafe, bootatrap, Bootstrap Framework, UI/UX Design, Coffee Shop Technology, Online Presence, Coffee Shop Website, Cafe Template, Mobile App Design, Web Application, Digital Presence, ">

    <meta name="description" content="Discover the perfect blend of design and functionality with Ombe, a Coffee Shop Mobile App Template crafted with Bootstrap and enhanced with Progressive Web App (PWA) capabilities. Elevate your coffee shop's online presence with a seamless, responsive, and feature-rich template. Explore a modern design, user-friendly interface, and PWA technology for an immersive mobile experience. Brew success for your coffee shop effortlessly – Ombe is the ideal template to caffeinate your digital presence.">

    <meta property="og:title" content="">
    <meta property="og:description" content="">

    <meta property="og:image" content="">

    <meta name="format-detection" content="telephone=no">

    <meta name="twitter:title" content="">
    <meta name="twitter:description" content="">

    <meta name="twitter:image" content="">
    <meta name="twitter:card" content="">

    <!-- Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui, viewport-fit=cover">

    <!-- Favicons Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('img/faviconcherry.jpg') ?>">

    <!-- Global CSS -->
    <link rel="stylesheet" href="<?= base_url('frontend/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('frontend/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('frontend/assets/vendor/nouislider/nouislider.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('frontend/assets/vendor/swiper/swiper-bundle.min.css') ?>">

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('frontend/assets/css/style.css') ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&family=Raleway:wght@300;400;500&display=swap" rel="stylesheet">

</head>
<body>
<div class="page-wrapper">

    <!-- Preloader -->
    <div id="preloader">
        <div class="loader">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <!-- Preloader end-->

    <!-- Header -->
    <header class="header header-fixed">
        <div class="header-content">
            <div class="left-content">
                <?php
                if($this->uri->segment(2) == 'item_detail' || $this->uri->segment(1) == 'restaurant'){
                  ?>
                    <a href="<?php echo base_url('/r/'.$data['name']); ?>" class="back-btn">
                        <i class="feather icon-arrow-left"></i>
                    </a>
                <?php
                }
                ?>
            </div>
            <div class="mid-content">
                <a href="<?php echo $this->uri->segment(2) == 'item_detail' ? base_url('/r/'.$data['name']) : '#'; ?>"><img width="100" src="<?php echo $data['rest_image'] ? base_url().'/login/public/settings/logo/'.$data['rest_image'] : base_url('img/gray-logo.svg'); ?>"/></a>
            </div>
            <div class="right-content d-flex align-items-center gap-4">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!--                <a href="javascript:void(0);" class="font-24">-->
<!--                    <i class="font-w700 feather icon-more-vertical-"></i>-->
<!--                </a>-->
            </div>
        </div>
    </header>
    <!-- Header -->

