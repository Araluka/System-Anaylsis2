function closeMessageBox() {
    // ปิดกล่องข้อความ
    document.querySelector('.overlay').style.display = 'none';
    // รีโหลดหน้าปัจจุบัน
    window.location.href = window.location.href;
}