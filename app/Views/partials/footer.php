<footer class="bg-primary bg-gradient text-center mt-auto py-3">
    <div class="container">
        <span class="text-white-50">iBoot &copy;
            <?php
            if ((int) (date('Y')) > 2021) {
                echo '2021-';
            }
            echo date('Y');
            ?>
            <?= lang('Text.design_and_development'); ?>:
            <a class="text-white text-decoration-none"
               title="<?= lang('Text.christos_karamolegkos'); ?>"
               href="<?= lang('Text.christoskaramo_website'); ?>"
               target="_blank" rel="author noopener">
                <?= lang('Text.christos_karamolegkos'); ?>
            </a>
        </span>
    </div>
</footer>