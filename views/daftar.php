<section class="page-heading" aria-labelledby="page-title">
    <div>
        <p class="eyebrow"><span class="eyebrow-line"></span>AGENDA KEGIATAN</p>
        <h1 id="page-title">Jadwal kegiatan<span class="title-dot">.</span></h1>
        <p class="page-description">Temukan waktu dan tempat untuk setiap rencana Anda.</p>
    </div>
    <a class="button button-primary" href="<?= e(url('tambah.php')) ?>"><?= ui_icon('plus') ?>Tambah kegiatan</a>
</section>

<section class="agenda" aria-labelledby="agenda-title">
    <div class="agenda-heading">
        <h2 id="agenda-title">Daftar kegiatan</h2>
        <span class="agenda-count"><?= count($kegiatan) ?> kegiatan</span>
    </div>

    <?php if ($kegiatan === []): ?>
        <div class="empty-state">
            <span class="empty-mark" aria-hidden="true"><span></span><span></span><span></span></span>
            <p class="empty-eyebrow">RUANG UNTUK RENCANA PERTAMA</p>
            <h3>Belum ada kegiatan.</h3>
            <p>Tambahkan kegiatan pertama Anda.<br>Nama, waktu, dan tempatnya akan tersusun di sini.</p>
            <a class="text-link" href="<?= e(url('tambah.php')) ?>">Buat kegiatan pertama <?= ui_icon('arrow') ?></a>
        </div>
    <?php else: ?>
        <ol class="agenda-list">
            <?php $bulanPendek = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']; ?>
            <?php foreach ($kegiatan as $item): ?>
                <li class="agenda-item">
                    <time class="date-stamp" datetime="<?= e($item['tanggal']) ?>" aria-label="<?= e(format_tanggal($item['tanggal'])) ?>">
                        <span class="date-day"><?= e(substr($item['tanggal'], 8, 2)) ?></span>
                        <span class="date-month"><?= e($bulanPendek[(int) substr($item['tanggal'], 5, 2)] ?? '') ?> <?= e(substr($item['tanggal'], 0, 4)) ?></span>
                    </time>
                    <div class="agenda-content">
                        <p class="agenda-date-label"><?= e(format_tanggal($item['tanggal'])) ?></p>
                        <h3><?= e($item['nama_kegiatan']) ?></h3>
                        <div class="agenda-meta">
                            <span><?= ui_icon('clock') ?><time datetime="<?= e(format_waktu($item['waktu'])) ?>"><?= e(format_waktu($item['waktu'])) ?> WIB</time></span>
                            <span><?= ui_icon('pin') ?><span><?= e($item['tempat']) ?></span></span>
                        </div>
                    </div>
                    <div class="row-actions" aria-label="Tindakan untuk <?= e($item['nama_kegiatan']) ?>">
                        <a class="action-link" href="<?= e(url('edit.php?id=' . (int) $item['id'])) ?>" aria-label="Edit kegiatan <?= e($item['nama_kegiatan']) ?>"><?= ui_icon('edit') ?><span>Edit</span></a>
                        <a class="action-link action-delete" href="<?= e(url('hapus.php?id=' . (int) $item['id'])) ?>" aria-label="Hapus kegiatan <?= e($item['nama_kegiatan']) ?>"><?= ui_icon('trash') ?><span>Hapus</span></a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>
</section>
