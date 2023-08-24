<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $name ?? '張三'  ?></title>
</head>
<body>

<?php for ($i=0;$i<10;$i++ ) { ?>
    <?php if($i > 3){ ?>
        <h2><?=$i ?></h2>
    <?php }?>
<?php }?>

</body>
</html>
<body>
<?=dump($_SERVER); ?>
</body>
</html>