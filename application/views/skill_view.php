<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Skills</title>
        <link href="<?php echo base_url(); ?>style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="content">
            <h1>Skills Table</h1>
            <?php echo anchor('skill/add/', 'add new data', array('class' => 'add')); ?>
            <p></p>
            <div class="data"><?php echo $skill_table; ?></div>
        </div>
    </body>
</html>
