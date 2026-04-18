// Переменная для хранения активной картинки
let activeImage = null;

// Функция форматирования текста
function formatDoc(cmd, value = null) {
    document.execCommand(cmd, false, value);
}

// Добавление ссылки
function addLink() {
    const url = prompt("Введите ссылку:", "https://");
    if (url) formatDoc('createLink', url);
}

// Вставка картинки
function addImage() {
    const url = prompt("Введите URL картинки:", "https://");
    if (url) formatDoc('insertImage', url);
}

// --- НОВАЯ ЛОГИКА ИЗМЕНЕНИЯ РАЗМЕРА (ПОЛЗУНОК) ---

function resizeImage() {
    if (!activeImage) {
        alert("Сначала кликните по картинке (она подсветится оранжевым)!");
        return;
    }

    const panel = document.getElementById('imageResizerPanel');
    const overlay = document.getElementById('resizerOverlay');
    const slider = document.getElementById('sizeSlider');
    const display = document.getElementById('sizeValue');

    // Показываем панель и фон
    panel.style.display = 'block';
    overlay.style.display = 'block';

    // Устанавливаем текущее значение в ползунок
    let currentWidth = activeImage.style.width || "100%";
    let numericValue = parseInt(currentWidth) || 100;
    
    slider.value = numericValue;
    display.innerText = numericValue;

    // Живое превью при движении ползунка
    slider.oninput = function() {
        let val = this.value;
        display.innerText = val;
        activeImage.style.width = val + "%";
        activeImage.style.height = "auto";
    };
}

// Функции управления окном ресайзера
function applySize() {
    closeResizer();
}

function closeResizer() {
    document.getElementById('imageResizerPanel').style.display = 'none';
    document.getElementById('resizerOverlay').style.display = 'none';
}

// --- КОНЕЦ ЛОГИКИ РЕЗАЙЗЕРА ---

// Ждем загрузки DOM, чтобы найти редактор
document.addEventListener('DOMContentLoaded', () => {
    const editor = document.getElementById('visual-editor');

    // Единый обработчик для выбора картинок
    editor.addEventListener('mousedown', function(e) {
        if (e.target.tagName === 'IMG') {
            activeImage = e.target;
            
            // Сбрасываем старые рамки
            editor.querySelectorAll('img').forEach(img => {
                img.style.outline = "none";
                img.style.boxShadow = "none";
            });

            // Ставим новую рамку и свечение
            e.target.style.outline = "3px solid #ff4d00";
            e.target.style.boxShadow = "0 0 10px rgba(255, 77, 0, 0.5)";
            
            // Предотвращаем дефолтный drag-and-drop браузера
            e.preventDefault(); 
        } else {
            // Кликнули мимо — сбрасываем выбор
            activeImage = null;
            editor.querySelectorAll('img').forEach(img => {
                img.style.outline = "none";
                img.style.boxShadow = "none";
            });
        }
    });
});

// Вставка фирменной таблицы для билда
function insertBuildTable() {
    const tableHTML = `
        <table style="width:100%; border-collapse:collapse; margin: 20px 0; background:#080808; border:1px solid #222;">
            <thead>
                <tr style="background:#111;">
                    <th style="padding:15px; border:1px solid #222;">АРТ</th>
                    <th style="padding:15px; border:1px solid #222;">НАЗВАНИЕ</th>
                    <th style="padding:15px; border:1px solid #222;">ТИР</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:15px; border:1px solid #222; text-align:center;">[IMG]</td>
                    <td style="padding:15px; border:1px solid #222;">Название предмета</td>
                    <td style="padding:15px; border:1px solid #222; text-align:center; color:#ffff00; font-weight:900;">S+</td>
                </tr>
            </tbody>
        </table><p></p>`;
    formatDoc('insertHTML', tableHTML);
}

// Синхронизация перед сохранением в базу
function syncEditor() {
    const visualEditor = document.getElementById('visual-editor');
    const hiddenInput = document.getElementById('hidden-content');
    
    // Очищаем оранжевые рамки перед записью в БД
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = visualEditor.innerHTML;
    tempDiv.querySelectorAll('img').forEach(img => {
        img.style.outline = "none";
        img.style.boxShadow = "none";
    });
    
    hiddenInput.value = tempDiv.innerHTML;
}