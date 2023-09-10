@extends('layouts.app')
@section('content')

<style>
    #img_container {
    position:relative;
    display:inline-block;
    text-align:center;
    border:1px solid red;
}

.button {
    position:absolute;
    bottom:0px;
    right:0px;
    top:50%;
}

.croppie-container {
    width: 100%;
    height: 75%;
}
</style>

<div class="container">
    <form action="{{ route('profile.save') }}" method="POST" id="profile_edit_form">
        @csrf
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div id="upload-demo" class="edit_profile_pic" style="display: none;"></div>
            <div id="preview-crop-image" class="img_container">
                <img src="{{(isset($profile_details['profile_pic']) && !empty($profile_details['profile_pic']))? asset('images/profile_pics/'.$username.'/'.$profile_details['profile_pic']) : asset('images/profile_pics/default-profile-pic.jpg')  }}" alt="" class="img-thumbnail w-100 rounded-circle">
                <a id='edit_profile_pic' class="text-dark button w-100 h-100 text-center"><i class="fas fa-3x fa-camera"></i></a>
            </div>
            <div class="edit_profile_pic" style="display: none;">
                <div class="input-group mt-4">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input"  id="image" aria-describedby="inputGroupFileimage">
                      <input type="hidden" name="profile_pic" id="profile_pic" value="">
                      <label class="custom-file-label" for="image">Choose file</label>
                    </div>
                    <div class="input-group-append">
                      <button class="btn btn-outline-success" type="button" id="btn-upload-image"><i class="fas fa-save"></i></button>
                    </div>
                    @error('profile_pic')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
            </div>
        </div>    
    </div>
    <div class="row justify-content-center mt-4 h1">
        {{$name}}
    </div>
    <div class="row justify-content-center h5">
        {{$email}}
    </div>
    <div class="row justify-content-between mt-5">
        <div class="col-lg-6 border">
            <div class="h2 text-center pt-2">Personal Details</div>
            <div class="my-3">
                <div class="h5">Bio</div>
                <input type="text" name="bio" id="bio" value="{{(isset($profile_details['bio']) && !empty($profile_details['bio']))? $profile_details['bio'] : '' }}" class="form-control">
            </div>
            <div class="my-3">
                <div class="h5">Course <span class="text-danger">*</span></div>
                <select name="course" id="course" class="form-control" required>
                    <option value="" disabled selected>Select Course</option>
                    @foreach ($courses_available as $course_option)
                        <option value="{{$course_option}}" {{(isset($profile_details['course']) && !empty($profile_details['course']) && ($course_option == $profile_details['course']))?'selected=selected':''}}>{{$course_option}}</option>
                    @endforeach
                </select>
                @error('course')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="my-3">
                <div class="h5">Year <span class="text-danger">*</span></div>
                @if (isset($profile_details['year_available']) && !empty($profile_details['year_available']))
                    <select name="year" id="year" class="form-control">
                        <option value="" disabled>Select Year</option>
                        @foreach ($profile_details['year_available'] as $year_available)
                            <option value="{{ $year_available }}" {{($year_available == $profile_details['year'])? 'selected': ''}}>{{ $year_available }}</option>
                        @endforeach
                    </select>
                @else
                    <select name="year" id="year" class="form-control" required disabled>
                        <option value="" disabled selected>Select Year</option>
                    </select>
                @endif
                
                @error('year')
                    <div class="text-danger">{{ $message }}</div>
                @enderror            </div>
            <div class="my-3">
                <div class="h5">Skills</div>
                <select class="form-control" multiple="multiple" name='skills[]' id='skills'>
                    @if (isset($profile_details['skillset']) && !empty($profile_details['skillset']) && is_array($profile_details['skillset']))
                    @foreach ($profile_details['skillset'] as $skill)
                        <option value="{{$skill}}" selected>{{$skill}}</option>
                    @endforeach
                        
                    @endif
                </select>
            </div>
        </div>
        <div class="col-lg-6 border text-center">
            <div class="h2">Social Links</div>
            <div class="row mt-5 h5">
                <div class="col-lg-9">Please Fill your Social Handles info</div>
                <div class="col-lg-3">Private <span data-toggle="tooltip" data-placement="top" title="Without your approval no one can access your private social links"><i class="fas fa-lg fa-circle"></i><i class="fas fa-sm fa-info text-light" style="margin-left:-15px;"></i></span></div>
            </div>
            <div class="my-3 row">
                <div class="col-lg-2 text-success"><i class="fab fa-3x fa-whatsapp"></i></div>
                <div class="col-lg-8">
                    <input type="text" placeholder="Whatsapp number without 0 or +91" name="whatsapp" id="whatsapp" class="form-control" value="{{(isset($profile_details['social_links']['whatsapp']['value']) && !empty($profile_details['social_links']['whatsapp']['value']))? $profile_details['social_links']['whatsapp']['value'] : '' }}">
                </div>
                <div class="col-lg-2">
                    <label class="switch">
                        <input type="checkbox" name="whatsapp_private_switch" {{ (isset($profile_details['social_links']['whatsapp']['is_private']) && $profile_details['social_links']['whatsapp']['is_private'] == 1)?'checked': '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-lg-2"></div>
                @error('whatsapp')
                    <div class="text-danger pl-4">{{ $message }}</div>
                @enderror
            </div>
            <div class="my-3 row">
                <div class="col-lg-2 text-primary"><i class="fab fa-3x fa-facebook"></i></div>
                <div class="col-lg-8">
                    <input type="text" placeholder="Facebook Profile URL" name="facebook" id="facebook" class="form-control" value="{{(isset($profile_details['social_links']['facebook']['value']) && !empty($profile_details['social_links']['facebook']['value']))? $profile_details['social_links']['facebook']['value'] : '' }}">
                </div>
                <div class="col-lg-2">
                    <label class="switch">
                        <input type="checkbox" name="facebook_private_switch" {{ (isset($profile_details['social_links']['facebook']['is_private']) && $profile_details['social_links']['facebook']['is_private'] == 1)?'checked': '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <div class="my-3 row">
                <div class="col-lg-2 text-dark"><i class="fab fa-3x fa-instagram"></i></div>
                <div class="col-lg-8">
                    <input type="text" placeholder="Instagram Profile URL" name="instagram" id="instagram" class="form-control" value="{{(isset($profile_details['social_links']['instagram']['value']) && !empty($profile_details['social_links']['instagram']['value']))? $profile_details['social_links']['instagram']['value'] : '' }}">
                </div>
                <div class="col-lg-2">
                    <label class="switch">
                        <input type="checkbox" name="instagram_private_switch" {{ (isset($profile_details['social_links']['instagram']['is_private']) && $profile_details['social_links']['instagram']['is_private'] == 1)?'checked': '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <div class="my-3 row">
                <div class="col-lg-2 text-primary"><i class="fab fa-3x fa-linkedin"></i></div>
                <div class="col-lg-8">
                    <input type="text" placeholder="LinkedIn" name="linkedin" id="linkedin" class="form-control" value="{{(isset($profile_details['social_links']['linkedin']['value']) && !empty($profile_details['social_links']['linkedin']['value']))? $profile_details['social_links']['linkedin']['value'] : '' }}">
                </div>
                <div class="col-lg-2">
                    <label class="switch">
                        <input type="checkbox" name="linkedin_private_switch" {{ (isset($profile_details['social_links']['linkedin']['is_private']) && $profile_details['social_links']['linkedin']['is_private'] == 1)?'checked': '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-left border">
        <button type="submit" class="btn btn-success m-2">Save</button>
        <a href="{{  route('profile') }}" class="btn btn-outline-secondary my-2">Cancel</a>
    </div>

</form>

</div>


<script>

$(document).ready(function(){

    $('#edit_profile_pic').click(function(e){
        e.preventDefault();
        $('.edit_profile_pic').show();
        $('#preview-crop-image').hide();
        
    });
    
    var resize = $('#upload-demo').croppie({
        enableExif: true,
        enableOrientation: true,    
        viewport: { // Default { width: 100, height: 100, type: 'square' } 
            width: 255,
            height: 255,
            type: 'circle' //square
        },
        boundary: {
            width: 255,
            height: 255
        }
    });


    $('#image').on('change', function () { 
    var reader = new FileReader();
        reader.onload = function (e) {
        resize.croppie('bind',{
            url: e.target.result
        });
        }
        reader.readAsDataURL(this.files[0]);
    });


    $('#btn-upload-image').on('click', function (ev) {
    resize.croppie('result', {
        type: 'canvas',
        size: 'viewport'
    }).then(function (img) {
        $('#profile_pic').val(img);
        $("#preview-crop-image").html('<img src="' + img + '" />');
        $('#preview-crop-image').show();
        $('.edit_profile_pic').hide();
    });
    });

    $('#course').on('change', function(e){
        e.preventDefault();

        $('#year').html('<option value="" disabled selected>Select Year</option>');
        $.ajax({
            url : "{{ route('get_course_year_details') }}",
            type: "GET",
            data : {
                course : $('#course').val()
            },
            success: function(res_data,status)
            {
                $('#year').removeAttr('disabled');
                for(let year of res_data)
                {
                    $('#year').append('<option value="'+year+'">'+year+'</option>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
        
            }
        });
    });

    $("#skills").select2({
        tags: true,
        tokenSeparators: [',', ' ']
    });

});
</script>
@endsection
