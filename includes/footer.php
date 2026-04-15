<footer class="main-footer">
    <div class="footer-centered-content">
        <p>&copy; <?= date('Y') ?> CORE 391. Все права защищены.</p>
    </div>
</footer>

<style>
    .main-footer {
        width: 100%;
        padding: 40px 0;
        background: #050505; /* Цвет твоего фона */
        border-top: 1px solid #1a1a1a;
        margin-top: 60px;
    }

    .footer-centered-content {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .footer-centered-content p {
        color: #555;
        font-size: 14px;
        letter-spacing: 1px;
        margin: 0;
    }
</style>

<script>
    // Скрипт для аккордеонов
    document.querySelectorAll('.accordion-header').forEach(header => {
        header.onclick = () => header.parentElement.classList.toggle('active');
    });
</script>

</body>
</html>