<html>
<head>

    <!--    css-->
    <title><?= $title ?></title>

    <?php
    foreach ($css as $file) {
        echo "\n<link href='{$file}' rel='stylesheet' media='screen'>";
    }
    ?>

    <!--    meta-->
    <?php
    foreach ($meta as $name => $content) {
        echo "<meta name='{$name}'  content='{$content}'>";
    }
    ?>


</head>

<body>

<?= $output; ?>


<?php
foreach ($script as $inner => $code) {

    if (is_array($code)){
        foreach ($code as $key => $value){
            echo "\n<script {$key}> {$value}</script>";
        }
    }else{
        echo "\n<script {$inner}> {$code}</script>";
    }

}
?>

</body>
</html>
