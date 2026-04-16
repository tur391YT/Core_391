document.addEventListener('DOMContentLoaded', () => {
    const visualEditor = document.getElementById('visual-editor');
    const hiddenInput = document.getElementById('real-content');

    if (!visualEditor) return;

    function syncData() {
        hiddenInput.value = visualEditor.innerHTML;
    }

    // Вставка шаблонов
    window.insertTemplate = function(type) {
        let html = '';
        
        if (type === 'full_guide') {
            // Генерируем 6 пустых строк для таблицы ресурсов
            let resourceRows = '';
            for(let i=0; i<6; i++) {
                resourceRows += `<tr><td>—</td><td><img src="img/items/placeholder.png"> x0 Предмет</td><td>0</td></tr>`;
            }

            html = `
                <h2 style="color:#ff4d00">МАТЕРИАЛЫ ВОЗВЫШЕНИЯ</h2>
                <table class="guide-table">
                    <thead><tr><th>Уровень</th><th>Ресурсы</th><th>Валюта</th></tr></thead>
                    <tbody>${resourceRows}</tbody>
                </table>

                <h2 style="color:#ff4d00">ЛУЧШЕЕ ОРУЖИЕ</h2>
                <table class="guide-table">
                    <thead><tr><th>Оружие</th><th>Описание пассивки</th><th>Ранг</th></tr></thead>
                    <tbody>
                        <tr><td><img src="img/weapons/placeholder.png"> Название</td><td>Описание эффекта...</td><td>S+</td></tr>
                        <tr><td><img src="img/weapons/placeholder.png"> Название</td><td>Описание эффекта...</td><td>S</td></tr>
                    </tbody>
                </table>
            `;
        } else if (type === 'news') {
            html = `
                <h2 style="color:#ff4d00">🔥 НОВОСТИ ОБНОВЛЕНИЯ</h2>
                <p>Здесь введите основной текст новости...</p>
                
                <table class="guide-table">
                    <thead><tr><th>Событие</th><th>Награды</th><th>Сроки</th></tr></thead>
                    <tbody>
                        <tr><td>Название ивента</td><td><img src="img/items/currency.png"> x600</td><td>До 30.04</td></tr>
                        <tr><td>Техработы</td><td><img src="img/items/currency.png"> x300</td><td>Завершено</td></tr>
                    </tbody>
                </table>

                <h3>Список изменений:</h3>
                <ul>
                    <li>Добавлен новый персонаж</li>
                    <li>Исправлены ошибки интерфейса</li>
                </ul>
            `;
        }

        visualEditor.focus();
        document.execCommand('insertHTML', false, html);
        syncData();
    };

    // --- НОВЫЕ ФУНКЦИИ ДЛЯ ТАБЛИЦ ---
    
    // Добавить строку в таблицу
    window.addRow = function() {
        const selection = window.getSelection();
        const row = selection.anchorNode.parentElement.closest('tr');
        if (row) {
            const newRow = row.cloneNode(true);
            // Очищаем текст в новой строке, оставляя структуру
            newRow.querySelectorAll('td').forEach(td => {
                if(!td.querySelector('img')) td.innerText = '—';
            });
            row.parentNode.insertBefore(newRow, row.nextSibling);
            syncData();
        } else {
            alert("Поставьте курсор внутрь таблицы, чтобы добавить строку!");
        }
    };

    // Удалить текущую строку
    window.deleteRow = function() {
        const selection = window.getSelection();
        const row = selection.anchorNode.parentElement.closest('tr');
        if (row && confirm("Удалить эту строку?")) {
            row.remove();
            syncData();
        }
    };

    window.execCmd = function(cmd) {
        document.execCommand(cmd, false, null);
        syncData();
    };

    window.confirmClear = function() {
        if (confirm("Очистить всё?")) {
            visualEditor.innerHTML = '';
            syncData();
        }
    };

    visualEditor.addEventListener('input', syncData);
});