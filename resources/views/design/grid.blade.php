<!-- Main container-->
<div class="container">

    <a href="{{ route('home') }}" style="font-size: large;"><i class="fa fa-arrow-circle-left" aria-hidden="true" style="margin-top:80px;margin-bottom:-80px;"></i>&nbsp;Return to Home Page</a>

    <div class="divider-new pt-5 wow fadeInDown" style="margin-top: -50px;">
        <h2 class="h2-responsive">Course Design</h2>
    </div>
    <div id="top_banner_msg" class="alert alert-dismissable wow fadeIn" style="display: none; margin-top: -20px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <span></span>
    </div>

    <!--Section: Best features-->
    <section id="best-features">

        <div class="card wow fadeIn">
            <table class="table table-bordered" style="margin-bottom: 0px;">
                <thead>
                <tr style="white-space:nowrap; ">
                    <th style="padding-top:0.5em; padding-bottom:0.5em">
                        Course Name:
                        <input id="course_name" name="course_name" type="text"   value="" style="width: 990px;"/>
                        <input id="course_id"   name="course_id"   type="hidden" value="" />
                    </th>
                </tr>
                </thead>
            </table>
        </div>

        <div class="row pt-3">

            <!--First columnn-->
            <div class="col-lg-3 mb-r">

                <!--Card-->
                <div class="card wow fadeIn">

                    <table class="table table-bordered table-hover" style="margin-bottom: 0px">
                        <thead>
                            <tr><th>Resources<div></div></th></tr>
                        </thead>
                        <tbody>
                            @foreach($rtypes as $resource_type)
                                <tr><td ondrop="drop(event)"><p style="margin-bottom:0" draggable="true" ondragstart="drag(event)" id="{{$resource_type->name}}">{{$resource_type->name}}</p></td></tr>
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
                <div class="card wow fadeIn" data-wow-delay="0.2s">
                    <table class="table table-bordered" id="learning_design">
                        <thead></thead>
                        <tbody></tbody>
                    </table>

                    <div class="form" style="max-width:100%; padding-top: 10px;">
                        <button class="col-lg-3" onclick="CreateRow()" style="width: 120px; height: 30px; padding: 5px; background-color: #007E33" >Add Row</button>
                        <button class="col-lg-3" onclick="RemoveRow()" style="width: 120px; height: 30px; padding: 5px; background-color: darkred">Remove Row</button>
                        <button class="col-lg-3" onclick="CreateCol()" style="width: 120px; height: 30px; padding: 5px; background-color: #007E33">Add Module</button>
                        <button class="col-lg-3" onclick="RemoveCol()" style="width: 120px; height: 30px; padding: 5px; background-color: darkred">Remove Module</button>
                        <button class="col-lg-3" style="width: 120px; height: 30px; visibility:hidden"></button>
                        <button class="col-lg-3" onclick="CleanTable()" style="width: 120px; height: 30px; padding: 5px; background-color: grey">Clean Table</button>
                    </div>
                </div>
                <!--/.Card-->

                <div class="form wow fadeInUp" style="margin-top:20px; padding:0; float: right;">
                    @include('utils.btn_submit', ['btn_utils_id' => 'btn_save_table', 'btn_utils_text' => 'Save Design'])
                </div>
            </div>
            <!--Second columnn-->
        </div>
    </section>
    <!--/Section: Best features-->
</div>