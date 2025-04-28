<?php
    require_once "../vendor/autoload.php";
    use Johnl\GlassesShop\Models\MYSQLHandler;

    // Load configuration
    $config = require_once '../src/config.php';

    // Initialize database handler
    $db = new MYSQLHandler($config);
    if (! $db->connect()) {
        throw new Exception("Database connection failed");
    }

    // Get product ID from the URL
    $productId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $product   = $db->get_record_by_id($productId);

    if (! $product) {
        echo "Product not found.";
        exit;
    }

    // Disconnect from the database
    $db->disconnect();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-5"><?php echo htmlspecialchars($product['product_name']); ?></h1>
        <div class="row">
            <div class="col-md-6">
                <img src="images/<?php echo htmlspecialchars($product['Photo']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
            </div>
            <div class="col-md-6">
                <h3>Details</h3>
                <p><strong>Category:</strong>                                              <?php echo htmlspecialchars($product['category']); ?></p>
                <p><strong>Country:</strong>                                             <?php echo htmlspecialchars($product['CouNtry']); ?></p>
                <p><strong>Price:</strong> $<?php echo htmlspecialchars($product['list_price']); ?></p>
                <p><strong>Stock Status:</strong>                                                  <?php echo $product['Units_In_Stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?></p>
                <p><strong>Rating:</strong>                                            <?php echo htmlspecialchars($product['Rating']); ?>/5</p>
                <a href="index.php" class="btn btn-primary mt-3">Back to Shop</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>