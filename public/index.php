<?php
    require_once "../vendor/autoload.php";
    use Johnl\GlassesShop\Models\MYSQLHandler;
    $config = require_once '../src/config.php';
    $db     = new MYSQLHandler($config);
    if (! $db->connect()) {
        throw new Exception("Database connection failed");
    }
    $items = $db->get_data();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glass Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-5">Glass Shop</h1>
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($items as $item): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="images/<?php echo htmlspecialchars($item['Photo']); ?>"
                             class="card-img-top"
                             alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['product_name']); ?></h5>
                            <p class="card-text mb-2">
                                <span class="badge bg-primary"><?php echo htmlspecialchars($item['category']); ?></span>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($item['CouNtry']); ?></span>
                            </p>
                            <div class="rating-stock">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="h5 mb-0">$<?php echo htmlspecialchars($item['list_price']); ?></span>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">
                                            <small>Rating:                                                                                                                     <?php echo htmlspecialchars($item['Rating']); ?>/5</small>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <?php if ($item['Units_In_Stock'] > 0): ?>
                                        <span class="badge bg-success">In Stock</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Out of Stock</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    $db->disconnect();
?>
