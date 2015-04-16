@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
    {{{ Lang::get('user/user.settings') }}} ::
    @parent
@stop

{{-- New Laravel 4 Feature in use --}}
@section('styles')
    @parent
    body {
    background: #f2f2f2;
    }
    .row{
    font-color : black;
    }
@stop

{{-- Content --}}
@section('content')
    <div class="page-header">
        <h3>Report </h3>
    </div>
    <div id="myMessage" role="alert"> </div>
    <!-- ./ content -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">

                        <h4> Request : 150 </h4>
                        <h4> SKUs    : 150 </h4>
                    <p><h3>Total Demand</h3></p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                {{--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>53
                        {{--<sup style="font-size: 20px">%</sup>--}}
                    </h3>
                    <p>Catalog Completed(resolved)</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                {{--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>44</h3>
                    <p>Open Request</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                {{--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>65</h3>
                    <p>Asin Created(Closed)</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                {{--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
            </dsiv>
        </div><!-- ./col -->
    </div>
    </div>
@stop