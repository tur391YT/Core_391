/**
 * Словарь терминов для разных игр
 */
const gameTerms = {
    genshin: { items: "Артефакты", set: "Сет", stats: "Статы", hero: "Персонаж" },
    zzz: { items: "Драйв-диски", set: "Серия", stats: "Характеристики", hero: "Агент" },
    hsr: { items: "Реликвии", set: "Набор", stats: "Параметры", hero: "Персонаж" },
    wuwa: { items: "Эхо", set: "Соната", stats: "Суб-статы", hero: "Резонатор" }
};

/**
 * Функция вставки шаблона в CKEditor
 * @param {string} type - Тип шаблона (full_guide, news, tier)
 * @param {object} editorInstance - Экземпляр запущенного редактора
 */
function insertTemplate(type, editorInstance) {
    if (!editorInstance) return;

    const category = document.querySelector('select[name="category"]').value;
    const t = gameTerms[category] || gameTerms.genshin;
    let html = '';

    if (type === 'full_guide') {
        html = `
            <h2 style="color: #fff; border-bottom: 2px solid #ff4d00; padding-bottom: 10px;">Материалы возвышения</h2>
            <div class="guide-table-wrapper">
                <table class="guide-table">
                    <thead><tr><th>Уровень</th><th>Ресурсы</th><th>Валюта</th></tr></thead>
                    <tbody><tr><td>80 → 90</td><td><div class="item-slot"><img src="img/items/placeholder.png"> x60 Предмет</div></td><td>1 000 000</td></tr></tbody>
                </table>
            </div>

            <h2 style="color: #fff; border-bottom: 2px solid #ff4d00; padding-bottom: 10px; margin-top: 40px;">Лучшее оружие</h2>
            <div class="guide-table-wrapper">
                <table class="guide-table">
                    <thead><tr><th>Оружие</th><th>Описание</th><th>Рекомендация</th></tr></thead>
                    <tbody><tr><td><div class="weapon-img-info"><img src="img/weapons/placeholder.png"><span class="stars">★★★★★</span><strong>Название</strong></div></td><td style="font-size: 13px; color: #ccc;">Эффект...</td><td style="font-size: 13px;">Лучший выбор.</td></tr></tbody>
                </table>
            </div>

            <h2 style="color: #fff; border-bottom: 2px solid #ff4d00; padding-bottom: 10px; margin-top: 40px;">Лучшие ${t.items}</h2>
            <div class="guide-table-wrapper">
                <table class="guide-table">
                    <thead><tr><th>${t.set}</th><th>Бонусы</th><th>Рекомендация</th></tr></thead>
                    <tbody><tr><td><div class="weapon-img-info"><img src="img/artifacts/placeholder.png"><span class="stars">★★★★★</span><strong>Название</strong></div></td><td style="font-size: 13px; color: #ccc;">Описание бонусов...</td><td style="font-size: 13px;">Основной набор.</td></tr></tbody>
                </table>
            </div>

            <h2 style="color: #fff; border-bottom: 2px solid #ff4d00; padding-bottom: 10px; margin-top: 40px;">Рекомендуемые ${t.stats}</h2>
            <div class="guide-table-wrapper">
                <table class="guide-table stats-table">
                    <thead><tr><th>Слот</th><th>Главный стат</th><th>Доп. ${t.stats}</th></tr></thead>
                    <tbody><tr><td><div class="stat-label"><img src="img/icons/cup.png"> Слот</div></td><td>Атака %</td><td>Криты > Скорость</td></tr></tbody>
                </table>
            </div>

            <h2 style="color: #fff; border-bottom: 2px solid #ff4d00; padding-bottom: 10px; margin-top: 40px;">Лучшие отряды</h2>
            <div class="guide-table-wrapper">
                <table class="guide-table teams-table">
                    <tbody><tr><td><div class="team-row"><div class="char-item"><img src="img/chars/placeholder.png"><span>${t.hero} 1</span></div><div class="char-item"><img src="img/chars/placeholder.png"><span>${t.hero} 2</span></div></div></td><td style="font-size: 13px; color: #ccc;">Синергия...</td></tr></tbody>
                </table>
            </div>
        `;
    } else if (type === 'news') {
        html = `<h2>Заголовок новости</h2><p>Текст новости...</p>`;
    } else if (type === 'tier') {
        html = `<h2>ТИР-ЛИСТ</h2><h3 style="background: #ff4d00; color: #fff; padding: 5px 15px; display: inline-block;">S-RANK</h3>`;
    }

    const viewFragment = editorInstance.data.processor.toView(html);
    const modelFragment = editorInstance.data.toModel(viewFragment);
    editorInstance.model.insertContent(modelFragment);
}