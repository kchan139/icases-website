<header>
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="/">iCases</a>
            </div>
            <nav>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/products">Products</a></li>
                    <li><a href="/search">Search</a></li>
                    <li><a href="/stores">Stores</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="/logout">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/login">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</header>
