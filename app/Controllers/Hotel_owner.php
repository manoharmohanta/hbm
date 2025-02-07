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
                // 'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
            ];

            $validationRules = [
                'name'     => 'required|min_length[3]|max_length[25]',
                'phone'    => 'required|numeric|max_length[10]',
            ];
            
            if (!$this->validate($validationRules)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => $this->validator->getErrors(),
                    'csrf_token' => csrf_hash()
                ]);
            }

            if ($this->request->getPost('password')) {
                $updatedData['password'] = password_hash($this->request->getPost('password'), PASSWORD_ARGON2ID); // Password will be hashed in the model

                $validationRules = [
                    'password' => 'required|min_length[8]',
                ];

                if (!$this->validate($validationRules)) {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => $this->validator->getErrors(),
                        'csrf_token' => csrf_hash()
                    ]);
                }
            }

            $response = $this->userModel->update($user['id'], $updatedData);

            if ($response) {
                // Update session data after saving
                $updatedUser = $userModel->find($user['id']);
                $session = session();
                $existingUserData = $session->get('user'); // Get current session data

                // Merge existing session user data with updated fields
                $updatedUserData = array_merge($existingUserData, $updatedUser);

                // Update the session with merged data
                $session->set('user', $updatedUserData);

                $response = array(
                    'status' => 'success',
                    'message' => 'User updated successfully',
                    'redirectUrl' => base_url(session()->get('controller').'/profile'),
                    'csrf_token' => csrf_hash()
                );
            }else{
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to update user.',
                    'redirectUrl' => base_url(session()->get('controller').'/profile'),
                    'csrf_token' => csrf_hash()
                );
            }

            return $this->response->setJSON($response);
        }

        $data['user'] = $this->userModel->select('users.*,roles.name as role_name')
                                        ->where('users.id', session()->get('user')['id'])
                                        ->join('roles', 'roles.id=users.role_id')
                                        ->first();
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
    
        $data['users'] = $this->UHORelationModel
                                        ->select('users.*, roles.name as role_name')
                                        ->join('users', 'users.id = u_h_o_relation.user_id') // First join users table
                                        ->join('roles', 'roles.id = users.role_id') // Then join roles table
                                        ->join('hotels', 'hotels.id = u_h_o_relation.hotel_id') // Then join roles table
                                        ->where('users.deleted_at', null)
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
                'name'     => $this->request->getPost('name'),
                'email'    => $this->request->getPost('email'),
                'phone'    => $this->request->getPost('phone'),
                'role_id'  => $this->request->getPost('role_id'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Hash the password
            ];

            // Insert user data
            $userInsertResponse = $this->userModel->insert($data);
            if (!$userInsertResponse) {
                return ['status' => 'error', 'message' => 'User insertion failed.', 'csrf_token' => csrf_hash()];
            }

            $insertedId = $this->userModel->insertID();

            // Insert relation between user and hotel
            $hotelRelationData = [
                'user_id'      => $insertedId,  // ID of the user to be assigned as manager
                'hotel_id'     => $this->request->getPost('hotel_id'),  // ID of the hotel
                'hotel_owner_id' => $userData['id'],
            ];

            $hotelRelationResponse = $this->UHORelationModel->insert($hotelRelationData);

            if (!$hotelRelationResponse) {
                return ['status' => 'error', 'message' => 'Failed to link user with hotel.', 'csrf_token' => csrf_hash()];
            }

            // Success response
            return $this->response->setJSON([
                'status'      => 'success',
                'message'     => 'User registered successfully.',
                'redirectUrl' => base_url($this->className . '/user'),
                'csrf_token'  => csrf_hash()
            ]);
        }

        // Handle non-POST requests (i.e., load form view)
        $data['hotels'] = $this->hotelModel->where('user_id', $userData['id'])->findAll();
        $data['roles'] = $this->roleModel->findAll();
        return view('template/include/header') . view('template/user_add', $data) . view('template/include/footer');
    }
    public function edit_user($id) {
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    
        $userData = $this->getUserDataFromSession();
        
        // Fetch user details
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url($this->className . '/user'))->with('error', 'User not found.');
        }
    
        // Fetch user's hotel relation
        $userRelation = $this->UHORelationModel->where('user_id', $id)->first();
    
        if ($this->request->getMethod() === 'post' || $this->request->hasHeader('HX-Request')) {
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'role_id' => $this->request->getPost('role_id'),
            ];
    
            // Update password only if provided
            $password = $this->request->getPost('password');
            if (!empty($password)) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            
            $update = $this->userModel->update($id, $data);
    
            if ($update) {
                // Update hotel-user relationship if hotel_id is changed
                $hotelId = $this->request->getPost('hotel_id');
                if ($userRelation && $hotelId != $userRelation['hotel_id']) {
                    $this->UHORelationModel->update($userRelation['id'], [
                        'hotel_id' => $hotelId
                    ]);
                }
    
                $response = [
                    'status' => 'success',
                    'message' => 'User updated successfully.',
                    'redirectUrl' => base_url($this->className . '/user'),
                    'csrf_token' => csrf_hash()
                ];
            } else {
                $errors = $this->userModel->errors();
                $response = [
                    'status' => 'error',
                    'message' => $errors ?: 'Failed to update user. Please try again.',
                    'csrf_token' => csrf_hash()
                ];
            }
    
            return $this->response->setJSON($response);
        }
    
        // Fetch hotels and roles for the dropdowns
        $data['hotels'] = $this->hotelModel->where('user_id', $userData['id'])->findAll();
        $data['roles'] = $this->roleModel->findAll();
        $data['users'] = $user;
        $data['userRelation'] = $userRelation;
    
        return view('template/include/header') . view('template/user_add', $data) . view('template/include/footer');
    }
    public function delete_user($id){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
        if ($this->request->getMethod() === 'post'  || $this->request->hasHeader('HX-Request')) {

            $this->UHORelationModel->where('user_id', $id)->delete();
            
            if ($this->userModel->delete($id)) {
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
                $errors = $this->hotelModel->errors();
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $errors ?: 'Failed to add hotel. Please try again.', // Fallback message if no specific errors
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
        if ($this->request->getMethod() === 'post' || $this->request->hasHeader('HX-Request')) {
            
        }
        $userId = $this->getUserDataFromSession()['id']; // Assuming user_id is stored in session
        $data['hotels'] = $this->hotelModel->where('user_id', $userId)->findAll();
        return view('template/include/header').view('template/room_view',$data).view('template/include/footer');
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