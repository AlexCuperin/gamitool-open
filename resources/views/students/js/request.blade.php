<script>
    $('.request').click(function(){
        $.ajax({
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{url('/gamification')}}/"+gld.id+"/deploy/courses",
            data: {
                instance_name:      instance_name,
                bearer:             bearer
            },
        }).done(function(courses) {
            var options = '<option value="">Select a course..</option>';
            courses.forEach(function(course){
                options = options.concat('<option value="' + course.id + '">' + course.name + '</option>');
            });
            $('#course_select').html(options);

        }).fail(function(courses){
            console.log("Error retrieving the courses");
        });
    });

</script>