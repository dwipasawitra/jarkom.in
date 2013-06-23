<p>Pada halaman ini, Anda dapat melihat konfirmasi kegiatan.</p>
<div class="success">
    <table>
        <tr>
            <td><strong>Nama Kegiatan</strong></td>
            <td><?php echo $kegiatan['nama_kegiatan']; ?></td>
        </tr>
        <tr>
            <td><strong>ID Kegiatan</strong></td>
            <td><?php echo $kegiatan['id_kegiatan']; ?></td>
        </tr>
    </table>
</div>
<table>
    <tr>
        <td>Nama Kontak</td>
        <td>No. Handphone</td>
        <td>Konfirmasi Kegiatan</td>
    </tr>
    <?php foreach($konfirmasi_kegiatan as $row) { ?>
        <tr>
            <td><?php echo $row['nama_kontak']; ?></td>
            <td><?php echo $row['no_handphone']; ?></td>
            <td>
                <?php
                    if($row['konfirmasi'] == 0) echo "TIDAK HADIR";
                        else echo "HADIR";
                ?>
            </td>
        </tr>
    <?php } ?>
</table>