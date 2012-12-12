<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Add a Person</title>
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
                            <td><input type="text" name="person_id" disabled="disable" 
                                       class="text" size="11"
                                       value="<?php echo set_value('person_id', $person_id); ?>"/></td>
                        </tr>
                        <tr>
                            <td valign="top">Active</td>
                            <td><?php echo $is_active; ?></td>
                        </tr>
                        <tr>
                            <td valign="top">First Name<span style="color:red;">*</span></td>
                            <td><input type="text" name="first_name"
                                       class="text" size="30" 
                                       value="<?php echo set_value('first_name', $first_name); ?>"/>
                        </tr>
                        <tr>
                            <td valign="top">Last Name<span style="color:red;">*</span></td>
                            <td><input type="text" name="last_name"
                                       class="text" size="30" 
                                       value="<?php echo set_value('last_name', $last_name); ?>"/>
                        </tr>
                        <tr>
                            <td valign="top">Middle Name</td>
                            <td><input type="text" name="middle_name"
                                       class="text" size="30" 
                                       value="<?php echo set_value('middle_name', $middle_name); ?>"/>
                        </tr>
                        <tr>
                            <td valign="top">Preferred Name</td>
                            <td><input type="text" name="preferred_name"
                                       class="text" size="30" 
                                       value="<?php echo set_value('preferred_name', $preferred_name); ?>"/>
                        </tr>
                        <tr>
                            <td valign="top">DOB</td>
                            <td><input type="text" name="date_of_birth"
                                       class="text" size="30" 
                                       value="<?php echo set_value('date_of_birth', $date_of_birth); ?>"/>
                                       <a href="javascript:void(0);" onclick="displayDatePicker('date_of_birth');">
                                       <img src="<?php echo base_url(); ?>images/calendar.png" alt="calendar" border="0" /></a>
                        </tr>
                        <tr>
                            <td valign="top">Gender</td>
                            <td><?php echo $gender; ?></td>
                        </tr>
                        <tr>
                            <td valign="top">Address 1</td>
                            <td><input type="text" name="address_1"
                                       class="text" size="50" 
                                       value="<?php echo set_value('address_1', $address_1); ?>"/>
                        </tr>
                        <tr>
                            <td valign="top">Address 2</td>
                            <td><input type="text" name="address_2"
                                       class="text" size="30" 
                                       value="<?php echo set_value('address_2', $address_2); ?>"/>
                        </tr>
                        <tr>
                            <td valign="top">City<span style="color:red;">*</span></td>
                            <td><input type="text" name="city"
                                       class="text" size="30" 
                                       value="<?php echo set_value('city', $city); ?>"/>
                        </tr>
                        <tr>
                            <td valign="top">City</td>
                            <td><input type="text" name="city"
                                       class="text" size="30" 
                                       value="<?php echo set_value('city', $city); ?>"/>
                        </tr>
                        <tr>
                            <td valign="top">State</td>
                            <td><?php echo $state; ?>
                        </tr>
                        <tr>
                            <td valign="top">ZIP Code</td>
                            <td><input type="text" name="postal_code"
                                       class="text" size="10" 
                                       value="<?php echo set_value('postal_code', $postal_code); ?>"/>
                        </tr>
                        <tr>
                            <td valign="top">Home Phone</td>
                            <td><input type="text" name="home_phone"
                                       class="text" size="10" 
                                       value="<?php echo set_value('home_phone', $home_phone); ?>"/>
                        </tr>
                        <tr>
                            <td valign="top">Mobile Phone</td>
                            <td><input type="text" name="mobile_phone"
                                       class="text" size="10" 
                                       value="<?php echo set_value('mobile_phone', $mobile_phone); ?>"/>
                        </tr>
                        <tr>
                            <td valign="top">SMS</td>
                            <td><?php echo $text_ok; ?></td>
                        </tr>
                        <tr>
                            <td valign="top">Mobile</td>
                            <td><?php echo $mobile_carrier_id; ?></td>
                        </tr>
                        <tr>
                            <td valign="top">Email Address</td>
                            <td><input type="text" name="email_address"
                                       class="text" size="50" 
                                       value="<?php echo set_value('email_address', $email_address); ?>"/>
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
