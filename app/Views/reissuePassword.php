<?= $this->extend('template') ?>

<?= $this->section('content') ?>
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-10 col-lg-8 col-xl-6 my-md-2">
                <div class="login-clean">
					<?php
                    if (isset($validationReissuePassword) && count($validationReissuePassword->getErrors()) > 0): ?>
                        <div class="alert alert-warning">
							<?= $validationReissuePassword->listErrors(); ?>
                        </div>
					<?php
                    endif;
if (isset($userNotFoundReissuePassword)): ?>
                        <div class="alert alert-warning">
                            <p><?= lang('Text.no_user_was_found_with_this', [lang('Text.username_or_email')]); ?>.</p>
                        </div>
					<?php
endif;
if (isset($tokenInvalid) && $tokenInvalid): ?>
                        <div class="alert alert-danger">
							<?= 'This token is invalid'; ?>
                        </div>
                    <?php
elseif (isset($passwordChanged) && $passwordChanged): ?>
                        <div class="alert alert-success">
                            <p><?= lang('Text.your_password_was_changed_successfully'); ?></p>
                        </div>
                        <div class="text-center">
                            <a href="<?= base_url('login'); ?>" class="btn btn-primary"><?= lang('Text.back_to_login'); ?></a>
                        </div>
                    <?php
elseif (isset($token)): ?>
                    <form action="<?= base_url('forgotPassword/token/' . $token); ?>" method="post">
						<?= csrf_field() ?>
                        <h2 class="text-center"><?= lang('Text.forgot_password'); ?></h2>
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
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary"><?= lang('Text.forgot_password'); ?></button>
                        </div>
                    </form>
                    <?php
endif; ?>
                </div>
            </div>

        </div>
    </div>
<?= $this->endSection() ?>