<div class="breadcrumb">
    <div class="container">
        <a href="/">Home</a> / <a href="/cart">Cart</a> / <span>Checkout</span>
    </div>
</div>

<section class="checkout-section">
    <div class="container">
        <h1>Checkout</h1>

        <div class="checkout-grid">
            <div class="checkout-form">
                <h2>Shipping Information</h2>
                <form method="POST" action="/checkout/process">
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" 
                               value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" 
                               value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Address *</label>
                        <textarea id="address" name="address" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city" required>
                    </div>

                    <div class="form-group">
                        <label for="notes">Order Notes (Optional)</label>
                        <textarea id="notes" name="notes" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-full">Place Order</button>
                </form>
            </div>

            <div class="checkout-summary">
                <h2>Order Summary</h2>
                <?php 
                $total = 0;
                foreach ($cartItems as $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                    <div class="checkout-item">
                        <span><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                        <span><?= number_format($subtotal, 0, ',', '.') ?>₫</span>
                    </div>
                <?php endforeach; ?>
                
                <div class="checkout-total">
                    <span>Total</span>
                    <span><?= number_format($total, 0, ',', '.') ?>₫</span>
                </div>
            </div>
        </div>
    </div>
</section>
