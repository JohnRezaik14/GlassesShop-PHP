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

    // Get page number from URL, default to 1 if not set
    $page_number = isset($_GET['page']) ? max((int) $_GET['page'], 1) : 1;

    $items_length = $db->get_total_count();

    // Calculate pagination details
    $pagination = calculateOffset($items_length, $page_number, $config['records_per_page']);
    $items      = $db->get_data([], $pagination['offset']);

    // Calculate pagination details
    function calculateOffset($items_length, $page_number, $pageSize)
    {
        $numberOfPages = (int) ceil($items_length / $pageSize);

        // Ensure page number is within valid range
        if ($page_number > $numberOfPages) {
            $page_number = $numberOfPages;
        }

        $offset = ($page_number - 1) * $pageSize;
        return [
            'offset'       => $offset,
            'current_page' => $page_number,
            'total_pages'  => $numberOfPages,
        ];
    }

    // Render items function
    function renderItems($items)
    {
        $output = '';
        foreach ($items as $item) {
            $photo           = htmlspecialchars($item['Photo']);
            $productName     = htmlspecialchars($item['product_name']);
            $category        = htmlspecialchars($item['category']);
            $country         = htmlspecialchars($item['CouNtry']);
            $price           = htmlspecialchars($item['list_price']);
            $stockBadgeClass = $item['Units_In_Stock'] > 0 ? 'bg-success' : 'bg-danger';
            $stockStatus     = $item['Units_In_Stock'] > 0 ? 'In Stock' : 'Out of Stock';
            $productId       = htmlspecialchars($item['id']); // Use the product ID for the details link

            $output .= <<<HTML
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="images/{$photo}" class="card-img-top" alt="{$productName}">
                    <div class="card-body">
                        <h5 class="card-title" title="{$productName}">{$productName}</h5>
                        <p class="card-text">
                            <span class="badge bg-primary">{$category}</span>
                            <span class="badge bg-secondary">{$country}</span>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">\$${price}</span>
                            <span class="badge {$stockBadgeClass}">{$stockStatus}</span>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="product.php?id={$productId}" class="btn btn-outline-primary btn-sm">Show Details</a>
                        </div>
                    </div>
                </div>
            </div>
            HTML;
        }
        return $output;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glass Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-5">Glass Shop</h1>
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?php echo renderItems($items); ?>
        </div>
        <?php if ($pagination['total_pages'] > 1): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php if ($pagination['current_page'] > 1): ?>
                    <a href="?page=<?php echo($pagination['current_page'] - 1); ?>" class="btn btn-primary mx-2">Previous</a>
                <?php endif; ?>

                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <a href="?page=<?php echo($pagination['current_page'] + 1); ?>" class="btn btn-primary mx-2">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    $db->disconnect();
?>
