<?= $this->extend('template') ?>

<?= $this->section('content') ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-10 col-lg-8 col-xl-6 my-md-2">
<?php
    $reg_enabled = config('Registration')->enabled;
if ($reg_enabled) : ?>
                <div class="login-clean">
                    <?php
                    if (isset($validation)): ?>
                        <div class="alert alert-warning">
                            <?= $validation->listErrors() ?>
                        </div>
                    <?php
                    endif;
    if (! isset($title)) {
        $title = lang('Text.sign_up');
    }
    if (! isset($action)) {
        $action = base_url('signup');
    }
    ?>
                    <form action="<?= $action; ?>" method="post" style="margin: 3vmin;">
                        <?= csrf_field() ?>
                        <h2 class="text-center"><?= $title; ?></h2>
                        <div class="illustration">
                            <img alt="iboot logo" class="img-responsive"
                                 src='<?= base_url('/assets/img/computer.png'); ?>'>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" type="text" id="username" name="username"
                                   placeholder="<?= lang('Text.username'); ?>" required>
                            <label class="required" for="username"><?= lang('Text.username'); ?></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" type="password" id="password" name="password"
                                   placeholder="<?= lang('Text.password'); ?>" required>
                            <label class="required" for="password"><?= lang('Text.password'); ?></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" type="password" id="password_confirm" name="password_confirm"
                                   placeholder="<?= lang('Text.retype_password'); ?>" required>
                            <label class="required" for="password_confirm"><?= lang('Text.retype_password'); ?></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" type="text" id="name" name="name"
                                   placeholder="<?= lang('Text.fullname'); ?>" required>
                            <label class="required" for="name"><?= lang('Text.name'); ?></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" type="text" id="email" name="email"
                                   placeholder="<?= lang('Text.email'); ?>" required>
                            <label class="required" for="email"><?= lang('Text.email'); ?></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" type="text" id="phone" name="phone"
                                   placeholder="<?= lang('Text.phone'); ?>">
                            <label for="phone"><?= lang('Text.phone'); ?></label>
                        </div>
                        <button type="submit" class="btn btn-primary"><?= lang('Text.sign_up'); ?></button>
                    </form>
<?php else : ?>
    <h1 class="text-center"><?= lang('Text.registration_is_disabled'); ?></h1>
    <h3 class="text-center"><?= lang('Text.ask_admin_register'); ?></h3>
    <a href="<?= site_url('/login')?>" title="iBoot Log In"><h5 class="text-center"><?= lang('Text.log_in'); ?></h5></a>
<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>