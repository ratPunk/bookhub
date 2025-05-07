document.addEventListener('DOMContentLoaded', function() {
    const burgerMenu = document.getElementById('burgerMenu');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.createElement('div');
    overlay.className = 'overlay';
    document.body.appendChild(overlay);
    
    burgerMenu.addEventListener('click', function() {
        this.classList.toggle('open');
        sidebar.classList.toggle('open');
        
        if (sidebar.classList.contains('open')) {
            overlay.style.display = 'block';
            overlay.addEventListener('click', closeSidebar);
        } else {
            overlay.style.display = 'none';
            overlay.removeEventListener('click', closeSidebar);
        }
    });
    
    function closeSidebar() {
        burgerMenu.classList.remove('open');
        sidebar.classList.remove('open');
        overlay.style.display = 'none';
        overlay.removeEventListener('click', closeSidebar);
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('open')) {
            closeSidebar();
        }
    });
});