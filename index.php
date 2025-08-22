<?php
require 'banco.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantina da Nonna - Sabores Autênticos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            line-height: 1.6;
            color: #333;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #f39c12;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        nav a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #f39c12;
            transition: width 0.3s ease;
        }

        nav a:hover::after {
            width: 100%;
        }

        nav a:hover {
            color: #f39c12;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(45deg, rgba(231, 76, 60, 0.9), rgba(230, 126, 34, 0.9)),
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><pattern id="pasta" patternUnits="userSpaceOnUse" width="100" height="100"><circle cx="50" cy="50" r="20" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="1000" height="1000" fill="url(%23pasta)"/></svg>');
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            z-index: 2;
            animation: fadeInUp 1s ease;
        }

        .hero h1 {
            font-size: 4rem;
            margin-bottom: 1rem;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
            background: linear-gradient(45deg, #fff, #f39c12);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .cta-button {
            background: linear-gradient(45deg, #e74c3c, #f39c12);
            color: white;
            padding: 15px 30px;
            font-size: 1.2rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.4);
        }

        /* Floating Elements */
        .floating-element {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .floating-element:nth-child(2) { top: 60%; right: 10%; animation-delay: 2s; }
        .floating-element:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 4s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Features Section */
        .features {
            padding: 5rem 0;
            background: #f8f9fa;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, #e74c3c, #f39c12);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #e74c3c, #f39c12);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .section-subtitle {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 3rem;
        }

        /* Weekly Menu */
        .weekly-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .menu-day {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 20px;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .menu-day::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, #f39c12, #e74c3c);
        }

        .menu-day.weekend::before {
            background: linear-gradient(45deg, #9b59b6, #e74c3c);
        }

        .menu-day:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .day-title {
            color: #f39c12;
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
            text-align: center;
            border-bottom: 2px solid rgba(243, 156, 18, 0.3);
            padding-bottom: 0.5rem;
        }

        .weekend .day-title {
            color: #9b59b6;
            border-bottom-color: rgba(155, 89, 182, 0.3);
        }

        .day-menu {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .dish {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            border-left: 3px solid #f39c12;
            transition: all 0.3s ease;
        }

        .weekend .dish {
            border-left-color: #9b59b6;
        }

        .dish:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .dish-name {
            font-weight: 500;
            color: #ecf0f1;
        }

        .dish-price {
            font-weight: bold;
            color: #f39c12;
            font-size: 1.1rem;
        }

        .weekend .dish-price {
            color: #9b59b6;
        }

        /* Contact Section */
        .contact {
            padding: 5rem 0;
            background: #ecf0f1;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
            margin-top: 3rem;
        }

        .contact-info {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 2rem 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.2rem;
            }
            
            nav ul {
                flex-direction: column;
                gap: 1rem;
            }
            
            .header-content {
                flex-direction: column;
            }
        }

        /* Scroll Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

       .login-button {
            background: linear-gradient(45deg, #f39c12, #e67e22);
            color: white !important;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            display: inline-block;
        }

        .login-button:hover {
            background: linear-gradient(45deg, #d35400, #e74c3c);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.25);
            color: #fff !important;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">🍝 Cantina da Nonna</div>
                <nav>
                    <ul>
                        <li><a href="#inicio">Início</a></li>
                        <li><a href="#cardapio">Cardápio</a></li>
                        <li><a href="#sobre">Sobre</a></li>
                        <li><a href="#contato">Contato</a></li>
                        <li><a href="<?=$url_base?>/views/user/login.php" class="login-button"> Login</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <section class="hero" id="inicio">
            <div class="floating-element">🍕</div>
            <div class="floating-element">🍝</div>
            <div class="floating-element">🥘</div>
            
            <div class="hero-content">
                <h1>Bem-vindos à nossa Cantina</h1>
                <p>Sabores autênticos, tradição familiar e muito amor em cada prato</p>
                <a href="#cardapio" class="cta-button">Ver Cardápio</a>
            </div>
        </section>

        <section class="features" id="sobre">
            <div class="container">
                <h2 class="section-title fade-in">Por que escolher nossa Cantina?</h2>
                <p class="section-subtitle fade-in">Tradição, qualidade e sabor em cada experiência</p>
                
                <div class="features-grid">
                    <div class="feature-card fade-in">
                        <div class="feature-icon">👨‍🍳</div>
                        <h3>Receitas Tradicionais</h3>
                        <p>Pratos preparados com receitas passadas de geração em geração, mantendo o sabor autêntico da culinária italiana.</p>
                    </div>
                    
                    <div class="feature-card fade-in">
                        <div class="feature-icon">🌱</div>
                        <h3>Ingredientes Frescos</h3>
                        <p>Utilizamos apenas ingredientes selecionados e frescos, muitos vindos diretamente de produtores locais.</p>
                    </div>
                    
                    <div class="feature-card fade-in">
                        <div class="feature-icon">🏠</div>
                        <h3>Ambiente Familiar</h3>
                        <p>Um espaço acolhedor onde você se sente em casa, perfeito para momentos especiais com família e amigos.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="menu-preview" id="cardapio">
            <div class="container">
                <h2 class="section-title fade-in">Cardápio da Semana</h2>
                <p class="section-subtitle fade-in">Pratos frescos preparados diariamente com muito carinho</p>
                
                <div class="weekly-menu">
                    <div class="menu-day fade-in">
                        <h3 class="day-title">📅 Segunda-feira</h3>
                        <div class="day-menu">
                            <div class="dish">
                                <span class="dish-name">🍝 Spaghetti Bolognese</span>
                                <span class="dish-price">R$ 25,00</span>
                            </div>
                            <div class="dish">
                                <span class="dish-name">🥗 Salada Caesar</span>
                                <span class="dish-price">R$ 18,00</span>
                            </div>
                            <div class="dish">
                                <span class="dish-name">🍰 Tiramisù</span>
                                <span class="dish-price">R$ 12,00</span>
                            </div>
                        </div>
                    </div>

                    <div class="menu-day fade-in">
                        <h3 class="day-title">📅 Terça-feira</h3>
                        <div class="day-menu">
                            <div class="dish">
                                <span class="dish-name">🍕 Pizza Margherita</span>
                                <span class="dish-price">R$ 32,00</span>
                            </div>
                            <div class="dish">
                                <span class="dish-name">🍲 Minestrone</span>
                                <span class="dish-price">R$ 15,00</span>
                            </div>
                            <div class="dish">
                                <span class="dish-name">🍨 Gelato</span>
                                <span class="dish-price">R$ 10,00</span>
                            </div>
                        </div>
                    </div>

                    <div class="menu-day fade-in">
                        <h3 class="day-title">📅 Quarta-feira</h3>
                        <div class="day-menu">
                            <div class="dish">
                                <span class="dish-name">🥘 Risotto ai Funghi</span>
                                <span class="dish-price">R$ 28,00</span>
                            </div>
                            <div class="dish">
                                <span class="dish-name">🥖 Bruschetta</span>
                                <span class="dish-price">R$ 16,00</span>
                            </div>
                            <div class="dish">
                                <span class="dish-name">🍮 Panna Cotta</span>
                                <span class="dish-price">R$ 11,00</span>
                            </div>
                        </div>
                    </div>

                    <div class="menu-day fade-in">
                        <h3 class="day-title">📅 Quinta-feira</h3>
                        <div class="day-menu">
                            <div class="dish">
                                <span class="dish-name">🍝 Fettuccine Alfredo</span>
                                <span class="dish-price">R$ 26,00</span>
                            </div>
                            <div class="dish">
                                <span class="dish-name">🧀 Antipasto Italiano</span>
                                <span class="dish-price">R$ 22,00</span>
                            </div>
                            <div class="dish">
                                <span class="dish-name">🍰 Cannoli</span>
                                <span class="dish-price">R$ 13,00</span>
                            </div>
                        </div>
                    </div>

                    <div class="menu-day fade-in">
                        <h3 class="day-title">📅 Sexta-feira</h3>
                        <div class="day-menu">
                            <div class="dish">
                                <span class="dish-name">🍖 Osso Buco</span>
                                <span class="dish-price">R$ 38,00</span>
                            </div>
                            <div class="dish">
                                <span class="dish-name">🍞 Focaccia</span>
                                <span class="dish-price">R$ 14,00</span>
                            </div>
                            <div class="dish">
                                <span class="dish-name">🍷 Zabaione</span>
                                <span class="dish-price">R$ 15,00</span>
                            </div>
                        </div>
                    </div>

                    <div class="menu-day weekend fade-in">
                        <h3 class="day-title">📅 Sábado & Domingo</h3>
                        <div class="day-menu">
                            <div class="dish">
                                <span class="dish-name">🍕 Pizza Especial do Chef</span>
                                <span class="dish-price">R$ 35,00</span>
                            </div>
                            <div class="dish">
                                <span class="dish-name">🍝 Lasagna della Casa</span>
                                <span class="dish-price">R$ 30,00</span>
                            </div>
                            <div class="dish">
                                <span class="dish-name">🥩 Bistecca alla Griglia</span>
                                <span class="dish-price">R$ 42,00</span>
                            </div>
                            <div class="dish">
                                <span class="dish-name">🍰 Torta della Nonna</span>
                                <span class="dish-price">R$ 16,00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact" id="contato">
            <div class="container">
                <h2 class="section-title fade-in">Venha nos Visitar</h2>
                <p class="section-subtitle fade-in">Estamos esperando por você!</p>
                
                <div class="contact-grid">
                    <div class="contact-info fade-in">
                        <h3>📍 Localização</h3>
                        <p>Rua das Delícias, 123<br>
                        Centro - Campinas, SP<br>
                        CEP: 13000-000</p>
                    </div>
                    
                    <div class="contact-info fade-in">
                        <h3>🕐 Horários</h3>
                        <p>Segunda a Quinta: 11h às 23h<br>
                        Sexta e Sábado: 11h às 24h<br>
                        Domingo: 11h às 22h</p>
                    </div>
                    
                    <div class="contact-info fade-in">
                        <h3>📞 Contato</h3>
                        <p>Telefone: (19) 3123-4567<br>
                        WhatsApp: (19) 99999-8888<br>
                        Email: contato@cantinadanonna.com.br</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Cantina da Nonna. Todos os direitos reservados. Feito com ❤️ e muito molho de tomate.</p>
        </div>
    </footer>

    <script>
        // Scroll Animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Smooth Scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Header Background on Scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(44, 62, 80, 0.95)';
            } else {
                header.style.background = 'linear-gradient(135deg, #2c3e50, #34495e)';
            }
        });

        // Add some interactive effects
        document.querySelectorAll('.menu-day').forEach(item => {
            item.addEventListener('mouseenter', () => {
                item.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            item.addEventListener('mouseleave', () => {
                item.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>