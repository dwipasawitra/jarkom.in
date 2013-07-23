Masukkan nomor yang bertanda merah seperti pada gambar ke dalam form di bawah Id Grup Facebook
<img src="<?php echo base_url('assets/img/fb.png') ?>"/>

<div>
    <table style="width: 100%;">
        
        Kelola Grup : 
        <a href="<?php echo site_url('kontak'); ?>">Kembali ke Kontak</a> 
         <?php foreach($data_grup as $grup) { ?>
            <tr style="border: 1px solid black;">
                <td style="width: 50%; padding: 2%;">
                    <strong><?php echo $grup['nama_grup']; ?></strong> <br/>
                    
                </td>
                <td style="width: 100%;padding: 2%;">
                    <form class="fb_form"> 
                        <input type="hidden" name="id_grup" value='<?php echo $grup['id_grup']; ?>'/>
                        ID Grup Facebook : <br/>
                        <input style="width:75%;" type="text" name="facebook_id_grup" class="txt_fb_id" value="<?php echo $grup['facebook_group_id'] ?>"/>
                    </form>
                </td>
            </tr>
        <?php } ?>
        
</table>
</div>
<script>
    $(document).ready(function () {
        $(".fb_form").submit(function() {
            $.post("<?php echo site_url('grup/sunting_facebook_grup'); ?>", $(this).serialize());
            return false; 
        });
        
        $(".txt_fb_id").change(function() {
            $(this).parent().trigger("submit");
        });
    });
</script>