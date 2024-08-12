// loadHeaderNav.js
document.addEventListener("DOMContentLoaded", function() {
    fetch('header.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-nav-container').innerHTML = data;
        })
        .catch(error => console.error('Error loading header and nav:', error));
});