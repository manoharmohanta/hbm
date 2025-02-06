<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\HotelModel;

class Hotel_manager extends BaseController{
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

    public function __construct(){
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
    // CURD Manager
    public function add_manager(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function manager(){
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
    // CURD Hotel
    public function hotel(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
        
        $hotelModel = new HotelModel();
        $userId = session()->get('user_id'); // Assuming user_id is stored in session
        $data['hotels'] = $hotelModel->getHotelsByUser($userId);
        return view('template/include/header').view('template/hotel_view',$data).view('template/include/footer');
    }
    public function add_hotel(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }

        return view('template/include/header').view('template/hotel_add').view('template/include/footer');
    }
    public function edit_hotel(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
        }
    }
    public function delete_hotel(){
        if (!$this->isUserLoggedIn()) {
            return redirect()->to(base_url('hotel/logout'));
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