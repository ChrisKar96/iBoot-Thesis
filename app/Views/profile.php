<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<?php
 $user = session()->get('user');
?>
    <main role="main" class="py-5">
        <div class="container">
            <div class="row">
                <h1 class="panel-heading"><?= lang('Text.profile') ?></h1>
                <div class="panel-body">
                    <h3><?= lang('Text.hi') . ', ' . $user->name ?></h3>
                    <hr>
                    <p><strong><?= lang('Text.username') ?>:</strong> <?= $user->username ?></p>
                    <p><strong><?= lang('Text.email') ?>:</strong> <?= $user->email ?> <strong><?= lang('Text.status') ?>:</strong>
                    <?php if ($user->verifiedEmail) : ?>
                        <span style='color:green;'><?= lang('Text.verified') ?></span>
                    <?php else : ?>
                        <span style='color:red;'><?= lang('Text.not_verified') ?></span> <a href="<?= base_url('sendEmailVerification/' . $user->email)?>"><?= lang('Text.resend_confirmation_email') ?></a>
                    <?php endif; ?>
                    </p>
                    <p><strong><?= lang('Text.phone') ?>:</strong> <?= $user->phone ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="login-clean">
                        <?php
                        if (isset($msgChangePassword) && $msgChangePassword): ?>
                            <div class="alert alert-success">
                                <?= lang("Text.password_changed") ?>
                            </div>
                        <?php
                        endif;
                        if (isset($msgChangePasswordNoMatchOld) && $msgChangePasswordNoMatchOld): ?>
                            <div class="alert alert-warning">
                                <?= lang("Text.old_password_is_incorrect") ?>
                            </div>
                        <?php
                        endif;
                        if (isset($validationChangePassword)): ?>
                            <div class="alert alert-warning">
                                <?= $validationChangePassword ?>
                            </div>
                        <?php
                        endif;
                        ?>
                        <form action="<?= base_url('profile'); ?>" method="post" style="margin: 3vmin;">
                            <?= csrf_field() ?>
                            <h4 class="text-center"><?= lang('Text.change_password'); ?></h4>
                            <div class="form-floating mb-3">
                                <input class="form-control" type="password" id="old_password" name="old_password"
                                       placeholder="<?= lang('Text.old_password'); ?>" required>
                                <label class="required" for="old_password"><?= lang('Text.old_password'); ?></label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" type="password" id="password" name="password"
                                       placeholder="<?= lang('Text.password'); ?>" required>
                                <label class="required" for="password"><?= lang('Text.password'); ?></label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" type="password" id="confirm_password" name="confirm_password"
                                       placeholder="<?= lang('Text.confirm_password'); ?>" required>
                                <label class="required" for="confirm_password"><?= lang('Text.confirm_password'); ?></label>
                            </div>
                            <input class="visually-hidden" type="number" id="id" name="id" value="<?= $user->id ?>" required readonly>
                            <button type="submit" class="btn btn-primary"><?= lang('Text.save'); ?></button>
                        </form>
                    </div>
                </div>
                <div class="col">
                    <div class="login-clean">
                        <?php
                        if (isset($msgChangeEmail) && $msgChangeEmail): ?>
                            <div class="alert alert-success">
                                <?= lang('Text.email_changed') ?>
                            </div>
                        <?php
                        endif;
                        if (isset($validationChangeEmail)): ?>
                            <div class="alert alert-warning">
                                <?= $validationChangeEmail ?>
                            </div>
                        <?php
                        endif;
                        ?>
                        <form action="<?= base_url('profile'); ?>" method="post" style="margin: 3vmin;">
                            <?= csrf_field() ?>
                            <h4 class="text-center"><?= lang('Text.change_email'); ?></h4>
                            <div class="form-floating mb-3">
                                <input class="form-control" type="email" id="email" name="email"
                                       placeholder="<?= lang('Text.email'); ?>" required>
                                <label class="required" for="email"><?= lang('Text.email'); ?></label>
                            </div>
                            <input class="visually-hidden" type="number" id="id" name="id" value="<?= $user->id ?>" required readonly>
                            <button type="submit" class="btn btn-primary"><?= lang('Text.save'); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?= $this->endSection() ?>