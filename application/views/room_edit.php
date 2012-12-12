<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Add a Room</title>
        <link href="<?php echo base_url(); ?>style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="content">
            <h1><?php echo $title; ?></h1>
            <?php echo $message; ?>
            <form method="post" action="<?php echo $action; ?>">
                <div class="data">
                    <table>
                        <tr>
                            <td width="30%">ID</td>
                            <td><input type="text" name="room_id" disabled="disable" 
                                       class="text" size="11"
                                       value="<?php echo set_value('room_id', $room_id); ?>"/></td>
                        </tr>
                        <tr>
                            <td valign="top">Facility<span style="color:red;">*</span></td>
                            <td><?php echo $facility_id; ?></td>
                        </tr>
                        <tr>
                            <td valign="top">Room Number<span style="color:red;">*</span></td>
                            <td><input type="text" name="room_number_txt"
                                       class="text" size="10" 
                                       value="<?php echo set_value('room_number_txt', $room_number_txt); ?>"/>
                        </tr>
                        <tr>
                            <td valign="top">Room Description</td>
                            <td><input type="text" name="description"
                                       class="text" size="40" 
                                       value="<?php echo set_value('description', $description); ?>"/>
                        </tr>
                        <tr>
                            <td valign="top">Time stamp</td>
                            <td><input type="text" name="update_dt_tm" disabled="disable" 
                                       class="text" size="30"
                                       value="<?php echo set_value('update_dt_tm', $update_dt_tm); ?>" />
                        </tr>
                        <tr>
                            <td valign="top">Updated by</td>
                            <td><input type="text" name="update_user_id" disabled="disable" 
                                       class="text" size="30"
                                       value="<?php echo set_value('update_user_id', $update_user_id) ?>" />
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type="submit" value="Save"/></td>
                        </tr>
                    </table>
                </div>
            </form>
            <br />
            <?php echo $link_back; ?>
        </div>
    </body>
</html>
