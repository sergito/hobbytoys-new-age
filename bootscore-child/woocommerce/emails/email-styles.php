/* =========================================== 
   HOBBY TOYS - EMAIL STYLES
   =========================================== */

/* Variables CSS */
:root {
    --primary-color: #EE285B;
    --secondary-color: #534fb5;
    --accent-color: #ffb900;
    --light-bg: #f4efe8;
    --text-dark: #2C3E50;
    --white: #ffffff;
    --gray-light: #f8f9fa;
    --gray-medium: #6c757d;
    --border-radius: 1rem;
    --border-radius-lg: 1.5rem;
    --border-radius-xl: 2rem;
    --shadow-soft: 0 8px 32px rgba(238, 40, 91, 0.1);
    --shadow-hover: 0 12px 40px rgba(238, 40, 91, 0.15);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Reset y Base */
* {
    font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    background-color: var(--light-bg);
    color: var(--text-dark);
    line-height: 1.6;
}

/* Contenedor principal del email */
.email-container {
    max-width: 600px;
    margin: 0 auto;
    background-color: var(--white);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-soft);
}

/* Header */
.email-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: var(--white);
    padding: 2rem;
    text-align: center;
    position: relative;
}

.email-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="3" fill="rgba(255,255,255,0.05)"/><circle cx="40" cy="80" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
}

.email-header h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 900;
    letter-spacing: 0.5px;
    position: relative;
    z-index: 1;
}

.email-header .logo {
    max-height: 60px;
    margin-bottom: 1rem;
}

/* Contenido principal */
.email-content {
    padding: 2rem;
}

.email-introduction {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background-color: var(--light-bg);
    border-radius: var(--border-radius);
    border-left: 4px solid var(--primary-color);
}

/* Tipografía */
.fw-black { font-weight: 900 !important; }

h1, h2, h3, h4, h5, h6 {
    font-weight: 900;
    letter-spacing: 0.5px;
    margin-top: 0;
}

h2 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

p, span, div {
    font-weight: 600;
}

/* Botones */
.btn {
    display: inline-block;
    padding: 0.75rem 2rem;
    background-color: var(--primary-color);
    color: var(--white) !important;
    text-decoration: none;
    border-radius: var(--border-radius);
    font-weight: 700;
    text-align: center;
    border: none;
    box-shadow: var(--shadow-soft);
    transition: var(--transition);
    cursor: pointer;
}

.btn:hover {
    background-color: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.btn-secondary {
    background-color: var(--accent-color);
    color: var(--text-dark) !important;
}

.btn-lg {
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
}

/* Tabla de pedido */
.order-table {
    width: 100%;
    margin: 1.5rem 0;
    border-collapse: collapse;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow-soft);
}

.order-table th {
    background-color: var(--primary-color);
    color: var(--white);
    padding: 1rem;
    font-weight: 700;
    text-align: left;
}

.order-table td {
    padding: 1rem;
    border-bottom: 1px solid #eee;
}

.order-table tr:nth-child(even) {
    background-color: var(--gray-light);
}

.order-table .total-row {
    background-color: var(--light-bg);
    font-weight: 700;
}

.order-table .total-row td {
    border-top: 2px solid var(--primary-color);
}

/* Información del cliente */
.customer-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin: 2rem 0;
}

.info-box {
    background-color: var(--gray-light);
    padding: 1.5rem;
    border-radius: var(--border-radius);
    border-left: 4px solid var(--secondary-color);
}

.info-box h3 {
    margin-top: 0;
    color: var(--secondary-color);
    font-size: 1.1rem;
}

/* Estados del pedido */
.order-status {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-weight: 700;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-processing {
    background-color: rgba(255, 185, 0, 0.2);
    color: #e67e22;
    border: 2px solid #ffb900;
}

.status-completed {
    background-color: rgba(39, 174, 96, 0.2);
    color: #27ae60;
    border: 2px solid #27ae60;
}

.status-on-hold {
    background-color: rgba(230, 126, 34, 0.2);
    color: #e67e22;
    border: 2px solid #e67e22;
}

.status-cancelled {
    background-color: rgba(231, 76, 60, 0.2);
    color: #e74c3c;
    border: 2px solid #e74c3c;
}

/* Footer */
.email-footer {
    background-color: var(--text-dark);
    color: var(--white);
    padding: 2rem;
    text-align: center;
}

.email-footer p {
    margin: 0.5rem 0;
    font-weight: 400;
}

.email-footer a {
    color: var(--accent-color);
    text-decoration: none;
}

.social-links {
    margin: 1rem 0;
}

.social-links a {
    display: inline-block;
    margin: 0 0.5rem;
    padding: 0.5rem;
    background-color: var(--primary-color);
    color: var(--white);
    border-radius: 50%;
    text-decoration: none;
    width: 40px;
    height: 40px;
    line-height: 30px;
    text-align: center;
}

/* Contenido adicional */
.email-additional-content {
    background-color: var(--light-bg);
    padding: 1.5rem;
    border-radius: var(--border-radius);
    margin: 2rem 0;
    border: 1px dashed var(--primary-color);
}

/* Responsive */
@media only screen and (max-width: 600px) {
    .email-container {
        margin: 0;
        border-radius: 0;
    }
    
    .email-header,
    .email-content,
    .email-footer {
        padding: 1rem;
    }
    
    .customer-info {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .order-table {
        font-size: 0.9rem;
    }
    
    .order-table th,
    .order-table td {
        padding: 0.5rem;
    }
}