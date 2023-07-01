<?= $this->extend('template') ?>

<?= $this->section('content') ?>
    <div class="container">

        <!-- Forgot username -->
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-10 col-lg-8 col-xl-6 my-md-2">
                <div class="login-clean">
                    <?php
                    if (isset($validationForgotUsername) && count($validationForgotUsername->getErrors()) > 0): ?>
                        <div class="alert alert-warning">
                            <?= $validationForgotUsername->listErrors(); ?>
                        </div>
                    <?php
                    endif;
if (isset($reminderSentUsername)): ?>
                    <div class="alert alert-success">
                        <p><?= lang('Text.you_will_receive_an_email_with', [lang('Text.your_username')]); ?>.</p>
                    </div>
                    <?php
endif;
if (isset($userNotFoundUsername)): ?>
                        <div class="alert alert-warning">
                            <p><?= lang('Text.no_user_was_found_with_this', [lang('Text.email')]); ?>.</p>
                        </div>
					<?php
endif;
?>
                    <form action="<?= base_url('forgotUsername'); ?>" method="post">
                        <?= csrf_field() ?>
                        <h2 class="text-center"><?= lang('Text.forgot_username'); ?></h2>
                        <div class="form-floating mb-3">
                            <input class="form-control" type="text" id="email" name="email"
                                   placeholder="<?= lang('Text.email'); ?>" required>
                            <label class="required" for="email"><?= lang('Text.email'); ?></label>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary"><?= lang('Text.forgot_username'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Forgot password -->
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-10 col-lg-8 col-xl-6 my-md-2">
                <div class="login-clean">
					<?php
if (isset($validationForgotPassword) && count($validationForgotPassword->getErrors()) > 0): ?>
                        <div class="alert alert-warning">
							<?= $validationForgotPassword->listErrors(); ?>
                        </div>
					<?php
endif;
if (isset($reminderSentPassword)): ?>
                        <div class="alert alert-success">
                            <p><?= lang('Text.you_will_receive_an_email_with', [lang('Text.instructions_to_reissue_your_password')]); ?>.</p>
                        </div>
					<?php
endif;
if (isset($userNotFoundPassword)): ?>
                        <div class="alert alert-warning">
                            <p><?= lang('Text.no_user_was_found_with_this', [lang('Text.username_or_email')]); ?>.</p>
                        </div>
					<?php
endif;
?>
                    <form action="<?= base_url('forgotPassword'); ?>" method="post">
						<?= csrf_field() ?>
                        <h2 class="text-center"><?= lang('Text.forgot_password'); ?></h2>
                        <div class="form-floating mb-3">
                            <input class="form-control" type="text" id="username" name="username"
                                   placeholder="<?= lang('Text.username_or_email'); ?>" required>
                            <label class="required" for="username"><?= lang('Text.username_or_email'); ?></label>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary"><?= lang('Text.forgot_password'); ?></button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
<?= $this->endSection() ?>