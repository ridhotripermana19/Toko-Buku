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


        $product = array(
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'address' => $address
        );

        // insert the array
        $collection->insert($product);


        // close connection to MongoDB
        $conn->close();

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
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://localhost/toko-buku/index.php">Sinar-Jaya</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li><a href="http://localhost/toko-buku/index.php">Home</a></li>
            <ul class="nav navbar-nav">
                
                <?php if (isset($_SESSION["account"])) { ?>
                    <li class="active"><a href="account.php">Orders</a></li>
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
    <div class="container">
        <h2>Login</h2>
        <form role="form" method="post">
            <div class="form-group">
                <label for="email">Username:</label>
                <input required name="username" type="text" class="form-control" id="email" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="email">Password:</label>
                <input required name="password" type="password" class="form-control" id="email"
                       placeholder="Password"></input>
            </div>
            <input type="hidden" name="login" value="login">
            <hr class="colorgraph">
            <button type="submit" class="btn btn-info btn-block">Login</button>
            <hr class="colorgraph">
            <p class="text-center">Belum Punya Akun? Silahkan Daftar Disini!</p>
            
        </form>
        <button type="register" class="btn btn-info btn-block" onClick="Javascript:window.location.href = 'http://localhost/toko-buku/account.php/';">Register</button>
            
    </div>
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
            <h2>Alamat</h2>
            <p id="address" contenteditable="true"><?php echo $address; ?></p>
            <button id="updateAddress">Update Address</button>
        </div>

    </div>

    <div class="container">
        <h2>Pemesanan</h2>
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


            $price = 0;
            foreach ($thisSearch as $obj) {
                $id = $obj["_id"];
                $price += $obj["price"] * $how_many["$id"];
                ?>

                <li class="list-group-item"><?php echo $obj["title"] . ' x ' . $how_many["$id"]; ?></li>


                <?php
            }

            ?>
            <li class="list-group-item">Total price: $<?php echo $price; ?></li>
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
</html>

