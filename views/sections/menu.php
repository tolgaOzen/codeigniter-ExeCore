<ul>


    <?php
    foreach ($menu as $item) {

        $activeColor = $item['active'] !== "" ? "style='color:{$item["color"]}'" : "";

        echo "<li class=\"{$item['active']}\"><a href='/{$item["link"]}' aria-expanded=\"true\"><i $activeColor class=\"{$item['icon']} m-auto\"></i><span $activeColor>{$item['displayName']}</span></a></li>";

    }

    ?>

</ul>



