<!DOCTYPE html>
<html lang="en" class="full-height">

<head>
    @include('partials/head')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            // Add smooth scrolling to all links
            $("a").on('click', function(event) {

                // Make sure this.hash has a value before overriding default behavior
                if (this.hash !== "") {
                    // Prevent default anchor click behavior
                    event.preventDefault();

                    // Store hash
                    var hash = this.hash;

                    // Using jQuery's animate() method to add smooth page scroll
                    // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 1400, 'swing', function(){

                        // Add hash (#) to URL when done scrolling (default click behavior)
                        window.location.hash = hash;
                    });
                } // End if
            });
        });
    </script>
</head>
<body>

<header>
@include('partials/navbar')

<!-- Main container-->
    <div class="container">

        <div class="divider-new pt-5 wow fadeInDown">
            <h2 class="h2-responsive">1. Choose the Activity</h2>
        </div>

        <!--Section: Best features-->
            <div class="row pt-3">

                <!--First columnn-->
                <div class="col-lg-9 mb-r">
                    <!--Card-->
                    <div class="card wow fadeIn" data-wow-delay="0.2s">

                        <table class="table table-bordered " id="learning_design" style="margin-bottom: 0px">
                            <thead>
                            <tr>
                                <th>Block 1</th>
                                <th>Block 2</th>
                                <th>Block 3</th>
                                <th>Block 4</th>
                                <th>Block 5</th>
                                <th>Block 6</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)">Content Page</td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)">Content Page</td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)">Content Page</td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)">Content Page</td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)">Content Page</td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)">Content Page</td>
                            </tr>
                            <tr>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)">Quiz</td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)">Submission</td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)">Submission</td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)">Quiz</td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)">Quiz</td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)">Submission</td>
                            </tr>
                            <tr>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)"><p id="discussion_forum">Discussion Forum</p></td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)"><p id="discussion_forum">Discussion Forum</p></td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)"><p id="discussion_forum">Discussion Forum</p></td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)"><p id="discussion_forum">Discussion Forum</p></td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)"><p id="content_page">Content Page</p></td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)"><p id="content_page">Content Page</p></td>
                            </tr>
                            <tr>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)"><p id="content_page">Content Page</p></td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)"><p id="content_page">Content Page</p></td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)"><p id="content_page">Content Page</p></td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)"><p id="content_page">Content Page</p></td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)"><p id="content_page">Content Page</p></td>
                                <td onclick="OnSelectActivity(this)" onmouseover="HoverCell(this)" onmouseout="OutCell(this)"><p id="content_page">Content Page</p></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--/.Card-->
                    <div class="form" style="max-width:100%; padding-top: 10px; margin-top: 30px;">
                        <input type="checkbox" class="col-lg-1" id="design_and">AND</input>
                        <input type="checkbox" class="col-lg-1" id="design_or">OR</input>
                        <input type="checkbox" class="col-lg-1" id="design_all">ALL</input>
                        <input type="checkbox" class="col-lg-1" id="design_any">ANY</input>
                    </div>
                </div>
                <!--First columnn-->

                <!--Second columnn-->
                <div class="col-lg-3 mb-r">
                    <!--Card-->
                    <div class="card wow fadeIn">

                        <table class="table table-bordered" id="gamification_tab" style="margin-bottom: 0px">
                            <thead>
                            <tr><th><p>Gamification Info</p><div></div></th></tr>
                            </thead>
                            <tbody>
                            <tr><td ondrop="drop(event)"><p draggable="true" ondragstart="drag(event)" id="activity"></p></td></tr>
                            <tr><td ondrop="drop(event)"><p draggable="true" ondragstart="drag(event)" id="actions"></p></td></tr>
                            <tr><td ondrop="drop(event)"><p draggable="true" ondragstart="drag(event)" id=""></p></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <!--/.Card-->

                    <div class="form wow fadeInUp" style="margin-top:20px; padding:0; float: right">
                        <a href="#actions_div"><button style="width: 160px">Next Step</button></a>
                    </div>
                </div>
                <!--Second columnn-->

            </div>
            <div id="actions_div" class="container" style="transition: all 1s ease">

        <div class="divider-new pt-5 wow fadeInDown" style="margin-top: 0px; padding-top: 0px;">
            <h2 class="h2-responsive">2. Choose the Actions</h2>
        </div>


            <div class="row pt-3">

            <!--First columnn-->
            <div class="col-lg-3 mb-r">
                <!--Card-->
                <div class="card wow fadeIn">

                    <table class="table table-bordered" id="select_actions" style="margin-bottom: 0px">
                        <thead>
                        <tr><th><p>Actions</p><div></div></th></tr>
                        </thead>
                        <tbody>
                        <tr><td></td></tr>
                        </tbody>
                    </table>
                </div>
                <!--/.Card-->
                <div class="form" style="max-width:100%; padding-top: 10px; margin-top: 20px;">
                    <input type="checkbox" class="col-lg-2" id="design_and">AND</input>
                    <input type="checkbox" class="col-lg-2" id="design_or">OR</input>
                </div>
            </div>
            <!--First columnn-->

            <!--Second columnn-->
            <div class="col-lg-3 mb-r">

            </div>
            <!--Second columnn-->

            <!--Third columnn-->
            <div class="col-lg-3 mb-r" style="visibility: hidden">

            </div>
            <!--Third columnn-->

            <!--Fourth columnn-->
            <div class="col-lg-3 mb-r">
                <!--Card-->
                <div class="card wow fadeIn">

                    <table class="table table-bordered" id="gamification_tab" style="margin-bottom: 0px">
                        <thead>
                        <tr><th><p>Conditions Info</p><div></div></th></tr>
                        </thead>
                        <tbody>
                        <tr><td ondrop="drop(event)"><p draggable="true" ondragstart="drag(event)" id="activity"></p></td></tr>
                        <tr><td ondrop="drop(event)"><p draggable="true" ondragstart="drag(event)" id="actions"></p></td></tr>
                        <tr><td ondrop="drop(event)"><p draggable="true" ondragstart="drag(event)" id=""></p></td></tr>
                        </tbody>
                    </table>
                </div>
                <!--/.Card-->
                <div class="form wow fadeInUp" style="margin-top:20px; padding:0; float: right">
                    <a href="#conditions_div"><button style="width: 160px">Next Step</button></a>
                </div>
            </div>
            <!--Fourth columnn-->
            </div>

        </div>

            <!-- Container 3 -->
            <div id="conditions_div" class="container" style="transition: all 1s ease">

                <div class="divider-new pt-5 wow fadeInDown" style="margin-top: 0px; padding-top: 0px;">
                    <h2 class="h2-responsive">3. Choose the Conditions</h2>
                </div>

                <!--Section: Best features-->
                <section id="best-features">

                    <div class="row pt-3">
                        <!--First columnn-->
                        <div class="col-lg-3 mb-r">
                            <!--Card-->
                            <div class="card wow fadeIn">

                                <table class="table table-bordered" id="select_conditions" style="margin-bottom: 0px">
                                    <thead>
                                    <tr><th><p>Conditions</p><div></div></th></tr>
                                    </thead>
                                    <tbody>
                                    <tr><td></td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--/.Card-->
                            <div class="form" style="max-width:100%; padding-top: 10px; margin-top: 20px;">
                                <input type="checkbox" class="col-lg-2" id="design_and">AND</input>
                                <input type="checkbox" class="col-lg-2" id="design_or">OR</input>
                            </div>
                        </div>
                        <!--First columnn-->

                        <!--Second columnn-->
                        <div class="col-lg-3 mb-r">

                        </div>
                        <!--Second columnn-->

                        <!--Third columnn-->
                        <div class="col-lg-3 mb-r" style="visibility: hidden">

                        </div>
                        <!--Third columnn-->

                        <!--Fourth columnn-->
                        <div class="col-lg-3 mb-r">
                            <!--Card-->
                            <div class="card wow fadeIn">

                                <table class="table table-bordered" id="gamification_tab" style="margin-bottom: 0px">
                                    <thead>
                                    <tr><th><p>Conditions Configuration</p><div></div></th></tr>
                                    </thead>
                                    <tbody>
                                    <tr><td ondrop="drop(event)"><p draggable="true" ondragstart="drag(event)" id="activity"></p></td></tr>
                                    <tr><td ondrop="drop(event)"><p draggable="true" ondragstart="drag(event)" id="actions"></p></td></tr>
                                    <tr><td ondrop="drop(event)"><p draggable="true" ondragstart="drag(event)" id=""></p></td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--/.Card-->
                            <div class="form wow fadeInUp" style="margin-top:20px; padding:0; float: right">
                                <a href="#rewards_div"><button style="width: 160px">Next Step</button></a>
                            </div>
                        </div>
                        <!--Fourth columnn-->

                    </div>
                </section>
            </div>

            <!-- Container 4 -->
            <div id="rewards_div" class="container" style="transition: all 1s ease">

                <div class="divider-new pt-5 wow fadeInDown" style="margin-top: 0px; padding-top: 0px;">
                    <h2 class="h2-responsive">4. Choose the Rewards</h2>
                </div>

                <!--Section: Best features-->
                <section id="best-features">

                    <div class="row pt-3">
                        <!--First columnn-->
                        <div class="col-lg-3 mb-r">
                            <!--Card-->
                            <div class="card wow fadeIn">

                                <table class="table table-bordered table-hover" id="gamification_tab" style="margin-bottom: 0px">
                                    <thead>
                                    <tr><th><p>Rewards</p><div></div></th></tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reward_types as $reward_type)
                                    <tr><td id="{{$reward_type->name}}" onclick="Reward_Configuration(this)" style="cursor:pointer">{{$reward_type->name}}</td></tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--/.Card-->
                        </div>
                        <!--First columnn-->

                        <!--Second columnn-->
                        <div class="col-lg-9 mb-r">
                            <!--Card-->
                            <div class="card wow fadeIn">

                                <table class="table table-bordered" id="gamification_tab" style="margin-bottom: 0px">
                                    <thead>
                                    <tr><th><p>Reward Configuration</p><div></div></th></tr>
                                    </thead>
                                    <tbody>
                                    <tr><td style="white-space:nowrap;">
                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <label>Type:</label>
                                                <p id="reward_type" style="display:inline"></p>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 5px">
                                                <label>Name:</label>
                                                <input type="text" style="width:40%"/>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 5px">
                                                <label>Image:</label>
                                                <input type="file" />
                                            </div>
                                            <div class="form-group" style="margin-bottom: 5px">
                                                <label>Quantity:</label>
                                                <input type="number" style="width:10%"/>
                                            </div>
                                        </td></tr>
                                    <tr id="reward_conf_tr" style="display:none"><td><div id="reward_conf_div"></div></td></tr>
                                    <tr id="reward_conf_tr_2" style="display:none"><td><div id="reward_conf_div_2"></div></td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--/.Card-->
                            <div class="form wow fadeInUp" style="margin-top:20px; padding:0; float: right">
                                <button style="margin:5px; width: 160px">Add More Actions</button><br>
                                <button style="margin:5px; width: 160px">Finish Design </button>
                            </div>
                        </div>
                        <!--Fourth columnn-->

                    </div>
                </section>
            </div>

    </div>


        @include('partials/footer')

<!-- SCRIPTS -->

    <!-- JQuery -->
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script> <!-- <script type="text/javascript" src="/resources/assets/js/jquery-3.2.1.min.js"></script>

    <!-- Bootstrap dropdown -->
    <script src="{{ asset('js/popper.min.js') }}"></script> <!-- <script type="text/javascript" src="/resources/assets/js/popper.min.js"></script>

    <!-- Bootstrap core JavaScript -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script> <!-- <script type="text/javascript" src="/resources/assets/js/bootstrap.min.js"></script>

    <!-- MDB core JavaScript -->
    <script src="{{ asset('js/mdb.min.js') }}"></script> <!-- <script type="text/javascript" src="/resources/assets/js/mdb.min.js"></script> -->

    <style>
        p{
            margin-bottom:0;
        }
    </style>

    <script>

        function Reward_Configuration(reward_type){
            document.getElementById("reward_conf_tr_2").style.display='none';
            document.getElementById("reward_type").innerHTML = reward_type.id;
            var cell = document.getElementById("reward_conf_div");
            switch(reward_type.id){
                case "Badges":
                    document.getElementById("reward_conf_tr").style.display='block';
                    cell.innerHTML = "<div class='form-group' style='margin-bottom: 5px; size=100%'>" +
                        "               <label>Badge Suite Name:</label>" +
                        "               <select></select>" +
                        "             </div>" +
                        "             <div class='form-group' style='margin-bottom: 5px;'>" +
                        "               <label>Badge Suite Quality:</label>" +
                        "               <select></select>" +
                        "             </div>";
                    break;
                case "Levels":
                    document.getElementById("reward_conf_tr").style.display='none';
                    cell.innerHTML = "";
                    break;
                case "Points":
                    document.getElementById("reward_conf_tr").style.display='none';
                    cell.innerHTML = "";
                    break;
                case "Redeemable Rewards":
                    document.getElementById("reward_conf_tr").style.display='block';
                    var table = document.getElementById("learning_design").innerHTML;
                    table = table.replace(/OnSelectActivity/g,"OnSelectRR");
                    cell.innerHTML = "<p>Select the Resource where the Privilege will be applied:</p><table id='rr_design' class='table table-bordered'>" + table + "</table>";
                    break;
            }

        }

        function OnSelectRR(rr_type){
            switch(rr_type.innerHTML){
                case("Content Page"):
                    rr_string= ["Unlock Content","Extra Live"];
                    break;
                case("Discussion Forum"):
                    rr_string= ["Extent Deadline","Teacher Assistant"];
                    break;
                case("Quiz"):
                    rr_string= ["Teacher Evaluation"];
                    break;
                default:
                    break;
            }
            document.getElementById("reward_conf_tr_2").style.display='block';
            var cell = document.getElementById("reward_conf_div_2");
            cell.innerHTML = "Select the Privilege:<br>" + rr_string[0] + "<input type='text' />";
        }

        function ActionsString(activity_type){
            if (activity_type.innerHTML.includes("Content Page") == true){
                actions_string = ["View","Mark as read"];
            } else if(activity_type.innerHTML.includes("Discussion Forum") == true){
                actions_string = ["View","Post", "Answer", "Give like", "Receive like"];
            } else if(activity_type.innerHTML.includes("Wiki") == true){
                actions_string = ["View","Edit"];
            } else if(activity_type.innerHTML.includes("Submission") == true){
                actions_string = ["View","Submit"];
            }
            return actions_string;
        }
        function ConditionsString(action_type){
                conditions_string = ["1. Do the action itself","<i>No configuration needed</i>","2. Do the action several times","Times: <input type='number'>", "3. Do the action before a date", "Deadline: <input type='date'>", "4. Do the action between a time frame","Start date: <input type='date'>  End date: <input type='date'>", "5. Be one of the first doing the action", "Participants limit: <input type='date'>"];
            return conditions_string;
        }
        function OnSelectActivity(cell){
            cell.setAttribute("style", "border-color:red; border-width:thick");
            var actions_string = ActionsString(cell);
            var layout = "<thead><tr><th><p>Actions</p></th></tr></thead><tbody>";
            for (var i=0;i<actions_string.length;i++){
                layout = layout + "<tr><td onclick='OnSelectAction(this)'><p>" + actions_string[i] + "</p></td></tr>";
            }

            layout = layout + "</tbody>";
            document.getElementById("select_actions").innerHTML = layout;
        }
        function OnSelectAction(cell){
            cell.setAttribute("style", "border-color:red; border-width:thick");
            var conditions_string = ConditionsString(cell);
            var layout = "<thead><tr><th><p>Conditions</p></th></tr></thead><tbody>";
            for (var i=0;i<conditions_string.length;i++){
                if (i%2==0) {
                    layout = layout + "<tr><td id='condition_" + i + "' onclick='OnSelectCondition(this)'><p>" + conditions_string[i] + "</p></td></tr>";
                } else {
                    layout = layout + "<tr><td id='condition_" + i + "' style='display:none'><p>" + conditions_string[i] + "</p></td></tr>";
                }
            }
            layout = layout + "</tbody>";
            document.getElementById("select_conditions").innerHTML = layout;
        }
        function OnSelectCondition(cell){
            var num = cell.id.split("_");
            var id_display = num[0] + "_" + (parseInt(num[1]) + 1);
            if (cell.getAttribute("style") == "border-color:red; border-width:thick") {
                cell.setAttribute("style", "border: 1px solid #e9ecef;");
                document.getElementById(id_display).setAttribute("style", "display:none");
            } else {
                cell.setAttribute("style", "border-color:red; border-width:thick");
                document.getElementById(id_display).setAttribute("style", "display:block");
            }
        }
        function HoverCell(cell){

            cell.setAttribute("style", "background-color:rgba(0,0,0,.075); -webkit-transition:.5s; transition:.5s;");

            document.getElementById("activity").innerHTML = cell.innerHTML;
            var actions_string = ActionsString(cell);

            var layout = "";
            for (var i=0;i<actions_string.length;i++){
                layout = layout + actions_string[i] + "<br>";
            }
            document.getElementById("actions").innerHTML = layout;
        }
        function OutCell(cell){
            cell.setAttribute("style", "background-color:rgba(0,0,0,0); -webkit-transition:.5s; transition:.5s;");
        }
    </script>

    <!-- Animations init-->
    <script>
        new WOW().init();
    </script>


</body>

</html>