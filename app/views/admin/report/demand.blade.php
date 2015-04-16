@extends('admin.layouts.default')

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

                        <h4> Request : {{$total_request}} </h4>
                        <h4> SKUs    : {{$total_sku}}  </h4>
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

                    <div class="form-group">
                        <label for="merchant_city_id">Total Count By : Stage</label>
                        <div>
                           {{Form::select('stage_id', $stage, '', array('class' => 'form-control', 'onChange'=>'getStage();'))}}
                        </div>
                    </div>
                    <h3>{{$total_catlog}}
                        {{--<sup style="font-size: 20px">%</sup>--}}
                    </h3>
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
                    <div class="form-group">
                        <label for="merchant_city_id">Total Count By : Status</label>
                        <div>
                           {{Form::select('status_id', $status, '', array('class' => 'form-control', 'onChange'=>'getStatus();'))}}
                        </div>
                    </div>
                    <h3>{{$total_status}}
                        {{--<sup style="font-size: 20px">%</sup>--}}
                    </h3>
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
                    <div class="form-group">
                        <label for="merchant_city_id">Active Count By Role </label>
                        <div>
                           {{Form::select('role_id', $role, '', array('class' => 'form-control', 'onChange'=>'getRole();'))}}
                        </div>
                    </div>
                    <h3>{{$total_status}}
                        {{--<sup style="font-size: 20px">%</sup>--}}
                    </h3>
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