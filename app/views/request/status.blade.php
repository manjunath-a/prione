<!-- app/views/request-form.blade.php -->
<!doctype html>
<html>
<head>
    <title>Request Form</title>

    <!-- load bootstrap -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <style>
        body    { padding-bottom:40px; padding-top:40px; }
    </style>
</head>
<body class="container">

<div class="row">
    <div class="col-sm-8 col-sm-offset-2">

        <div class="page-header">
            <h1><span class="glyphicon glyphicon-flash"></span> Requester Status</h1>
        </div>
        <div class="form-group">
            <label for="contact_number">Tickect Status</label>
            {{ Form::text('ticket_id', null, array('class' => 'form-control')) }}
        </div>
        {{--<div class="alert alert-success alert-block">--}}
            {{--<h4>Success</h4>--}}
                 {{--Ticket :: #{{ $ticketid }} Created successfully.--}}
        {{--</div>--}}

    </div>
</div>

</body>
</html>
