document.addEventListener('DOMContentLoaded', function() {
    // เลือกปุ่มทั้งหมดใน payment-options
    const creditDebitCardButton = document.getElementById('credit-debit-card');
    const cashOnDeliveryButton = document.getElementById('cash-on-delivery');
    
    // ฟังก์ชันเปลี่ยนสีพื้นหลังของปุ่มที่ถูกคลิก
    function changeButtonColor(event) {
        // ลบสีพื้นหลังจากปุ่มทั้งหมดก่อน
        creditDebitCardButton.style.backgroundColor = '#FFC0CB';
        cashOnDeliveryButton.style.backgroundColor = '#FFC0CB';
        
        // เปลี่ยนสีพื้นหลังของปุ่มที่ถูกคลิก
        event.target.style.backgroundColor = '#BB7DAD';
    }

    // เพิ่ม event listener ให้กับปุ่มทั้งหมด
    creditDebitCardButton.addEventListener('click', changeButtonColor);
    cashOnDeliveryButton.addEventListener('click', changeButtonColor);
});