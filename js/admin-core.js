let myEditor;

document.addEventListener("DOMContentLoaded", function() {
    const editorElement = document.querySelector('#editor');
    
    if (editorElement) {
        ClassicEditor
            .create(editorElement, {
                language: 'ru',
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                }
            })
            .then(editor => {
                myEditor = editor;
            })
            .catch(error => {
                console.error('Ошибка инициализации редактора:', error);
            });
    }
});
/**
 * Функция полной очистки редактора с подтверждением
 */
function confirmClear() {
    if (confirm("Вы уверены, что хотите полностью очистить содержимое статьи? Это действие нельзя отменить.")) {
        if (myEditor) {
            myEditor.setData('');
        }
    }
}