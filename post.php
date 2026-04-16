@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap');

:root {
    --accent: #ff4d00;
    --bg-dark: #050505;
    --card-bg: #0f0f0f;
    --header-bg: #0a0a0a;
    --text-main: #ffffff;
    --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Inter', sans-serif;
    background-color: var(--bg-dark);
    color: var(--text-main);
    line-height: 1.6;
    overflow-x: hidden;
}

/* --- ШАПКА --- */
header {
    background: var(--header-bg);
    border-bottom: 1px solid #1a1a1a;
    padding: 12px 0;
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-container {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 40px;
}

.logo img { height: 42px; display: block; }

.nav-menu { display: flex; gap: 30px; align-items: center; }
.nav-menu a {
    color: var(--text-main);
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    transition: 0.3s;
}
.nav-menu a:hover { color: var(--accent); }

/* --- HERO SECTION --- */
.hero {
    position: relative;
    height: 65vh;
    min-height: 550px;
    background: url('../img/banner.png') no-repeat center 10% / cover;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    margin-bottom: -60px;
}

.hero-overlay {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(to bottom, rgba(5,5,5,0.2), rgba(5,5,5,1));
}

.hero-content { position: relative; z-index: 2; }
.hero-content h1 {
    font-size: 82px;
    font-weight: 900;
    letter-spacing: 5px;
    text-transform: uppercase;
    text-shadow: 0 0 30px rgba(255, 77, 0, 0.2);
}
.accent-text { color: var(--accent); }

/* --- ГИБКИЕ ТАБЛИЦЫ ГАЙДОВ --- */
.ck-content {
    color: #fff;
    font-family: 'Inter', sans-serif;
}

.guide-table-wrapper {
    margin: 25px 0;
    overflow-x: auto;
    background: #0a0a0a;
    border-radius: 12px;
    border: 1px solid #1a1a1a;
    width: 100%;
}

.guide-table {
    width: 100% !important;
    border-collapse: collapse;
    text-align: left;
    table-layout: auto; /* Свободное растяжение */
    min-width: 600px;
}

.guide-table th {
    background: #111;
    color: var(--accent);
    padding: 15px;
    text-transform: uppercase;
    font-size: 12px;
    font-weight: 700;
    border-bottom: 2px solid #1a1a1a;
}

.guide-table td {
    padding: 15px;
    border-bottom: 1px solid #111;
    vertical-align: middle;
    color: #ccc;
}

/* Элементы внутри таблиц */
.item-slot, .weapon-img-info, .char-item, .stat-label {
    display: flex;
    align-items: center;
    gap: 12px;
}

.item-slot img, .weapon-img-info img, .char-item img, .stat-label img {
    width: 45px;
    height: 45px;
    background: #151515;
    border: 1px solid #333;
    border-radius: 8px;
    object-fit: contain;
    flex-shrink: 0;
}

.stars { color: #ffb400; font-size: 10px; display: block; }

/* --- ФУТЕР --- */
footer {
    width: 100%;
    padding: 40px 0;
    background: var(--bg-dark);
    border-top: 1px solid #1a1a1a;
    margin-top: 60px;
}

.footer-container {
    max-width: 1400px;
    margin: 0 auto;
    text-align: center;
    padding: 0 40px;
}

.footer-container p { color: #555; font-size: 14px; letter-spacing: 1px; }

/* Заголовки в контенте */
.ck-content h2 {
    color: #fff;
    border-left: 4px solid var(--accent);
    padding-left: 15px;
    margin: 40px 0 20px;
    font-size: 24px;
    font-weight: 900;
}