<div id="tambah-pengingat" class="modal-dialog">
    <h1>Tambah Pengingat</h1>
    <div class="error"></div>
    <form>
    <table>
        <tr>
            <td><strong>Waktu Pengingat</strong></td>
            <td><input type="text" name="menit_sebelumnya"> menit sebelumnya</td>
        </tr>
        <tr>
            <td><strong>Pesan Pengingat</strong></td>
            <td><input type="text" name="pesan"></td>
        </tr>
    </table>
    <input type="hidden" name="id_kegiatan" value="<?php echo $id_kegiatan; ?>"/>
    <input type="submit" value="Tambah Pengingat" />
    </form>
</div>
<p>Pada halaman ini, Anda dapat membuat pengingat kegiatan baru pada kegiatan Anda</p>
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
<span class="link-action" onclick="tambah_pengingat_kegiatan()">Tambah Pengingat</span>
<table>
    <tr>
        <td>Waktu Pengingat</td>
        <td>Pesan Pengingat</td>
    </tr>
    <?php foreach($pengingat_kegiatan as $row) { ?>
        <tr>
            <td><?php echo $row['menit_sebelumnya']; ?></td>
            <td><?php echo $row['pesan']; ?></td>
        </tr>
    <?php } ?>
</table>
<script>
    function tambah_pengingat_kegiatan()
    {
        $("#tambah-pengingat").modal();
        $("#tambah-pengingat .error").hide();
    }
    
    $(document).ready(function() {
        $("#tambah-pengingat").hide();
        $("#tambah-pengingat form").submit(function(){
           $.post("<?php echo site_url('kegiatan/tambah_pengingat'); ?>", $(this).serialize(), function(data){
               if(data.hasil === true) {
                   window.location = "/kegiatan/pengingat_kegiatan?id_kegiatan=<?php echo $id_kegiatan; ?>";
               } else {
                   $("#tambah-pengingat .error").show();
                   $("#tambah-pengingat .error").html(data.pesan);
               }
           }, "json");
           return false;
       });
    });
</script>