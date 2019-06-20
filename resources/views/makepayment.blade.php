<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <head>
          <title>Stripe Payment Gateway</title>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .red{
                color:red;
                }
            .form-area
            {
                background-color: #FAFAFA;
                padding: 10px 40px 60px;
                margin: 10px 0px 60px;
                border: 1px solid GREY;
            }
            .form-area .txtTitle{
                width: 24%;
                margin-bottom: 0;
                text-align: left;
            }
            .form-area input{
                width: 75%;
                display: inline-block;
                color: black;
                font-weight: 600;
            }
            .form-area select{
                width: 37%;
                display: inline-block;
                color: #666;
                font-weight: 600;
            }
            .form-area .confirmcheck{
                text-align:left;   
            }
            .form-area .confirmcheck label{
                width: auto;
                color: #636b6f;
                font-weight: 600;
                vertical-align: top;
            }
            .form-area .confirmcheck input{
                width: auto;
                height: auto;
                margin-top: 0;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
<!--                <div class="title m-b-md">
                    Demo
                </div>-->

            <div class="container">
                <div class="col-md-offset-3 col-md-6">
                    @if (session('status'))
                        <div class="alert alert-danger">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong>Failed!</strong> {{ session('status') }}
                        </div>
                    @endif
                </div>
                <div class="col-md-offset-3 col-md-6">
                    <div class="form-area">  
                        {{ Form::open(['url' => 'storepayment', 'class' => 'form-horizontal', 'id'=>'paymentform','role' => 'form', 'method' => 'post']) }}
                            <br style="clear:both">
                            <h3 style="margin-bottom: 25px; text-align: center;">Make Test Payment</h3>
                            <div class="form-group">
                                <label class="txtTitle">Order Id:</label>
                                <input type="text" class="form-control" id="order_id" name="order_id" placeholder="Order Id" value="<?=$data['order_id'];?>" readonly>
                            </div>
<!--                            <div class="form-group">
                                <label class="txtTitle">Product Name:</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Product Name">
                            </div>-->
                            <div class="form-group">
                                <label class="txtTitle">Order Description:</label>
                                <input type="text" class="form-control" id="order_desc" name="order_desc" placeholder="Order Description" value="{{ old('order_desc') }}">
                            </div>
                            <div class="form-group">
                                <label class="txtTitle">Amount ($):</label>
                                <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount" required value="{{ old('amount') }}" onkeypress="return isNumber(event)">
                            </div>
                            <div class="form-group">
                                <label class="txtTitle">Card Number:</label>
                                <input type="text" class="form-control" id="card_number" name="card_number" placeholder="Card Number" required maxlength="16" onkeypress="return isNumber(event)">
                            </div>
                            <div class="form-group">
                                <label class="txtTitle">Expire Date:</label>
                                <select id="exp_month" name="exp_month" class="form-control" required>
                                    <option value="">Select month</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                                <select id="exp_year" name="exp_year" class="form-control">
                                    <option value="">Select year</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>

                            </div>

                            <div class="form-group">
                                <label class="txtTitle">CVV:</label>
                                <input type="text" class="form-control" id="cvv" name="cvv" placeholder="CVV" required maxlength="3" onkeypress="return isNumber(event)">
                            </div>
                            <div class="form-group confirmcheck">
                                <label class="txtTitle">Want to save card details for future use ?:</label>
                                <input type="checkbox" class="form-control" name="is_check" id="is_check" value="Yes">
                            </div>
                            <a href="/home"><button type="button" class="btn btn-primary pull-left">Back To Dashboard</button></a>
                            <input type="hidden" name="product_id" id="product_id" value="1">
                            <button type="submit" id="submit" name="submit" class="btn btn-primary pull-right">Pay</button>
                       {{ Form::close() }}
                    </div>
                </div>
            </div>
                
                
                
                
                
            </div>
        </div>
    </body>
</html>

<script>
$(document).ready(function(){ 
    $('#characterLeft').text('140 characters left');
    $('#message').keydown(function () {
        var max = 140;
        var len = $(this).val().length;
        if (len >= max) {
            $('#characterLeft').text('You have reached the limit');
            $('#characterLeft').addClass('red');
            $('#btnSubmit').addClass('disabled');            
        } 
        else {
            var ch = max - len;
            $('#characterLeft').text(ch + ' characters left');
            $('#btnSubmit').removeClass('disabled');
            $('#characterLeft').removeClass('red');            
        }
    });    
});

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>