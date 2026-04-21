/**
 * Управление вкладками в профиле CORE SYSTEM
 * Обработка переключений между Обзором и Настройками
 */
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.nav-btn[data-tab]');
    const contents = document.querySelectorAll('.tab-content');

    buttons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Предотвращаем стандартное поведение, если это кнопка
            e.preventDefault();

            const target = btn.getAttribute('data-tab');

            // 1. Сброс активных состояний у кнопок
            buttons.forEach(b => b.classList.remove('active'));
            
            // 2. Сброс активных состояний у контента
            contents.forEach(c => {
                c.classList.remove('active');
                // Небольшая задержка для перезапуска анимации появления
                c.style.opacity = "0";
            });

            // 3. Активация текущей кнопки
            btn.classList.add('active');

            // 4. Активация выбранной вкладки по ID
            const activeTab = document.getElementById(target);
            if (activeTab) {
                activeTab.classList.add('active');
                // Плавное проявление
                setTimeout(() => {
                    activeTab.style.opacity = "1";
                }, 10);
            }

            // Логи в консоль для отладки (можно потом удалить)
            console.log(`CORE SYSTEM: Переключение на вкладку [${target}]`);
        });
    });
});