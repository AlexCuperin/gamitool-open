<script>
    var instance_type;
    var instance_name;
    var bearer;


    function get_courses(){

       instance_type = $('.select_platform.selected span').html();
       instance_name  = $('#site').val();
       bearer = $('#bearer').val();

        $.ajax({
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{url('/gamification')}}/"+gld.id+"/deploy/courses",
            data: {
                instance_type:      instance_type,
                instance_name:      instance_name,
                bearer:             bearer
            },
        }).done(function(courses) {
            console.log(courses);
            button_done();

            var options = '<option value="">Select a course..</option>';
            courses.forEach(function(course){
                options = options.concat('<option value="' + course.id + '">' + course.name + '</option>');
            });
            $('#course_select').html(options);

        }).fail(function(msg){
            console.log("Error retrieving the courses: ");
            console.log(msg);
        });
    }

    function fill_select(resources, type){
        $('.'+type).addClass('selectable').html("");

        var html = '<option value="">Select a resource..</option>';
        resources.forEach(function(resource){
            html = html.concat('<option value="'+resource.id+'">'+resource.name+'</option>');
        });
        $('.'+type).html(html);
    }

    function get_resources(){

        $.ajax({
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{url('/gamification')}}/"+gld.id+"/deploy/resources",
            data: {
                instance_type:  instance_type,
                course_id:      $('select#course_select').val(),
                instance_name:  instance_name,
                bearer:         bearer
            },
        }).done(function(resources) {
            resources_loaded();

            if (resources['assignments']) fill_select(resources['assignments'],"Assignment");
            if (resources['content_pages']) fill_select(resources['content_pages'],"Content_Page");
            if (resources['forums']) fill_select(resources['forums'],"Discussion_Forum");
            if (resources['external_tools'])fill_select(resources['external_tools'],"External_Tool");
            //if (resources['external_urls']) fill_select(resources.assingments,"External_URL");
            if (resources['files']) fill_select(resources['files'],"File");
            if (resources['peer_reviews']) fill_select(resources['peer_reviews'],"Peer_Review");
            if (resources['quizzes']) fill_select(resources['quizzes'],"Quiz");
            if (resources['wikis']) fill_select(resources['wikis'],"Wiki");
            if (resources['3DVW']) fill_select(resources['3DVW'],"3DVW");

        }).fail(function(msg){
            console.log("Error retrieving the resources");
        });
    }

    function send_deployment(){
        console.log('------------');
        var all_res = true;

        var array_selects = [];
        $('.selectable').each(function(){
            var select = $(this);
            var parent = select.parent();

            //select.val(select.find('option').last().val());

            if(select.val() == ""){
                  parent.addClass("text-danger");
                  all_res = false;
                  return;
            }else parent.removeClass("text-danger");

            var idx = parent.attr('data-index');
            var proc_select = { resource_id:     ld.resources[idx].id,
                                instance_res_id: select.val()
                              };
            console.log(proc_select);
            array_selects.push(proc_select);
        });

        var tab_name    = $('#tab_name').val();
        var description = $('#description').val();
        console.log(array_selects);

        if(!all_res){alert('Unselected resources'); return;}
        if(tab_name == ""){alert('No name provided'); return;}

        console.log('sending');

        $.ajax({
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{url('/gamification/'.$gld->id.'/deploy/new')}}",
            data: {
                instance_type:  instance_type,
                instance_name:  instance_name,
                bearer:         bearer,
                course_name:    $('select#course_select').text(),
                course_id:      $('select#course_select').val(),
                resources:      array_selects,

                tab_name:       tab_name,
                description:    description
            },
        }).done(function(msg) {
             console.log(msg);
             window.location="{{URL::to('home')}}";
        }).fail(function(msg){
             console.log(msg);
        });
    }
</script>