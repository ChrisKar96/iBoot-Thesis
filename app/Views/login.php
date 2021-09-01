<?= $this->extend('template') ?>

<?= $this->section('content') ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 my-md-2">
                <div class="login-clean">
                    <?php if (session()->getFlashdata('msg')):?>
                        <div class="alert alert-warning">
                            <?= session()->getFlashdata('msg') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('login'); ?>" method="post">
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
                        <button type="submit" class="btn btn-primary">Log In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>