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
                    <h2 class="text-center">Είσοδος Χρήστη</h2>
                    <div class="illustration">
                        <img alt="logo here" class="img-responsive" src='<?php echo base_url(); ?>/assets/img/computer.png'>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" id="username" name="username" placeholder="Username" required>
                        <label class= "required" for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="password" id="password" name="password" placeholder="Password" required>
                        <label class= "required" for="password">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="password" id="password_confirm" name="password_confirm" placeholder="Retype Password" required>
                        <label class= "required" for="password_confirm">Retype Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" id="name" name="name" placeholder="Name" required>
                        <label class= "required" for="name">Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" id="phone" name="phone" placeholder="Phone">
                        <label for="phone">Phone</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>