<?php
require_once('database.php');
$db= $conn;
$contact_us=$nameErr=$emailErr=$subjectErr=$msgErr='';
// set empty input value into the contact field
$set_name=$set_email=$set_subject=$set_msg='';


extract($_POST);
if(isset($contact))
{
   
   //regular expression
   $validName="/^[a-zA-Z ]*$/"; // full Name
   $validEmail="/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/"; // Email
   
 
 //Full Name Validation
if(empty($full_name)){
  $nameErr="Full Name is Required"; 
}
else if (!preg_match($validName,$full_name)) {
  $nameErr="Only Characters and white spaces are allowed";
}
else{
  $nameErr=true;
}
//Email Address Validation
if(empty($email)){
  $emailErr="Email is Required"; 
}
else if (!preg_match($validEmail,$email)) {
  $emailErr="Invalid Email Address";
}
else{
  $emailErr=true;
}

//Subject Name Validation
if(empty($subject)){
  $subjectErr="Subject is Required"; 
}else{
  $subjectErr=true;
}
    
//message Validation
if(empty($msg)){
  $msgErr="Message is Required"; 
}else{
  $msgErr=true;
}



// check all fields are valid or not
if( $nameErr==1 && $emailErr==1 && $subjectErr==1 && $msgErr==1)
{
 
   //legal input values
   $fullName=  legal_input($full_name);
   $emailAddress=  legal_input($email);
   $subject=  legal_input($contact);
   $message=  legal_input($msg);
   
   // call fucntion to store contact message
   store_message($fullName,$emailAddress,$subject,$message)
   // function whic is contained sending mail script
   $contact_us=send_mail($fullName,$emailAddress,$subject,$message);
}
else{
    // set user input value into the contact field

  $set_name    = $full_name;
  $set_email   = $email;
  $set_subject = $subject;
  $set_msg     = $msg;
}
}
// convert illegal input value to ligal value formate
function legal_input($value) {
  $value = trim($value);
  $value = stripslashes($value);
  $value = htmlspecialchars($value);
  return $value;
}
// function to send mail to the website owner
function send_mail($fullName,$emailAddress,$subject,$message){
   
  
            $to = 'codingstatus@gmail.com'; // Enter Website Owner Email
            $subject = 'Contact Message was sent by'.$fullName;
            $message = '<h2>Contact Message Details</h2>
                      <h3>Full Name</h3>
                      <p>'.$fullName.'</p>
                      <h3>Email Address</h3>
                      <p>'.$emailAddress.'</p>
                      <h3>Subject</h3>
                      <p>'.$subject.'</p>
                      <h3>Message</h3>
                      <p>'.$message.'</p>';
            
            // Set content-type header for sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: '.$emailAddress.'('.$fullName.')'. "\r\n";
            
            // Send email
            if(mail($to,$subject,$message,$headers)){
                 return 'Your Message was sent Successfully';
               
            }else{
                return 'Message is unable to send, please try again.';
                
            }
}


// function to insert user data into database table
function store_message($fullName,$emailAddress,$subject,$message){

   global $db;
   $sql="INSERT INTO contact (full_name,email,subject,message) VALUES(?,?,?,?)";
   $query=$db->prepare($sql);
   $query->bind_param('ssss',$fullName,$email,$subject, $message);
   $exec= $query->execute();
    if($exec==true)
    {
     return "You are registered successfully";
    }
    else
    {
      return "Error: " . $sql . "<br>" .$db->error;
    }
}

?>