<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<section class="content-header">
    <h1>
        <?=$ash_title?>
        <small><?=$ash_discription?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <?php foreach ($ash_lists as $item){ ?>
        <li><?=$item?></li>
        <?php } ?>
    </ol>
</section>