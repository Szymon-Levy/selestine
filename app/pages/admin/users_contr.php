<?php

//add user
if ($action == 'add') {
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    //get signup form values
    $user_name = trim($_POST['username']);
    $first_name = trim($_POST['firstname']) ?? NULL;
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['retype-password']);
    $about = trim($_POST['about']) ?? NULL;
    $instagram = $_POST['instagram'] ? trim($_POST['instagram']) : NULL;
    $facebook = $_POST['facebook'] ? trim($_POST['facebook']) : NULL;
    $twitter = $_POST['twitter'] ? trim($_POST['twitter']) : NULL;
    $type = !empty($_POST['type']) ? 'admin' : 'user';
    $avatar = $_FILES['avatar'] ?? null;
  
    //validate
    $errors = [];

    //validate avatar
    $current_avatar = null;
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (!empty($avatar['name'])) {
      if (!in_array($avatar['type'], $allowed_types)) {
        $errors['avatar'] = 'JPG, PNG and WEBP only allowed!';
      } 
      else if ($avatar['size'] > 300000) {
        $errors['avatar'] = 'Maximum filesize is 300kb!';
      }
      else {
        $uploaded_image_path = 'users/avatars/' . time() . basename($avatar['name']);
        $current_avatar = $uploaded_image_path;
      }
    }
  
    if (strlen($first_name) > 50) {
      $errors['first_name'] = 'First name cannot be longer than 50 characters!';
    }

    if (empty($user_name)) {
      $errors['user_name'] = 'User name cannot be empty!';
    }
    else if (str_contains($user_name, ' ')) {
      $errors['user_name'] = 'No spaces in user name!';
    }
    else if (strlen($user_name) < 6 || strlen($user_name) > 35) {
      $errors['user_name'] = 'Username cannot be shorter than 6 characters and longer than 35 characters!';
    }
  
    $email_query = 'SELECT id FROM users WHERE email = :email limit 1;';
    $is_email_in_db = db_query($pdo, $email_query, ['email' => $email])->fetch();
  
    if (empty($email)) {
      $errors['email'] = 'Email cannot be empty!';
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Wrong email format!';
    }
    else if ($is_email_in_db) {
      $errors['email'] = 'This email is already taken!';
    }
  
    if (empty($password)) {
      $errors['password'] = 'Password cannot be empty!';
    }
    else if (strlen($password) < 8) {
      $errors['password'] = 'Password cannot be shorter than 8 characters!';
    }
    else if ($password !== $password2) {
      $errors['password2'] = 'Passwords do not match!';
    }

    if (strlen($about) > 500) {
      $errors['about'] = 'About text cannot be longer than 500 characters!';
    }

    if (!empty($instagram) && !preg_match('/^[a-zA-Z0-9\-\_]+$/', $instagram)) {
      $errors['instagram'] = 'Not allowed characters used!';
    }

    if (!empty($facebook) && !preg_match('/^[a-zA-Z0-9\-\_]+$/', $facebook)) {
      $errors['facebook'] = 'Not allowed characters used!';
    }

    if (!empty($twitter) && !preg_match('/^[a-zA-Z0-9\-\_]+$/', $twitter)) {
      $errors['twitter'] = 'Not allowed characters used!';
    }
  
    if(empty($errors)) {
      
      //save new user to database
      $arguments = [];
      $arguments['first_name']   = $first_name;
      $arguments['user_name']    = $user_name;
      $arguments['email']        = $email;
      $arguments['pass']         = hash_password($password);
      $arguments['about']        = $about;
      $arguments['instagram']    = $instagram;
      $arguments['facebook']     = $facebook;
      $arguments['twitter']      = $twitter;
      $arguments['account_type'] = $type;
      
      if ($current_avatar) {
        //upload avatar
        move_uploaded_file($avatar['tmp_name'], FILESYSTEM_PATH . '/assets/images/' . $uploaded_image_path);
        
        $arguments['avatar'] = $current_avatar;
        $query = 'INSERT INTO users (avatar, first_name, user_name, email, pass, about, instagram, facebook, twitter, account_type) 
                  VALUES (:avatar, :first_name, :user_name, :email, :pass, :about, :instagram, :facebook, :twitter, :account_type);';
      }
      else {
        $query = 'INSERT INTO users (user_name, first_name, email, pass, about, instagram, facebook, twitter, account_type) 
                  VALUES (:user_name, :first_name, :email, :pass, :about, :instagram, :facebook, :twitter, :account_type);';
      }
      db_query($pdo, $query, $arguments);
      
      $_SESSION['MESSAGE_SUCCESS'] = 'New user has been successfully added.';
      redirect('admin/users');
    }
  }
}
//edit user
else if ($action == 'edit') {
  $user_query = 'SELECT * FROM users WHERE id = :id LIMIT 1';
  $user = db_query($pdo, $user_query, ['id' => $id])->fetch();

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
      //get signup form values
      $first_name = trim($_POST['firstname']) ?? NULL;
      $user_name = trim($_POST['username']);
      $email = trim($_POST['email']);
      $password = trim($_POST['password']);
      $password2 = trim($_POST['retype-password']);
      $about = trim($_POST['about']) ?? NULL;
      $instagram = $_POST['instagram'] ? trim($_POST['instagram']) : NULL;
      $facebook = $_POST['facebook'] ? trim($_POST['facebook']) : NULL;
      $twitter = $_POST['twitter'] ? trim($_POST['twitter']) : NULL;
      $type = !empty($_POST['type']) ? 'admin' : 'user';
      $avatar = $_FILES['avatar'] ?? null;
    
      //validate
      $errors = [];

      //validate avatar
      $current_avatar = $user['avatar'];
      $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
      if (!empty($avatar['name'])) {
        if (!in_array($avatar['type'], $allowed_types)) {
          $errors['avatar'] = 'JPG, PNG and WEBP only allowed!';
        } else if ($avatar['size'] > 300000) {
          $errors['avatar'] = 'Maximum filesize is 300kb!';
        } else {
          $uploaded_image_path = 'users/avatars/' . time() . basename($avatar['name']);
          $current_avatar = $uploaded_image_path;
        }
      }

      if (strlen($first_name) > 50) {
        $errors['first_name'] = 'First name cannot be longer than 50 characters!';
      }
    
      if (empty($user_name)) {
        $errors['user_name'] = 'User name cannot be empty!';
      }
      else if (str_contains($user_name, ' ')) {
        $errors['user_name'] = 'No spaces in user name!';
      }
      else if (strlen($user_name) < 6 || strlen($user_name) > 35) {
        $errors['user_name'] = 'Username cannot be shorter than 6 characters and longer than 35 characters!';
      }
    
      $email_query = 'SELECT id FROM users WHERE email = :email AND id != :id limit 1;';
      $is_email_in_db = db_query($pdo, $email_query, ['email' => $email, 'id' => $id])->fetch();
    
      if (empty($email)) {
        $errors['email'] = 'Email cannot be empty!';
      }
      else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Wrong email format!';
      }
      else if ($is_email_in_db) {
        $errors['email'] = 'This email is already taken!';
      }
    
      if (!empty($password) && strlen($password) < 8) {
        $errors['password'] = 'Password cannot be shorter than 8 characters!';
      }
      else if ($password !== $password2) {
        $errors['password2'] = 'Passwords do not match!';
      }

      if (strlen($about) > 500) {
        $errors['about'] = 'About text cannot be longer than 500 characters!';
      }

      if (!empty($instagram) && !preg_match('/^[a-zA-Z0-9\-\_]+$/', $instagram)) {
        $errors['instagram'] = 'Not allowed characters used!';
      }
  
      if (!empty($facebook) && !preg_match('/^[a-zA-Z0-9\-\_]+$/', $facebook)) {
        $errors['facebook'] = 'Not allowed characters used!';
      }
  
      if (!empty($twitter) && !preg_match('/^[a-zA-Z0-9\-\_]+$/', $twitter)) {
        $errors['twitter'] = 'Not allowed characters used!';
      }
    
      if(empty($errors)) {
        //upload new avatar and delete the old one
        if ($current_avatar != 'users/avatars/default-profile-picture.jpg') {
          move_uploaded_file($avatar['tmp_name'], FILESYSTEM_PATH . '/assets/images/' . $uploaded_image_path);
          if ($user['avatar'] != 'users/avatars/default-profile-picture.jpg' && $user['avatar'] != $current_avatar) {
            delete_image($user['avatar']);
          }
        }

        //edit user in database
        $arguments                 = [];
        $arguments['id']           = $id;
        $arguments['first_name']   = $first_name;
        $arguments['user_name']    = $user_name;
        $arguments['email']        = $email;
        $arguments['about']        = $about;
        $arguments['instagram']    = $instagram;
        $arguments['facebook']     = $facebook;
        $arguments['twitter']      = $twitter;
        $arguments['account_type'] = $type;
        $arguments['avatar']       = $current_avatar;
        
        if (empty($password)) {
          $query = 'UPDATE users 
                    SET first_name = :first_name, user_name = :user_name, email = :email, about = :about, instagram = :instagram, facebook = :facebook, twitter = :twitter, account_type = :account_type, avatar = :avatar 
                    WHERE id = :id;';
        }
        else {
          $arguments['pass'] = hash_password($password);
          $query = 'UPDATE users 
                    SET first_name = :first_name, user_name = :user_name, email = :email, about = :about, instagram = :instagram, facebook = :facebook, twitter = :twitter, pass = :pass, account_type = :account_type, avatar = :avatar 
                    WHERE id = :id;';
        }
        
        db_query($pdo, $query, $arguments);
        
        $_SESSION['MESSAGE_SUCCESS'] = 'User data has been successfully edited.';
        redirect('admin/users');
      }
    }
  }
}
//delete user
else if ($action == 'delete') {
  $user_query = 'SELECT * FROM users WHERE id = :id LIMIT 1';
  $user = db_query($pdo, $user_query, ['id' => $id])->fetch();

  if ($id == ADMIN_ID) {
    $_SESSION['MESSAGE_ERROR'] = 'Admin cannot be deleted.';
    redirect('admin/users');
    die();
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
      //delete avatar of user
      if ($user['avatar'] != 'users/avatars/default-profile-picture.jpg') {
        delete_image($user['avatar']);
      }

      //delete user from database
      $arguments['id'] = $id;
      
      $delete_query = 'DELETE FROM users WHERE id = :id;';
      
      db_query($pdo, $delete_query, $arguments);
      
      $_SESSION['MESSAGE_SUCCESS'] = 'User has been successfully deleted.';
      redirect('admin/users');

    }
  }
}