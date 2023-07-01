<?= $this->extend('template') ?>

<?= $this->section('content') ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-10 col-lg-8 col-xl-6 my-md-2">
                <div class="login-clean">
                    <?php
                    if (session()->getFlashdata('msg')): ?>
                        <div class="alert alert-warning">
                            <?= session()->getFlashdata('msg') ?>
                        </div>
                    <?php
                    endif;
if (isset($validation)): ?>
                        <div class="alert alert-warning">
                            <?= $validation->listErrors(); ?>
                        </div>
                    <?php
endif;
?>
                    <form action="<?= base_url('login'); ?>" method="post" style="margin: 3vmin;">
                        <?= csrf_field() ?>
                        <h2 class="text-center"><?= lang('Text.log_in'); ?></h2>
                        <div class="illustration">
                            <img alt="iboot logo" class="img-responsive"
                                 src='<?= base_url('/assets/img/computer.png'); ?>'>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" type="text" id="username" name="username"
                                   placeholder="<?= lang('Text.username_or_email'); ?>" required>
                            <label class="required" for="username"><?= lang('Text.username_or_email'); ?></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" type="password" id="password" name="password"
                                   placeholder="<?= lang('Text.password'); ?>" required>
                            <label class="required" for="password"><?= lang('Text.password'); ?></label>
                        </div>
                        <button type="submit" class="btn btn-primary"><?= lang('Text.log_in'); ?></button>
                        <div class="row mt-3">
                            <div class="col text-center">
                                <a href="<?= base_url('forgotCredentials'); ?>"><?= lang('Text.forgot_credentials'); ?></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>