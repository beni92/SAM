<?php
namespace Sam\Server\Controllers;

use Sam\Server\Models\Bank;
use Sam\Server\Models\Customer;
use Sam\Server\Models\Depot;
use Sam\Server\Models\Employee;
use Sam\Server\Models\User;

class IndexController extends ControllerBase
{

    public function indexAction($init = false)
    {
        if($init == "init") {
            $this->db->begin();

            $bank = new Bank();
            $bank->setName("MullhollandDriveBank");
            $bank->setVolume(1000000000);
            if($bank->save() === false) {
                $this->db->rollback();
                return json_encode(array("error"=> "could not create bank"));
            }

            $loginNr = "Emp101";
            $firstname = "Test";
            $lastname = "Employee";
            $phone = "01 00200301";
            $password = "sam101";

            $loginNr1 = "Cust101";
            $firstname1 = "Test";
            $lastname1 = "Customer";
            $phone1 = "02 92929292";
            $password1 = "sam101";

            $user = new User();
            $user->setBankId($bank->getId());
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setPhone($phone);
            $user->setLoginNr($loginNr);

            $user1 = new User();
            $user1->setBankId($bank->getId());
            $user1->setPassword(password_hash($password1, PASSWORD_DEFAULT));
            $user1->setFirstname($firstname1);
            $user1->setLastname($lastname1);
            $user1->setPhone($phone1);
            $user1->setLoginNr($loginNr1);

            if($user->save() === false) {
                $this->db->rollback();
                return json_encode(array("error" => "user could not be created", "code" => 00));
            }



            $employee = new Employee();
            $employee->setUserId($user->getId());
            if($employee->save() === false) {
                $this->db->rollback();
                return json_encode(array("error" => "user could not be created", "code" => 02));
            }

            $user->setCreatedByEmployeeId($employee->getId());
            if($user->save() === false) {
                $this->db->rollback();
                return json_encode(array("error" => "user could not be created", "code" => 05));
            }

            $user1->setCreatedByEmployeeId($employee->getId());

            if($user1->save() === false) {
                $this->db->rollback();
                return json_encode(array("error" => "user1 could not be created", "code" => 04));
            }


            $customer = new Customer();
            $customer->setUserId($user1->getId());
            $customer->setBudget(50000);
            if($customer->save() === false) {
                $this->db->rollback();
                return json_encode(array("error" => "user could not be created", "code" => 01));
            }

            $depot = new Depot();
            $depot->setBudget(50000);
            $depot->setCustomerId($customer->getId());
            if($depot->save() === false) {
                $this->db->rollback();
                return json_encode(array("error" => "depot could not be created", "code" => 03));
            }

            $this->db->commit();

            return json_encode("initial data-sets created");
        }

        return json_encode("nothing happend");
    }
}

