<section class="error-page" aria-labelledby="page-title">
    <p class="error-code"><?= (int) $status ?></p>
    <p class="eyebrow">HALAMAN BELUM DAPAT DITAMPILKAN</p>
    <h1 id="page-title"><?= e($title) ?><span class="title-dot">.</span></h1>
    <p class="page-description"><?= e($message) ?></p>
    <a class="button button-primary" href="<?= e(url()) ?>"><?= ui_icon('back') ?>Kembali ke daftar</a>
</section>
