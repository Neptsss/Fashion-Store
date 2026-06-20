<h1>Hello</h1>
<?php foreach ($data['nama'] as $item) : ?>
    <?php var_dump($item['nama_lengkap']) ?> 

<?php endforeach; ?>
<p>Hello my name is <?= $data['nama']; ?> pekerjaan : <?= $data['pekerjaan']; ?></p>