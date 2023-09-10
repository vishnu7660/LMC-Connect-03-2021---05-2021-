@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <img src="{{(isset($profile_details['profile_pic']) && !empty($profile_details['profile_pic']))? asset('images/profile_pics/'.$username.'/'.$profile_details['profile_pic']) : asset('images/profile_pics/default-profile-pic.jpg')  }}" alt="Profile Picture" class="img-thumbnail w-100 rounded-circle">
        </div>
    </div>
    <div class="row justify-content-center mt-4 h1">
        {{$name}}
    </div>
    <div class="row justify-content-center h5">
        {{$email}}
    </div>
    <div class="row justify-content-center">
        @if(isset($show_locked) && $show_locked == true)
            @if (isset($profile_details['social_links']['whatsapp']) && !empty($profile_details['social_links']['whatsapp']))
                @if(isset($profile_details['social_links']['whatsapp']['is_private']) && $profile_details['social_links']['whatsapp']['is_private'] == 0)
                    <a href="https://api.whatsapp.com/send/?phone=91{{$profile_details['social_links']['whatsapp']['value']}}" class="p-0 mx-3 btn"><i class="text-success fab fa-2x fa-whatsapp mt-1"></i></a>
                @else
                    <span data-toggle="tooltip" data-placement="top" title="Disabled! Request Access to use."><a href="#" class="p-0 mx-3 btn disabled"><i class="text-success fab fa-2x fa-whatsapp mt-1"></i></a></span>
                @endif
            @endif
            @if (isset($profile_details['social_links']['facebook']) && !empty($profile_details['social_links']['facebook']))
                @if(isset($profile_details['social_links']['facebook']['is_private']) && $profile_details['social_links']['facebook']['is_private'] == 0)
                    <a href="{{$profile_details['social_links']['whatsapp']['value']}}" class="p-0 mx-3 btn"><i class="text-primary fab fa-2x fa-facebook mt-1"></i></a>
                @else
                    <span data-toggle="tooltip" data-placement="top" title="Disabled! Request Access to use."><a href="#" class="p-0 mx-3 btn disabled"><i class="text-primary fab fa-2x fa-facebook mt-1"></i></a></span>
                @endif
            @endif
            @if (isset($profile_details['social_links']['instagram']) && !empty($profile_details['social_links']['instagram']))
                @if(isset($profile_details['social_links']['instagram']['is_private']) && $profile_details['social_links']['instagram']['is_private'] == 0)
                    <a href="{{$profile_details['social_links']['instagram']['value']}}" class="p-0 mx-3 btn"><i class="instagram-icon-color fab fa-2x fa-instagram mt-1"></i></a>
                @else
                    <span data-toggle="tooltip" data-placement="top" title="Disabled! Request Access to use."><a href="#" class="p-0 mx-3 btn disabled"><i class="instagram-icon-color fab fa-2x fa-instagram mt-1"></i></a></span>
                @endif
            @endif
            @if (isset($profile_details['social_links']['linkedin']) && !empty($profile_details['social_links']['linkedin']))
                @if(isset($profile_details['social_links']['linkedin']['is_private']) && $profile_details['social_links']['linkedin']['is_private'] == 0)
                    <a href="{{$profile_details['social_links']['linkedin']['value']}}" class="p-0 mx-3 btn"><i class="text-primary fab fa-2x fa-linkedin mt-1"></i></a>
                @else
                    <span data-toggle="tooltip" data-placement="top" title="Disabled! Request Access to use."><a href="#" class="btn disabled"><i class="text-primary fab fa-2x fa-linkedin mt-1"></i></a></span>
                @endif
            @endif
            @if($profile_details['social_links']['whatsapp']['is_private'] == 1 || $profile_details['social_links']['facebook']['is_private'] == 1 || $profile_details['social_links']['instagram']['is_private'] == 1 || $profile_details['social_links']['linkedin']['is_private'] == 1)
                @if(isset($social_access_status) && $social_access_status == 'pending')
                    <a class="btn btn-secondary disabled">Pending Approval</a>                    
                @else
                    <a id="request_access" class="btn btn-secondary">Request Access</a>
                @endif
            @endif
        @else
            @if (isset($profile_details['social_links']['whatsapp']) && !empty($profile_details['social_links']['whatsapp']))
                <a href="https://api.whatsapp.com/send/?phone=91{{$profile_details['social_links']['whatsapp']['value']}}" class="p-0 mx-3 btn"><i class="text-success fab fa-2x fa-whatsapp mt-1"></i></a>
            @elseif (isset($show_locked) && $show_locked == FALSE)
            
            @else
                <span data-toggle="tooltip" data-placement="top" title="Complete your profile!"><a href="#" class="p-0 mx-3 btn disabled"><i class="text-success fab fa-2x fa-whatsapp mt-1"></i></a></span>
            @endif
            @if (isset($profile_details['social_links']['facebook']) && !empty($profile_details['social_links']['facebook']))
                <a href="{{$profile_details['social_links']['facebook']['value']}}" class="p-0 mx-3 btn"><i class="text-primary fab fa-2x fa-facebook mt-1"></i></a>
            @elseif (isset($show_locked) && $show_locked == FALSE)
            
            @else
                <span data-toggle="tooltip" data-placement="top" title="Complete your profile!"><a href="#" class="p-0 mx-3 btn disabled"><i class="text-primary fab fa-2x fa-facebook mt-1"></i></a></span>
            @endif
            @if (isset($profile_details['social_links']['instagram']) && !empty($profile_details['social_links']['instagram']))
                <a href="{{$profile_details['social_links']['instagram']['value']}}" class="p-0 mx-3 btn"><i class="instagram-icon-color fab fa-2x fa-instagram mt-1"></i></a>
            @elseif (isset($show_locked) && $show_locked == FALSE)
            
            @else
                <span data-toggle="tooltip" data-placement="top" title="Complete your profile!"><a href="#" class="p-0 mx-3 btn disabled"><i class="instagram-icon-color fab fa-2x fa-instagram mt-1"></i></a></span>
            @endif
            @if (isset($profile_details['social_links']['linkedin']) && !empty($profile_details['social_links']['linkedin']))
                <a href="{{$profile_details['social_links']['linkedin']['value']}}" class="p-0 mx-3 btn"><i class="text-primary fab fa-2x fa-linkedin mt-1"></i></a>
            @elseif (isset($show_locked) && $show_locked == FALSE)
            
            @else
                <span data-toggle="tooltip" data-placement="top" title="Complete your profile!"><a href="#" class="p-0 mx-3 btn disabled"><i class="text-primary fab fa-2x fa-linkedin mt-1"></i></a></span>
            @endif
        @endif
    </div>
    <div class="row justify-content-center mt-5">
        <div class="col-lg-4">
            <div class="h2 text-center">Personal Details</div>
            <div class="mt-3">
                <div class="h5">Bio</div>
                <div>{{(isset($profile_details['bio']) && !empty($profile_details['bio']))? $profile_details['bio'] : 'N/A' }}</div>
            </div>
            <div class="mt-3">
                <div class="h5">Course</div>
                <div>{{(isset($profile_details['course']) && !empty($profile_details['course']))? $profile_details['course'] : 'N/A' }}</div>
            </div>
            <div class="mt-3">
                <div class="h5">Year</div>
                <div>{{(isset($profile_details['year']) && !empty($profile_details['year']))? $profile_details['year'] : 'N/A' }}</div>
            </div>
            <div class="mt-3">
                <div class="h5">Skills</div>
                @if (empty($profile_details['skillset']))
                    <div>N/A</div>

                @else
                    <div class="row row-cols-2">
                        <ul>
                            @foreach ($profile_details['skillset'] as $skill)
                                <li class="col">{{$skill}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-6 text-center border">
            <div class="h2">Posts</div>
        </div>
    </div>
</div>

@if (isset($profile_details['social_links']) && !empty($profile_details['social_links']))

@if(((isset($profile_details['social_links']['whatsapp']) && $profile_details['social_links']['whatsapp']['is_private'] == 1) || (isset($profile_details['social_links']['facebook']) && $profile_details['social_links']['facebook']['is_private'] == 1) || (isset($profile_details['social_links']['instagram']) && $profile_details['social_links']['instagram']['is_private'] == 1) || (isset($profile_details['social_links']['linkedin']) && $profile_details['social_links']['linkedin']['is_private'] == 1)) && isset($show_locked) && $show_locked == true)
<script>
$(document).ready(function(){

    $('#request_access').click(function(e){
        e.preventDefault();
        $('#request_access').addClass('disabled');
        var username = JSON.parse('{!! json_encode($username) !!}');

        $.ajax({
            url : "{{ route('social_access.create_request') }}",
            type: "POST",
            data : {
                username : username
            },
            success: function(res_data)
            {
                console.log('her');
                $('#request_access').html('Pending Approval');
            },
            error: function (res_data)
            {   
                console.log(res_data);
                if(res_data.responseJSON.type == 'custom')
                alertify.alert('Error', res_data.responseJSON.message);
                else
                alertify.alert('Error', 'Something Went Wrong!');
            }
        });
    });
});
</script>
@endif
@endif



@endsection
