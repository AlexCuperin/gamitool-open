<script>
    var instance_type;
    var instance_name;
    var bearer;
    var modules;

    function get_courses(){

       instance_type = $('.select_platform.selected span').html();
       instance_name  = $('#site').val();
       bearer = $('#bearer').val();

        $.ajax({
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{url('/design/import/courses')}}",
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

    function get_resources(){

        $.ajax({
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{url('/design/import/resources')}}",
            data: {
                instance_type:  instance_type,
                instance_name:  instance_name,
                bearer:         bearer,
                course_id:      $('select#course_select').val()
            },
        }).done(function(modules_tmp) {
            console.log(modules_tmp);
            modules = modules_tmp;
            resources_loaded();

            $('#learning_design').html('<thead></thead> <tbody></tbody>');
            $('#learning_desing > thead').ready(function () {
                create_table(modules_tmp);
                populate_table(modules_tmp);
            });


        }).fail(function(msg){
            console.log("Error retrieving the resources");
            console.log(msg.toString());
        });
    }

    function save_import(){
        rows = 0;

        for(j=0; j<columns; j++) {
            if (modules[j]['resources'].length > rows){
                rows = modules[j]['resources'].length;
            }
        }

        console.log('sending');
        $.ajax({
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{url('/design/import/save')}}",
            data: {
                instance_type:    instance_type,
                instance_name:    instance_name,
                bearer:           bearer,

                course_name:      $('select#course_select option:selected').text(),
                course_id:        $('select#course_select').val(),
                modules:          modules,
                rows:             rows
            },
        }).done(function(msg) {
             console.log(msg);
            {{--window.location="{{URL::to('home')}}";--}}
            $('#import_success #modal-title').text('Course Successfully Imported!');
            $('#import_success #import_text1').text('The course importation was successfully completed. Your course is now available for new gamifications in ' +
                'your GamiTool Home Page.');

        }).fail(function(msg){
             console.log(msg);
            $('#import_success #modal-title').text('Oooops! Import Error');
            $('#import_success #import_text1').text('An error occurred during the importation of the course. Close this window and ' +
                'try it again. If this error persists, contact the administrator of the tool.');
        });
        $('#import_success').modal('show');
    }

</script>