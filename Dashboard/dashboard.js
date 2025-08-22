document.addEventListener('DOMContentLoaded', function() {
    // Add any JavaScript functionality here
    console.log('Dashboard loaded');

    // Example: Add click event to navigation items
    const navItems = document.querySelectorAll('nav ul li');
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            navItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });
});