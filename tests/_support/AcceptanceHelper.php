<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class AcceptanceHelper extends \Codeception\Module
{
    public function _failed(TestCase $test, $fail)
    {
        $file = fopen("testerEmail.txt", "r");
        $email = $line = fgets($file);
        fclose($file);
        $message = json_decode($fail->getMessage());
        $sent = mail($email, 'Test Failed', $message->errorMessage);
        if ($sent){
            echo "Email was sent to ". $email;
        }
    }
}