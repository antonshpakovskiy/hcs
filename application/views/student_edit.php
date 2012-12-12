<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Add a Student</title>
        <link href="<?php echo base_url(); ?>style.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>calendar.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>calendar.js"></script>
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
                            <td><input type="text" name="student_id" disabled="disable" 
                                       class="text" size="11"
                                       value="<?php echo set_value('student_id', $student_id); ?>"/></td>
                        </tr>
                        <tr>
                            <td valign="top">Person ID<span style="color:red;">*</span></td>
                            <td><?php echo $person_id; ?></td>
                        </tr>
                        <tr>
                            <td valign="top">Room ID</td>
                            <td><?php echo $room_id; ?></td>
                        </tr>
                        <tr>
                            <td valign="top">Teacher ID</td>
                            <td><?php echo $teacher_id; ?></td>
                        </tr>
                        <tr>
                            <td valign="top">Enroll Start Date</td>
                            <td><input type="text" name="enroll_start_date"
                                       class="text" size="15" 
                                       value="<?php echo set_value('enroll_start_date', $enroll_start_date); ?>"/>
                                       <a href="javascript:void(0);" onclick="displayDatePicker('enroll_start_date');">
                                       <img src="<?php echo base_url(); ?>images/calendar.png" alt="calendar" border="0" /></a>
                        </tr>
                        <tr>
                            <td valign="top">Enroll End Date</td>
                            <td><input type="text" name="enroll_end_date"
                                       class="text" size="15" 
                                       value="<?php echo set_value('enroll_end_date', $enroll_end_date); ?>"/>
                                       <a href="javascript:void(0);" onclick="displayDatePicker('enroll_end_date');">
                                       <img src="<?php echo base_url(); ?>images/calendar.png" alt="calendar" border="0" /></a>
                        </tr>
                        <tr>
                            <td valign="top">Time stamp</td>
                            <td><input type="text" name="update_dt_tm" disabled="disable" 
                                       class="text" size="15"
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
