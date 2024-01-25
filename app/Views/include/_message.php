<?php if ($session->getFlashdata('error')) {?>
<div class='alert alert-danger'><?=$session->getFlashdata('error')?></div>
<?php } else if ($session->getFlashdata('success')) {?>
<div class='alert alert-success'><?=$session->getFlashdata('success')?></div>
<?php } else {?>
<?php }?>