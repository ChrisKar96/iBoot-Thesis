<?php

if (! isset($title)) {
    $title = lang('Text.dashboard');
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= site_url(); ?>">iBoot</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="<?= lang('Text.toggle_navigation_bar'); ?>">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?php
            if (session()->get('isLoggedIn')) : ?>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= ($title === lang('Text.computers')) ? ' active' : ''; ?>"
                           aria-current="page" href="<?= site_url('computers'); ?>"><?= lang('Text.computers'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($title === lang('Text.groups')) ? ' active' : ''; ?>" aria-current="page"
                           href="<?= site_url('groups'); ?>"><?= lang('Text.groups'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($title === lang('Text.labs')) ? ' active' : ''; ?>" aria-current="page"
                           href="<?= site_url('labs'); ?>"><?= lang('Text.labs'); ?></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= ($title === lang('Text.ipxe_blocks') || $title === lang('Text.schedules')) ? ' active' : ''; ?>"
                           href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= lang('Text.boot_menu_options'); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item <?= ($title === lang('Text.ipxe_blocks')) ? ' active' : ''; ?>"
                                   href="<?= site_url('ipxeblocks'); ?>"><?= lang('Text.ipxe_blocks'); ?></a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= ($title === lang('Text.boot_menu')) ? ' active' : ''; ?>"
                                   href="<?= site_url('boot_menu'); ?>"><?= lang('Text.boot_menu'); ?></a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= ($title === lang('Text.schedules')) ? ' active' : ''; ?>"
                                   href="<?= site_url('schedules'); ?>"><?= lang('Text.schedules'); ?></a>
                            </li>
                        </ul>
                    </li>
					<?php
                    if (session()->get('user')['isAdmin']) : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($title === lang('Text.users')) ? ' active' : ''; ?>" aria-current="page"
                               href="<?= site_url('users'); ?>"><?= lang('Text.users'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($title === lang('Text.log_viewer')) ? ' active' : ''; ?>" aria-current="page"
                               href="<?= site_url('logs'); ?>"><?= lang('Text.log_viewer'); ?></a>
                        </li>
					<?php
                    endif; ?>
                </ul>
            <?php
            endif; ?>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 settings-menu">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-lg fa-language"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item <?= (session()->get('locale') === 'el') ? 'active' : ''; ?>"
                               href="<?= site_url('locale/el'); ?>">
                                Ελληνικά
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?= (session()->get('locale') === 'en') ? 'active' : ''; ?>"
                               href="<?= site_url('locale/en'); ?>">
                                English
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php
                        if (session()->get('isLoggedIn')): ?>
                            <li>
                                <a class="dropdown-item" href="<?= site_url('profile') ?>">
                                    <i class="fas fa-sliders-h fa-fw"></i> <?= lang('Text.profile'); ?>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= site_url('logout') ?>">
                                    <i class="fas fa-sign-out-alt fa-fw"></i> <?= lang('Text.log_out'); ?>
                                </a>
                            </li>
                        <?php
                        else: ?>
                            <li><a class="dropdown-item <?= ($title === lang('Text.log_in')) ? ' active' : ''; ?>"
                                   href="<?= site_url('login') ?>"><?= lang('Text.log_in'); ?></a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item <?= ($title === lang('Text.sign_up')) ? ' active' : ''; ?>"
                                   href="<?= site_url('signup') ?>"><?= lang('Text.sign_up'); ?></a></li>
                        <?php
                        endif; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>