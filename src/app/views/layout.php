<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Premium iPhone cases - Clear, Leather, Silicone, Rugged, and Wallet cases for all iPhone models">
    <meta name="keywords" content="iPhone cases, phone cases, iPhone 15 cases, protective cases">
    <title>iPhone Cases - Premium Protection for Your Device</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/components/header.php'; ?>

    <main>
        <?php
        if (isset($viewFile)) {
            require_once $viewFile;
        }
        ?>
    </main>

    <?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>
