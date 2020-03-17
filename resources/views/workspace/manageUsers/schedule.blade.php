@extends('layouts.layout')

@section('title')
    Schedule
@endsection

@section('links')
    <link rel="stylesheet" href="{{asset('css/jquery.datetimepicker.css')}}">
    <script src="{{asset('js/jquery.datetimepicker.js')}}"></script>
    <script src="{{asset('js/jquery.datetimepicker.full.min.js')}}"></script>
    <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <script src="{{asset('js/manageUsers/schedule.js')}}"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
@endsection

@section('content')

    <div class="main-content">
        <!-- header area start -->
        <div class="header-area">
            <div class="row align-items-center">
                <!-- nav and search button -->
                <div class="col-md-6 col-sm-8 clearfix">
                    <div class="nav-btn pull-left">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="search-box pull-left">
                        <form id="searchForm" method="GET" >
                            <input id="searchUser" type="text" name="search" placeholder="Search by name or username..." required>
                            <i class="ti-search"></i>
                        </form>
                    </div>
                </div>
                <div class="col-md-6 col-sm-4 clearfix">
                    <ul class="notification-area pull-right">
                        <li id="full-view"><i class="ti-fullscreen"></i></li>
                        <li id="full-view-exit"><i class="ti-zoom-out"></i></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="page-title-area">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="breadcrumbs-area clearfix m-3">
                        <h4 class="page-title pull-left">Schedule Of Staffs</h4>
                        <ul class="breadcrumbs pull-left">
                            <li><a href="{{url('/')}}">Home</a></li>
                            <li><span>schedule</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content-inner">
            <div class="container-fluid">
                <div class="row">
                    <div class=" col-12">
                        <main role="main" class="mt-1">
                            <div class=" mt-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                                <h1 class="h2">Today</h1>
                                <button  id="newScheduleButton"  class="btn btn-outline-primary">
                                    <i class="ti-plus"></i>
                                </button>
                            </div>
                            <div class="table-responsive ">
                                <div class="modal fade" id="newScheduleModal" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">New Schedule</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" id="newScheduleForm">
                                                    @csrf
                                                    <div class="form-group mt-2">
                                                        <label for="name">Username</label>
                                                        <select name="usernameNew" class="form-control" id="usernameNew">

                                                        </select>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="start">Start time</label>
                                                        <input  value="" name="startNew" type="text" class="form-control" id="startNew" placeholder="Start time">
                                                    </div>
                                                    <div class="form-group mt-2" data-provide="datepicker">
                                                        <label for="end">End time</label>
                                                        <input  value="" name="endNew" type="text" class=" form-control" id="endNew" placeholder="End time">
                                                    </div>
                                                    <div class="form-group mt-2" data-provide="datepicker">
                                                        <label for="totaltime">Total time</label>
                                                        <input value="0" name="totaltimeNew" type="text" class="form-control" id="totaltimeNew" placeholder="Total time" readonly>
                                                    </div>
                                                    <div class="form-group mt-2" data-provide="datepicker">
                                                        <label for="date">Date</label>
                                                        <input value="" name="dateNew" type="text" class="form-control" id="dateNew" placeholder="Date">
                                                    </div>
                                                    <div class="modal-footer mt-4" data-provide="datepicker">
                                                        <button type="submit" class="btn btn-primary">Create</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $('#startNew').attr('autocomplete','off');
                                                    $('#startNew').datetimepicker({
                                                        timepicker : true,
                                                        datepicker : false,
                                                        step: 15,
                                                        minTime: '05:00',
                                                        format: 'H:i',
                                                        theme: "dark",
                                                        onShow: function(ct){
                                                            this.setOptions({
                                                                maxTime: $('#endNew').val() ?  $('#endNew').val()-15 : '23:00'
                                                        })
                                                        }
                                                    }).on("change", function() {
                                                        if ($('#endNew').val()) {
                                                            var time =moment($('#endNew').val(),"HH:mm").diff(moment( $('#startNew').val(),"HH:mm"),'hours', true );
                                                            $('#totaltimeNew').val(time);
                                                        }
                                                    });

                                                    $('#endNew').attr('autocomplete','off');
                                                    $("#endNew").datetimepicker({
                                                        timepicker : true,
                                                        datepicker : false,
                                                        step: 15,
                                                        format: 'H:i',
                                                        theme: "dark",
                                                        maxTime: '23:00',
                                                        onShow: function(ct){
                                                           this.setOptions({
                                                                minTime: $('#startNew').val() ? $('#startNew').val()+15 : '05:00'
                                                            })
                                                        }
                                                    }).on("change", function() {
                                                        var time =moment($('#endNew').val(),"HH:mm").diff(moment( $('#startNew').val(),"HH:mm"),'hours', true );
                                                        $('#totaltimeNew').val(time);
                                                    });

                                                    $('#dateNew').attr('autocomplete','off');
                                                    $("#dateNew").datetimepicker({
                                                        yearStart: 2020,
                                                        yearEnd: 2100,
                                                        timepicker : false,
                                                        datepicker : true,
                                                        format: 'd-m-Y',
                                                        theme : 'dark',
                                                        onShow: function(ct) {
                                                            this.setOptions({
                                                                minDate: 'today'
                                                            })
                                                        }
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Date</th>
                                        <th>Total time</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody  id="listSchedule">

                                    </tbody>
                                </table>
                                <div class="text-center mb-2"  id="loading" style="display: none;">
                                    <img src="{{asset("images/loading.gif")}}" alt="loading..." style="margin-bottom: 70px">
                                </div>
                                <div id="seeMore" class="text-center" style="display: none; ">
                                    <button id= "moreNewPosts"class="btn btn-primary w-50" style="margin-bottom: 70px">
                                        See more
                                        <i class="ti-arrow-circle-down"></i>
                                    </button>
                                </div>
                            </div>
                        </main>
                    </div>
                </div>
                <hr>
                <div class="row mt-5">
                    <div class="col-12">
                        <main role="main" class="mt-1">
                            <div class=" mt-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                                <h1 class="h2">Schedule Fillter</h1>
                                <button  id="exportScheduleFillter"  class="btn btn-outline-primary float-right">
                                    Export
                                    <i class="ti-notepad"></i>
                                </button>
                            </div>
                            <div class="mb-2">
                                <form class="form-inline d-inline-block">
                                    <label class="sr-only" for="fromFillter">From</label>
                                    <input value="" type="text" class="form-control mb-2 mr-sm-2" id="fromFillter" placeholder="From">

                                    <label class="sr-only" for="toFillter">To</label>
                                    <input value="" type="text" class="form-control mb-2 mr-sm-2" id="toFillter" placeholder="To">
                                </form>
                                <script>
                                    $('#fromFillter').attr('autocomplete','off');
                                    $("#fromFillter").datetimepicker({
                                        yearStart: 2020,
                                        yearEnd: 2100,
                                        timepicker : false,
                                        datepicker : true,
                                        format: 'd-m-Y',
                                        theme : 'dark',
                                        onShow: function(ct){
                                            this.setOptions({
                                                maxDate: $('#toFillter').val() ? $('#toFillter').val() : false
                                            })
                                        }
                                    }).on("change", function() {
                                        if ($('#toFillter').val()) {
                                            loadListScheduleFillter();
                                        }
                                    });

                                   $('#toFillter').attr('autocomplete','off');
                                   $("#toFillter").datetimepicker({
                                       yearStart: 2020,
                                       yearEnd: 2100,
                                       timepicker : false,
                                       datepicker : true,
                                       format: 'd-m-Y',
                                       theme : 'dark',
                                       onShow: function(ct){
                                           this.setOptions({
                                               minDate: $('#fromFillter').val() ? $('#fromFillter').val() : false
                                           })
                                       }
                                   }).on("change", function() {
                                       if ($('#fromFillter').val()) {
                                           loadListScheduleFillter();
                                       }
                                   });


                                </script>
                            </div>


                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Date</th>
                                    <th>Total time</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody  id="listScheduleFillter">

                                </tbody>
                            </table>

                        </main>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{--Modal--}}
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-2"  id="loading_modal" style="display: none;">
                        <img src="{{asset("images/loading.gif")}}" alt="loading..." style="margin-bottom: 190px; margin-top: 187px">
                    </div>

                    <form id="form_modal">

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
