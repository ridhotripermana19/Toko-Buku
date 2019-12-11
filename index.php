<?php
//===================================== Toko Buku Sinar Jaya ===================================================
//===================================== Created By Triwibowo ===================================================
//===================================== Back-End Toko        ===================================================

//lanjut atau buat session
session_start();
//exception handling
try {
    // import file koneksi db.php
    require_once 'db.php';
    // memilih collection products
    $collection = $db->products;
    //Pencarian dengan method $_GET & Regex untuk membuat regular expression (kasus disini query untuk mencari string yang matching)
    if (isset($_GET["search_cat"]) && isset($_GET["keyword"])) {
        $keyword = $_GET["keyword"];
        $value = $_GET["search_cat"];
        $query = array($value => array('$regex' => new MongoRegex("/$keyword/i")));
        $cursor = $collection->find($query);
    } else {
        // fetch semua data product
        $cursor = $collection->find();
    }
    // Berapa banyak data ditemukan, jika tidak ada yang ditemukan maka akan memberi null.
    $num_docs = $cursor->count();

// penanganan masalah dengan catch MongoConnectionException (permasalahan koneksi ke mongodb)
} catch (MongoConnectionException $e) {
    // jika ada error tampilkan pesan.
    echo $e->getMessage();
// penanganan masalah dengan catch  (penanganan berbagai kondisi error)
} catch (MongoException $e) {
    // jika ada error tampilkan pesan.
    echo $e->getMessage();
}
?>

<?php 
//===================================== Front-End Toko        ===================================================   ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Toko Buku Sinar Jaya</title>

    <!--Bagian Css untuk class colorgraph (tepatnya digunakan untuk tag <hr> atau baris) -->
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
    <!--Style Toko Menggunakan Bootstrap, jquery, dan font-awesome -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link href="styleku.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <script src="resources/js/script.js"></script>
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
                <li class="active"><a color="#d12741" href="index.php"><i class="fa fa-fw fa-home"></i>Home</a></li>
                <?php 
                    //Pembuatan SESSION account
                    if (isset($_SESSION["account"])) { 
                ?> 
                    <li  class="active"><a href="account.php"><i class="fa fa-fw fa-user"></i>Orders</a></li>
                <?php } else { ?>
                    <li class="active"><a href="account.php"><i class="fa fa-fw fa-user"></i>Account</a></li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">

                <?php 
                    //Pembuatan SESSION account
                    if (isset($_SESSION["account"])) { 
                ?>      <!--membuat link pada list logout yang ketika di klik akan memanggil session logout pada file account.php -->
                        <li><a href="account.php?logout"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="well well-sm">
        <strong>Display</strong>
        <div class="btn-group">
            <a href="#" id="list" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-th-list">
            </span>List</a> <a href="#" id="grid" class="btn btn-danger btn-sm"><span
                    class="glyphicon glyphicon-th"></span>Grid</a>
        </div>
        <a id="addToCart" class="btn btn-danger btn-md" href="cart.php"><span
                class="glyphicon glyphicon-shopping-cart btn-md"
                id="basket"></span> Buku di Keranjang</a>
    </div>
  <!--Conditional Exception if jika id telah didapat ($_GET) (isset selalu mengembalikan nilai false ketika dicheck menambahkan ! akan mengembalikan nilai true) -->
    <?php if (!isset($_GET["id"])) { ?>
        <div class="row">
            <form action="index.php" method="get">
                <div class="form-group pull-right">
                    <label for="sel1">Choose category</label>
                    <select name="search_cat" class="form-control" id="sel1">
                        <!-- Option Value Untuk Pencarian -->
                        <option value="title">Nama Buku</option>
                        <option value="author">Penulis Buku</option>
                        <option value="description">Deskripsi Buku</option>
                        <option value="tanggal-terbit">Tanggal Terbit Buku</option>
                        <option value="isbn">ISBN Buku</option>
                        <option value="penerbit">Penerbit Buku</option>
                        <option value="halaman">Halaman Buku</option>
                        <option value="price">Harga Buku</option>
                    </select>
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control" placeholder="Search for...">
      <span class="input-group-btn">
        <button class="btn btn-danger" type="submit">Go!</button>
      </span>
                    </div><!-- /input-group -->
                </div>

            </form>
        </div>
    <?php } else { ?>
        <a onclick="window.history.back();" href="#" class="btn btn-danger btn-lg">
            <span class="glyphicon glyphicon-arrow-left"></span> Back
        </a>
    <?php } ?>
<!--Conditional Exception if jika id telah didapat ($_GET) (isset selalu mengembalikan nilai false ketika dicheck menambahkan ! akan mengembalikan nilai true) -->
    <?php if (!isset($_GET["id"])) { ?>
        <div id="products" class="row list-group">
            <?php
            if ($num_docs > 0) {
                // mengulang hasil (loop over the result)
                foreach ($cursor as $obj) {
                    ?>
                    <div class="item  col-xs-4 col-lg-4">
                        <div class="thumbnail">
                            <img class="group list-group-image" src="uploads/<?php echo $obj['image']; ?>" alt=""/>
                            <div class="caption">
                                <h4 class="group inner list-group-item-heading font"><a
                                        href="index.php?id=<?php echo $obj['_id']; ?>"><?php echo $obj['title']; ?></a>
                                </h4>
                                <p id="description" class="group inner list-group-item-text"  >
                                    <?php echo $obj['description']; ?></p>
                                <p id="author" class="group inner list-group-item-text">
                                    <?php echo $obj['author']; ?></p>
                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <p class="lead">
                                            Rp <?php echo $obj['price']; ?></p>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <a class="btn btn-danger"
                                           onclick=addtocart('<?php echo $obj['_id'] . "','" . urlencode($obj['title']) . "','" . $obj["price"]; ?>')>Add
                                            to cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php
                }
            } else {
                // jika produk tidak ditemukan
                echo "Hasil tidak ditemukan \n";
            }


            ?>
        </div>

    <?php } else {
        // Membuat collection baru dengan MongoCollection dengan nama products
        $collection = new MongoCollection($db, 'products');
        // Menemukan sebanyak 1 dokumen (findOne) pada collection product yang didapat dari id
        $sweetQuery = $collection->findOne(array('_id' => new MongoId($_GET["id"])));
        // fetch semua dokumen dari collection product
        $cursor = $collection->find($sweetQuery);
        // perulangan untuk menampilkan dokumen beserta fieldnya
        foreach ($cursor as $obj) {
            ?>
            <div class="item">
                <div class="thumbnail">
                    <img class="group list-group-image" src="uploads/<?php echo $obj['image']; ?>" alt=""/>
                    <div class="caption">

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Penulis</th>
                                    <th scope="col">Tanggal Terbit</th>
                                    <th scope="col">Penerbit</th>
                                    <th scope="col">ISBN</th>
                                    <th scope="col">Halaman</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><a href="index.php?id=<?php echo $obj['_id']; ?>"><?php echo $obj['title']; ?></td>
                                    <td><?php echo $obj['author']; ?></td>
                                    <td><?php echo $obj['tanggal-terbit']; ?></td>
                                    <td><?php echo $obj['penerbit']; ?></td>
                                    <td><?php echo $obj['isbn']; ?></td>
                                    <td><?php echo $obj['halaman']; ?> </td>
                                </tr>
                            </tbody>
                        </table><hr>
                        <h4><strong>Deksripsi Buku</strong></h4>
                        <p class="group inner list-group-item-text">
                            <?php echo $obj['description']; ?></p><hr class="colorgraph">

                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <p class="lead">
                                    Rp <?php echo $obj['price']; ?></p>
                            </div>

                            
                            <div class="col-xs-12 col-md-6">
                                <a class="btn btn-danger"
                                   onclick=addtocart('<?php echo $obj['_id'] . "','" . urlencode($obj['title']) . "','" . $obj["price"]; ?>')>Add
                                    to cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    // Tutup Koneksi MongoDB
    $conn->close();
    ?>
</div>
</body>
</html>