<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Http\Response;
use App\Models\Profile;
use App\Models\SocialAccessRequests;
class ProfileController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('verified');
    }

    public function index()
    {
        $logged_in_user = Auth::user();
        
        $logged_in_user_details = $logged_in_user->getAttributes();

        $data = array(
            'name' => $logged_in_user_details['name'],
            'email' => $logged_in_user_details['email'],
            'username' => $logged_in_user_details['username'],
            'department' => $logged_in_user_details['department'],
        ); 
        
        if(!empty($logged_in_user->profile()->get()->all()))
        {
            
            $profile_details = $logged_in_user->profile()->get()->all()[0]->getAttributes();
            if(!empty($profile_details['skillset']))
            {
                $profile_details['skillset'] = json_decode($profile_details['skillset'], TRUE);
            }
            if(!empty($profile_details['social_links']))
            {
                $profile_details['social_links'] = json_decode($profile_details['social_links'], TRUE);
            }
            $data['profile_details'] = $profile_details;
        }

        if(empty($logged_in_user->posts()->get()->all()))
        {
            $data['posts_data'] = array();
        }
        else
        {
            $posts_details = $logged_in_user->posts()->get()->all()[0]->getAttributes();
            dd($posts_details);
        }
    
    

        return view('profile.profile', $data);
    }

    public function edit()
    {
        $logged_in_user = Auth::user();

        $logged_in_user_details = $logged_in_user->getAttributes();

        $courses_available = config('LMC.'.$logged_in_user_details['department']);

        $data = array(
            'name' => $logged_in_user_details['name'],
            'email' => $logged_in_user_details['email'],
            'username' => $logged_in_user_details['username'],
            'department' => $logged_in_user_details['department'],
            'courses_available' => $courses_available
        ); 



        if(empty($logged_in_user->profile()->get()->all()))
        {
            $profile_details = array();
            $data['profile_details'] = $profile_details;
        }
        else
        {
            
            $profile_details = $logged_in_user->profile()->get()->all()[0]->getAttributes();

            if(!empty($profile_details['skillset']))
            {
                $profile_details['skillset'] = json_decode($profile_details['skillset'], TRUE);
            }
            if(!empty($profile_details['social_links']))
            {
                $profile_details['social_links'] = json_decode($profile_details['social_links'], TRUE);
            }
            if(!empty($profile_details['course']))
            {
                $profile_details['year_available'] = config('LMC.'.$profile_details['course']);
            }
            $data['profile_details'] = $profile_details;
        }


            return view('profile.edit_profile', $data);
    }

    public function get_course_year_details(Request $request)
    {
        $get_data = $request->all();

        $course = $get_data['course'];

        $year = config('LMC.'.$course);

        return (new Response($year, 201))
              ->header('Content-Type', 'application/json');

    }

    public function save(Request $request)
    {

        $request->validate([
            'course' => 'required',
            'year' => 'required',
            'whatsapp' => 'numeric|digits:10|nullable'
        ]);

        $post_data = $request->all();
        $logged_in_user = Auth::user();
        $logged_in_user_details = $logged_in_user->getAttributes();

        $imageName = '';
        if(isset($post_data['profile_pic']) && !empty($post_data['profile_pic']))
        {
            if(!is_dir('images/profile_pics/'.$logged_in_user_details['username']))
            {
                mkdir('images/profile_pics/'.$logged_in_user_details['username']);
            }
            $folderPath = public_path('images/profile_pics/'.$logged_in_user_details['username'].'/');
 
            $image_parts = explode(";base64,", $post_data['profile_pic']);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
     
            $imageName = uniqid().'_'.$logged_in_user_details['username'].'.'.$image_type;
     
            $imageFullPath = $folderPath.$imageName;
     
            file_put_contents($imageFullPath, $image_base64);
     
        }

        $social_links = array();

        if(isset($post_data['whatsapp']) && !empty($post_data['whatsapp']))
        {
            $social_links['whatsapp']['value'] = $post_data['whatsapp'];

            if(isset($post_data['whatsapp_private_switch']) && !empty($post_data['whatsapp_private_switch']) && $post_data['whatsapp_private_switch'] == 'on')
            {
                $social_links['whatsapp']['is_private'] = 1;
            }
            else
            {
                $social_links['whatsapp']['is_private'] = 0;
            }
        }

        if(isset($post_data['facebook']) && !empty($post_data['facebook']))
        {
            $social_links['facebook']['value'] = $post_data['facebook'];

            if(isset($post_data['facebook_private_switch']) && !empty($post_data['facebook_private_switch']) && $post_data['facebook_private_switch'] == 'on')
            {
                $social_links['facebook']['is_private'] = 1;
            }
            else
            {
                $social_links['facebook']['is_private'] = 0;
            }
        }

        if(isset($post_data['instagram']) && !empty($post_data['instagram']))
        {
            $social_links['instagram']['value'] = $post_data['instagram'];

            if(isset($post_data['instagram_private_switch']) && !empty($post_data['instagram_private_switch']) && $post_data['instagram_private_switch'] == 'on')
            {
                $social_links['instagram']['is_private'] = 1;
            }
            else
            {
                $social_links['instagram']['is_private'] = 0;
            }
        }

        if(isset($post_data['linkedin']) && !empty($post_data['linkedin']))
        {
            $social_links['linkedin']['value'] = $post_data['linkedin'];

            if(isset($post_data['linkedin_private_switch']) && !empty($post_data['linkedin_private_switch']) && $post_data['linkedin_private_switch'] == 'on')
            {
                $social_links['linkedin']['is_private'] = 1;
            }
            else
            {
                $social_links['linkedin']['is_private'] = 0;
            }
        }

        $profile = Profile::where('user_id', $logged_in_user_details['id'])->first();
        if($profile == null)
        {
            $profile = new Profile;
            $profile->user_id = $logged_in_user_details['id'];
            if($imageName != '')
                $profile->profile_pic = $imageName;
            if($post_data['bio'] != null)
                $profile->bio = $post_data['bio'];

            $profile->course = $post_data['course'];
            $profile->year = $post_data['year'];
            
            if(!empty($post_data['skills']))
                $profile->skillset = json_encode($post_data['skills']);
            
            if(!empty($social_links))
                $profile->social_links = json_encode($social_links);
        }
        else
        {
            $stored_profile_data = $profile->getAttributes();
            if($imageName != '')
            {
                $profile->profile_pic = $imageName;
            }
            if($post_data['bio'] != $stored_profile_data['bio'])
            {
                $profile->bio = $post_data['bio'];
            }
            if($post_data['course'] != $stored_profile_data['course'])
            {
                $profile->course = $post_data['course'];
            }
            if($post_data['year'] != $stored_profile_data['year'])
            {
                $profile->year = $post_data['year'];
            }
            if(json_encode($post_data['skills']) != $stored_profile_data['skillset'])
            {
                $profile->skillset = json_encode($post_data['skills']);
            }
            if(json_encode($social_links) != $stored_profile_data['social_links'])
            {
                $profile->social_links = json_encode($social_links);
            }
        }

        if($profile->save())
        {
           return redirect(route('profile'));
        }
    }

    public function view_user($username)
    {
        $user = User::where('username',$username)->first();
        if($user == null)
        {
            abort(404);
        }

        $user_details = $user->getAttributes();
        $profile = $user->profile()->get()->all();

        $data = array(
            'name' => $user_details['name'],
            'email' => $user_details['email'],
            'username' => $user_details['username'],
            'department' => $user_details['department'],
        ); 
        
        $data['show_locked'] = true;

        if(!empty($profile))
        {
            
            $profile_details = $profile[0]->getAttributes();
            if(!empty($profile_details['skillset']))
            {
                $profile_details['skillset'] = json_decode($profile_details['skillset'], TRUE);
            }
            if(!empty($profile_details['social_links']))
            {
                $profile_details['social_links'] = json_decode($profile_details['social_links'], TRUE);
        
                $logged_in_user_id = Auth::id();
                $requested_to_id = $user_details['id'];
                $social_access_request = SocialAccessRequests::where('requested_by_user_id',$logged_in_user_id)->where('requested_to_user_id',$requested_to_id)->first();
                if($social_access_request != null)
                {
                    $social_access_request = $social_access_request->getAttributes();
                    if($social_access_request['status'] == 'approved')
                    {
                        $data['show_locked'] = FALSE;
                    }
                    else if($social_access_request['status'] == 'pending')
                    {
                        $data['social_access_status'] = $social_access_request['status'];
                    }
                }
            }
            $data['profile_details'] = $profile_details;
        }
        
        return view('profile.profile', $data);
    }
}
