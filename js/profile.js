document.addEventListener('DOMContentLoaded', function() {
    const navButtons = document.querySelectorAll('.nav-btn[data-tab]');
    const tabContents = document.querySelectorAll('.tab-content');

    navButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');

            // 1. Убираем активный класс у всех кнопок
            navButtons.forEach(btn => btn.classList.remove('active'));
            // 2. Скрываем все вкладки
            tabContents.forEach(tab => tab.classList.remove('active'));

            // 3. Активируем нужную кнопку и вкладку
            button.classList.add('active');
            const activeTab = document.getElementById(targetTab);
            if (activeTab) {
                activeTab.classList.add('active');
            }
        });
    });
});