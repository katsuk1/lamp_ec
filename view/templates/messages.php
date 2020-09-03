<?php foreach(get_errors() as $error){ ?>
    <p class="alert text-danger" style="text-shadow:1px 1px 0 #FFF, -1px -1px 0 #FFF,
              -1px 1px 0 #FFF, 1px -1px 0 #FFF,
              0px 1px 0 #FFF,  0-1px 0 #FFF,
              -1px 0 0 #FFF, 1px 0 0 #FFF;">
        <span><?php print h($error); ?></span>
    </p>
<?php } ?>
<?php foreach(get_messages() as $message){ ?>
    <p class="alert text-success" 
       style="text-shadow:1px 1px 0 #FFF, -1px -1px 0 #FFF,
              -1px 1px 0 #FFF, 1px -1px 0 #FFF,
              0px 1px 0 #FFF,  0-1px 0 #FFF,
              -1px 0 0 #FFF, 1px 0 0 #FFF;">
        <span><?php print h($message); ?></span>
    </p>
<?php } ?>