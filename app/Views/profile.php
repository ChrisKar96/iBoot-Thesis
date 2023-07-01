<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<?php
 $user = session()->get('user');
?>
    <main role="main" class="py-5">
        <div class="container">
            <div class="row">
                <h1 class="panel-heading">Profile</h1>
                <div class="panel-body">
                    <h3>Hi, <?= $user->name ?></h3>
                    <hr>
                    <p><strong>Username:</strong> <?= $user->username ?></p>
                    <p><strong>Email:</strong> <?= $user->email ?> <strong>Status:</strong>
                    <?php if ($user->verifiedEmail) {
                        echo "<span style='color:green;'>Verified</span>";
                    } else {
                        echo "<span style='color:red;'>Not Verified</span> <a href='" . base_url('sendEmailVerification/' . $user->email) . "'>Send confirmation mail again</a>";
                    } ?>
                    </p>
                    <p><strong>Phone:</strong> <?= $user->phone ?></p>
                </div>
            </div>
        </div>
    </main>

<?= $this->endSection() ?>