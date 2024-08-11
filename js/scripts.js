document.addEventListener('DOMContentLoaded', function() {
    const searchButton = document.getElementById('search-button');
    const searchInput = document.getElementById('search-input');

    searchButton.addEventListener('click', function() {
        const query = searchInput.value.trim();
        if (query) {
            // การเปลี่ยนเส้นทางไปยังหน้าผลลัพธ์การค้นหาหรือการค้นหาภายในหน้านั้น
            window.location.href = `search.html?q=${encodeURIComponent(query)}`;
        }
    });

    searchInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            searchButton.click();
        }
    });
});
