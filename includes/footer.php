<footer>
    <div class="header-container">
        <p>&copy; <?= date('Y') ?> CORE 391. Все права защищены.</p>
    </div>
</footer>

<script>
    // Скрипт для аккордеонов (если есть в контенте)
    document.querySelectorAll('.accordion-header').forEach(header => {
        header.onclick = () => header.parentElement.classList.toggle('active');
    });
</script>

</body>
</html>