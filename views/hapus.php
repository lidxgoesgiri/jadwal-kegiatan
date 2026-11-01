<a class="back-link" href="<?= e(url()) ?>"><?= ui_icon('back') ?>Kembali ke daftar</a>
<section class="confirmation-layout" aria-labelledby="page-title">
    <div class="confirmation-symbol" aria-hidden="true"><?= ui_icon('trash') ?></div>
    <p class="eyebrow">KONFIRMASI PENGHAPUSAN</p>
    <h1 id="page-title">Hapus kegiatan ini<span class="title-dot">?</span></h1>
    <p class="page-description">Kegiatan berikut akan dihapus dari daftar.<br>Tindakan ini tidak dapat dibatalkan.</p>
    <div class="confirmation-item">
        <h2><?= e($item['nama_kegiatan']) ?></h2>
        <p><?= e(format_tanggal($item['tanggal'])) ?> · <?= e(format_waktu($item['waktu'])) ?> WIB</p>
        <p class="confirmation-place"><?= ui_icon('pin') ?><span><?= e($item['tempat']) ?></span></p>
    </div>
    <form class="confirmation-actions" action="<?= e(url('hapus.php?id=' . (int) $item['id'])) ?>" method="post">
        <?= csrf_field() ?>
        <a class="button button-secondary" href="<?= e(url()) ?>">Batal</a>
        <button class="button button-danger" type="submit"><?= ui_icon('trash') ?>Ya, hapus kegiatan</button>
    </form>
</section>
