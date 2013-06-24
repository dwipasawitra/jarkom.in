<div id="tambah-pengingat" class="modal-dialog">
    <h1>Tambah Pengingat</h1>
    <div class="error"></div>
    <form>
    <table>
        <tr>
            <td class="col_title" style="vertical-align: text-top;"><strong>Waktu Pengingat</strong></td>
            <td class="col_content"><input type="text" name="menit_sebelumnya" size="5"> <br/>menit sebelum acara</td>
        </tr>
        <tr>
            <td><strong>Pesan Pengingat</strong></td>
            <td><input type="text" name="pesan" size="25" id="konten"></td>
        </tr>
        <tr>
            <td  style="vertical-align:top" ><strong>Jumlah Karakter</strong></td>
            <td><input id="jumlah-karakter" type="text" readonly="readonly" size="4" value="0"/></td>
        </tr>
        <td  style="vertical-align:top" ><strong>Kredit yang dibutuhkan</strong></td>
            <td><input id="kredit-dibutuhkan" type="text" readonly="readonly" size="4" value="0"/>
                <span id="kredit-dibutuhkan-msg"><br/>Kredit Anda tidak mencukupi!</span>
            </td>
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
<span class="link-action" onclick="tambah_pengingat_kegiatan()">Tambah Pengingat</span>
<table>
    <?php foreach($pengingat_kegiatan as $row) { ?>
    <tr>
        <td class="col_title"><strong>Waktu Pengingat</strong></td>
        <td class="col_content"><?php echo $row['menit_sebelumnya']; ?> menit sebelum acara</td>
    </tr>
    
    <tr>
        <td><strong>Pesan Pengingat</strong></td>
        <td><?php echo $row['pesan']; ?></td>
    </tr>
    <tr colspan="2">
        <td>&nbsp;</td>
        
    </tr>
    <?php } ?>
</table>
<script>
    
    
    function tambah_pengingat_kegiatan()
    {
        $("#tambah-pengingat").modal({overlayClose: true});
        $("#tambah-pengingat .error").hide();
    }
    
    $(document).ready(function() {
        var jumlah_karakter = 0, kredit_dibutuhkan, kredit_saat_ini;
        
        $("#tambah-pengingat").hide();
        $("#kredit-dibutuhkan-msg").hide();
        $("#tambah-pengingat form").submit(function(){
            if(kredit_dibutuhkan <= kredit_saat_ini && jumlah_karakter <= 160) {
                $.post("<?php echo site_url('kegiatan/tambah_pengingat'); ?>", $(this).serialize(), function(data){
                   if(data.hasil === true) {
                       window.location = "<?php echo site_url('/kegiatan/kelola_pengingat'); ?>?id_kegiatan=<?php echo $id_kegiatan; ?>";
                   } else {
                       $("#tambah-pengingat .error").show();
                       $("#tambah-pengingat .error").html(data.pesan);
                   }
               }, "json");
           } else {
               $("#tambah-pengingat .error").html("Pengingat tidak dapat dibuat, jumlah karakter pesan melebihi ketentuan atau kredit tidak mencukupi");
               $("#tambah-pengingat .error").fadeIn(500);
           }
           return false;
       });
       $("#konten").keyup(function() {
           jumlah_karakter = $(this).val().length;
           $("#jumlah-karakter").val(jumlah_karakter);
           if(jumlah_karakter > 160) {
               $("#jumlah-karakter").css("background-color", "red");
           } else {
               $("#jumlah-karakter").css("background-color", "white");
           }
           
           // Lakukan pengecekan jumlah kontak yang terlibat
           var masukan = { 
               kontak: "<?php echo $kegiatan['nama_grup']; ?>",
               panjang_sms: jumlah_karakter
           };
           
           $.post("<?php echo site_url('sms/jumlah_kredit_dibutuhkan'); ?>", $.param(masukan), function(data) {
               kredit_dibutuhkan = data.kredit_dibutuhkan;
               kredit_saat_ini = data.kredit_saat_ini;
               $("#kredit-dibutuhkan").val(kredit_dibutuhkan);
               if(kredit_dibutuhkan > kredit_saat_ini) {
                   $("#kredit-dibutuhkan").css("background-color", "red");
                   $("#kredit-dibutuhkan-msg").fadeIn(500);
               } else {
                   $("#kredit-dibutuhkan").css("background-color", "white");
                   $("#kredit-dibutuhkan-msg").fadeOut(500);
               }
           }, "json");
       });
    });
</script>