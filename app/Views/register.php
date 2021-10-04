<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 my-md-2">
            <div class="login-clean">
                <?php if (isset($validation)):?>
                    <div class="alert alert-warning">
                        <?= $validation->listErrors() ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('register'); ?>" method="post">
                    <h2 class="text-center"><?= lang('Text.sign_up'); ?></h2>
                    <div class="illustration">
                        <img alt="iboot logo" class="img-responsive" src='<?= base_url('/assets/img/computer.png'); ?>'>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" id="username" name="username" placeholder="<?= lang('Text.username'); ?>" required>
                        <label class= "required" for="username"><?= lang('Text.username'); ?></label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="password" id="password" name="password" placeholder="<?= lang('Text.password'); ?>" required>
                        <label class= "required" for="password"><?= lang('Text.password'); ?></label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="password" id="password_confirm" name="password_confirm" placeholder="<?= lang('Text.retype_password'); ?>" required>
                        <label class= "required" for="password_confirm"><?= lang('Text.retype_password'); ?></label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" id="name" name="name" placeholder="<?= lang('Text.name'); ?>" required>
                        <label class= "required" for="name"><?= lang('Text.name'); ?></label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" id="phone" name="phone" placeholder="<?= lang('Text.phone'); ?>">
                        <label for="phone"><?= lang('Text.phone'); ?></label>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= lang('Text.sign_up'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>