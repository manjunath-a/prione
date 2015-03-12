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
            <h1><span class="glyphicon glyphicon-flash"></span> Requester Form</h1>
        </div>

        <!-- FORM STARTS HERE -->
        <form method="POST" action="/request" novalidate>

            {{--<div class="page-header">--}}
                {{--<h2>Requester Details</h2>--}}
            {{--</div>--}}

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" name="name" placeholder="Your Name">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control" name="email" placeholder="xxxxxx@example.com">
            </div>

            <div class="form-group">
                <label for="contactnumber">Contact Number</label>
                <input type="text" id="contactnumber" class="form-control" name="contactnumber" placeholder="+91-1234567890">
            </div>

            <div class="page-header">
                <h2>Merchant Details</h2>
            </div>

            <div class="form-group">
                <label for="name">Merchant Name</label>
                <input type="text" id="name" class="form-control" name="merchantname" placeholder="Your Name">
            </div>

            <div class="form-group">
                <label for="merchantid">Merchant ID</label>
                <input type="text" id="merchantid" class="form-control" name="merchantid" >
            </div>

            <div class="form-group">
                <label for="merchantcity">Merchant city</label>
                <div>
                    <?php
                        echo Form::select('Bangalore',
                            array('1' => 'Chennai',
                                '2' => 'Bangalore',
                                '3' => 'Coimbatore',
                                '4' => 'Cochin'), '', array('class' => 'form-control'));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="merchantchannel">Merchant Sales Channel</label>
                <div>
                    <?php
                    echo Form::select('Portal', array('1' => 'Portal'), '1', array('class' => 'form-control'));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="pocname">POC Name</label>
                <input type="text" id="pocname" class="form-control" name="pocname" placeholder="Your Name">
            </div>

            <div class="form-group">
                <label for="pocemail">POC Email</label>
                <input type="email" id="pocemail" class="form-control" name="pocemail" placeholder="xxxxxx@example.com">
            </div>

            <div class="form-group">
                <label for="poccontactnumber">POC Contact Number</label>
                <input type="text" id="poccontactnumber" class="form-control" name="poccontactnumber" placeholder="+91-1234567890">
            </div>

            <div class="page-header">
                <h2>Request Details</h2>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <div>
                    <?php
                        echo Form::select('Books', array('1' => 'Books'), '1', array('class' => 'form-control'));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="sku">Number of SKU</label>
                <input type="number" id="sku" class="form-control" name="sku">
            </div>


            <div class="form-group">
                <label for="category">Images Available</label>
                <div>
                    <?php
                    echo Form::select('No', array('1' => 'No','2' => 'Yes'), '1', array('class' => 'form-control'));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="comment">Comment</label>
                <textarea  id="comment" class="form-control" name="comment" rows="4" cols="50">
                </textarea>
            </div>

            <button type="submit" class="btn btn-success">Submit</button>

        </form>

    </div>
</div>

</body>
</html>
