<?php 
namespace App\Services;
use App\Services\Interfaces\ILoginService;
use App\Repositories\LoginRepository;
use App\Repositories\Interfaces\ILoginRepository;
use App\Models\User;
use Exception;

class LoginService implements ILoginService{
    private ILoginRepository $loginRepository;

    public function __construct(){
        $this->loginRepository = new LoginRepository();
    }

    public function login($username, $password){
        $user = $this->loginRepository->login($username, $password);
        if($user){
            if(password_verify($password, $user->password)){
                return $user;
            }
        }

        return null;
    }

    public function createAccount(User $user, $confirmPassword){
        if(!empty($user->firstname) && !empty($user->lastname) && !empty($user->username) && !empty($user->email) && !empty($user->password) && !empty($confirmPassword)){
            if(!filter_var($user->email, FILTER_VALIDATE_EMAIL)){
                throw new Exception("Invalid email.");
            }

            if($this->loginRepository->usernameExists($user)){
                throw new Exception("Username already exists.");
            }

            if($this->loginRepository->emailExists($user)){
                throw new Exception("Email already exists.");
            }

            $this->validatePassword($user->password);

            if(!isset($confirmPassword) || $user->password !== $confirmPassword){
                throw new Exception("Passwords do not match.");
            }

            $user->password = password_hash($user->password, PASSWORD_DEFAULT);

            $this->loginRepository->createAccount($user);
        }
        
    }

    public function validatePassword($password){
        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long.");
        }
        if (!preg_match('/[A-Z]/', $password)) {
            throw new Exception("Password must contain an uppercase letter.");
        }
        if (!preg_match('/[a-z]/', $password)) {
            throw new Exception("Password must contain a lowercase letter.");
        }
        if (!preg_match('/[0-9]/', $password)) {
            throw new Exception("Password must contain a number.");
        }
        if (!preg_match('/[\W]/', $password)) {
            throw new Exception("Password must contain a special character.");
        }
    }
}
?>