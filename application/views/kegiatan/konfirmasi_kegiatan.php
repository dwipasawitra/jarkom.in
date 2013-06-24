<p>Pada halaman ini, Anda dapat melihat konfirmasi kegiatan.</p>
<div class="success">
    <table>
        <tr>
            <td class="col_title"><strong>Kegiatan</strong></td>
            <td class="col_content"><?php echo $kegiatan['nama_kegiatan']; ?></td>
        </tr>
        <tr>
            <td><strong>ID Kegiatan</strong></td>
            <td><?php echo $kegiatan['id_kegiatan']; ?></td>
        </tr>
        <tr>
            <td><strong>Kelompok</strong></td>
            <td><?php echo $kegiatan['nama_grup']; ?></td>
        </tr>
    </table>
</div>
<table class="bordered-table">
    <tr>
        <td style="width: 50%;"><center><strong>Nama Kontak</strong></center></td>
        <td style="width: 50%;"><center><strong>No. Handphone</strong></center></td>
        <td style="width: 10%;"><center><strong>Konfirmasi</strong></center></td>
    </tr>
    <?php foreach($konfirmasi_kegiatan as $row) { ?>
        <tr>
            <td><center><?php echo $row['nama_kontak']; ?></center></td>
            <td><center><?php echo $row['no_handphone']; ?><center></td>
            <td><center>
                <?php
                    if($row['konfirmasi'] == 0) echo "TIDAK HADIR";
                        else echo "HADIR";
                ?></center>
            </td>
        </tr>
    <?php } ?>
</table>