    <nav class="nav-wrap">
        <div class="navbar">
            <div class="nav-container">
                <a href="#" class="nav-title">Stellar & Co.</a>
                <ul class="nav-menu">
                    <li>
                        <a href="" class="active">Men</a>
                    </li>
                    <li>
                        <a href="">Women</a>
                    </li>
                    <li>
                        <a href="">Kids</a>
                    </li>
                </ul>
            </div>
            <div class="nav-container">
                <form action="" method="get">
                    <div class="input-icon">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search Collection" class="input-control">
                    </div>
                </form>
                <div class="nav-menu">
                    <p id="profile"><i class="bi bi-person-circle"></i></p>
                </div>
            </div>
        </div>
        <div class="profile-menu" id="profile-menu">
            <ul class="profile-item">
                <?php if(true): ?>
                <li>
                    <a href="">Profile</a>
                </li>
                <li>
                    <a href="">History</a>
                </li>
                
                <form action="">
                    <button type="submit" class="logout">Sign Out</button>
                </form>
                <?php else: ?>
                        <a href="" class="login">Sign In</a>
                <?php endif; ?>
            </ul>
        </div>
    </nav>