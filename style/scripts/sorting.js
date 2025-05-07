document.addEventListener('DOMContentLoaded', function() {
    const categoryButtons = document.querySelectorAll('.category-btn');
    const cards = document.querySelectorAll('.card');
    const noPostsMessage = document.querySelector('.no-posts');
    
    // Обработчик для кнопок категорий
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Обновляем активную кнопку
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Фильтруем посты
            let visiblePosts = 0;
            
            cards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.classList.remove('hidden');
                    visiblePosts++;
                } else {
                    card.classList.add('hidden');
                }
            });
            
            // Показываем/скрываем сообщение "Нет постов"
            if (visiblePosts === 0) {
                noPostsMessage.querySelector('p').textContent = 
                    category === 'all' ? "Пока нет ни одного поста" : `Нет постов в категории "${category}"`;
                noPostsMessage.style.display = 'block';
            } else {
                noPostsMessage.style.display = 'none';
            }
            
            // Обновляем URL без перезагрузки страницы
            const newUrl = category === 'all' 
                ? 'index.php' 
                : `index.php?category=${encodeURIComponent(category)}`;
            history.pushState(null, '', newUrl);
        });
    });
    
    // При загрузке страницы проверяем параметр category в URL
    const urlParams = new URLSearchParams(window.location.search);
    const urlCategory = urlParams.get('category');
    
    if (urlCategory) {
        const matchingButton = Array.from(categoryButtons).find(
            btn => btn.dataset.category === urlCategory
        );
        
        if (matchingButton) {
            matchingButton.click();
        }
    }
});