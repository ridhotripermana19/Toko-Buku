<?php
session_start();
if ($_SESSION["account"] != "5deff0702d75ea7000000039") {
    header("Location: ../index.php");
}

if (isset($_GET["delete"])) {
    try {
        require_once '../db.php';
        $id = $_GET["delete"];
        $collection = $db->orders;
        $collection->remove(array('_id' => new MongoId($id)));
        header("Location: manage_products.php");
    } catch (MongoConnectionException $e) {
        // if there was an error, we catch and display the problem here
        echo $e->getMessage();
    } catch (MongoException $e) {
        echo $e->getMessage();
    }
}
if (isset($_POST["status"])) {
    try {
        require_once '../db.php';

        // a new products collection object
        $collection = $db->orders;


        // Create an array of values to insert
        $user_id = (isset($_POST["user_id"]) ? $_POST["user_id"] : $user_id = '0');
        $nama_user = (isset($_POST["nama_user"]) ? $_POST["nama_user"] : $nama_user = '0');
        $ids = (isset($_POST["ids"]) ? $_POST["ids"] : $ids = '0');
        $status = (isset($_POST["status"]) ? $_POST["status"] : $status = '0');


        if (isset($_POST["update"])) {

            $product_array = array(
                '_id' => new MongoId($_POST["update"])
            );

            // fetch the Jackets record
            $document = $collection->findOne($product_array);
            // specify new values for Jackets
            $document['status'] = $status;
            $collection->save($document);

            header("Location: manage_order.php");
        }

        // close connection to MongoDB
        $conn->close();

    } catch (MongoConnectionException $e) {
        // if there was an error, we catch and display the problem here
        echo $e->getMessage();
    } catch (MongoException $e) {
        echo $e->getMessage();
    }


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sinar-Jaya Administration</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style>
        .container {
            margin-top: 20px;
        }

        .image-preview-input {
            position: relative;
            overflow: hidden;
            margin: 0px;
            color: #333;
            background-color: #fff;
            border-color: #ccc;
        }

        .image-preview-input input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            padding: 0;
            font-size: 20px;
            cursor: pointer;
            opacity: 0;
            filter: alpha(opacity=0);
        }

        .image-preview-input-title {
            margin-left: 2px;
        }
        body {
                /* Location of the image */
                background-image: url(1.jpg);
                
                /* Background image is centered vertically and horizontally at all times */
                background-position: center center;
                
                /* Background image doesn't tile */
                background-repeat: no-repeat;
                
                /* Background image is fixed in the viewport so that it doesn't move when 
                the content's height is greater than the image's height */
                background-attachment: fixed;
                
                /* This is what makes the background image rescale based
                on the container's size */
                background-size: cover;
                
                /* Set a background color that will be displayed
                while the background image is loading */
                background-color: #464646;
         }
         a {
            color: #d12741;
            text-decoration: none;
        }
    </style>
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
            <a href="../index.php" class="navbar-brand"><img src="sinarjaya.svg" height="28" width="68" alt="SinarJaya"></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class="active"><a color="#d12741" href="../index.php"><i class="fa fa-fw fa-home"></i>Home</a></li>
                <li class="active"><a color="#d12741" href="manage_products.php"><i class="glyphicon glyphicon-shopping-cart"></i>Manage products</a></li>
                <?php if (isset($_SESSION["account"])) { ?>
                    <li class="active"><a href="manage_order.php?order"><i class="glyphicon glyphicon-shopping-cart"></i>Orders</a></li>
                <?php } else { ?>
                    <li class="active"><a href="../account.php">Login</a></li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">

                <?php if (isset($_SESSION["account"])) { ?>
                    <li><a href="../account.php?logout"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>


<div class="container">
    <h2>Manage Orders</h2>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID Order</th>
            <th>ID User</th>
            <th>Nama User</th>
            <th>ID BUKU</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        try {
            require_once '../db.php';
            $collection = new MongoCollection($db, 'orders');

            $something = $collection->find();
            foreach ($something as $obj) {
                $status = $obj["status"];

                if (strlen($status) > 20)
                    $status = substr($status, 0, 17) . '...';
                ?>
                <tr>
                    <td><?php echo $obj["_id"]; ?></td>
                    <td><?php echo $obj["user_id"]; ?></td>
                    <td><?php echo $obj["nama_user"]; ?></td>
                    <td><?php echo $obj["ids"]; ?></td>
                    <td><?php echo $status; ?></td>
                    <td>
                        <a class="btn btn-warning"
                           href="manage_order.php?update=<?php echo $obj["_id"]; ?>">Update</a>
                        <a class="btn btn-danger"
                           href="manage_order.php?delete=<?php echo $obj["_id"]; ?>">Delete</a>
                    </td>

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

<?php if (isset($_GET["update"])) {

    require_once '../db.php';
    $collection = new MongoCollection($db, 'orders');

    $something = $collection->findOne(array('_id' => new MongoId($_GET["update"])));
    $cursor = $collection->find($something);
    foreach ($cursor as $obj) {
        $id = $obj["_id"];
        $status = $obj["status"];
    }

    ?>
    <div class="container">
        <div class="row">
            <h2>Update product</h2>
            <form role="form" method="post" enctype="multipart/form-data">
                <div class="form-group">

                <div class="form-group">
                    <label for="email">Status:</label>
                    <textarea required name="status" type="text" class="form-control" id="email"
                      placeholder="Status Order"><?php echo $status; ?></textarea>
                </div>

                <div class="col-xs-12 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                </div>
                <input type="hidden" name="update" value="<?php echo $id; ?>">
        </div>
        <button type="submit" class="btn btn-default">Simpan</button>
        </form>
    </div>
<?php } ?>
<script>
    $(document).on('click', '#close-preview', function () {
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function () {
                $('.image-preview').popover('show');
            },
            function () {
                $('.image-preview').popover('hide');
            }
        );
    });

    $(function () {
        // Create the close button
        var closebtn = $('<button/>', {
            type: "button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class", "close pull-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger: 'manual',
            html: true,
            title: "<strong>Preview</strong>" + $(closebtn)[0].outerHTML,
            content: "There's no image",
            placement: 'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function () {
            $('.image-preview').attr("data-content", "").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("Browse");
        });
        // Create the preview image
        $(".image-preview-input input:file").change(function () {
            var img = $('<img/>', {
                id: 'dynamic',
                width: 250,
                height: 200
            });
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("Change");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
                img.attr('src', e.target.result);
                $(".image-preview").attr("data-content", $(img)[0].outerHTML).popover("show");
            }
            reader.readAsDataURL(file);
        });
    });
</script>
</body>
</html>

