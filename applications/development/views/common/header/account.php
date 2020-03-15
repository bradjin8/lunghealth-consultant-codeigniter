<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ($strCurrentPage != 'account/login')
{
?>
<div id="header-account-bar">
    <?php 
    if ($this->session->userdata('booUserLogin')) 
    {
        if ($this->user->isOwner())
        {
        ?>
            <a href="<?php echo site_url('properties/manage'); ?>">Properties</a>
        <?php
        }
        ?>
            <a href="<?php echo site_url('account/logout'); ?>">Log Out</a>
            
            
        <?php
    }
    else
    {
        ?><a href="<?php echo site_url('account/login'); ?>">Log In</a><?php
    }
    ?> 
</div>
<?php
}