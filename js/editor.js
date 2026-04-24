// Переменная для хранения активной картинки
let activeImage = null;

/**
 * Основные функции форматирования
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

function addImage() {
    const url = prompt("Введите URL картинки:", "https://");
    if (url) {
        // Вставляем картинку с базовым стилем
        const imgHTML = `<img src="${url}" style="max-width:100%; height:auto; border-radius:8px; margin:10px 0;">`;
        document.execCommand('insertHTML', false, imgHTML);
    }
}

/**
 * Логика изменения размера изображений
 */
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
 * Слушатели событий
 */
document.addEventListener('DOMContentLoaded', () => {
    const editor = document.getElementById('visual-editor');
    const categorySelect = document.querySelector('select[name="category"]');
    
    if (!editor) return;

    // 1. Выбор картинки (выделение рамкой)
    editor.addEventListener('click', function(e) {
        if (e.target.tagName === 'IMG') {
            activeImage = e.target;
            
            // Сбрасываем стили у всех остальных
            editor.querySelectorAll('img').forEach(img => {
                img.style.outline = "none";
                img.style.boxShadow = "none";
            });

            // Определяем цвет темы
            const themeColor = (categorySelect && categorySelect.value === 'wuwa') ? '#ffcc00' : '#ff4d00';
            
            // Применяем выделение
            activeImage.style.outline = `3px solid ${themeColor}`;
            activeImage.style.boxShadow = `0 0 15px ${themeColor}80`; 
        } else {
            // Если кликнули не по картинке - снимаем выделение
            activeImage = null;
            editor.querySelectorAll('img').forEach(img => {
                img.style.outline = "none";
                img.style.boxShadow = "none";
            });
        }
    });

    // 2. Динамическая тема админки при смене категории
    if (categorySelect) {
        const updateTheme = () => {
            const isWuwa = categorySelect.value === 'wuwa';
            const themeColor = isWuwa ? '#ffcc00' : '#ff4d00';
            
            // Меняем переменные или классы
            document.body.classList.toggle('theme-wuwa', isWuwa);

            // Перекрашиваем кнопку таблицы и заголовки
            const buildBtn = document.querySelector('.btn-insert-table') || 
                             Array.from(document.querySelectorAll('button')).find(b => b.innerText.includes('ТАБЛИЦА'));
            
            if (buildBtn) buildBtn.style.backgroundColor = themeColor;
            
            const headerTitle = document.querySelector('.form-header h2');
            if (headerTitle) headerTitle.style.borderBottomColor = themeColor;
        };

        categorySelect.addEventListener('change', updateTheme);
        updateTheme(); // Запуск при загрузке
    }
});

/**
 * Умная таблица билда
 */
function insertBuildTable() {
    const categorySelect = document.querySelector('select[name="category"]');
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
        editor.focus();
        document.execCommand('insertHTML', false, tableHTML);
    }
}

/**
 * Подготовка контента перед отправкой в PHP
 */
function prepareContent() {
    const visualEditor = document.getElementById('visual-editor');
    const hiddenInput = document.getElementById('hidden-content');
    
    if (!visualEditor || !hiddenInput) return;

    // Создаем клон для очистки от технических стилей редактора
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = visualEditor.innerHTML;

    // Удаляем рамки выделения с картинок
    tempDiv.querySelectorAll('img').forEach(img => {
        img.style.outline = "none";
        img.style.boxShadow = "none";
        // Убеждаемся, что пути к картинкам корректны
    });

    hiddenInput.value = tempDiv.innerHTML;
}