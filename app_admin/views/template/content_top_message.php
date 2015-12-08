<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="col-md-12">
    <div class="alert alert-dismissable <?=$ctm_clsss?>">
        <button type="button" 
                class="close" 
                data-dismiss="alert" 
                aria-hidden="true">&times;</button>
        <h4><i class="icon fa <?=$ctm_icon?>"></i> <?=$ctm_title?>!</h4>
        <?=$ctm_msg?>
    </div>
</div>