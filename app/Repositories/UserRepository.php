<?php namespace App\Repositories;


use App\Role;
use App\Setting;
use App\User;
use Illuminate\Support\Facades\Storage;

class UserRepository extends DbRepository{


    /**
     * Construct
     * @param User $model
     */
    function __construct(User $model)
    {
        $this->model = $model;
        $this->limit = 10;
    }

    /**
     * save a user
     * @param $data
     */
    public function store($data)
    {
        
        $data = $this->prepareData($data);
       
        $user = $this->model->create($data);

        $role = (isset($data['role'])) ? $data['role'] : Role::whereName('medico')->first();
        
        $user->assignRole($role);
        
        if(isset($data['speciality']))
            $user->assignSpeciality($data['speciality']);

        $user->createSettings();
        
        $exists = Storage::disk('public')->exists('avatars/'. $user->id.'/avatar.jpg');
        
        if(! $exists ) Storage::disk('public')->copy('avatars/default-avatar.jpg', 'avatars/'. $user->id.'/avatar.jpg');

        return $user;
    }

    /**
     * Update a user
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function update($id, $data)
    {
        $user = $this->model->findOrFail($id);
        $data = $this->prepareData($data);

        $user->fill($data);

        if(isset($data['speciality']))
            $user->assignSpeciality($data['speciality']);

        if(isset($data['minTime']) || isset($data['maxTime']))
        {
           $settings = Setting::where('user_id',$user->id)->first();
           
           if($settings)
            {
                $settings->fill($data);
                $settings->save();
            }
            
        }
        
        $user->save();


        return $user;
    }

    

    /**
     * Find all the users for the admin panel
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAll($search = null)
    {
        $order = 'created_at';
        $dir = 'desc';

        if (! count($search) > 0) return $this->model->paginate($this->limit);

        if (trim($search['q']))
        {
            $users = $this->model->Search($search['q']);
        } else
        {
            $users = $this->model;
        }

        if (isset($search['active']) && $search['active'] != "")
        {
            $users = $users->where('active', '=', $search['active']);
        }
        if (isset($search['role']) && $search['role'] != "")
        {
            $users = $users->whereHas('roles', function ($query) use ($search) {
                        $query->where('name',  $search['role']);
                    });
            
        }

        if (isset($search['order']) && $search['order'] != "")
        {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != "")
        {
            $dir = $search['dir'];
        }


        return $users->orderBy('users.'.$order , $dir)->paginate($this->limit);

    }


    //List of users for the modal view of user.

    public function list_users($value = null, $search = null)
    {

        if ($search && $value != "")
            $users = ($value) ? $this->model->where('id', '<>', $value)->search($search)->paginate(8) : $this->model->paginate(8);
        else if ($value != "")
            $users = ($value) ? $this->model->where('id', '<>', $value)->paginate(8) : $this->model->paginate(8);
        else
            $users = $this->model->search($search)->paginate(8);

        return $users;
    }

     /**
     * Delete user
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function delete($id)
    {
        
        $user = $this->model->findOrFail($id)->delete();
     
        return $user;
    }



    private function prepareData($data)
    {
        if(empty($data['password']))
           return $data = array_except($data, array('password'));

        $data['password'] = bcrypt($data['password']);

        return $data;
    }


}