document.addEventListener('DOMContentLoaded', () => {
    const visualEditor = document.getElementById('visual-editor');
    const hiddenInput = document.getElementById('real-content');

    if (!visualEditor) return;

    function syncData() {
        hiddenInput.value = visualEditor.innerHTML;
    }

    // Вставка масштабного шаблона
    window.insertTemplate = function(type) {
        let html = '';
        if (type === 'full_guide') {
            // Таблица ресурсов на все уровни прокачки
            let resRows = '';
            const levels = ['1-20','20-40','40-50','50-60','60-70','70-80','80-90'];
            levels.forEach(lvl => {
                resRows += `<tr><td>${lvl}</td><td><img src="img/items/placeholder.png"> x0 Предмет</td><td>0 моры</td></tr>`;
            });

            html = `
                <h2 style="color:#ff4d00">МАТЕРИАЛЫ ВОЗВЫШЕНИЯ</h2>
                <table class="guide-table">
                    <thead><tr><th>Уровень</th><th>Ресурсы</th><th>Валюта</th></tr></thead>
                    <tbody>${resRows}</tbody>
                </table>

                <h2 style="color:#ff4d00">ЛУЧШЕЕ ОРУЖИЕ</h2>
                <table class="guide-table">
                    <thead><tr><th>Оружие</th><th>Описание пассивки</th><th>Ранг</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>
                                <img src="img/weapons/placeholder.png"><br>
                                <small style="color:#aaa;">АТК: 674 | Крит: 44%</small>
                            </td>
                            <td><b>Название оружия</b><br>Описание эффекта и бонусов...</td>
                            <td>S+</td>
                        </tr>
                    </tbody>
                </table>

                <h2 style="color:#ff4d00">ЛУЧШЕЕ ОТРЯДЫ</h2>
                <table class="guide-table">
                    <thead><tr><th>Роль</th><th>Персонажи</th><th>Синергия</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>Main DPS</td>
                            <td><img src="img/chars/placeholder.png"> <img src="img/chars/placeholder.png"></td>
                            <td>Краткое описание взаимодействия...</td>
                        </tr>
                    </tbody>
                </table>
            `;
        } else if (type === 'news') {
            html = `<h2 style="color:#ff4d00">🔥 НОВОСТИ</h2><p>Введите текст новости здесь...</p>`;
        }
        
        visualEditor.innerHTML = html; 
        syncData();
    };

    // Функция добавления строки
    window.addRow = function() {
        const sel = window.getSelection();
        const row = sel.anchorNode?.parentElement?.closest('tr');
        if (row) {
            const newRow = row.cloneNode(true);
            // Очищаем текстовое содержимое в копии, чтобы не дублировать старые цифры
            newRow.querySelectorAll('td').forEach((td, index) => {
                if (index !== 1) td.innerText = '—'; // Оставляем структуру иконок только во 2-й колонке если нужно
            });
            row.parentNode.insertBefore(newRow, row.nextSibling);
            syncData();
        } else {
            alert("Поставьте курсор в таблицу, чтобы добавить строку");
        }
    };

    // Функция удаления строки
    window.deleteRow = function() {
        const sel = window.getSelection();
        const row = sel.anchorNode?.parentElement?.closest('tr');
        if (row && confirm("Удалить выбранную строку?")) { 
            row.remove(); 
            syncData(); 
        }
    };

    // Синхронизация при ручном вводе
    visualEditor.addEventListener('input', syncData);
});