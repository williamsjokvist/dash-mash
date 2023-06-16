<?php

include "../commons/connect.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Check recaptcha click
    if (!empty(filter_input(INPUT_POST, 'g-recaptcha-response'))) {


        /* ////////////////////////// VERIFY CAPTCHA RESULT (cURL) ///////////////////// */
        $captcha_res = filter_input(INPUT_POST, 'g-recaptcha-response');

        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $data = array(
            'secret' => urlencode('6Lc09DoUAAAAANfOUfGsBcap9tvbvj0DNDtOdI1Z'),
            'response' => urlencode($captcha_res)
        );

        //open connection
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($curl), true);


        //execute post
        $result = curl_exec($curl);

        //close connection
        curl_close($curl);

        var_dump($response);

        if ($response['success'] == true) {

            //The password is confirmed
            if (filter_input(INPUT_POST, 'pass') == filter_input(INPUT_POST, 'pass_confirm')) {

                // escape variables for security (protects against sql injections)
                $new_user_name = $dbconnect->escape_string(filter_input(INPUT_POST, 'username'));
                $new_user_email = $dbconnect->escape_string(filter_input(INPUT_POST, 'email'));
                $new_user_pass = $dbconnect->escape_string(sha1(filter_input(INPUT_POST, 'pass')));


                /* ///////////////////// VERIFY IF USER ALREADY EXISTS ///////////////// */
                $verify_new_user = $dbconnect->prepare("SELECT * from `user-accounts` WHERE user_email = ? OR user_name = ?");
                $verify_new_user->bind_param('ss', $new_user_email, $new_user_name);
                $verify_new_user->execute();
                $result_user_verification = $verify_new_user->get_result();


                if (mysqli_num_rows($result_user_verification) > 0) {
                    $_SESSION['acc_msg'] = "User with this e-mail or username already exist";
                    echo $_SESSION['acc_msg'];
                } else { /* Email or username doesn't already exist in db, add user */

                    $sql = "INSERT INTO `user-accounts` (user_name, user_pass, user_email) VALUES (?, ?, ?) ";
                    $stmt_add_user = $dbconnect->prepare($sql) or die($dbconnect->error);
                    $stmt_add_user->bind_param('sss', $new_user_name, $new_user_pass, $new_user_email);
                    $stmt_add_user->execute();

                    /* Add a profile */
                    $stmt_fetch_userid = $dbconnect->prepare("SELECT user_id FROM `user-accounts` WHERE user_name = ?") or die($dbconnect->error);
                    $stmt_fetch_userid->bind_param('s', $new_user_name);
                    $stmt_fetch_userid->execute();
                    $res_fetch_userid = $stmt_fetch_userid->get_result();
                    if (mysqli_num_rows($res_fetch_userid) > 0) {
                        while ($row_userid = $res_fetch_userid->fetch_assoc()) {
                            $new_user_id = $row_userid['user_id'];
                            echo $new_user_id;
                        }
                    }

                    $stmt_add_profile = $dbconnect->prepare("INSERT INTO `user-profile` (profile_user_id) VALUES (?) ");
                    $stmt_add_profile->bind_param('i', $new_user_id);
                    $stmt_add_profile->execute();
                    $stmt_add_profile->close();

                    $_SESSION['acc_msg'] = " Your account has been registered; you can now log in!";
                    
                    $stmt_add_user->close();
                }

                $verify_new_user->close();
                $dbconnect->close();
            } else {
                $_SESSION['acc_msg'] = "The passwords do not match";
                echo $_SESSION['acc_msg'];
            }
        } else {
            $_SESSION['acc_msg'] = "You have been proven to not be human by the Google Corporation.";
        }
    } else {
        $_SESSION['acc_msg'] = "You must prove your humanity";
    }
}

header("Location: https://dashmash.ddns.net/index.php");
