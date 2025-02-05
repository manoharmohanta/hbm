<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\HotelModel;
use App\Models\UHORelationModel;
use App\Models\RoleModel;
use CodeIgniter\I18n\Time;

class Hotel_owner extends BaseController{
    /*
        We have super_admin, hotel_owner, hotel_manager, staff, customer, housekeeping, front_office, kitchen as roles. Lets discuss one by one each role and what they can perform.
        Before we check all the roles lets see what tables we have users, roles, hotels, managers, staff, rooms, room_bookings, menu_items, orders.
        Lets see the flow of the website.
        1. Hotel Owner - He will able to perform CURD All Hotels he have and assign hotel_manager, housekeeping, front_office, kitchen and staff.
        2. Hotel Manager - He can able to just perform CURD to his assgined hotel details like  housekeeping, front_office, kitchen and staff.
        3. Front Office - He will able to add/view/edit the customer entered his hotel, add/edit/view/ approve the orders recived by customer and check out.
        4. House Keeping - He will be seeing all the checkout rooms and make it ready for checkin.
        5. Kitchen - He will able to see the all the approved list of items which is order by customer and approved by front office.
        6. Super Admit - He will able to see all the things.
    */
    // Dashboard
    protected $roleModel;
    protected $className;
    protected $hotelModel;
    protected $UHORelationModel;
    protected $userModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->hotelModel = new HotelModel();
        $this->UHORelationModel = new UHORelationModel();
        $this->userModel = new UserModel();
        $this->className = (new \ReflectionClass($this))->getShortName();
    }
    public function index(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
        
        return view('template/include/header').view('template/main').view('template/include/footer');
        
    }
    //Prfile Page of user
    public function profile(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }

        if ($this->request->isAJAX() || $this->request->hasHeader('HX-Request')) {
            $user = $this->getUserDataFromSession();
            $userModel = new UserModel();

            // Prepare the updated data
            $updatedData = [
                'name'  => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
            ];

            if ($this->request->getPost('password')) {
                $updatedData['password'] = $this->request->getPost('password'); // Password will be hashed in the model
            }

            // Call updateUser() from UserModel
            $response = $userModel->updateUser($user['id'], $updatedData);

            if ($response['status'] === 'success') {
                // Update session data after saving
                $updatedUser = $userModel->find($user['id']);
                $session = session();
                $existingUserData = $session->get('user'); // Get current session data

                // Merge existing session user data with updated fields
                $updatedUserData = array_merge($existingUserData, $updatedUser);

                // Update the session with merged data
                $session->set('user', $updatedUserData);

                $response['redirectUrl'] = base_url(session()->get('controller').'/profile');
            }

            return $this->response->setJSON($response);
        }

        $data['user'] = $this->getUserDataFromSession();
        return view('template/include/header') . view('template/profile', $data) . view('template/include/footer');
    }
    // CURD Role
    public function add_role(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }

        if ($this->request->getMethod() === 'post' || $this->request->hasHeader('HX-Request')) {
            $data = [
                'name' => $this->request->getPost('name'),
            ];

            $response = $this->roleModel->addRole($data);

            if ($response['status'] === 'success') {
                $response['redirectUrl'] = base_url($this->className.'/role');
            }

            return $this->response->setJSON($response);
        }

        return view('template/include/header') . view('template/role_add') . view('template/include/footer');
    }
    public function role(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }

        $data['roles'] = $this->roleModel->findAll();
        return view('template/include/header') . view('template/role_view', $data) . view('template/include/footer');
    }
    public function edit_role($id){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }

        $role = $this->roleModel->find($id);
        if (!$role) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Role not found.',
                'csrf_token' => csrf_hash()
            ]);
        }

        if ($this->request->getMethod() === 'post'  || $this->request->hasHeader('HX-Request')) {
            $data = [
                'name' => $this->request->getPost('name'),
            ];

            $response = $this->roleModel->updateRole($id, $data);

            if ($response['status'] === 'success') {
                $response['redirectUrl'] = base_url($this->className.'/role');
            }

            return $this->response->setJSON($response);
        }

        return view('template/include/header') . view('template/role_add', ['role' => $role]) . view('template/include/footer');
    }
    public function delete_role($id){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }

        if ($this->request->getMethod() === 'post'  || $this->request->hasHeader('HX-Request')) {

            $response = $this->roleModel->deleteRole($id);

            if ($response['status'] === 'success') {
                $response['redirectUrl'] = base_url($this->className.'/role');
            }

            return $this->response->setJSON($response);
        }
    }
    //CURD User
    public function user() {
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    
        $userData = $this->getUserDataFromSession();
    
        $data['users'] = $this->userModel
            ->select('users.*, roles.name as role_name')
            ->join('roles', 'roles.id = users.role_id')
            ->join('managers', 'users.id = managers.user_id', 'left')
            ->join('hotels', 'hotels.id = managers.hotel_id', 'left')
            ->where('users.id !=', $userData['id']) // Exclude logged-in user if needed
            ->groupStart()
                ->where('hotels.user_id', $userData['id']) // Fetch staff of hotels owned
                ->orWhere('managers.user_id IS NOT NULL') // Ensure user is a manager or staff
            ->groupEnd()
            ->groupBy('users.id') // Avoid duplicate users
            ->get()
            ->getResultArray();
    
        return view('template/include/header') . view('template/user_view', $data) . view('template/include/footer');
    }
    public function add_user(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }

        $userData = $this->getUserDataFromSession();

        if ($this->request->getMethod() === 'post' || $this->request->hasHeader('HX-Request')) {
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Hash the password
            ];
            $response = $this->userModel->insert($data);
            $insertedId = $this->userModel->insertID();

            if($response['status'] == 'success'){
                $data = [
                    'user_id'  => $insertedId,  // ID of the user to be assigned as manager
                    'hotel_id' => $this->request->getPost('hotel_id'),        // ID of the hotel
                ];

                $response = $this->UHORelationModel->insert($data);

                if($response){
                    $response = array(
                        'status' => 'success',
                        'message' => 'User registered successfully.',
                        'redirectUrl' => base_url($this->className . '/user'),
                        'csrf_token' => csrf_hash()
                    );
                }
            }

            return $this->response->setJSON($response);
        }
        $user = $this->getUserDataFromSession();
        $data['hotels'] = $this->hotelModel->where('user_id',$user['id'])
                                            ->findAll();
        $data['roles'] = $this->roleModel->findAll();
        return view('template/include/header') . view('template/user_add',$data) . view('template/include/footer');
    }
    public function edit_user($id){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
        if ($this->request->getMethod() === 'post' || $this->request->hasHeader('HX-Request')) {
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Hash the password
            ];
            $userId = $this->request->getPost('id');

            $response = $this->userModel->updateUser($userId,$data);

            if($response){
                $response['redirectUrl'] = base_url(session()->get('controller').'/user');
            }

            return $this->response->setJSON($response);
        }
        $data['users'] = $this->userModel->select('users.*, roles.name as role_name')
                                        ->where('users.id',$id)
                                        ->join('roles', 'roles.id = users.role_id')
                                        ->join('managers', 'manager.user_id = users.user_id')
                                        ->first();
        $data['roles'] = $this->roleModel->findAll();
        // echo "<pre>";print_r($data); exit();
        return view('template/include/header') . view('template/user_add', $data) . view('template/include/footer');
    }
    public function delete_user($id){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
        if ($this->request->getMethod() === 'post'  || $this->request->hasHeader('HX-Request')) {

            $response = $this->userModel->delete($id);

            if ($response) {
                $response = array(
                    'status' => 'success',
                    'message' => 'User deleted successfully.',
                    'csrf_token' => csrf_hash(),
                    'redirectUrl' => base_url($this->className.'/user')
                );
            }else{
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to delete user.',
                    'csrf_token' => csrf_hash(),
                    'redirectUrl' => base_url($this->className.'/user')
                );
            }

            // print_r($response); exit();

            return $this->response->setJSON($response);
        }
    }

    // CURD Hotel
    public function hotel(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
        
        $userId = $this->getUserDataFromSession()['id']; // Assuming user_id is stored in session
        $data['hotels'] = $this->hotelModel->where('user_id', $userId)->findAll();
        return view('template/include/header').view('template/hotel_view',$data).view('template/include/footer');
    }
    public function add_hotel(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }

        if ($this->request->getMethod() === 'post' || $this->request->hasHeader('HX-Request')) {
            // Define strict validation rules
            $validationRules = [
                'name' => 'required|min_length[3]|max_length[255]|alpha_numeric_space',
                'phone' => 'required|regex_match[/^[0-9]{10}$/]', // Ensures exactly 10 digits
                'email_id' => 'required|valid_email',
                'address' => 'required|min_length[5]|max_length[500]',
            ];

            if (!$this->validate($validationRules)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $this->validator->getErrors(),
                    'csrf_token' => csrf_hash()
                ]);
            }

            // Sanitize input to prevent XSS
            $userData = $this->getUserDataFromSession();
            $data = [
                'user_id' => (int) $userData['id'], // Force user_id to integer
                'name' => strip_tags($this->request->getPost('name')),
                'phone' => strip_tags($this->request->getPost('phone')),
                'email_id' => strip_tags($this->request->getPost('email_id')),
                'address' => strip_tags($this->request->getPost('address')),
                'hotel_owner_id' => $userData['id'],
                'created_at' => Time::now()->toDateTimeString()
            ];

            // Insert data securely
            if ($this->hotelModel->insert($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Hotel added successfully.',
                    'redirectUrl' => base_url($this->className . '/hotel'),
                    'csrf_token' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to add hotel. Please try again.',
                    'csrf_token' => csrf_hash()
                ]);
            }
        }

        return view('template/include/header')
            . view('template/hotel_add')
            . view('template/include/footer');
    }
    public function edit_hotel($id){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }

        $hotel = $this->hotelModel->find($id);
        if (!$hotel) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Hotel not found.',
                'csrf_token' => csrf_hash()
            ]);
        }

        if ($this->request->getMethod() === 'post' || $this->request->hasHeader('HX-Request')) {
            // Define strict validation rules
            $validationRules = [
                'name' => 'required|min_length[3]|max_length[255]|alpha_numeric_space',
                'phone' => 'required|regex_match[/^[0-9]{10}$/]', // Ensures exactly 10 digits
                'email_id' => 'required|valid_email',
                'address' => 'required|min_length[5]|max_length[500]',
            ];

            if (!$this->validate($validationRules)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $this->validator->getErrors(),
                    'csrf_token' => csrf_hash()
                ]);
            }

            // Sanitize input to prevent XSS
            $userData = $this->getUserDataFromSession();
            $data = [
                'user_id' => (int) $userData['id'], // Force user_id to integer
                'name' => strip_tags($this->request->getPost('name')),
                'phone' => strip_tags($this->request->getPost('phone')),
                'email_id' => strip_tags($this->request->getPost('email_id')),
                'address' => strip_tags($this->request->getPost('address')),
                'hotel_owner_id' => $userData['id'],
                'updated_at' => Time::now()->toDateTimeString()
            ];

            $response = $this->hotelModel->update($id, $data);

            if ($response) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Hotel updated successfully.',
                    'redirectUrl' => base_url($this->className . '/hotel'),
                    'csrf_token' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update hotel.',
                    'csrf_token' => csrf_hash()
                ]);
            }
        }
        return view('template/include/header') . 
                view('template/hotel_add', ['hotel' => $hotel]) . 
                view('template/include/footer');
    }
    public function delete_hotel($id){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }

        if ($this->request->getMethod() === 'post'  || $this->request->hasHeader('HX-Request')) {

            $hotel = $this->hotelModel->find($id);
            if (!$hotel) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Hotel not found.',
                    'csrf_token' => csrf_hash()
                ]);
            }

            $response = $this->hotelModel->delete($id);

            if ($response) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Hotel deleted successfully.',
                    'redirectUrl' => base_url($this->className . '/hotel'),
                    'csrf_token' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update hotel.',
                    'csrf_token' => csrf_hash()
                ]);
            }
        }
    }
    // Hotel Rooms 
    public function room(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function add_room(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function edit_room(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function delete_room(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    // Hotel Staff
    public function hotel_staff(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function add_hotel_staff(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function edit_hotel_staff(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function delete_hotel_staff(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    // Hotel Menu Staff
    public function menu(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function add_menu(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function edit_menu(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function delete_menu(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    // Hotel Orders
    public function order(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function add_order(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function edit_order(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function delete_order(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    // Hotel Bookings 
    public function booking(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function add_booking(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function edit_booking(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function delete_booking(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    //check out
    public function checkout() {
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }

    // CURD Manager
    public function manager(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function add_manager(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function edit_manager(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function delete_manager(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }

    // Private function to check if the user is logged in
    private function isUserLoggedIn(){
        // Check if user data exists in session
        return session()->has('user');
    }

    // Access the full user data from session
    private function getUserDataFromSession(){
        return session()->get('user');
        //$userData = $this->getUserDataFromSession();
        // You can now access any field, e.g., $userData['name'], $userData['email'], etc.
    }
}