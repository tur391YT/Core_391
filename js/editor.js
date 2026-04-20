// Переменная для хранения активной картинки
let activeImage = null;

// Функция форматирования текста
function formatDoc(cmd, value = null) {
    document.execCommand(cmd, false, value);
}

function addLink() {
    const url = prompt("Введите ссылку:", "https://");
    if (url) formatDoc('createLink', url);
}

function addImage() {
    const url = prompt("Введите URL картинки:", "https://");
    if (url) formatDoc('insertImage', url);
}

// --- ЛОГИКА ИЗМЕНЕНИЯ РАЗМЕРА ---
function resizeImage() {
    if (!activeImage) {
        alert("Сначала кликните по картинке!");
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
        display.innerText = numericValue;
        slider.oninput = function() {
            display.innerText = this.value;
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

// Ждем загрузки DOM
document.addEventListener('DOMContentLoaded', () => {
    const editor = document.getElementById('visual-editor');
    const categorySelect = document.querySelector('select[name="category"]');
    
    if (!editor) return;

    // 1. Выбор картинки в редакторе (с динамической рамкой)
    editor.addEventListener('mousedown', function(e) {
        if (e.target.tagName === 'IMG') {
            activeImage = e.target;
            
            // Сбрасываем стили у всех остальных картинок
            editor.querySelectorAll('img').forEach(img => {
                img.style.outline = "none";
                img.style.boxShadow = "none";
            });

            // Определяем цвет на основе выбранной категории
            const currentThemeColor = (categorySelect && categorySelect.value === 'wuwa') ? '#ffcc00' : '#ff4d00';
            
            // Применяем обводку и тень в цвет темы
            e.target.style.outline = `3px solid ${currentThemeColor}`;
            e.target.style.boxShadow = `0 0 10px ${currentThemeColor}80`; 
            
            e.preventDefault(); 
        } else {
            activeImage = null;
            editor.querySelectorAll('img').forEach(img => {
                img.style.outline = "none";
                img.style.boxShadow = "none";
            });
        }
    });

    // 2. ДИНАМИЧЕСКАЯ ТЕМА АДМИНКИ
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            const themeColor = (this.value === 'wuwa') ? '#ffcc00' : '#ff4d00';
            
            // Добавляем класс на body для CSS-переменных
            if (this.value === 'wuwa') {
                document.body.classList.add('theme-wuwa');
            } else {
                document.body.classList.remove('theme-wuwa');
            }

            // Прямое изменение элементов
            const mainTitle = document.querySelector('h1');
            if (mainTitle) mainTitle.style.color = themeColor;
            
            const allButtons = document.querySelectorAll('button, .btn-insert-table');
            allButtons.forEach(btn => {
                if (btn.innerText.includes('ТАБЛИЦА')) {
                    btn.style.backgroundColor = themeColor;
                }
            });
            console.log("Тема изменена: " + this.value);
        });
    }
});

// --- УМНАЯ ТАБЛИЦА БИЛДА ---
function insertBuildTable() {
    const categorySelect = document.querySelector('select[name="category"]');
    const currentCategory = categorySelect ? categorySelect.value : 'default';
    const editor = document.getElementById('visual-editor');
    const tableId = 'table-' + Date.now();
    let tableHTML = '';

    if (currentCategory === 'wuwa') {
        tableHTML = `
            <p><br></p>
            <table id="${tableId}" style="width:100%; border-collapse:collapse; margin: 20px 0; background:#080808; border:1px solid #333;">
                <thead>
                    <tr style="background:#1a1a1a;">
                        <th style="padding:15px; border:1px solid #333; color:#ffcc00; text-transform:uppercase; font-size:12px; width:25%;">ЭХО / СЛОТ</th>
                        <th style="padding:15px; border:1px solid #333; color:#ffcc00; text-transform:uppercase; font-size:12px;">НАЗВАНИЕ</th>
                        <th style="padding:15px; border:1px solid #333; color:#ffcc00; text-transform:uppercase; font-size:12px; width:15%;">ТИР</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:15px; border:1px solid #222; text-align:center; color:#fff;">[IMG]</td>
                        <td style="padding:15px; border:1px solid #222; color:#fff;">Название Эхо</td>
                        <td style="padding:15px; border:1px solid #222; text-align:center; color:#ffcc00; font-weight:900;">S+</td>
                    </tr>
                </tbody>
            </table>
            <p><br></p>`;
    } else {
        tableHTML = `
            <p><br></p>
            <table id="${tableId}" style="width:100%; border-collapse:collapse; margin: 20px 0; background:#080808; border:1px solid #222;">
                <thead>
                    <tr style="background:#111;">
                        <th style="padding:15px; border:1px solid #222; color:#ff4d00; width:25%;">АРТ</th>
                        <th style="padding:15px; border:1px solid #222; color:#ff4d00;">НАЗВАНИЕ</th>
                        <th style="padding:15px; border:1px solid #222; color:#ff4d00; width:15%;">ТИР</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:15px; border:1px solid #222; text-align:center; color:#fff;">[IMG]</td>
                        <td style="padding:15px; border:1px solid #222; color:#fff;">Название предмета</td>
                        <td style="padding:15px; border:1px solid #222; text-align:center; color:#ffff00; font-weight:900;">S+</td>
                    </tr>
                </tbody>
            </table>
            <p><br></p>`;
    }

    if(editor) {
        editor.focus();
        document.execCommand('insertHTML', false, tableHTML);
    }
}

// Очистка стилей перед сохранением
function syncEditor() {
    const visualEditor = document.getElementById('visual-editor');
    const hiddenInput = document.getElementById('hidden-content');
    if (!visualEditor || !hiddenInput) return;
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = visualEditor.innerHTML;
    tempDiv.querySelectorAll('img').forEach(img => {
        img.style.outline = "none";
        img.style.boxShadow = "none";
    });
    hiddenInput.value = tempDiv.innerHTML;
}