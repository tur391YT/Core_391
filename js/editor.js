// Переменная для хранения активной картинки
let activeImage = null;

/**
 * Основные функции форматирования и очистки
 */
function formatDoc(cmd, value = null) {
    document.execCommand(cmd, false, value);
    const editor = document.getElementById('visual-editor');
    if (editor) editor.focus();
}

function addLink() {
    const url = prompt("Введите ссылку:", "https://");
    if (url) formatDoc('createLink', url);
}

// Удаляет надпись-заглушку при начале редактирования или вставке контента
function clearPlaceholder() {
    const editor = document.getElementById('visual-editor');
    const placeholder = editor.querySelector('.empty-area');
    if (placeholder) { placeholder.remove(); }
}

// Очистка редактора с подтверждением и возвратом заглушки
function clearEditor() {
    if (confirm("Вы уверены, что хотите полностью очистить содержимое редактора?")) {
        const editor = document.getElementById('visual-editor');
        editor.innerHTML = '<div class="empty-area" style="color: #444; pointer-events: none;">Выберите игру выше и нажмите "СГЕНЕРИРОВАТЬ ШАБЛОН"...</div>';
        activeImage = null;
    }
}

/**
 * Логика изображений (Вставка, выделение, изменение размера)
 */
function insertImg() {
    const url = prompt("Прямая ссылка на изображение:");
    if (url) {
        const editor = document.getElementById('visual-editor');
        clearPlaceholder();
        editor.focus();
        const imgHtml = `<div style="text-align: center; margin: 20px 0;"><img src="${url}" style="max-width: 100%; height: auto; border-radius: 8px; border: 1px solid #1a1a1a;"></div>`;
        document.execCommand('insertHTML', false, imgHtml + '<p><br></p>');
    }
}

function resizeImage() {
    if (!activeImage) {
        alert("Сначала кликните по картинке в редакторе!");
        return;
    }
    const panel = document.getElementById('imageResizerPanel');
    const overlay = document.getElementById('resizerOverlay');
    const slider = document.getElementById('sizeSlider');
    const display = document.getElementById('sizeValue');

    if(panel && overlay) {
        panel.style.display = 'block';
        overlay.style.display = 'block';
    }

    let currentWidth = activeImage.style.width || "100%";
    let numericValue = parseInt(currentWidth) || 100;
    
    if(slider && display) {
        slider.value = numericValue;
        display.innerText = numericValue + "%";
        slider.oninput = function() {
            display.innerText = this.value + "%";
            activeImage.style.width = this.value + "%";
            activeImage.style.height = "auto";
        };
    }
}

function applySize() { closeResizer(); }
function closeResizer() {
    const panel = document.getElementById('imageResizerPanel');
    const overlay = document.getElementById('resizerOverlay');
    if(panel) panel.style.display = 'none';
    if(overlay) overlay.style.display = 'none';
}

/**
 * Изменение интерфейса и динамические кнопки под выбранную игру
 */
function updateToolbarButtons() {
    const categorySelect = document.getElementById('game-category');
    if (!categorySelect) return;
    
    const game = categorySelect.value;
    const wpBtn = document.getElementById('add-weapon-btn');
    const artBtn = document.getElementById('add-artifact-btn');
    
    if (game === 'wuwa') {
        if (wpBtn) wpBtn.innerText = '+ ОРУЖИЕ';
        if (artBtn) artBtn.innerText = '+ ЭХО';
    } else if (game === 'genshin') {
        if (wpBtn) wpBtn.innerText = '+ ОРУЖИЕ';
        if (artBtn) artBtn.innerText = '+ АРТЕФАКТЫ';
    } else if (game === 'hsr') {
        if (wpBtn) wpBtn.innerText = '+ КОНУС';
        if (artBtn) artBtn.innerText = '+ РЕЛИКВИИ';
    } else if (game === 'zzz') {
        if (wpBtn) wpBtn.innerText = '+ АМПЛИФИКАТОР';
        if (artBtn) artBtn.innerText = '+ ДРАЙВ-ДИСКИ';
    }
}

/**
 * Функции динамического добавления строк прямо ВНУТРЬ таблиц/блоков гайда
 */
function addRowToWeapon(button) {
    const table = button.closest('.wp-table-wrapper').querySelector('.wp-table-weapon tbody');
    if (!table) return;
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td class="wp-cell-center">
            <div class="wp-item-icon-wrapper"><div class="wp-item-blank-icon"></div></div>
            <div class="wp-item-name" contenteditable="true">Альтернативное оружие</div>
            <div class="wp-stars" contenteditable="true">★★★★★</div>
            <div class="wp-item-sub" contenteditable="true">Базовые параметры</div>
        </td>
        <td class="wp-cell-effect" contenteditable="true">
            <p>Описание пассивного эффекта альтернативного снаряжения...</p>
        </td>
    `;
    table.appendChild(newRow);
}

function addRowToArtifact(button) {
    const container = button.closest('.wp-artifacts-container').querySelector('.wp-artifacts-list-wrapper');
    if (!container) return;
    const game = document.getElementById('game-category').value;
    
    let setLabel = "Альтернативный комплект";
    let specList = `<li><strong>Часы:</strong> ATK% / МС</li><li><strong>Кубок:</strong> Элем. урон</li><li><strong>Шапка:</strong> Крит. шанс / урон</li>`;
    
    if (game === 'wuwa') {
        setLabel = "Альтернативная Соната";
        specList = `<li><strong>4-cost:</strong> Крит. шанс</li><li><strong>3-cost:</strong> Элем. урон</li><li><strong>1-cost:</strong> ATK%</li>`;
    } else if (game === 'hsr') {
        setLabel = "Альтернативные Релики";
        specList = `<li><strong>Тело:</strong> Крит. шанс</li><li><strong>Ноги:</strong> Скорость</li><li><strong>Сфера:</strong> Элем. урон</li>`;
    } else if (game === 'zzz') {
        setLabel = "Альтернативные Драйв-диски";
        specList = `<li><strong>4-й сектор:</strong> Крит. шанс</li><li><strong>5-й сектор:</strong> Элем. урон</li><li><strong>6-й сектор:</strong> Импульс / Энергия</li>`;
    }

    const newBlock = document.createElement('div');
    newBlock.className = 'wp-grid-echo';
    newBlock.style.margin = '20px 0 0 0';
    newBlock.innerHTML = `
        <div class="wp-echo-card-left">
            <div class="wp-block-header-text">Комплект</div>
            <div class="wp-echo-meta">
                <div class="wp-item-blank-icon circle"></div>
                <div class="wp-item-name" contenteditable="true">${setLabel}</div>
                <div class="wp-set-desc" contenteditable="true">2 части: Бонус характеристик<br>4-5 частей: Описание эффекта альтернативного сета...</div>
            </div>
        </div>
        <div class="wp-echo-card-right">
            <div class="wp-block-header-text">Рекомендуемые основные статы</div>
            <div class="wp-echo-pool">
                <div class="wp-echo-item">
                    <div class="wp-item-blank-icon"></div>
                    <div class="wp-echo-info">
                        <div class="wp-item-name" contenteditable="true">Приоритет суб-статов:</div>
                        <div class="wp-echo-stats" contenteditable="true">Крит. шанс -> Крит. урон -> ATK%</div>
                    </div>
                </div>
                <ul class="wp-echo-stats-list" contenteditable="true">
                    ${specList}
                </ul>
            </div>
        </div>
    `;
    container.appendChild(newBlock);
}

function addRowToTeam(button) {
    const table = button.closest('.wp-table-wrapper').querySelector('.wp-table-team tbody');
    if (!table) return;
    const game = document.getElementById('game-category').value;
    const isThreeSlot = (game === 'wuwa'); // WuWa - 3 слота, остальные игры - 4 слота

    let slotsHTML = `
        <div class="wp-slot">
            <span class="wp-slot-role main-dd">Мейн-ДД</span>
            <div class="wp-item-blank-icon"></div>
            <span class="wp-slot-name" contenteditable="true">Персонаж 1</span>
        </div>
        <div class="wp-slot">
            <span class="wp-slot-role sub-dd">Сап-ДД</span>
            <div class="wp-item-blank-icon"></div>
            <span class="wp-slot-name" contenteditable="true">Персонаж 2</span>
        </div>
        <div class="wp-slot">
            <span class="wp-slot-role support">Саппорт</span>
            <div class="wp-item-blank-icon"></div>
            <span class="wp-slot-name" contenteditable="true">Персонаж 3</span>
        </div>
    `;

    if (!isThreeSlot) {
        slotsHTML += `
        <div class="wp-slot">
            <span class="wp-slot-role support" style="background: #00bcd4;">Саппорт/Хил</span>
            <div class="wp-item-blank-icon"></div>
            <span class="wp-slot-name" contenteditable="true">Персонаж 4</span>
        </div>`;
    }

    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>
            <div class="wp-team-slots">
                ${slotsHTML}
            </div>
        </td>
        <td class="wp-cell-effect" contenteditable="true">
            <p>Описание тактики новой альтернативной команды, её синергии и преимуществ...</p>
        </td>
    `;
    table.appendChild(newRow);
}

/**
 * Полная генерация структуры Wotpack под конкретную игру
 */
function applyGameTemplate() {
    const editor = document.getElementById('visual-editor');
    const categorySelect = document.getElementById('game-category');
    if (!editor || !categorySelect) return;

    clearPlaceholder();
    const game = categorySelect.value;
    
    let labelChar = "Персонажа";
    let labelWeapon = "Оружие";
    let labelArtifacts = "Лучшие Артефакты";
    let labelSet = "Комплект";
    let labelConsts = "Созвездия";
    let labelSkills = "Таланты";
    
    let prioritySkillsText = "Обычная атака -> Элементальный навык -> Взрыв стихий";
    let artSpecItems = `<li><strong>Часы:</strong> ATK% / Мастерство стихий</li><li><strong>Кубок:</strong> Элементальный урон</li><li><strong>Шапка:</strong> Крит. шанс / Крит. урон</li>`;
    let isThreeSlotTeam = (game === 'wuwa'); // WuWa — 3 слота, Genshin/HSR/ZZZ — 4 слота.

    if (game === 'wuwa') {
        labelChar = "Резонатора";
        labelWeapon = "Оружие";
        labelArtifacts = "Лучшее Эхо";
        labelSet = "Соната (Set)";
        labelConsts = "Цепочка резонанса (Дубликаты)";
        labelSkills = "Навыки";
        prioritySkillsText = "Resonance Liberation -> Resonance Skill -> Forte Circuit";
        artSpecItems = `<li><strong>4-cost:</strong> Крит. шанс / Крит. урон</li><li><strong>3-cost:</strong> Элементальный урон / Элементальный урон</li><li><strong>1-cost:</strong> ATK% / ATK%</li>`;
    } else if (game === 'hsr') {
        labelChar = "Персонажа";
        labelWeapon = "Световой конус";
        labelArtifacts = "Лучшие Реликварии и Планарки";
        labelSet = "Реликвии / Планарки";
        labelConsts = "Эйдолоны";
        labelSkills = "Следы и Способности";
        prioritySkillsText = "Сверхспособность -> Навык -> Талант -> Базовая атака";
        artSpecItems = `<li><strong>Тело:</strong> Крит. шанс / Крит. урон</li><li><strong>Ноги:</strong> Скорость / ATK%</li><li><strong>Планарная сфера:</strong> Элементальный урон</li><li><strong>Соединительная вязь:</strong> Восстановление энергии / ATK%</li>`;
    } else if (game === 'zzz') {
        labelChar = "Агента";
        labelWeapon = "Амплификатор";
        labelArtifacts = "Лучшие Драйв-диски";
        labelSet = "Драйв-диски (4+2)";
        labelConsts = "Ментальная картина (Дубликаты)";
        labelSkills = "Навыки и Приоритеты";
        prioritySkillsText = "Запуск за цепочки / Ульта -> Особая атака -> Базовая атака -> Уклонение";
        artSpecItems = `<li><strong>4-й сектор:</strong> Крит. шанс / Крит. урон / СМЭ</li><li><strong>5-й сектор:</strong> Элементальный урон / ATK%</li><li><strong>6-й сектор:</strong> Восстановление энергии / Импульс / ATK%</li>`;
    }

    // Рендеринг блока команды с учётом количества слотов (3 или 4)
    let teamSlotsHeaderHTML = `
        <div class="wp-slot">
            <span class="wp-slot-role main-dd">Мейн-ДД</span>
            <div class="wp-item-blank-icon"></div>
            <span class="wp-slot-name">Персонаж 1</span>
        </div>
        <div class="wp-slot">
            <span class="wp-slot-role sub-dd">Сап-ДД</span>
            <div class="wp-item-blank-icon"></div>
            <span class="wp-slot-name">Персонаж 2</span>
        </div>
        <div class="wp-slot">
            <span class="wp-slot-role support">Саппорт</span>
            <div class="wp-item-blank-icon"></div>
            <span class="wp-slot-name">Персонаж 3</span>
        </div>
    `;
    
    if (!isThreeSlotTeam) {
        teamSlotsHeaderHTML += `
        <div class="wp-slot">
            <span class="wp-slot-role support" style="background: #00bcd4;">Саппорт/Хил</span>
            <div class="wp-item-blank-icon"></div>
            <span class="wp-slot-name">Персонаж 4</span>
        </div>`;
    }

    const template = `
        <h3 class="wp-section-title">Описание ${labelChar}</h3>
        <p>Краткое введение, роль персонажа в мете и особенности его геймплея...</p>
        
        <h3 class="wp-section-title">Преимущества и Недостатки</h3>
        <div style="display: flex; gap: 20px; margin: 20px 0;" contenteditable="false">
            <div class="pros-box" contenteditable="true">
                <b>Преимущества:</b>
                <ul style="margin: 10px 0; padding-left: 20px; color: #ddd; font-size: 14px;">
                    <li>Высокий базовый урон и отличная синергия.</li>
                </ul>
            </div>
            <div class="cons-box" contenteditable="true">
                <b>Недостатки:</b>
                <ul style="margin: 10px 0; padding-left: 20px; color: #ddd; font-size: 14px;">
                    <li>Требователен к экипировке и правильной ротации.</li>
                </ul>
            </div>
        </div>

        <h3 class="wp-section-title">Приоритет прокачки ${labelSkills}</h3>
        <p>В какую очередь стоит вливать ресурсы для максимальной эффективности:</p>
        <p><b>Порядок прокачки:</b> ${prioritySkillsText}</p>

        <h3 class="wp-section-title">Лучшие ${labelConsts}</h3>
        <p>Описание самых полезных созвездий/эффектов при получении копий:</p>
        <ul>
            <li><strong>C1 / E1:</strong> Краткое описание ключевого первого дубликата...</li>
            <li><strong>C2 / E2:</strong> Краткое описание второго дубликата...</li>
            <li><strong>C6 / E6:</strong> Описание финального сильнейшего баффа...</li>
        </ul>

        <h3 class="wp-section-title">Лучшее снаряжение (${labelWeapon})</h3>
        <div class="wp-table-wrapper" contenteditable="false">
            <table class="wp-table-weapon">
                <thead>
                    <tr>
                        <th style="width: 30%;">${labelWeapon}</th>
                        <th style="width: 70%;">Эффект / Характеристики</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="wp-cell-center">
                            <div class="wp-item-icon-wrapper"><div class="wp-item-blank-icon"></div></div>
                            <div class="wp-item-name" contenteditable="true">Название сигнатурки</div>
                            <div class="wp-stars" contenteditable="true">★★★★★</div>
                            <div class="wp-item-sub" contenteditable="true">Базовые параметры и мейн-стат</div>
                        </td>
                        <td class="wp-cell-effect" contenteditable="true">
                            <p>Описание пассивного бонуса, условий его срабатывания и синергии...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="wp-inline-add-btn" onclick="addRowToWeapon(this)" style="margin-top: 10px; width: 100%; padding: 10px; background: #141414; border: 1px dashed #333; color: #ff4d00; font-weight: bold; cursor: pointer; border-radius: 6px;">➕ ДОБАВИТЬ ВАРИАНТ ОРУЖИЯ</button>
        </div>

        <h3 class="wp-section-title">${labelArtifacts}</h3>
        <div class="wp-artifacts-container" contenteditable="false">
            <div class="wp-artifacts-list-wrapper">
                <div class="wp-grid-echo">
                    <div class="wp-echo-card-left">
                        <div class="wp-block-header-text">${labelSet}</div>
                        <div class="wp-echo-meta">
                            <div class="wp-item-blank-icon circle"></div>
                            <div class="wp-item-name" contenteditable="true">Название фулл-комплекта</div>
                            <div class="wp-set-desc" contenteditable="true">2 части: Бонус характеристик<br>4-5 частей: Уникальный эффект сета при выполнении условий</div>
                        </div>
                    </div>
                    <div class="wp-echo-card-right">
                        <div class="wp-block-header-text">Рекомендуемые основные статы</div>
                        <div class="wp-echo-pool">
                            <div class="wp-echo-item">
                                <div class="wp-item-blank-icon"></div>
                                <div class="wp-echo-info">
                                    <div class="wp-item-name" contenteditable="true">Приоритет суб-статов:</div>
                                    <div class="wp-echo-stats" contenteditable="true">Крит. шанс -> Крит. урон -> ATK% -> Восстановление</div>
                                </div>
                            </div>
                            <ul class="wp-echo-stats-list" contenteditable="true">
                                ${artSpecItems}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="wp-inline-add-btn" onclick="addRowToArtifact(this)" style="margin-top: 15px; width: 100%; padding: 10px; background: #141414; border: 1px dashed #333; color: #ff4d00; font-weight: bold; cursor: pointer; border-radius: 6px;">➕ ДОБАВИТЬ ЕЩЕ СЕТ АРТЕФАКТОВ</button>
        </div>

        <h3 class="wp-section-title">Лучшие отряды и команды</h3>
        <div class="wp-table-wrapper" contenteditable="false">
            <table class="wp-table-team">
                <thead>
                    <tr>
                        <th style="width: 55%;">Компоновка группы</th>
                        <th style="width: 45%;">Описание синергии и тактика</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="wp-team-slots">
                                ${teamSlotsHeaderHTML}
                            </div>
                        </td>
                        <td class="wp-cell-effect" contenteditable="true">
                            <p>Очередность прожатия кнопок (ротация), как комбинировать баффы и ультимейты для нанесения максимального урона...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="wp-inline-add-btn" onclick="addRowToTeam(this)" style="margin-top: 10px; width: 100%; padding: 10px; background: #141414; border: 1px dashed #333; color: #ff4d00; font-weight: bold; cursor: pointer; border-radius: 6px;">➕ ДОБАВИТЬ ДРУГОЙ ОТРЯД</button>
        </div>

        <h3 class="wp-section-title">Как играть (Ротация)</h3>
        <p>Пошаговое руководство по ведению боя на данном герое...</p>
        <p><br></p>
    `;
    
    editor.innerHTML = template;
    
    // Подкрашиваем новые служебные кнопки под цвет темы активной игры
    const themeColor = (game === 'wuwa') ? '#ffcc00' : '#ff4d00';
    editor.querySelectorAll('.wp-inline-add-btn').forEach(b => b.style.color = themeColor);
}

/**
 * Добавление отдельно стоящих блоков через верхний тулбар (Твой старый функционал)
 */
function addDynamicRow(type) {
    const editor = document.getElementById('visual-editor');
    const categorySelect = document.getElementById('game-category');
    if (!editor || !categorySelect) return;

    clearPlaceholder();
    editor.focus();
    const game = categorySelect.value;

    let html = '';
    if (type === 'weapon') {
        let weaponLabel = "Оружие";
        if (game === 'hsr') weaponLabel = "Световой конус";
        if (game === 'zzz') weaponLabel = "Амплификатор";

        html = `
        <div class="wp-table-wrapper" contenteditable="false">
            <table class="wp-table-weapon" style="margin: 15px 0;">
                <thead>
                    <tr>
                        <th style="width: 30%;">${weaponLabel}</th>
                        <th style="width: 70%;">Эффект / Характеристики</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="wp-cell-center">
                            <div class="wp-item-icon-wrapper"><div class="wp-item-blank-icon"></div></div>
                            <div class="wp-item-name" contenteditable="true">Название предметов</div>
                            <div class="wp-stars" contenteditable="true">★★★★★</div>
                            <div class="wp-item-sub" contenteditable="true">Параметры статов</div>
                        </td>
                        <td class="wp-cell-effect" contenteditable="true">
                            <p>Описание пассивного эффекта...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="wp-inline-add-btn" onclick="addRowToWeapon(this)" style="width:100%; padding:8px; background:#141414; border:1px dashed #333; color:\${game === 'wuwa' ? '#ffcc00' : '#ff4d00'}; font-weight:bold; cursor:pointer; border-radius:6px;">➕ ДОБАВИТЬ СТРОКУ ОРУЖИЯ</button>
        </div>`;
    } else if (type === 'artifact') {
        let setLabel = "Комплект";
        let specList = `<li><strong>Часы:</strong> Стат</li><li><strong>Кубок:</strong> Стат</li><li><strong>Шапка:</strong> Стат</li>`;
        
        if (game === 'wuwa') {
            setLabel = "Соната (Set)";
            specList = `<li><strong>3-cost:</strong> Элемент</li><li><strong>1-cost:</strong> ATK%</li>`;
        } else if (game === 'hsr') {
            setLabel = "Реликвии / Планарки";
            specList = `<li><strong>Тело:</strong> Стат</li><li><strong>Ноги:</strong> Скорость</li><li><strong>Планарка:</strong> Стат</li>`;
        } else if (game === 'zzz') {
            setLabel = "Драйв-диски";
            specList = `<li><strong>4-й сектор:</strong> Крит / СМЭ</li><li><strong>5-й сектор:</strong> Элемент</li><li><strong>6-й сектор:</strong> Энергия / Импульс</li>`;
        }

        html = `
        <div class="wp-artifacts-container" contenteditable="false">
            <div class="wp-artifacts-list-wrapper">
                <div class="wp-grid-echo" style="margin: 15px 0;">
                    <div class="wp-echo-card-left">
                        <div class="wp-block-header-text">${setLabel}</div>
                        <div class="wp-echo-meta">
                            <div class="wp-item-blank-icon circle"></div>
                            <div class="wp-item-name" contenteditable="true">Название нового сета</div>
                            <div class="wp-set-desc" contenteditable="true">Описание бонусов сета...</div>
                        </div>
                    </div>
                    <div class="wp-echo-card-right">
                        <div class="wp-block-header-text">Рекомендуемые статы</div>
                        <div class="wp-echo-pool">
                            <div class="wp-echo-item">
                                <div class="wp-item-blank-icon"></div>
                                <div class="wp-echo-info">
                                    <div class="wp-item-name" contenteditable="true">Новые приоритеты</div>
                                    <div class="wp-echo-stats" contenteditable="true">Основные статы</div>
                                </div>
                            </div>
                            <ul class="wp-echo-stats-list" contenteditable="true">
                                ${specList}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="wp-inline-add-btn" onclick="addRowToArtifact(this)" style="width:100%; padding:8px; background:#141414; border:1px dashed #333; color:\${game === 'wuwa' ? '#ffcc00' : '#ff4d00'}; font-weight:bold; cursor:pointer; border-radius:6px;">➕ ДОБАВИТЬ ЕЩЕ СЕТ</button>
        </div>`;
    } else if (type === 'team') {
        const isThreeSlot = (game === 'wuwa');
        let slotsHTML = `
            <div class="wp-slot"><span class="wp-slot-role main-dd">Мейн-ДД</span><div class="wp-item-blank-icon"></div><span class="wp-slot-name" contenteditable="true">Имя</span></div>
            <div class="wp-slot"><span class="wp-slot-role sub-dd">Сап-ДД</span><div class="wp-item-blank-icon"></div><span class="wp-slot-name" contenteditable="true">Имя</span></div>
            <div class="wp-slot"><span class="wp-slot-role support">Саппорт</span><div class="wp-item-blank-icon"></div><span class="wp-slot-name" contenteditable="true">Имя</span></div>
        `;
        if(!isThreeSlot) {
            slotsHTML += `<div class="wp-slot"><span class="wp-slot-role support" style="background:#00bcd4;">Саппорт</span><div class="wp-item-blank-icon"></div><span class="wp-slot-name" contenteditable="true">Имя</span></div>`;
        }

        html = `
        <div class="wp-table-wrapper" contenteditable="false">
            <table class="wp-table-team" style="margin: 15px 0;">
                <thead>
                    <tr>
                        <th style="width: 55%;">Персонажи</th>
                        <th style="width: 45%;">Описание команды</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><div class="wp-team-slots">${slotsHTML}</div></td>
                        <td class="wp-cell-effect" contenteditable="true"><p>Описание синергии...</p></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="wp-inline-add-btn" onclick="addRowToTeam(this)" style="width:100%; padding:8px; background:#141414; border:1px dashed #333; color:\${game === 'wuwa' ? '#ffcc00' : '#ff4d00'}; font-weight:bold; cursor:pointer; border-radius:6px;">➕ ДОБАВИТЬ СТРОКУ ОТРЯДА</button>
        </div>`;
    }

    if (!document.execCommand('insertHTML', false, html + '<p><br></p>')) {
        editor.innerHTML += html + '<p><br></p>';
    }
}

/**
 * Умная таблица билда (Твой старый метод)
 */
function insertBuildTable() {
    const categorySelect = document.getElementById('game-category');
    const isWuwa = categorySelect && categorySelect.value === 'wuwa';
    const editor = document.getElementById('visual-editor');
    
    const color = isWuwa ? '#ffcc00' : '#ff4d00';
    const label = isWuwa ? 'ЭХО / СОНЕТ' : 'АРТЕФАКТ / ОРУЖИЕ';

    const tableHTML = `
        <div class="build-table-container" style="margin: 20px 0;">
            <table style="width:100%; border-collapse:collapse; background:#0a0a0a; border:1px solid #222; border-radius:8px; overflow:hidden;">
                <thead>
                    <tr style="background:#111;">
                        <th style="padding:15px; border:1px solid #222; color:${color}; text-transform:uppercase; font-size:12px; width:30%;">${label}</th>
                        <th style="padding:15px; border:1px solid #222; color:${color}; text-transform:uppercase; font-size:12px;">ХАРАКТЕРИСТИКИ</th>
                        <th style="padding:15px; border:1px solid #222; color:${color}; text-transform:uppercase; font-size:12px; width:15%;">TIER</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:15px; border:1px solid #222; color:#fff;" contenteditable="true">Название предмета</td>
                        <td style="padding:15px; border:1px solid #222; color:#ccc;" contenteditable="true">Главные статы...</td>
                        <td style="padding:15px; border:1px solid #222; text-align:center; color:#ffff00; font-weight:900;" contenteditable="true">S+</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p><br></p>
    `;

    if(editor) {
        clearPlaceholder();
        editor.focus();
        document.execCommand('insertHTML', false, tableHTML);
    }
}

/**
 * Подготовка контента перед отправкой формы в PHP
 */
function prepareContent() {
    const visualEditor = document.getElementById('visual-editor');
    const hiddenInput = document.getElementById('hidden-content');
    
    if (!visualEditor || !hiddenInput) return;

    // Создаем клон для очистки от технических стилей редактора
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = visualEditor.innerHTML;

    // 1. Очищаем рамки выделения с картинок на сохраненной копии
    tempDiv.querySelectorAll('img').forEach(img => {
        img.style.outline = "none";
        img.style.boxShadow = "none";
    });

    // 2. Полностью вырезаем служебные интерактивные кнопки добавления строк,
    // чтобы они не сохранялись в БД и не отображались на самом сайте пользователям
    tempDiv.querySelectorAll('.wp-inline-add-btn').forEach(btn => btn.remove());

    hiddenInput.value = tempDiv.innerHTML;
}

/**
 * Инициализация всех слушателей событий после загрузки DOM
 */
document.addEventListener('DOMContentLoaded', () => {
    const editor = document.getElementById('visual-editor');
    const categorySelect = document.getElementById('game-category');
    
    if (!editor) return;

    // 1. Выбор картинки (выделение динамической рамкой)
    editor.addEventListener('click', function(e) {
        if (e.target.tagName === 'IMG') {
            activeImage = e.target;
            
            // Сбрасываем стили у всех остальных картинок в редакторе
            editor.querySelectorAll('img').forEach(img => {
                img.style.outline = "none";
                img.style.boxShadow = "none";
            });

            // Определяем цвет темы
            const themeColor = (categorySelect && categorySelect.value === 'wuwa') ? '#ffcc00' : '#ff4d00';
            
            // Применяем выделение к активной картинке
            activeImage.style.outline = `3px solid ${themeColor}`;
            activeImage.style.boxShadow = `0 0 15px ${themeColor}80`; 
        } else {
            // Если кликнули мимо картинок - убираем рамки и сбрасываем переменную
            activeImage = null;
            editor.querySelectorAll('img').forEach(img => {
                img.style.outline = "none";
                img.style.boxShadow = "none";
            });
        }
    });

    // 2. Тема админки и динамические кнопки при смене категории
    if (categorySelect) {
        const updateTheme = () => {
            const isWuwa = categorySelect.value === 'wuwa';
            const themeColor = isWuwa ? '#ffcc00' : '#ff4d00';
            
            document.body.classList.toggle('theme-wuwa', isWuwa);

            const buildBtn = document.querySelector('.btn-insert-table') || 
                             Array.from(document.querySelectorAll('button')).find(b => b.innerText.includes('ТАБЛИЦА'));
            
            if (buildBtn) buildBtn.style.backgroundColor = themeColor;
            
            const headerTitle = document.querySelector('.form-header h2');
            if (headerTitle) headerTitle.style.borderBottomColor = themeColor;

            // Вызов кастомизации кнопок тулбара
            updateToolbarButtons();
            
            // Перекрашиваем инлайновые кнопки внутри контента, если шаблон уже был сгенерирован
            editor.querySelectorAll('.wp-inline-add-btn').forEach(b => b.style.color = themeColor);
        };

        categorySelect.addEventListener('change', updateTheme);
        updateTheme(); 
    }

    // 3. Автоматический сброс заглушки при фокусе на редактор
    editor.addEventListener('focus', clearPlaceholder);
});