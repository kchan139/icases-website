<div class="breadcrumb">
    <div class="container">
        <a href="/">Home</a> / <span>Shopping Cart</span>
    </div>
</div>

<section class="cart-section">
    <div class="container">
        <h1>Shopping Cart</h1>

        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (empty($cartItems)): ?>
            <div class="empty-cart">
                <p>Your cart is empty</p>
                <a href="/products" class="btn">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-grid">
                <div class="cart-items">
                    <?php 
                    $total = 0;
                    foreach ($cartItems as $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <div class="cart-item">
                            <img src="/assets<?= htmlspecialchars($item['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($item['name']) ?>"
                                 onerror="this.src='/assets/images/placeholder.jpg'">
                            <div class="cart-item-details">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="cart-item-model"><?= htmlspecialchars($item['iphone_model']) ?></p>
                                <p class="cart-item-price"><?= number_format($item['price'], 0, ',', '.') ?>₫</p>
                            </div>
                            <div class="cart-item-quantity">
                                <form method="POST" action="/cart/update">
                                    <input type="hidden" name="cart_item_id" value="<?= $item['id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" 
                                           min="1" max="<?= $item['stock_quantity'] ?>" 
                                           onchange="this.form.submit()">
                                </form>
                            </div>
                            <div class="cart-item-subtotal">
                                <?= number_format($subtotal, 0, ',', '.') ?>₫
                            </div>
                            <div class="cart-item-remove">
                                <form method="POST" action="/cart/remove">
                                    <input type="hidden" name="cart_item_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn-remove">✕</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <h2>Order Summary</h2>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span><?= number_format($total, 0, ',', '.') ?>₫</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span><?= number_format($total, 0, ',', '.') ?>₫</span>
                    </div>
                    <form method="POST" action="/checkout">
                        <button type="submit" class="btn btn-full">Proceed to Checkout</button>
                    </form>
                    <a href="/products" class="continue-shopping">Continue Shopping</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
