<?php
session_start();
if (isset($_POST["register"])) {
    try {
        require_once 'db.php';


        // a new products collection object
        $collection = $db->users;

        // Create an array of values to insert
        $username = (isset($_POST["username"]) ? $_POST["username"] : $username = null);
        $password = (isset($_POST["password"]) ? md5($_POST["password"]) : $password = null);

        $email = (isset($_POST["email"]) ? $_POST["email"] : $email = null);
        $address = (isset($_POST["address"]) ? $_POST["address"] : $address = null);


        $user = array(
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'address' => $address
        );

        // insert the array
        $collection->insert($user);
    } catch (MongoConnectionException $e) {
        // if there was an error, we catch and display the problem here
        echo $e->getMessage();
    } catch (MongoException $e) {
        echo $e->getMessage();
    }
}


if (isset($_POST["login"])) {

    try {
        require_once 'db.php';
        $username = (isset($_POST["username"]) ? $_POST["username"] : $username = null);
        $password = (isset($_POST["password"]) ? md5($_POST["password"]) : $password = null);

        $collection = $db->users;
        $login = $collection->findOne(array("username" => $username, "password" => $password));
            if ($login) {
                $_SESSION["account"] = $login['_id'];
                $_SESSION["usernameLogin"] = $login['username'];
            } else {
                $_SESSION["account"] = null;
            }
        
    } catch (MongoConnectionException $e) {
        // if there was an error, we catch and display the problem here
        echo $e->getMessage();
    } catch (MongoException $e) {
        echo $e->getMessage();
    }
}



if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: account.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Account</title>
    <style type="text/css">
        .colorgraph {
            height: 7px;
            border-top: 0;
            background: #c4e17f;
            border-radius: 5px;
            background-image: -webkit-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
            background-image: -moz-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
            background-image: -o-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
            background-image: linear-gradient(to right, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
        }

        .btn-danger{
            background-color: hsl(351, 68%, 11%) !important;
            background-repeat: repeat-x;
            background-image: -o-linear-gradient(top, #d12741, #2f080e);
            background-image: linear-gradient(#d12741, #2f080e);
            border-color: #2f080e #2f080e hsl(351, 68%, 1.5%);
            color: #fff !important;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.62);
            }
        .btn-danger:hover, .btn-danger:focus, .btn-danger:active, .btn-danger.active, .open .dropdown-toggle.btn-danger {
            background-color: hsl(360, 68%, 60%) !important;
            background-repeat: repeat-x;
            background-image: linear-gradient(#fbeded, #de5353);
            border-color: #de5353 #de5353 hsl(360, 68%, 51%);
            color: #333 !important;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.59);
        }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/layoutku.css">
    <link rel="stylesheet" type="text/css" href="resources/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="index.php" class="navbar-brand"><img src="uploads/sinarjaya.svg" height="28" width="68" alt="SinarJaya"></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li><a class="active" href="index.php"><i class="fa fa-fw fa-home"></i>Home</a></li>
            <ul class="nav navbar-nav">
                <li><a href="account.php"><i class="fa fa-fw fa-user"></i> Account</a></li>

                <?php if (isset($_SESSION["account"])) { ?>
                    <li class="active"><a href="account.php"><i class="glyphicon glyphicon-shopping-cart"></i>Orders</a></li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">

                <?php if (isset($_SESSION["account"])) { ?>
                    <li><a href="account.php?logout"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<?php if (!isset($_SESSION["account"])) { ?>
    <div class="top-content">
            <div class="inner-bg">
                <div class="container">
                	
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <h1><strong>Toko Buku Sinar Jaya</strong> Login &amp; Register Forms</h1>
                            <div class="description">
                            	<p>
	                            	Ini adalah <strong>"laman form login dan register "</strong> pada <strong>Toko Buku</strong><a href="index.php" target="_blank"><strong> Sinar Jaya</strong></a> , 
	                            	Silahkan daftar dan Login untuk dapat berbelanja di toko kami.
                            	</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-5">
                        	
                        	<div class="form-box">
	                        	<div class="form-top">
	                        		<div class="form-top-left">
	                        			<h3>Login ke toko buku kami</h3>
	                            		<p>Isilah username dan password untuk masuk, apabila belum mendaftar, silahkan mendaftar terlebih dahulu!</p>
	                        		</div>
	                        		<div class="form-top-right">
	                        			<i class="fa fa-lock"></i>
	                        		</div>
	                            </div>
	                            <div class="form-bottom">
				                    <form role="form" method="post" class="login-form">
				                    	<div class="form-group">
				                    		<label class="sr-only" for="form-username">Username</label>
				                        	<input type="text" name="username" placeholder="Username..." class="form-username form-control" id="form-username">
				                        </div>
				                        <div class="form-group">
				                        	<label class="sr-only" for="form-password">Password</label>
				                        	<input type="password" name="password" placeholder="Password..." class="form-password form-control" id="form-password">
                                        </div>
                                        <input type="hidden" name="login" value="login">
				                        <button type="submit" class="btn">Login</button>
				                    </form>
			                    </div>
		                    </div>
		                
		                	<div class="social-login">
	                        	<h3>...Atau Login dengan:</h3>
	                        	<div class="social-login-buttons">
		                        	<a class="btn btn-link-2" href="#">
		                        		<i class="fa fa-facebook"></i> Facebook
		                        	</a>
		                        	<a class="btn btn-link-2" href="#">
		                        		<i class="fa fa-twitter"></i> Twitter
		                        	</a>
		                        	<a class="btn btn-link-2" href="#">
		                        		<i class="fa fa-google-plus"></i> Google Plus
		                        	</a>
	                        	</div>
	                        </div>
	                        
                        </div>
                        
                        <div class="col-sm-1 middle-border"></div>
                        <div class="col-sm-1"></div>
                        	
                        <div class="col-sm-5">
                        	
                        	<div class="form-box">
                        		<div class="form-top">
	                        		<div class="form-top-left">
	                        			<h3>Daftar Sekarang</h3>
	                            		<p>Isi data pendafataran dibawah ini untuk dapat berbelanja di toko kami!</p>
	                        		</div>
	                        		<div class="form-top-right">
	                        			<i class="fa fa-pencil"></i>
	                        		</div>
	                            </div>
	                            <div class="form-bottom">
				                    <form role="form" method="post" class="registration-form">
				                    	<div class="form-group">
				                    		<label class="sr-only" for="form-first-name">Username</label>
				                        	<input type="text" name="username" placeholder="Username..." class="form-first-name form-control" id="form-first-name">
				                        </div>
				                        <div class="form-group">
				                        	<label class="sr-only" for="form-last-name">Password</label>
				                        	<input type="password" name="password" placeholder="Password..." class="form-last-name form-control" id="form-last-name">
				                        </div>
				                        <div class="form-group">
				                        	<label class="sr-only" for="form-email">Email</label>
				                        	<input type="email" name="email" placeholder="Email..." class="form-email form-control" id="form-email">
				                        </div>
				                        <div class="form-group">
				                        	<label class="sr-only" for="form-about-yourself">Alamat Pengiriman:</label>
				                        	<textarea name="address" placeholder="Alamat pengiriman anda..." 
				                        				class="form-about-yourself form-control" id="form-about-yourself"></textarea>
                                        </div>
                                        <input type="hidden" name="register" value="register">
				                        <button type="submit" class="btn">Register</button>
				                    </form>
			                    </div>
                        	</div>	
                        </div>
                    </div> 
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer>
        	<div class="container">
        		<div class="row">
        			
        			<div class="col-sm-8 col-sm-offset-2">
        				<div class="footer-border"></div>
        				<p>Made By Toko Buku <a href="index.php" target="_blank"><strong>Sinar Jaya</strong></a> 
        					Selamat Berbelanja. <i class="fa fa-smile-o"></i></p>
        			</div>
        			
        		</div>
        	</div>
        </footer>
<?php } elseif (!isset($_GET["order"])) { ?>


    <?php
    try {
        require_once 'db.php';
        $collection = new MongoCollection($db, 'users');
        $sweetQuery = $collection->findOne(array('_id' => new MongoId($_SESSION["account"])));

        $cursor = $collection->find($sweetQuery);
        foreach ($cursor as $obj) {
            if (isset($obj["address"]))
                $address = $obj["address"];
        }
    } catch (MongoConnectionException $e) {
        // if there was an error, we catch and display the problem here
        echo $e->getMessage();
    } catch (MongoException $e) {
        echo $e->getMessage();
    } ?>

    <div class="container">
        <div class="jumbotron">
            <h2>Your address</h2>
            <p id="address" contenteditable="true"><?php echo $address; ?></p>
            <button class="btn btn-danger"id="updateAddress">Update Address</button>
        </div>
    </div>

    <div class="container">
        <h2>Your orders</h2>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Order ID</th>

                <th>Details</th>
            </tr>
            </thead>
            <tbody>
            <?php
            try {
                require_once 'db.php';
                $collection = new MongoCollection($db, 'orders');

                $something = $collection->find(array('user_id' => new MongoId($_SESSION["account"])));
                foreach ($something as $obj) {
                    ?>
                    <tr>
                        <td><?php echo $obj["_id"]; ?></td>
                        <td><a href="account.php?order=<?php echo $obj["_id"]; ?>">Order details</a></td>
                    </tr>
                <?php }    
            } catch (MongoConnectionException $e) {
                // if there was an error, we catch and display the problem here
                echo $e->getMessage();
            } catch (MongoException $e) {
                echo $e->getMessage();
            } ?>
     
            <?php
            try {
                require_once 'db.php';
                $collection = new MongoCollection($db, 'orders');

                $something = $collection->find(array('nama_user' => $_SESSION["usernameLogin"]));
                foreach ($something as $obj) {
                    ?>
                        <?php $obj["nama_user"]; ?>
                <?php }    
            } catch (MongoConnectionException $e) {
                // if there was an error, we catch and display the problem here
                echo $e->getMessage();
            } catch (MongoException $e) {
                echo $e->getMessage();
            } ?>

            </tbody>
        </table>
    </div>


    <?php } else { ?>
    <div class="container">
        <h2>Order details</h2>
        <ul id="basket_list" class="list-group">
            <?php
            try {
                require_once 'db.php';
                $collection = new MongoCollection($db, 'orders');
                $sweetQuery = $collection->findOne(array('_id' => new MongoId($_GET["order"])));

                $cursor = $collection->find($sweetQuery);

                foreach ($cursor as $obj) {
                    $ids = $obj["ids"];
                    $ids = explode(";", $ids);
                    $status = $obj["status"];
                }
            } catch (MongoConnectionException $e) {
                // if there was an error, we catch and display the problem here
                echo $e->getMessage();
            } catch (MongoException $e) {
                echo $e->getMessage();
            }


            $collection = new MongoCollection($db, 'products');

            $_ids = array();
            foreach ($ids as $separateIds) {
                $_ids[] = $separateIds instanceof MongoId ? $separateIds : new MongoId($separateIds);
            }
            $thisSearch = $collection->find(array('_id' => array('$in' => $_ids)));

            $how_many = array_count_values($ids);
            $how_status = $status;


            $price = 0;
            foreach ($thisSearch as $obj) {
                $id = $obj["_id"];
                $price += $obj["price"] * $how_many["$id"];
                ?>

                <li class="list-group-item"><?php echo $obj["title"] . ' x ' . $how_many["$id"]; ?></li>

                <?php
            }

            ?>
            <li class="list-group-item">Total Harga: Rp<?php echo $price; ?></li>
            <li class="list-group-item">Status:  <?php echo $status; ?></li>
        </ul>
    </div>
<?php } ?>


<?php
if (isset($_POST["u_address"])) {
    try {
        require_once 'db.php';
        $collection = new MongoCollection($db, 'users');
        // the array of user criteria
        $product_array = array(
            '_id' => $_SESSION["account"]
        );
        // fetch the Jackets record
        $document = $collection->findOne($product_array);
        // specify new values for Jackets
        $document['address'] = $_POST["u_address"];
        // save back to the database
        $collection->save($document);


    } catch (MongoConnectionException $e) {
        // if there was an error, we catch and display the problem here
        echo $e->getMessage();
    } catch (MongoException $e) {
        echo $e->getMessage();
    }

}
?>
</body>
<script>
    $(document).ready(function () {
        $("#updateAddress").click(function () {
            $.post("account.php",
                {
                    u_address: $('#address').html()

                },
                function (data, status) {
                    alert("Your address has been updated!");
                });
        });
    });
</script>
 <!-- Javascript -->
 <script src="assets/js/jquery-1.11.1.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.backstretch.min.js"></script>
        <script src="assets/js/scripts.js"></script>
</body>
</html>

