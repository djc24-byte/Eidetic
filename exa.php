<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website Title</title>
    <style>
        /* Add your CSS styles here */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            background-color: #c7e90b;
            padding: 10px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            max-width: 60px;
            /* Adjust the size as needed */
        }

        .login-signup {
            padding: 10px;
        }

        .navbar {
            width: 100%;
            margin: auto;


            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f7faf8;
            z-index: 1;
            /* Added z-index to keep it above the background image */
            border: solid black 1px;
        }

        .navbar ul li {
            list-style: none;
            display: inline-block;
            margin: 8px;
            position: relative;
        }

        .navbar ul li a {
            text-decoration: none;
            color: black;
            text-transform: uppercase;

            font-weight: bold;
        }

        .navbar ul li::after {
            content: '';
            height: 3px;
            width: 0;
            background: darkgrey;
            position: absolute;
            left: 0;
            bottom: -10px;
            transition: 0.5s;
        }

        .navbar ul li:hover::after {
            width: 100%;
        }

        .nav-item img {
            width: 13px;
            height: 13px;
            margin-right: 5px;
        }


        @media screen and (min-width: 768px) {
            .menu-icon {
                display: none;
            }
        }

        /* Media query for responsive design */
        @media screen and (max-width: 768px) {
            nav {
                flex-direction: column;
                align-items: center;
                text-align: center;
                display: none;
            }

            nav.active {
                display: flex;
            }

            nav a {
                margin: 5px 0;
            }

            .menu-icon {
                cursor: pointer;
                font-size: 24px;
                text-align: right;
                display: block;
            }

            header {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>

<body>
    <header>
        <img src="eidetic.png" alt="Your Logo" class="logo">
        <div class="login-signup">
            <a href="#">Login</a> | <a href="#">Sign Up</a>
        </div>
        <div class="menu-icon" onclick="toggleNav()">&#9776;</div>
    </header>
    <div class="navbar">
        <nav>
            <ul>
                <li class="nav-item"><img src="home.png"><a href="#" onclick="closeMenu()">HOME</a></li>
                <li class="nav-item"><img src="products.png"><a href="#" onclick="closeMenu()">PRODUCTS</a></li>
                <li class="nav-item"><a href="#" onclick="closeMenu()">ABOUT US</a></li>
                <li class="nav-item"><img src="contact.png"><a href="#" onclick="closeMenu()">CONTACT US</a></li>
            </ul>
        </nav>
    </div>

    <script>
        function toggleNav() {
            var nav = document.querySelector('nav');
            nav.classList.toggle('active');
        }

        function closeMenu() {
            var nav = document.querySelector('nav');
            nav.classList.remove('active');
        }
    </script>
</body>

</html>