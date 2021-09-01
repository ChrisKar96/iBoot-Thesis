<?php

if (! isset($title)) {
    $title = 'Dashboard';
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= site_url(); ?>">iBoot</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php
        if (session()->get('isLoggedIn')) : ?>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($title === 'Computers') ? ' active' : ''; ?>"
                       aria-current="page" href="<?= site_url('computers'); ?>">Computers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($title === 'Groups') ? ' active' : ''; ?>" aria-current="page"
                       href="<?= site_url('groups'); ?>">Groups</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= ($title === 'Buildings' || $title === 'Rooms') ? ' active' : ''; ?>"
                       href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Locations</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item <?= ($title === 'Buildings') ? ' active' : ''; ?>"
                               href="<?= site_url('buildings'); ?>">Buildings</a></li>
                        <li><a class="dropdown-item <?= ($title === 'Rooms') ? ' active' : ''; ?>"
                               href="<?= site_url('rooms'); ?>">Rooms</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= ($title === 'OS Images' || $title === 'Configurations') ? ' active' : ''; ?>"
                       href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Boot Menu Options
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item <?= ($title === 'OS Images') ? ' active' : ''; ?>"
                               href="<?= site_url('os-images'); ?>">OS Images</a></li>
                        <li><a class="dropdown-item <?= ($title === 'Configurations') ? ' active' : ''; ?>"
                               href="<?= site_url('configurations'); ?>">Configurations</a></li>
                    </ul>
                </li>
            </ul>
            <a class="btn btn-light m-sm-2" href="<?= site_url('profile') ?>">Profile</a>
            <a class="btn btn-danger m-sm-2" href="<?= site_url('logout') ?>">Log Out</a>
            <?php
            elseif ($title === 'Log In'): ?>
                <a class="btn btn-light my-2 my-sm-0" href="<?= site_url('register') ?>">Register</a>
            <?php
            elseif ($title === 'Register'): ?>
                <a class="btn btn-light my-2 my-sm-0" href="<?= site_url('login') ?>">Log In</a>
            <?php
            endif; ?>
        </div>
    </div>
</nav>
