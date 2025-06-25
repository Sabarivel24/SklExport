<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKL Exports - Premium Garment Manufacturing</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            line-height: 1.6;
            color: #333;
        }

        header {
            background-color: #1a1a1a;
            color: white;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .menu-toggle {
            display: none;
            font-size: 28px;
            cursor: pointer;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin-left: 30px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #ffd700;
        }

        .hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            text-align: center;
            color: white;
            margin-top: 60px;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #ffd700;
            color: #1a1a1a;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #ffed4a;
        }

        .services {
            padding: 80px 0;
            background-color: #f9f9f9;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .service-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .service-card h3 {
            margin: 20px 0;
            color: #1a1a1a;
        }

        .about {
            padding: 80px 0;
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .contact {
            padding: 80px 0;
            background-color: #f9f9f9;
        }

        .contact-info {
            text-align: center;
            margin-bottom: 40px;
        }

        footer {
            background-color: #1a1a1a;
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
                color: white;
            }

            .nav-links {
                display: none;
                flex-direction: column;
                background-color: #1a1a1a;
                position: absolute;
                top: 60px;
                right: 20px;
                width: 200px;
                border-radius: 10px;
                z-index: 1001;
                padding: 10px 0;
            }

            .nav-links li {
                margin: 10px 0;
                text-align: center;
            }

            .nav-links.active {
                display: flex;
            }

            .hero h1 {
                font-size: 36px;
            }

            .about-content {
                grid-template-columns: 1fr;
            }

            .container {
                flex-direction: row;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">SKL Exports</div>
            <div class="menu-toggle" onclick="toggleMenu()">☰</div>
            <ul class="nav-links" id="navLinks">
                <li><a href="#home">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Welcome to SKL Exports</h1>
            <p>Your Trusted Partner in Premium Garment Manufacturing</p>
            <a href="#contact" class="btn">Get in Touch</a>
        </div>
    </section>

    <section class="services" id="services">
        <div class="container">
            <h2 class="section-title">Our Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <h3>Custom Manufacturing</h3>
                    <p>Tailored garment manufacturing solutions to meet your specific requirements and quality standards.</p>
                </div>
                <div class="service-card">
                    <h3>Bulk Production</h3>
                    <p>Efficient large-scale production with consistent quality and timely delivery.</p>
                </div>
                <div class="service-card">
                    <h3>Quality Control</h3>
                    <p>Rigorous quality control processes ensuring the highest standards in every garment.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="about" id="about">
        <div class="container">
            <h2 class="section-title">About Us</h2>
            <div class="about-content">
                <div>
                    <p>SKL Exports is a leading garment manufacturing company with years of experience in the industry. We specialize in producing high-quality garments for clients worldwide, maintaining the highest standards of quality and customer satisfaction.</p>
                    <p>Our state-of-the-art facilities and skilled workforce enable us to deliver exceptional products that meet international standards.</p>
                </div>
                <div>
                    <p>We take pride in our commitment to:</p>
                    <ul>
                        <li>Quality craftsmanship</li>
                        <li>Timely delivery</li>
                        <li>Competitive pricing</li>
                        <li>Customer satisfaction</li>
                        <li>Sustainable practices</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="contact" id="contact">
        <div class="container">
            <h2 class="section-title">Contact Us</h2>
            <div class="contact-info">
                <p>Email: contact@sklexports.com</p>
                <p>Phone: +91 0421-4212600</p>
                <p>Address: S.K.L. Exports HO SF . No.396/2E,295/1C, Mahavishnu Nagar, Angeripalayam Post, Tiruppur - 641603, TamilNadu, India.</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 SKL Exports. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            document.getElementById("navLinks").classList.toggle("active");
        }
    </script>
</body>
</html>
