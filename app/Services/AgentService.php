<?php

namespace App\Services;

use App\Models\User;
use App\Models\Agent;
use App\Support\MediaStorage;

class AgentService
{

    public function getAllAgents_backup()
    {
        return Agent::orderBy('created_at', 'desc')->paginate(config('constants.ROW_PER_PAGE'));
    }

    public function getAllAgents()
    {
        return Agent::select('agents.*', 'users.username')
        ->join('users', 'users.id', '=', 'agents.user_id')
        ->orderBy('agents.created_at', 'desc') 
        ->paginate(config('constants.ROW_PER_PAGE')); 
    }

    


    public function create_agent($request)
    {
        
        if ($request->hasFile('profile_image')) {
            $fileNameToStore = MediaStorage::store($request->file('profile_image'), 'agents');
            
        } else {
            
            $fileNameToStore = '';
        }
        $agent_id = str_pad(mt_rand(1, 9999), 4);
        $user = User::create([
            'user_id' => str_pad(mt_rand(1, 9999999999999), 20),
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' =>  $request->username,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'address' => $request->address,
            'profile_image' => $fileNameToStore,
            'status' => $request->status,
            'user_type' =>'agent',
            'password' => bcrypt($request->password),
        ]);
        //$agent_id = str_pad(mt_rand(1, 9999), 4);
        $agent = new Agent([
            'agent_id' => $agent_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'birth_day' => $request->birth_day,
            'phone_number' => $request->phone_number,
            'status' => $request->status,
            'address' => $request->address,
            'description' => $request->description,
        ]);

        
        $user->agent()->save($agent);

        return $user;
    }

    public function getAgentWithUser($id)
    {
        $agent = Agent::findOrFail($id);
        $user = $agent->user()->firstOrFail();
        
        return compact('agent', 'user');
    }

    public function getAgentEditData($id)
    {
        $agent = Agent::findOrFail($id);
        $user = $agent->user()->firstOrFail();
        
        return compact('agent', 'user');
    }


    public function updateAgent($request, $id)
    {
        // Find the user by id
        //dd($id);die();
        $agent = Agent::findOrFail($id);
        $user = User::findOrFail($agent->user_id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        //$user->username = $id;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->status = $request->status;
        $user->user_type ='agent';
        //$user->password = bcrypt($request->password);
        if(!empty($request->password)) {
			$user->password = bcrypt($request->password);
		}
        if ($request->hasFile('profile_image')) {
           
            $fileNameToStore = MediaStorage::replace($request->file('profile_image'), 'agents', $user->profile_image);
            $user->profile_image = $fileNameToStore;
               
        }
        $user->save();
        $agent = $user->agent;
        $agent->first_name = $request->first_name;
        $agent->last_name = $request->last_name;
        $agent->gender = $request->gender;
        $agent->birth_day = $request->birth_day;
        $agent->phone_number = $request->phone_number;
        $agent->status = $request->status;
        $agent->address = $request->address;
        $agent->description = $request->description;
        

        // Save the agent
        $agent->save();
    }

    public function searchAgents($request)
    {
        $searchTerm = trim($request->input('search'));
        $query = Agent::query();

        $query->where(function($q) use ($searchTerm) {
            $q->where('agent_id', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
        });

        return $query->paginate(config('constants.ROW_PER_PAGE'));
    }

    public function deleteAgentAndUser($id)
    {
        $agent = Agent::findOrFail($id);
        $user = $agent->user;
        MediaStorage::delete($user->profile_image, 'agents');
        $agent->delete();
        $user->delete();
        if($user->delete()) {
            return true;
        }
        return false;
    }
}
