<?php
    
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $encrypted_password = password_hash($password, PASSWORD_DEFAULT); 
    $status = 'active'; 


    function save_data_supabase($email, $passwd){
        $SUPABASE_URL = 'https://nodzqbagdhlnsngvvdor.supabase.co';
        $SUPABASE_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im5vZHpxYmFnZGhsbnNuZ3Z2ZG9yIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzMxODM1OTUsImV4cCI6MjA0ODc1OTU5NX0.Up58mTEnpjHzyL49DzNPxp01YIFy4qk80GdEflh1xfw'; 
        
        $url = "$SUPABASE_URL/rest/v1/users";
    
        $data = [
            'email' => $email,
            'password' => $passwd,
        ];
    
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $SUPABASE_KEY",
            "apikey: $SUPABASE_KEY"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
    
        curl_close($ch);
    
        if ($response === false) {
            echo "Error: unable to save data to Supabase";
            exit;
        } else {
            $responseData = json_decode($response, true);
            echo "User has been created: " . json_encode($responseData);
        }
    }
    
    


    require('../config/db_connection.php');

    $check_email_query = "SELECT * FROM client WHERE email = '$email'";
    $check_email_result = pg_query($conn, $check_email_query);

    if (pg_num_rows($check_email_result) > 0) {
        echo "<script>
                alert('El correo electrónico ya está registrado.');
                window.history.back();
              </script>";
    } else {
        $query = "INSERT INTO client (first_name, last_name, email, password, created_at, status) 
                  VALUES ('$first_name', '$last_name', '$email', '$encrypted_password', CURRENT_TIMESTAMP, '$status')";

        $result = pg_query($conn, $query);

        if ($result) {
            save_data_supabase($first_name, $last_name, $email, $encrypted_password);
            $base_url = "http://" . $_SERVER['HTTP_HOST'] . "/taller/";
            header("Location: $base_url");
            echo "Usuario creado exitosamente.";
            exit;
        } else {
            echo "Error: " . pg_last_error($conn);
        }
    }

    pg_close($conn);
?>
