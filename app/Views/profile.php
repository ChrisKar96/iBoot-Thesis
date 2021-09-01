<?= $this->extend('template') ?>

<?= $this->section('content') ?>

    <main role="main" class="py-5">
        <div class="container">
            <div class="row">
                <h1 class="panel-heading">Profile</h1>
                <div class="panel-body">
                    <h3>Hi, <?= $user['name'] ?></h3>
                    <hr>
                    <p>Username: <?= $user['username'] ?></p>
                    <p>Phone: <?= $user['phone'] ?></p>
                </div>
            </div>
        </div>
    </main>

<?= $this->endSection() ?>