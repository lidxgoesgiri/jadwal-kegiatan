<?php
$isEdit = $mode === 'edit';
$firstInvalid = null;
foreach (['nama_kegiatan', 'tanggal', 'waktu', 'tempat'] as $field) {
    if (isset($errors[$field])) {
        $firstInvalid = $field;
        break;
    }
}
?>
<a class="back-link" href="<?= e(url()) ?>"><?= ui_icon('back') ?>Kembali ke daftar</a>
<section class="form-layout" aria-labelledby="page-title">
    <div class="form-introduction">
        <p class="eyebrow"><span class="eyebrow-line"></span><?= $isEdit ? 'PERBARUI RENCANA' : 'KEGIATAN BARU' ?></p>
        <h1 id="page-title"><?= $isEdit ? 'Edit kegiatan' : 'Tambah kegiatan' ?><span class="title-dot">.</span></h1>
        <p class="page-description"><?= $isEdit ? 'Sesuaikan detail kegiatan agar rencana tetap jelas dan teratur.' : 'Mulai dari nama, lalu tentukan waktu dan tempatnya.' ?></p>
        <p class="form-side-note"><span aria-hidden="true">01 — 04</span>Empat detail untuk satu rencana.</p>
    </div>
    <div class="form-surface">
        <div class="form-surface-heading">
            <h2>Detail kegiatan</h2>
            <p>Semua kolom wajib diisi.</p>
        </div>
        <?php if ($errors !== []): ?>
            <div class="notice notice-error form-error-summary" role="alert">
                <?= ui_icon('alert') ?>
                <p><?= e($errors['_form'] ?? 'Periksa kembali isian yang ditandai di bawah ini.') ?></p>
            </div>
        <?php endif; ?>
        <form action="<?= e(url($isEdit ? 'edit.php?id=' . $id : 'tambah.php')) ?>" method="post">
            <?= csrf_field() ?>
            <div class="field">
                <label for="nama_kegiatan">Nama kegiatan</label>
                <input id="nama_kegiatan" name="nama_kegiatan" type="text" required
                    value="<?= e($values['nama_kegiatan']) ?>" placeholder="Misalnya, diskusi kelompok"
                    aria-describedby="nama-hint<?= isset($errors['nama_kegiatan']) ? ' nama-error' : '' ?>"
                    <?= isset($errors['nama_kegiatan']) ? 'aria-invalid="true"' : '' ?>
                    <?= $firstInvalid === 'nama_kegiatan' ? 'autofocus' : '' ?>>
                <p class="field-hint" id="nama-hint">Nama yang mudah dikenali, maksimal 150 karakter.</p>
                <?php if (isset($errors['nama_kegiatan'])): ?><p class="field-error" id="nama-error"><?= e($errors['nama_kegiatan']) ?></p><?php endif; ?>
            </div>
            <div class="field-pair">
                <div class="field">
                    <label for="tanggal">Tanggal</label>
                    <input id="tanggal" name="tanggal" type="date" required value="<?= e($values['tanggal']) ?>"
                        aria-describedby="tanggal-hint<?= isset($errors['tanggal']) ? ' tanggal-error' : '' ?>"
                        <?= isset($errors['tanggal']) ? 'aria-invalid="true"' : '' ?>
                        <?= $firstInvalid === 'tanggal' ? 'autofocus' : '' ?>>
                    <p class="field-hint" id="tanggal-hint">Pilih tanggal kegiatan.</p>
                    <?php if (isset($errors['tanggal'])): ?><p class="field-error" id="tanggal-error"><?= e($errors['tanggal']) ?></p><?php endif; ?>
                </div>
                <div class="field">
                    <label for="waktu">Waktu</label>
                    <input id="waktu" name="waktu" type="time" step="60" required value="<?= e($values['waktu']) ?>"
                        aria-describedby="waktu-hint<?= isset($errors['waktu']) ? ' waktu-error' : '' ?>"
                        <?= isset($errors['waktu']) ? 'aria-invalid="true"' : '' ?>
                        <?= $firstInvalid === 'waktu' ? 'autofocus' : '' ?>>
                    <p class="field-hint" id="waktu-hint">Gunakan waktu WIB.</p>
                    <?php if (isset($errors['waktu'])): ?><p class="field-error" id="waktu-error"><?= e($errors['waktu']) ?></p><?php endif; ?>
                </div>
            </div>
            <div class="field">
                <label for="tempat">Tempat</label>
                <input id="tempat" name="tempat" type="text" required value="<?= e($values['tempat']) ?>"
                    placeholder="Misalnya, ruang diskusi lantai 2"
                    aria-describedby="tempat-hint<?= isset($errors['tempat']) ? ' tempat-error' : '' ?>"
                    <?= isset($errors['tempat']) ? 'aria-invalid="true"' : '' ?>
                    <?= $firstInvalid === 'tempat' ? 'autofocus' : '' ?>>
                <p class="field-hint" id="tempat-hint">Lokasi kegiatan, maksimal 150 karakter.</p>
                <?php if (isset($errors['tempat'])): ?><p class="field-error" id="tempat-error"><?= e($errors['tempat']) ?></p><?php endif; ?>
            </div>
            <div class="form-actions">
                <a class="button button-secondary" href="<?= e(url()) ?>">Batal</a>
                <button class="button button-primary" type="submit"><?= $isEdit ? 'Simpan perubahan' : 'Simpan kegiatan' ?><?= ui_icon('arrow') ?></button>
            </div>
        </form>
    </div>
</section>
