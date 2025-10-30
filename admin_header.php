<header>
    <div class="menu-icon" onclick="toggleNav()">&#9776;</div>
</header>
<div class="navbar">
    <a href="index.php"><img src="eidetic.png" alt="Your Logo" class="logo"></a>
    <ul>
        <li class="nav-item"><img src="home.png"><a href="admin_index.php" onclick="closeMenu()">HOME</a></li>
        <li class="nav-item"><img src="products.png"><a href="admin_order.php" onclick="closeMenu()">Order</a></li>
        <li class="nav-item"><a href="admin_user.php" onclick="closeMenu()">Users</a></li>
        <li class="nav-item"><a href="admin_reqsel.php" onclick="closeMenu()">Generate Report</a></li>
        <li class="nav-item"><a href="admin_stocks.php" onclick="closeMenu()">Inventory</a></li>
        <li class="nav-item"><img src="sign.png"><a href="#" onclick="closeMenu()">PROFILE</a></li>
        <?php
        if (isset($_SESSION['Email'])) {
            echo '<a href="logout.php" style="color: white; text-decoration: none; padding: 7px; background-color: black;">Logout</a>';
        } else {
            echo '<li class="nav-item"><a href="#" onclick="openRegisterModal()" style="color: white; text-decoration: none; padding: 7px; background-color: black; ">SIGN UP</a></li>';

            echo '<li class="nav-item"><a href="#" onclick="openLoginModal()" style="color: white; text-decoration: none; padding: 7px; background-color: black; ">LOGIN</a></li>';


        }
        ?>



        </li>
    </ul>




</div>